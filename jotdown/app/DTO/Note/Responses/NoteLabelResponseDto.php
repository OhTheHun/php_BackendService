<?php

namespace App\DTO\Note\Responses;

class NoteLabelResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $color,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
        ];
    }
}
