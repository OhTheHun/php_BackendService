<?php

namespace App\DTO\Auth\Requests;

class SendResetPasswordOtpRequestDto
{
    public function __construct(
        public readonly string $email,
    ) {}
}
