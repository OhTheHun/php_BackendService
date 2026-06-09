<?php

namespace App\DTO\User\Responses;

class ChangeUserPasswordResponseDto
{
    public function __construct(
        public readonly string $message,
    ) {
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
