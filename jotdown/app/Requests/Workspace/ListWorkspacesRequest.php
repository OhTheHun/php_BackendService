<?php

namespace App\Requests\Workspace;

use App\DTO\Workspace\Requests\ListWorkspacesRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class ListWorkspacesRequest extends FormRequest
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
        ];
    }

    public function toDto(): ListWorkspacesRequestDto
    {
        return new ListWorkspacesRequestDto($this->validated()['token']);
    }
}
