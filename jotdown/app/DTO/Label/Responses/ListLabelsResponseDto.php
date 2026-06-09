<?php

namespace App\DTO\Label\Responses;

class ListLabelsResponseDto
{
    public function __construct(
        public readonly array $labels,
    ) {}

    public function toArray(): array
    {
        return [
            'labels' => array_map(fn (LabelResponseDto $label) => $label->toArray(), $this->labels),
        ];
    }
}
