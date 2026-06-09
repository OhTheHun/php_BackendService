<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\CreateNoteShareRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateNoteShareRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'permission' => ['required', 'in:view,edit'],
        ];
    }

    public function toDto(): CreateNoteShareRequestDto
    {
        $validated = $this->validated();

        return new CreateNoteShareRequestDto(
            $validated['id'],
            $validated['token'],
            $validated['email'],
            $validated['permission'],
        );
    }
}
