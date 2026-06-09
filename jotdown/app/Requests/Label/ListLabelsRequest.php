<?php

namespace App\Requests\Label;

use App\DTO\Label\Requests\ListLabelsRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class ListLabelsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'token' => (string) $this->bearerToken(),
        ]);
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
        ];
    }

    public function toDto(): ListLabelsRequestDto
    {
        $validated = $this->validated();

        return new ListLabelsRequestDto(
            $validated['token'],
        );
    }
}
