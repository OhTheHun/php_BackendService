<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\UpdateNoteRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
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
            'workspace_id' => ['nullable', 'exists:workspaces,Id'],
            'folder_id' => ['nullable', 'exists:folders,Id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'visibility' => ['nullable', 'in:private,shared,public'],
            'label_ids' => ['nullable', 'array'],
            'label_ids.*' => ['exists:labels,Id'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'workspace_id' => ['description' => 'Workspace id.', 'example' => '019e95ec-5335-711c-9a4b-930f4653bd1d'],
            'folder_id' => ['description' => 'Folder id.', 'example' => '019e95ec-5335-711c-9a4b-930f4653bd1d'],
            'title' => ['description' => 'New note title.', 'example' => 'Tieu de moi'],
            'content' => ['description' => 'New note content.', 'example' => 'Noi dung moi'],
            'color' => ['description' => 'Hex note color.', 'example' => '#FEF3C7'],
            'visibility' => ['description' => 'private, shared, or public.', 'example' => 'shared'],
            'label_ids' => ['description' => 'Array of label ids.', 'example' => ['019e95ec-5335-711c-9a4b-930f4653bd1d']],
        ];
    }

    public function toDto(): UpdateNoteRequestDto
    {
        $validated = $this->validated();

        return new UpdateNoteRequestDto(
            id: $validated['id'],
            token: $validated['token'],
            workspaceId: $validated['workspace_id'] ?? null,
            folderId: $validated['folder_id'] ?? null,
            title: $validated['title'] ?? null,
            content: array_key_exists('content', $validated) ? $validated['content'] : null,
            color: $validated['color'] ?? null,
            visibility: $validated['visibility'] ?? null,
            labelIds: array_key_exists('label_ids', $validated) ? $validated['label_ids'] : null,
        );
    }
}
