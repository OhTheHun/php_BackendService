<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\UpdateNoteFavoriteRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteFavoriteRequest extends FormRequest
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
            'is_favorite' => ['required', 'boolean'],
        ];
    }

    public function bodyParameters(): array
    {
        return ['is_favorite' => ['description' => 'Favorite status.', 'example' => true]];
    }

    public function toDto(): UpdateNoteFavoriteRequestDto
    {
        $validated = $this->validated();

        return new UpdateNoteFavoriteRequestDto($validated['id'], $validated['token'], $this->boolean('is_favorite'));
    }
}
