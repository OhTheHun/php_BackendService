<?php

namespace App\Requests\Folder;

use App\DTO\Folder\Requests\ListFoldersRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class ListFoldersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'token' => (string) $this->bearerToken(),
            'workspace_id' => $this->route('workspaceId') !== null
                ? trim((string) $this->route('workspaceId'))
                : $this->input('workspace_id'),
        ]);
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'workspace_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F-]{36}$/'],
        ];
    }

    public function toDto(): ListFoldersRequestDto
    {
        $validated = $this->validated();

        return new ListFoldersRequestDto(
            $validated['token'],
            $validated['workspace_id'] ?? null,
        );
    }
}
