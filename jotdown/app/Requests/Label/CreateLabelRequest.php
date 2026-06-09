<?php

namespace App\Requests\Label;

use App\DTO\Label\Requests\CreateLabelRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateLabelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['token' => (string) $this->bearerToken()]);
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function toDto(): CreateLabelRequestDto
    {
        $validated = $this->validated();

        return new CreateLabelRequestDto(
            token: $validated['token'],
            name: $validated['name'],
            color: $validated['color'] ?? null,
        );
    }
}
