<?php

namespace App\DTO\User\Requests;

class UpdateUserAvatarRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $avatarUrl,
        public readonly string $token,
    ) {}
}
