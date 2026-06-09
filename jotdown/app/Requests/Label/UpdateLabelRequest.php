<?php

namespace App\Requests\Label;

use App\DTO\Label\Requests\UpdateLabelRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLabelRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function toDto(): UpdateLabelRequestDto
    {
        $validated = $this->validated();

        return new UpdateLabelRequestDto(
            id: $validated['id'],
            token: $validated['token'],
            name: $validated['name'] ?? null,
            color: $validated['color'] ?? null,
        );
    }
}
