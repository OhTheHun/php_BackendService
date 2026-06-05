<?php

namespace App\Mappings\Auth;

use Carbon\CarbonInterface;

class ResetPasswordOtpToPasswordResetToken
{
    public function transform(string $email, string $hashedOtp, string $hashedToken, CarbonInterface $expiresAt): array
    {
        return [
            'email' => $email,
            'otp' => $hashedOtp,
            'token' => $hashedToken,
            'created_at' => now(),
            'expires_at' => $expiresAt,
            'is_used' => false,
        ];
    }
}
