<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\ShowNoteRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class ShowNoteRequest extends FormRequest
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
            'id' => ['required', 'regex:/^[0-9a-fA-F-]{36}$/'],
            'token' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [];
    }

    public function toDto(): ShowNoteRequestDto
    {
        $validated = $this->validated();

        return new ShowNoteRequestDto($validated['id'], $validated['token']);
    }
}
