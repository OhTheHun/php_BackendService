<?php

namespace App\Services;

use App\DTO\Folder\Requests\ListFoldersRequestDto;
use App\DTO\Folder\Requests\CreateFolderRequestDto;
use App\DTO\Folder\Requests\DeleteFolderRequestDto;
use App\DTO\Folder\Requests\UpdateFolderRequestDto;
use App\DTO\Folder\Responses\FolderMessageResponseDto;
use App\DTO\Folder\Responses\ListFoldersResponseDto;
use App\Mappings\Folder\FolderToFolderResponseDto;
use App\Models\Folder;
use App\Repositories\Interface\IFolderRepository;
use App\Repositories\Interface\IWorkspaceRepository;
use App\Services\Interface\IFolderService;
use App\Services\Interface\IJwtTokenService;
use Illuminate\Validation\ValidationException;

class FolderService implements IFolderService
{
    public function __construct(
        private readonly IFolderRepository $folderRepository,
        private readonly IWorkspaceRepository $workspaceRepository,
        private readonly IJwtTokenService $jwtTokenService,
        private readonly FolderToFolderResponseDto $folderToFolderResponseDto,
    ) {}

    public function list(ListFoldersRequestDto $request): ListFoldersResponseDto
    {
        $user = $this->authorize($request->token);

        if ($request->workspaceId !== null) {
            $this->assertOwnedWorkspace($request->workspaceId, $user['id']);
        }

        $folders = $this->folderRepository
            ->getByUser($user['id'], $request)
            ->map(fn ($folder) => $this->folderToFolderResponseDto->transform($folder))
            ->all();

        return new ListFoldersResponseDto($folders);
    }

    public function create(CreateFolderRequestDto $request): FolderMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $this->assertOwnedWorkspace($request->workspaceId, $user['id']);
        $folder = $this->folderRepository->create([
            'CreatedBy' => $user['email'],
            'user_id' => $user['id'],
            'workspace_id' => $request->workspaceId,
            'name' => $request->name,
            'DeleteFlag' => false,
        ]);

        return new FolderMessageResponseDto(
            message: 'Folder created successfully.',
            folder: $this->folderToFolderResponseDto->transform($folder),
        );
    }

    public function update(UpdateFolderRequestDto $request): FolderMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $folder = $this->folderRepository->update($this->findOwnedFolder($request->id, $user['id']), [
            'name' => $request->name,
            'UpdatedBy' => $user['email'],
        ]);

        return new FolderMessageResponseDto(
            message: 'Folder updated successfully.',
            folder: $this->folderToFolderResponseDto->transform($folder),
            includeCreatedAt: false,
        );
    }

    public function delete(DeleteFolderRequestDto $request): FolderMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $folder = $this->findOwnedFolder($request->id, $user['id']);

        if ((int) ($folder->notes_count ?? 0) > 0) {
            throw ValidationException::withMessages([
                'folder_id' => ['Folder is currently in use.'],
            ]);
        }

        $this->folderRepository->softDelete($folder, $user['email']);

        return new FolderMessageResponseDto('Folder deleted successfully.');
    }

    private function findOwnedFolder(string $id, string $userId): Folder
    {
        $folder = $this->folderRepository->findOwnedById($id, $userId);

        if ($folder === null) {
            throw ValidationException::withMessages(['folder_id' => ['Folder does not exist.']]);
        }

        return $folder;
    }

    private function assertOwnedWorkspace(string $workspaceId, string $userId): void
    {
        if ($this->workspaceRepository->findOwnedById($workspaceId, $userId) === null) {
            throw ValidationException::withMessages(['workspace_id' => ['Workspace does not exist.']]);
        }
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
