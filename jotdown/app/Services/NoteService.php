<?php

namespace App\Services;

use App\DTO\Note\Requests\CreateNoteRequestDto;
use App\DTO\Note\Requests\DeleteNoteRequestDto;
use App\DTO\Note\Requests\ListNotesRequestDto;
use App\DTO\Note\Requests\ShowNoteRequestDto;
use App\DTO\Note\Requests\UpdateNoteArchiveRequestDto;
use App\DTO\Note\Requests\UpdateNoteFavoriteRequestDto;
use App\DTO\Note\Requests\UpdateNotePinRequestDto;
use App\DTO\Note\Requests\UpdateNoteProtectionRequestDto;
use App\DTO\Note\Requests\UpdateNoteRequestDto;
use App\DTO\Note\Requests\UpdateNoteShareRequestDto;
use App\DTO\Note\Responses\ListNotesResponseDto;
use App\DTO\Note\Responses\NoteArchiveResponseDto;
use App\DTO\Note\Responses\NoteFavoriteResponseDto;
use App\DTO\Note\Responses\NoteMessageResponseDto;
use App\DTO\Note\Responses\NotePinResponseDto;
use App\DTO\Note\Responses\NoteProtectionResponseDto;
use App\DTO\Note\Responses\NoteShareResponseDto;
use App\DTO\Note\Responses\ShowNoteResponseDto;
use App\Mappings\Note\DeletedNoteToNoteMessageResponseDto;
use App\Mappings\Note\NotesPaginatorToListNotesResponseDto;
use App\Mappings\Note\NoteToCreatedNoteResponseDto;
use App\Mappings\Note\NoteToNoteArchiveResponseDto;
use App\Mappings\Note\NoteToNoteFavoriteResponseDto;
use App\Mappings\Note\NoteToNotePinResponseDto;
use App\Mappings\Note\NoteToNoteProtectionResponseDto;
use App\Mappings\Note\NoteToNoteShareResponseDto;
use App\Mappings\Note\NoteToShowNoteResponseDto;
use App\Mappings\Note\NoteToUpdatedNoteResponseDto;
use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use App\Repositories\Interface\IFolderRepository;
use App\Repositories\Interface\INoteRepository;
use App\Repositories\Interface\IUserRepository;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\INoteService;
use App\Services\Interface\IPasswordHasherService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NoteService implements INoteService
{
    private const NOTE_LIST_CACHE_SECONDS = 60;

    public function __construct(
        private readonly INoteRepository $noteRepository,
        private readonly IFolderRepository $folderRepository,
        private readonly IUserRepository $userRepository,
        private readonly IJwtTokenService $jwtTokenService,
        private readonly NotesPaginatorToListNotesResponseDto $notesPaginatorToListNotesResponseDto,
        private readonly NoteToShowNoteResponseDto $noteToShowNoteResponseDto,
        private readonly NoteToCreatedNoteResponseDto $noteToCreatedNoteResponseDto,
        private readonly NoteToUpdatedNoteResponseDto $noteToUpdatedNoteResponseDto,
        private readonly DeletedNoteToNoteMessageResponseDto $deletedNoteToNoteMessageResponseDto,
        private readonly NoteToNotePinResponseDto $noteToNotePinResponseDto,
        private readonly NoteToNoteFavoriteResponseDto $noteToNoteFavoriteResponseDto,
        private readonly NoteToNoteArchiveResponseDto $noteToNoteArchiveResponseDto,
        private readonly IPasswordHasherService $passwordHasherService,
        private readonly NoteToNoteProtectionResponseDto $noteToNoteProtectionResponseDto,
        private readonly NoteToNoteShareResponseDto $noteToNoteShareResponseDto,
    ) {}

    public function list(ListNotesRequestDto $request): ListNotesResponseDto
    {
        $user = $this->authorize($request->token);
        $this->validateWorkspaceFolderFilter($request, $user['id']);
        $cacheKey = $this->noteListCacheKey($user['id'], $request);
        $cached = Cache::get($cacheKey);

        if ($cached instanceof ListNotesResponseDto) {
            return $cached;
        }

        $response = $this->notesPaginatorToListNotesResponseDto->transform(
            $this->noteRepository->paginateByUser($user['id'], $request)
        );

        Cache::put($cacheKey, $response, now()->addSeconds(self::NOTE_LIST_CACHE_SECONDS));
        $this->rememberNoteListCacheKey($user['id'], $cacheKey);

        return $response;
    }

    public function show(ShowNoteRequestDto $request): ShowNoteResponseDto
    {
        return $this->noteToShowNoteResponseDto->transform($this->findOwnedNote($request->id, $request->token));
    }

    public function create(CreateNoteRequestDto $request): NoteMessageResponseDto
    {
        return DB::transaction(function () use ($request): NoteMessageResponseDto {
            $user = $this->authorize($request->token);
            $this->ensureCanCreateNote($this->findUserWithPlanForUpdate($user['id']));
            $folder = $this->resolveOwnedFolder($request->folderId, $user['id']);

            $note = $this->noteRepository->create([
                'CreatedBy' => $user['email'],
                'user_id' => $user['id'],
                'workspace_id' => $folder?->workspace_id ?? $request->workspaceId,
                'folder_id' => $folder?->Id,
                'title' => $request->title,
                'content' => $request->content,
                'color' => $request->color,
                'visibility' => $request->visibility,
                'DeleteFlag' => false,
            ], $request->labelIds);

            $this->forgetNoteListCache($user['id']);

            return $this->noteToCreatedNoteResponseDto->transform($note);
        });
    }

    public function update(UpdateNoteRequestDto $request): NoteMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $note = $this->findOwnedNoteByUserId($request->id, $user['id']);
        $folder = $this->resolveOwnedFolder($request->folderId, $user['id']);

        $data = array_filter([
            'workspace_id' => $folder?->workspace_id ?? $request->workspaceId,
            'folder_id' => $folder?->Id,
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color,
            'visibility' => $request->visibility,
            'UpdatedBy' => $user['email'],
        ], fn ($value) => $value !== null);

        $updatedNote = $this->noteRepository->update($note, $data, $request->labelIds);
        $this->forgetNoteListCache($user['id']);

        return $this->noteToUpdatedNoteResponseDto->transform($updatedNote);
    }

    public function delete(DeleteNoteRequestDto $request): NoteMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $this->noteRepository->softDelete($this->findOwnedNoteByUserId($request->id, $user['id']), $user['email']);
        $this->forgetNoteListCache($user['id']);

        return $this->deletedNoteToNoteMessageResponseDto->transform();
    }

    public function updatePin(UpdateNotePinRequestDto $request): NotePinResponseDto
    {
        $user = $this->authorize($request->token);
        $note = $this->noteRepository->updatePin(
            $this->findOwnedBaseNoteByUserId($request->id, $user['id']),
            $request->isPinned,
            $user['email'],
        );
        $this->forgetNoteListCache($user['id']);

        return $this->noteToNotePinResponseDto->transform($note);
    }

    public function updateFavorite(UpdateNoteFavoriteRequestDto $request): NoteFavoriteResponseDto
    {
        $user = $this->authorize($request->token);
        $note = $this->noteRepository->updateFavorite(
            $this->findOwnedBaseNoteByUserId($request->id, $user['id']),
            $request->isFavorite,
            $user['email'],
        );
        $this->forgetNoteListCache($user['id']);

        return $this->noteToNoteFavoriteResponseDto->transform($note);
    }

    public function updateArchive(UpdateNoteArchiveRequestDto $request): NoteArchiveResponseDto
    {
        $user = $this->authorize($request->token);
        $note = $this->noteRepository->updateArchive(
            $this->findOwnedBaseNoteByUserId($request->id, $user['id']),
            $request->archived,
            $user['email'],
        );
        $this->forgetNoteListCache($user['id']);

        return $this->noteToNoteArchiveResponseDto->transform($note);
    }

    public function updateProtection(UpdateNoteProtectionRequestDto $request): NoteProtectionResponseDto
    {
        $user = $this->authorize($request->token);
        $note = $this->findOwnedBaseNoteByUserId($request->id, $user['id']);

        if ($request->isProtected) {
            $hashedPassword = $this->passwordHasherService->hash($request->password);
            $updatedNote = $this->noteRepository->updateProtection($note, true, $hashedPassword, $user['email']);
            $message = 'Note protection enabled successfully.';
        } else {
            if (! $note->is_protected || ! $note->note_password || ! $this->passwordHasherService->verify($request->password, $note->note_password)) {
                throw ValidationException::withMessages(['password' => ['Note password is incorrect.']]);
            }
            $updatedNote = $this->noteRepository->updateProtection($note, false, null, $user['email']);
            $message = 'Note protection disabled successfully.';
        }

        $this->forgetNoteListCache($user['id']);

        return $this->noteToNoteProtectionResponseDto->transform($updatedNote, $message);
    }

    public function updateShare(UpdateNoteShareRequestDto $request): NoteShareResponseDto
    {
        $user = $this->authorize($request->token);
        $note = $this->findOwnedBaseNoteByUserId($request->id, $user['id']);

        $updatedNote = $this->noteRepository->updateShare($note, $request->visibility, $user['email']);
        $this->forgetNoteListCache($user['id']);

        $message = 'Note sharing settings updated successfully.';

        return $this->noteToNoteShareResponseDto->transform($updatedNote, $message);
    }

    private function findOwnedNote(string $id, string $token): Note
    {
        $user = $this->authorize($token);

        return $this->findOwnedNoteByUserId($id, $user['id']);
    }

    private function findOwnedNoteByUserId(string $id, string $userId): Note
    {
        $note = $this->noteRepository->findOwnedById($id, $userId);

        if ($note === null) {
            throw ValidationException::withMessages(['id' => ['Note does not exist.']]);
        }

        return $note;
    }

    private function findOwnedBaseNoteByUserId(string $id, string $userId): Note
    {
        $note = $this->noteRepository->findOwnedBaseById($id, $userId);

        if ($note === null) {
            throw ValidationException::withMessages(['id' => ['Note does not exist.']]);
        }

        return $note;
    }

    private function authorize(string $token): array
    {
        $payload = $this->jwtTokenService->verify($token);

        if ($payload === null || ! isset($payload['sub'], $payload['email'])) {
            throw ValidationException::withMessages(['authorization' => ['Invalid or expired token.']]);
        }

        return ['id' => $payload['sub'], 'email' => $payload['email']];
    }

    private function findUserWithPlanForUpdate(string $userId): User
    {
        $user = $this->userRepository->findByIdWithPlanForUpdate($userId);

        if ($user === null) {
            throw ValidationException::withMessages(['authorization' => ['Invalid or expired token.']]);
        }

        return $user;
    }

    private function ensureCanCreateNote(User $user): void
    {
        $plan = $user->plan;

        if ($plan === null || ! $plan->status || $plan->DeleteFlag) {
            throw ValidationException::withMessages(['plan_id' => ['Your plan is inactive or unavailable.']]);
        }

        if ($plan->max_notes !== null && $this->noteRepository->countActiveByUser($user->Id) >= $plan->max_notes) {
            throw ValidationException::withMessages(['plan_id' => ['Your plan note limit has been reached.']]);
        }
    }

    private function resolveOwnedFolder(?string $folderId, string $userId): ?Folder
    {
        if ($folderId === null) {
            return null;
        }

        $folder = $this->folderRepository->findOwnedById($folderId, $userId);

        if ($folder === null) {
            throw ValidationException::withMessages(['folder_id' => ['Folder does not exist.']]);
        }

        return $folder;
    }

    private function validateWorkspaceFolderFilter(ListNotesRequestDto $request, string $userId): void
    {
        if ($request->folderId === null) {
            return;
        }

        $folder = $this->folderRepository->findOwnedById($request->folderId, $userId);

        if ($folder === null) {
            throw ValidationException::withMessages(['folder_id' => ['Folder does not exist.']]);
        }

        if ($request->workspaceId !== null && $folder->workspace_id !== $request->workspaceId) {
            throw ValidationException::withMessages([
                'folder_id' => ['Folder does not belong to this workspace.'],
            ]);
        }
    }

    private function noteListCacheKey(string $userId, ListNotesRequestDto $request): string
    {
        return 'notes:list:'.md5(json_encode([
            'user_id' => $userId,
            'workspace_id' => $request->workspaceId,
            'folder_id' => $request->folderId,
            'label_id' => $request->labelId,
            'visibility' => $request->visibility,
            'is_pinned' => $request->isPinned,
            'is_favorite' => $request->isFavorite,
            'is_protected' => $request->isProtected,
            'archived' => $request->archived,
            'search' => $request->search,
            'sort' => $request->sort,
            'direction' => $request->direction,
            'page' => $request->page,
            'per_page' => $request->perPage,
        ]));
    }

    private function rememberNoteListCacheKey(string $userId, string $cacheKey): void
    {
        $indexKey = 'notes:list:index:'.$userId;
        $cacheKeys = Cache::get($indexKey, []);

        if (! in_array($cacheKey, $cacheKeys, true)) {
            $cacheKeys[] = $cacheKey;
            Cache::put($indexKey, $cacheKeys, now()->addDay());
        }
    }

    private function forgetNoteListCache(string $userId): void
    {
        $indexKey = 'notes:list:index:'.$userId;

        foreach (Cache::get($indexKey, []) as $cacheKey) {
            Cache::forget($cacheKey);
        }

        Cache::forget($indexKey);
    }
}
