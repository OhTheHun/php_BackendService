<?php

namespace App\Requests\Folder;

use App\DTO\Folder\Requests\UpdateFolderRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFolderRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDto(): UpdateFolderRequestDto
    {
        $validated = $this->validated();

        return new UpdateFolderRequestDto(
            id: $validated['id'],
            token: $validated['token'],
            name: $validated['name'],
        );
    }
}
