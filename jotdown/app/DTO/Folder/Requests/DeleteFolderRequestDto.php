<?php

namespace App\DTO\Folder\Requests;

class DeleteFolderRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
    ) {}
}
