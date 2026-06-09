<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\Folder\CreateFolderRequest;
use App\Requests\Folder\DeleteFolderRequest;
use App\Requests\Folder\ListFoldersRequest;
use App\Requests\Folder\UpdateFolderRequest;
use App\Services\Interface\IFolderService;
use Illuminate\Http\JsonResponse;

class FolderController extends Controller
{
    public function __construct(
        private readonly IFolderService $folderService,
    ) {}

    public function index(ListFoldersRequest $request): JsonResponse
    {
        return response()->json($this->folderService->list($request->toDto())->toArray());
    }

    public function store(CreateFolderRequest $request): JsonResponse
    {
        return response()->json($this->folderService->create($request->toDto())->toArray(), 201);
    }

    public function update(UpdateFolderRequest $request): JsonResponse
    {
        return response()->json($this->folderService->update($request->toDto())->toArray());
    }

    public function destroy(DeleteFolderRequest $request): JsonResponse
    {
        return response()->json($this->folderService->delete($request->toDto())->toArray());
    }
}
