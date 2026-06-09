<?php

namespace App\Requests\User;

use App\DTO\User\Requests\UpdateUserAvatarRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAvatarRequest extends FormRequest
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
            'id' => ['required', 'regex:/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/'],
            'avatar_url' => ['required', 'url', 'max:1000'],
            'token' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'avatar_url' => [
                'description' => 'Cloudinary avatar URL returned after direct upload.',
                'example' => 'https://res.cloudinary.com/dqicyabgq/image/upload/v123/Jotdown/Avatar/avatar.png',
            ],
        ];
    }

    public function toDto(): UpdateUserAvatarRequestDto
    {
        $validated = $this->validated();

        return new UpdateUserAvatarRequestDto(
            id: $validated['id'],
            avatarUrl: $validated['avatar_url'],
            token: $validated['token'],
        );
    }
}
