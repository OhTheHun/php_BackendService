<?php

namespace App\DTO\Auth\Requests;

class RegisterRequestDto
{
    public function __construct(
        public readonly string $displayName,
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
