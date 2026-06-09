<?php

namespace App\Requests\User;

use App\DTO\User\Requests\GetUserAvatarUploadSignatureRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class GetUserAvatarUploadSignatureRequest extends FormRequest
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
            'token' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [];
    }

    public function toDto(): GetUserAvatarUploadSignatureRequestDto
    {
        $validated = $this->validated();

        return new GetUserAvatarUploadSignatureRequestDto(
            id: $validated['id'],
            token: $validated['token'],
        );
    }
}
