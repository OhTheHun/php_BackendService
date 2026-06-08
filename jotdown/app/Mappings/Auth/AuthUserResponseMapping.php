<?php

namespace App\Mappings\Auth;

use App\Models\User;

class AuthUserResponseMapping
{
    public static function transform(User $user, string $token): array
    {
        return [
            'user' => [
                'id' => $user->Id,
                'display_name' => $user->display_name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'avatar_url' => $user->avatar_url,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}
