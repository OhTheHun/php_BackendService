<?php

namespace App\Services\Interface;

use App\DTO\Folder\Requests\ListFoldersRequestDto;
use App\DTO\Folder\Requests\CreateFolderRequestDto;
use App\DTO\Folder\Requests\DeleteFolderRequestDto;
use App\DTO\Folder\Requests\UpdateFolderRequestDto;
use App\DTO\Folder\Responses\FolderMessageResponseDto;
use App\DTO\Folder\Responses\ListFoldersResponseDto;

interface IFolderService
{
    public function list(ListFoldersRequestDto $request): ListFoldersResponseDto;

    public function create(CreateFolderRequestDto $request): FolderMessageResponseDto;

    public function update(UpdateFolderRequestDto $request): FolderMessageResponseDto;

    public function delete(DeleteFolderRequestDto $request): FolderMessageResponseDto;
}
