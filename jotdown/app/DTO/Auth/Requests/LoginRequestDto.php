<?php

namespace App\DTO\Auth\Requests;

class LoginRequestDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
