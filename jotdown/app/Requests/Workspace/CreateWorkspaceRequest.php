<?php

namespace App\Requests\Workspace;

use App\DTO\Workspace\Requests\CreateWorkspaceRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateWorkspaceRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function toDto(): CreateWorkspaceRequestDto
    {
        $validated = $this->validated();

        return new CreateWorkspaceRequestDto(
            token: $validated['token'],
            name: $validated['name'],
            description: $validated['description'] ?? null,
        );
    }
}
