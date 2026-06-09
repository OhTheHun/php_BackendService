<?php

namespace App\DTO\Note\Requests;

class DeleteNoteRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
    ) {}
}
