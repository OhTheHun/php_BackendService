<?php

namespace App\Requests\Plan;

use App\DTO\Plan\Requests\ListPlansRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class ListPlansRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function bodyParameters(): array
    {
        return [];
    }

    public function toDto(): ListPlansRequestDto
    {
        return new ListPlansRequestDto();
    }
}
