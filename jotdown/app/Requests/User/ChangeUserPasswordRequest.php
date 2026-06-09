<?php

namespace App\Requests\User;

use App\DTO\User\Requests\ChangeUserPasswordRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => trim((string) $this->route('id')),
            'token' => (string) $this->bearerToken(),
        ]);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'regex:/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/'],
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'token' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'current_password' => [
                'description' => 'Current password.',
                'example' => 'oldpassword123',
            ],
            'password' => [
                'description' => 'New password with at least 8 characters.',
                'example' => 'newpassword123',
            ],
        ];
    }

    public function toDto(): ChangeUserPasswordRequestDto
    {
        $validated = $this->validated();

        return new ChangeUserPasswordRequestDto(
            id: $validated['id'],
            currentPassword: $validated['current_password'],
            password: $validated['password'],
            token: $validated['token'],
        );
    }
}
