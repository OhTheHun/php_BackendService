<?php

namespace App\DTO\Note\Requests;

class UpdateNotePinRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly bool $isPinned,
    ) {}
}
