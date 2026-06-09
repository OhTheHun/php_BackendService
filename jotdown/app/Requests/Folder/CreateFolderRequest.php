<?php

namespace App\Requests\Folder;

use App\DTO\Folder\Requests\CreateFolderRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateFolderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'workspace_id' => trim((string) $this->route('workspaceId')),
            'token' => (string) $this->bearerToken(),
        ]);
    }

    public function rules(): array
    {
        return [
            'workspace_id' => ['required', 'regex:/^[0-9a-fA-F-]{36}$/'],
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDto(): CreateFolderRequestDto
    {
        $validated = $this->validated();

        return new CreateFolderRequestDto(
            workspaceId: $validated['workspace_id'],
            token: $validated['token'],
            name: $validated['name'],
        );
    }
}
