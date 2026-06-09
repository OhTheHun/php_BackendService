<?php

namespace App\Requests\User;

use App\DTO\User\Requests\GetUserProfileRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class GetUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => trim((string) $this->route('id')),
        ]);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'regex:/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/'],
        ];
    }

    public function pathParameters(): array
    {
        return [
            'id' => [
                'description' => 'User id returned from login response.',
                'example' => '019e95d6-7974-7057-be7d-a480436d815b',
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [];
    }

    public function toDto(): GetUserProfileRequestDto
    {
        $validated = $this->validated();

        return new GetUserProfileRequestDto(
            id: $validated['id'],
        );
    }
}
