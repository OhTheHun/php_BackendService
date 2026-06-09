<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\DeleteNoteRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class DeleteNoteRequest extends FormRequest
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
        return ['id' => ['required', 'regex:/^[0-9a-fA-F-]{36}$/'], 'token' => ['required', 'string']];
    }

    public function bodyParameters(): array
    {
        return [];
    }

    public function toDto(): DeleteNoteRequestDto
    {
        $validated = $this->validated();

        return new DeleteNoteRequestDto($validated['id'], $validated['token']);
    }
}
