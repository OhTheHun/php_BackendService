<?php

namespace App\DTO\Note\Requests;

class ShowNoteRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
    ) {}
}
