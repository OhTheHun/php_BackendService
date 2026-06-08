<?php

namespace App\Mappings\Auth;

use App\DTO\Auth\Requests\RegisterRequestDto;

class RegisterRequestDtoToUser
{
    public function transform(RegisterRequestDto $request, string $hashedPassword): array
    {
        return [
            'CreatedBy' => $request->email,
            'display_name' => $request->displayName,
            'email' => $request->email,
            'password' => $hashedPassword,
            'role' => 'user',
            'status' => 'active',
            'theme' => 'light',
            'font_size' => 'medium',
            'default_note_color' => '#ffffff',
            'DeleteFlag' => false,
        ];
    }
}
