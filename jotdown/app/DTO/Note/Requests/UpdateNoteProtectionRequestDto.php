<?php

namespace App\DTO\Note\Requests;

class UpdateNoteProtectionRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly bool $isProtected,
        public readonly string $password,
    ) {}
}
