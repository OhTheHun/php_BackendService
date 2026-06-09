<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\CreateNoteRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateNoteRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
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
            'title' => ['description' => 'Note title.', 'example' => 'Tieu de ghi chu'],
            'content' => ['description' => 'Note content.', 'example' => 'Noi dung ghi chu'],
            'color' => ['description' => 'Hex note color.', 'example' => '#ffffff'],
            'visibility' => ['description' => 'private, shared, or public.', 'example' => 'private'],
            'label_ids' => ['description' => 'Array of label ids.', 'example' => ['019e95ec-5335-711c-9a4b-930f4653bd1d']],
        ];
    }

    public function toDto(): CreateNoteRequestDto
    {
        $validated = $this->validated();

        return new CreateNoteRequestDto(
            token: $validated['token'],
            workspaceId: $validated['workspace_id'] ?? null,
            folderId: $validated['folder_id'] ?? null,
            title: $validated['title'] ?? 'Ghi chú mới',
            content: $validated['content'] ?? null,
            color: $validated['color'] ?? '#ffffff',
            visibility: $validated['visibility'] ?? 'private',
            labelIds: $validated['label_ids'] ?? [],
        );
    }
}
