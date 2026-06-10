<?php

namespace App\DTO\Auth\Responses;

class VerifyResetPasswordOtpResponseDto
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
