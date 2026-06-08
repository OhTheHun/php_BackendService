<?php

namespace App\Mappings\Auth;

use App\DTO\Auth\Responses\AuthResponseDto;
use App\DTO\Auth\Responses\AuthUserResponseDto;
use App\Models\User;

class UserAndTokenToAuthResponseDto
{
    public function transform(User $user, string $token): AuthResponseDto
    {
        return new AuthResponseDto(
            user: new AuthUserResponseDto(
                id: $user->Id,
                displayName: $user->display_name,
                email: $user->email,
                role: $user->role,
                status: $user->status,
                avatarUrl: $user->avatar_url,
            ),
            token: $token,
        );
    }
}
