<?php

namespace App\Requests\Auth;

use App\DTO\Auth\Requests\VerifyResetPasswordOtpRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class VerifyResetPasswordOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Email address of the user.',
                'example' => 'user@example.com',
            ],
            'otp' => [
                'description' => 'Six-digit OTP sent by email.',
                'example' => '123456',
            ],
            'password' => [
                'description' => 'New password with at least 8 characters.',
                'example' => 'newpassword123',
            ],
        ];
    }

    public function toDto(): VerifyResetPasswordOtpRequestDto
    {
        $validated = $this->validated();

        return new VerifyResetPasswordOtpRequestDto(
            email: $validated['email'],
            otp: $validated['otp'],
            password: $validated['password'],
        );
    }
}
