<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\UpdateNoteProtectionRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteProtectionRequest extends FormRequest
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
            'is_protected' => ['required', 'boolean'],
            'password' => ['required', 'string', 'min:4'],
        ];
    }

    public function toDto(): UpdateNoteProtectionRequestDto
    {
        $validated = $this->validated();

        return new UpdateNoteProtectionRequestDto(
            $validated['id'],
            $validated['token'],
            $this->boolean('is_protected'),
            $validated['password'],
        );
    }
}
