<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\UpdateNoteShareRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteShareRequest extends FormRequest
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
            'visibility' => ['required', 'in:private,public'],
        ];
    }

    public function toDto(): UpdateNoteShareRequestDto
    {
        $validated = $this->validated();

        return new UpdateNoteShareRequestDto(
            $validated['id'],
            $validated['token'],
            $validated['visibility'],
        );
    }
}
