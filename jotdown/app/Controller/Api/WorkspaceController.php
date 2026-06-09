<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\Workspace\CreateWorkspaceRequest;
use App\Requests\Workspace\DeleteWorkspaceRequest;
use App\Requests\Workspace\ListWorkspacesRequest;
use App\Requests\Workspace\UpdateWorkspaceRequest;
use App\Services\Interface\IWorkspaceService;
use Illuminate\Http\JsonResponse;

class WorkspaceController extends Controller
{
    public function __construct(
        private readonly IWorkspaceService $workspaceService,
    ) {}

    public function index(ListWorkspacesRequest $request): JsonResponse
    {
        return response()->json($this->workspaceService->list($request->toDto())->toArray());
    }

    public function store(CreateWorkspaceRequest $request): JsonResponse
    {
        return response()->json($this->workspaceService->create($request->toDto())->toArray(), 201);
    }

    public function update(UpdateWorkspaceRequest $request): JsonResponse
    {
        return response()->json($this->workspaceService->update($request->toDto())->toArray());
    }

    public function destroy(DeleteWorkspaceRequest $request): JsonResponse
    {
        return response()->json($this->workspaceService->delete($request->toDto())->toArray());
    }
}
