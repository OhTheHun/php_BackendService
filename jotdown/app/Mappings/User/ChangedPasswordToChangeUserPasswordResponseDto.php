<?php

namespace App\Mappings\User;

use App\DTO\User\Responses\ChangeUserPasswordResponseDto;

class ChangedPasswordToChangeUserPasswordResponseDto
{
    public function transform(): ChangeUserPasswordResponseDto
    {
        return new ChangeUserPasswordResponseDto(
            message: 'Password changed successfully.',
        );
    }
}
