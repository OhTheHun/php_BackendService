<?php

namespace App\Services\Interface;

use App\DTO\Workspace\Requests\CreateWorkspaceRequestDto;
use App\DTO\Workspace\Requests\DeleteWorkspaceRequestDto;
use App\DTO\Workspace\Requests\ListWorkspacesRequestDto;
use App\DTO\Workspace\Requests\UpdateWorkspaceRequestDto;
use App\DTO\Workspace\Responses\ListWorkspacesResponseDto;
use App\DTO\Workspace\Responses\WorkspaceMessageResponseDto;

interface IWorkspaceService
{
    public function list(ListWorkspacesRequestDto $request): ListWorkspacesResponseDto;

    public function create(CreateWorkspaceRequestDto $request): WorkspaceMessageResponseDto;

    public function update(UpdateWorkspaceRequestDto $request): WorkspaceMessageResponseDto;

    public function delete(DeleteWorkspaceRequestDto $request): WorkspaceMessageResponseDto;
}
