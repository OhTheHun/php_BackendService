<?php

namespace App\Services;

use App\DTO\Workspace\Requests\CreateWorkspaceRequestDto;
use App\DTO\Workspace\Requests\DeleteWorkspaceRequestDto;
use App\DTO\Workspace\Requests\ListWorkspacesRequestDto;
use App\DTO\Workspace\Requests\UpdateWorkspaceRequestDto;
use App\DTO\Workspace\Responses\ListWorkspacesResponseDto;
use App\DTO\Workspace\Responses\WorkspaceMessageResponseDto;
use App\Mappings\Workspace\WorkspaceToWorkspaceResponseDto;
use App\Models\User;
use App\Models\Workspace;
use App\Repositories\Interface\IUserRepository;
use App\Repositories\Interface\IWorkspaceRepository;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\IWorkspaceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkspaceService implements IWorkspaceService
{
    public function __construct(
        private readonly IWorkspaceRepository $workspaceRepository,
        private readonly IUserRepository $userRepository,
        private readonly IJwtTokenService $jwtTokenService,
        private readonly WorkspaceToWorkspaceResponseDto $workspaceToWorkspaceResponseDto,
    ) {}

    public function list(ListWorkspacesRequestDto $request): ListWorkspacesResponseDto
    {
        $user = $this->authorize($request->token);

        $workspaces = $this->workspaceRepository
            ->getByUser($user['id'])
            ->map(fn ($workspace) => $this->workspaceToWorkspaceResponseDto->transform($workspace))
            ->all();

        return new ListWorkspacesResponseDto($workspaces);
    }

    public function create(CreateWorkspaceRequestDto $request): WorkspaceMessageResponseDto
    {
        return DB::transaction(function () use ($request): WorkspaceMessageResponseDto {
            $user = $this->authorize($request->token);
            $this->ensureCanCreateWorkspace($this->findUserWithPlanForUpdate($user['id']));
            $workspace = $this->workspaceRepository->create([
                'CreatedBy' => $user['email'],
                'user_id' => $user['id'],
                'name' => $request->name,
                'description' => $request->description,
                'DeleteFlag' => false,
            ]);

            return new WorkspaceMessageResponseDto(
                message: 'Workspace created successfully.',
                workspace: $this->workspaceToWorkspaceResponseDto->transform($workspace),
            );
        });
    }

    public function update(UpdateWorkspaceRequestDto $request): WorkspaceMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $workspace = $this->findOwnedWorkspace($request->id, $user['id']);
        $updatedWorkspace = $this->workspaceRepository->update($workspace, array_filter([
            'name' => $request->name,
            'description' => $request->description,
            'UpdatedBy' => $user['email'],
        ], fn ($value) => $value !== null));

        return new WorkspaceMessageResponseDto(
            message: 'Workspace updated successfully.',
            workspace: $this->workspaceToWorkspaceResponseDto->transform($updatedWorkspace),
            includeCreatedAt: false,
        );
    }

    public function delete(DeleteWorkspaceRequestDto $request): WorkspaceMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $workspace = $this->findOwnedWorkspace($request->id, $user['id']);

        if ((int) ($workspace->folders_count ?? 0) > 0 || (int) ($workspace->notes_count ?? 0) > 0) {
            throw ValidationException::withMessages([
                'workspace_id' => ['Workspace is currently in use.'],
            ]);
        }

        $this->workspaceRepository->softDelete($workspace, $user['email']);

        return new WorkspaceMessageResponseDto('Workspace deleted successfully.');
    }

    private function findOwnedWorkspace(string $id, string $userId): Workspace
    {
        $workspace = $this->workspaceRepository->findOwnedById($id, $userId);

        if ($workspace === null) {
            throw ValidationException::withMessages(['id' => ['Workspace does not exist.']]);
        }

        return $workspace;
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

    private function ensureCanCreateWorkspace(User $user): void
    {
        $plan = $user->plan;

        if ($plan === null || ! $plan->status || $plan->DeleteFlag) {
            throw ValidationException::withMessages(['plan_id' => ['Your plan is inactive or unavailable.']]);
        }

        if ($plan->max_workspaces !== null && $this->workspaceRepository->countActiveByUser($user->Id) >= $plan->max_workspaces) {
            throw ValidationException::withMessages(['plan_id' => ['Your plan workspace limit has been reached.']]);
        }
    }
}
