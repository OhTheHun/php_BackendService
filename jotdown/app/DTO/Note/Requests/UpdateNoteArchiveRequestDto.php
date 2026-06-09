<?php

namespace App\DTO\Note\Requests;

class UpdateNoteArchiveRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly bool $archived,
    ) {}
}
