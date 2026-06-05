<?php

namespace App\Requests\Auth;

use App\DTO\Auth\Requests\RegisterRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'display_name' => [
                'description' => 'Display name of the user.',
                'example' => 'Nguyen Van A',
            ],
            'email' => [
                'description' => 'Email address of the user.',
                'example' => 'user@example.com',
            ],
            'password' => [
                'description' => 'Password with at least 8 characters.',
                'example' => 'password123',
            ],
        ];
    }

    public function toDto(): RegisterRequestDto
    {
        $validated = $this->validated();

        return new RegisterRequestDto(
            displayName: $validated['display_name'],
            email: $validated['email'],
            password: $validated['password'],
        );
    }
}
