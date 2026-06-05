<?php

namespace App\Requests\Auth;

use App\DTO\Auth\Requests\SendResetPasswordOtpRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class SendResetPasswordOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Email address that will receive the OTP.',
                'example' => 'user@example.com',
            ],
        ];
    }

    public function toDto(): SendResetPasswordOtpRequestDto
    {
        $validated = $this->validated();

        return new SendResetPasswordOtpRequestDto(
            email: $validated['email'],
        );
    }
}
