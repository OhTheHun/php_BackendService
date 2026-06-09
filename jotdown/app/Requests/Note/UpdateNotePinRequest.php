<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\UpdateNotePinRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNotePinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['id' => trim((string) $this->route('id')), 'token' => (string) $this->bearerToken()]);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'regex:/^[0-9a-fA-F-]{36}$/'],
            'token' => ['required', 'string'],
            'is_pinned' => ['required', 'boolean'],
        ];
    }

    public function bodyParameters(): array
    {
        return ['is_pinned' => ['description' => 'Pin status.', 'example' => true]];
    }

    public function toDto(): UpdateNotePinRequestDto
    {
        $validated = $this->validated();

        return new UpdateNotePinRequestDto($validated['id'], $validated['token'], $this->boolean('is_pinned'));
    }
}
