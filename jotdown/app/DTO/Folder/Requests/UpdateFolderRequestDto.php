<?php

namespace App\DTO\Folder\Requests;

class UpdateFolderRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly string $name,
    ) {}
}
