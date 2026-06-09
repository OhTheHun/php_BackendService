<?php

namespace App\Requests\Folder;

use App\DTO\Folder\Requests\DeleteFolderRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class DeleteFolderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => trim((string) $this->route('folderId')),
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

    public function toDto(): DeleteFolderRequestDto
    {
        $validated = $this->validated();

        return new DeleteFolderRequestDto($validated['id'], $validated['token']);
    }
}
