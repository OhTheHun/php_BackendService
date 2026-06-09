<?php

namespace App\DTO\User\Requests;

class ChangeUserPasswordRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $currentPassword,
        public readonly string $password,
        public readonly string $token,
    ) {
    }
}
