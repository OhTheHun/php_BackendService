<?php

namespace App\DTO\User\Responses;

class UpdateUserAvatarResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly UpdatedUserSummaryResponseDto $user,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'user' => $this->user->toArray(),
        ];
    }
}
