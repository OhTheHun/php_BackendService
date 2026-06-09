<?php

namespace App\DTO\User\Responses;

class GetUserProfileResponseDto
{
    public function __construct(
        public readonly GetUserProfileUserResponseDto $user,
        public readonly GetUserProfileStatsResponseDto $stats,
    ) {}

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'stats' => $this->stats->toArray(),
        ];
    }
}
