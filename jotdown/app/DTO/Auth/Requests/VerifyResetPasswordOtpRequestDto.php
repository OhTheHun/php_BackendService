<?php

namespace App\DTO\Auth\Requests;

class VerifyResetPasswordOtpRequestDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $otp,
        public readonly string $password,
    ) {
    }
}
