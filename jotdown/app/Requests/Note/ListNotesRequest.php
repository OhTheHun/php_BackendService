<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\ListNotesRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class ListNotesRequest extends FormRequest
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
            'workspace_id' => ['nullable', 'exists:workspaces,Id'],
            'folder_id' => ['nullable', 'exists:folders,Id'],
            'label_id' => ['nullable', 'exists:labels,Id'],
            'visibility' => ['nullable', 'in:private,shared,public'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_favorite' => ['nullable', 'boolean'],
            'is_protected' => ['nullable', 'boolean'],
            'archived' => ['nullable', 'boolean'],
            'search' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'string', 'in:updated_at,created_at,title'],
            'direction' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function bodyParameters(): array
    {
        return [];
    }

    public function toDto(): ListNotesRequestDto
    {
        $validated = $this->validated();

        return new ListNotesRequestDto(
            token: $validated['token'],
            workspaceId: $validated['workspace_id'] ?? null,
            folderId: $validated['folder_id'] ?? null,
            labelId: $validated['label_id'] ?? null,
            visibility: $validated['visibility'] ?? null,
            isPinned: array_key_exists('is_pinned', $validated) ? $this->boolean('is_pinned') : null,
            isFavorite: array_key_exists('is_favorite', $validated) ? $this->boolean('is_favorite') : null,
            isProtected: array_key_exists('is_protected', $validated) ? $this->boolean('is_protected') : null,
            archived: array_key_exists('archived', $validated) ? $this->boolean('archived') : null,
            search: $validated['search'] ?? null,
            sort: $validated['sort'] ?? 'updated_at',
            direction: $validated['direction'] ?? 'desc',
            page: (int) ($validated['page'] ?? 1),
            perPage: (int) ($validated['per_page'] ?? 20),
        );
    }
}
