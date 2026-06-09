<?php

namespace App\Requests\User;

use App\DTO\User\Requests\UpdateUserAppearanceRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAppearanceRequest extends FormRequest
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
            'theme' => ['required', 'string', 'in:light,dark,system'],
            'font_size' => ['required', 'string', 'in:small,medium,large'],
            'default_note_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'token' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'theme' => [
                'description' => 'Application theme.',
                'example' => 'dark',
            ],
            'font_size' => [
                'description' => 'Default font size.',
                'example' => 'medium',
            ],
            'default_note_color' => [
                'description' => 'Default note color as hex code.',
                'example' => '#ffffff',
            ],
        ];
    }

    public function toDto(): UpdateUserAppearanceRequestDto
    {
        $validated = $this->validated();

        return new UpdateUserAppearanceRequestDto(
            id: $validated['id'],
            theme: $validated['theme'],
            fontSize: $validated['font_size'],
            defaultNoteColor: $validated['default_note_color'],
            token: $validated['token'],
        );
    }
}
