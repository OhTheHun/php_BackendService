<?php

namespace App\Requests\User;

use App\DTO\User\Requests\UpdateUserDisplayNameRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDisplayNameRequest extends FormRequest
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
            'display_name' => ['required', 'string', 'max:255'],
            'token' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'display_name' => [
                'description' => 'New display name.',
                'example' => 'Nguyen Van B',
            ],
        ];
    }

    public function toDto(): UpdateUserDisplayNameRequestDto
    {
        $validated = $this->validated();

        return new UpdateUserDisplayNameRequestDto(
            id: $validated['id'],
            displayName: $validated['display_name'],
            token: $validated['token'],
        );
    }
}
