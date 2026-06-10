<?php

namespace App\DTO\Auth\Responses;

class AuthUserResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $displayName,
        public readonly string $email,
        public readonly string $role,
        public readonly string $status,
        public readonly ?string $avatarUrl,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'display_name' => $this->displayName,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'avatar_url' => $this->avatarUrl,
        ];
    }
}
