<?php

namespace App\DTO\User\Responses;

class UpdatedUserSummaryResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $displayName,
        public readonly string $email,
        public readonly ?string $avatarUrl,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'display_name' => $this->displayName,
            'email' => $this->email,
            'avatar_url' => $this->avatarUrl,
        ];
    }
}
