<?php

namespace App\Services;

use App\DTO\Note\Requests\CreateNoteShareRequestDto;
use App\DTO\Note\Requests\GetSharedNotesRequestDto;
use App\DTO\Note\Responses\CreateNoteShareResponseDto;
use App\DTO\Note\Responses\GetSharedNotesResponseDto;
use App\Mappings\Note\GetSharedNoteResponseDtosToGetSharedNotesResponseDto;
use App\Mappings\Note\NoteShareToCreateNoteShareResponseDto;
use App\Mappings\Note\NoteShareToGetSharedNoteResponseDto;
use App\Repositories\Interface\INoteRepository;
use App\Repositories\Interface\INoteShareRepository;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\INoteShareService;
use Illuminate\Validation\ValidationException;

class NoteShareService implements INoteShareService
{
    public function __construct(
        private readonly INoteShareRepository $noteShareRepository,
        private readonly INoteRepository $noteRepository,
        private readonly IJwtTokenService $jwtTokenService,
        private readonly NoteShareToGetSharedNoteResponseDto $noteShareToGetSharedNoteResponseDto,
        private readonly GetSharedNoteResponseDtosToGetSharedNotesResponseDto $getSharedNoteResponseDtosToGetSharedNotesResponseDto,
        private readonly NoteShareToCreateNoteShareResponseDto $noteShareToCreateNoteShareResponseDto,
    ) {}

    public function getSharedNotes(GetSharedNotesRequestDto $request): GetSharedNotesResponseDto
    {
        $startedAt = microtime(true);

        $notes = $this->noteShareRepository
            ->getSharedNotes($request)
            ->map(fn ($noteShare) => $this->noteShareToGetSharedNoteResponseDto->transform($noteShare))
            ->all();

        $elapsedMs = round((microtime(true) - $startedAt) * 1000, 2);

        return $this->getSharedNoteResponseDtosToGetSharedNotesResponseDto->transform($notes, $elapsedMs);
    }

    public function createShare(CreateNoteShareRequestDto $request): CreateNoteShareResponseDto
    {
        $user = $this->authorize($request->token);

        $note = $this->noteRepository->findOwnedBaseById($request->noteId, $user['id']);
        if ($note === null) {
            throw ValidationException::withMessages(['id' => ['Note does not exist.']]);
        }

        $noteShare = $this->noteShareRepository->create([
            'note_id' => $request->noteId,
            'shared_by_user_id' => $user['id'],
            'shared_with_email' => $request->email,
            'permission' => $request->permission,
            'CreatedBy' => $user['email'],
            'DeleteFlag' => false,
        ]);

        return $this->noteShareToCreateNoteShareResponseDto->transform($noteShare, 'Note shared successfully.');
    }

    private function authorize(string $token): array
    {
        $payload = $this->jwtTokenService->verify($token);

        if ($payload === null || ! isset($payload['sub'], $payload['email'])) {
            throw ValidationException::withMessages(['authorization' => ['Invalid or expired token.']]);
        }

        return ['id' => $payload['sub'], 'email' => $payload['email']];
    }
}
