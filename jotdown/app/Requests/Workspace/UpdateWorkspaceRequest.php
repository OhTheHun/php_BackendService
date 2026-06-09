<?php

namespace App\Requests\Workspace;

use App\DTO\Workspace\Requests\UpdateWorkspaceRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkspaceRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function toDto(): UpdateWorkspaceRequestDto
    {
        $validated = $this->validated();

        return new UpdateWorkspaceRequestDto(
            id: $validated['id'],
            token: $validated['token'],
            name: $validated['name'] ?? null,
            description: $validated['description'] ?? null,
        );
    }
}
