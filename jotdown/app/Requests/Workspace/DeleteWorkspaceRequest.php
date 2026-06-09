<?php

namespace App\Requests\Workspace;

use App\DTO\Workspace\Requests\DeleteWorkspaceRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class DeleteWorkspaceRequest extends FormRequest
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

    public function toDto(): DeleteWorkspaceRequestDto
    {
        $validated = $this->validated();

        return new DeleteWorkspaceRequestDto($validated['id'], $validated['token']);
    }
}
