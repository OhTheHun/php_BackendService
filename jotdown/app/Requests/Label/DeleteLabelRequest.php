<?php

namespace App\Requests\Label;

use App\DTO\Label\Requests\DeleteLabelRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class DeleteLabelRequest extends FormRequest
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

    public function toDto(): DeleteLabelRequestDto
    {
        $validated = $this->validated();

        return new DeleteLabelRequestDto($validated['id'], $validated['token']);
    }
}
