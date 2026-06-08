<?php

namespace App\DTO\Auth\Responses;

class AuthResponseDto
{
    public function __construct(
        public readonly AuthUserResponseDto $user,
        public readonly string $token,
        public readonly string $tokenType = 'Bearer',
    ) {
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'token' => $this->token,
            'token_type' => $this->tokenType,
        ];
    }
}
