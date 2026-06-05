<?php

namespace App\Requests\Note;

use App\DTO\Note\Requests\GetSharedNotesRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class GetSharedNotesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shared_with_email' => ['nullable', 'email', 'max:255'],
            'permission' => ['nullable', 'string', 'in:view,edit'],
            'include_expired' => ['nullable', 'boolean'],
        ];
    }

    public function queryParameters(): array
    {
        return [
            'shared_with_email' => [
                'description' => 'Filter notes shared with this email.',
                'example' => 'user@example.com',
            ],
            'permission' => [
                'description' => 'Filter by share permission.',
                'example' => 'view',
            ],
            'include_expired' => [
                'description' => 'Include expired shared notes.',
                'example' => false,
            ],
        ];
    }

    public function toDto(): GetSharedNotesRequestDto
    {
        $validated = $this->validated();

        return new GetSharedNotesRequestDto(
            sharedWithEmail: $validated['shared_with_email'] ?? null,
            permission: $validated['permission'] ?? null,
            includeExpired: $this->boolean('include_expired'),
        );
    }
}
