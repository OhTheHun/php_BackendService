<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\UpdateNoteArchiveRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteArchiveRequest extends FormRequest
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
            'archived' => ['required', 'boolean'],
        ];
    }

    public function bodyParameters(): array
    {
        return ['archived' => ['description' => 'Archive status.', 'example' => true]];
    }

    public function toDto(): UpdateNoteArchiveRequestDto
    {
        $validated = $this->validated();

        return new UpdateNoteArchiveRequestDto($validated['id'], $validated['token'], $this->boolean('archived'));
    }
}
