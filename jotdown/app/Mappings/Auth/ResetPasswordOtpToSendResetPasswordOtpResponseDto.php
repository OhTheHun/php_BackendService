<?php

namespace App\Mappings\Auth;

use App\DTO\Auth\Responses\SendResetPasswordOtpResponseDto;
use Carbon\CarbonInterface;

class ResetPasswordOtpToSendResetPasswordOtpResponseDto
{
    public function transform(CarbonInterface $expiresAt): SendResetPasswordOtpResponseDto
    {
        return new SendResetPasswordOtpResponseDto(
            message: 'OTP has been sent to your email.',
            expiresAt: $expiresAt->toISOString(),
        );
    }
}
