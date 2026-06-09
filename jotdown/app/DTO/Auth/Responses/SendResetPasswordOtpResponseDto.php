<?php

namespace App\DTO\Auth\Responses;

class SendResetPasswordOtpResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $expiresAt,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'expires_at' => $this->expiresAt,
        ];
    }
}
