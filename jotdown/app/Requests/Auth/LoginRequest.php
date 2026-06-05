<?php

namespace App\Requests\Auth;

use App\DTO\Auth\Requests\LoginRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Email address of the user.',
                'example' => 'user@example.com',
            ],
            'password' => [
                'description' => 'Password of the user.',
                'example' => 'password123',
            ],
        ];
    }

    public function toDto(): LoginRequestDto
    {
        $validated = $this->validated();

        return new LoginRequestDto(
            email: $validated['email'],
            password: $validated['password'],
        );
    }
}
