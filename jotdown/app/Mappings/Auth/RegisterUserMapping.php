<?php

namespace App\Mappings\Auth;

class RegisterUserMapping
{
    public static function transform(array $data, string $hashedPassword): array
    {
        return [
            'CreatedBy' => $data['email'],
            'display_name' => $data['display_name'],
            'email' => $data['email'],
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
