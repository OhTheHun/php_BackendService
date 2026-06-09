<?php

namespace App\DTO\User\Requests;

class UpdateUserDisplayNameRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $displayName,
        public readonly string $token,
    ) {
    }
}
