<?php

namespace App\Mappings\Auth;

use App\DTO\Auth\Responses\VerifyResetPasswordOtpResponseDto;

class VerifiedResetPasswordOtpToVerifyResetPasswordOtpResponseDto
{
    public function transform(): VerifyResetPasswordOtpResponseDto
    {
        return new VerifyResetPasswordOtpResponseDto(
            message: 'Password has been reset successfully.',
        );
    }
}
