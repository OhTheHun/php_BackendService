<?php

namespace App\DTO\Label\Responses;

class LabelMessageResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly ?LabelResponseDto $label = null,
    ) {}

    public function toArray(): array
    {
        $data = ['message' => $this->message];

        if ($this->label !== null) {
            $data['label'] = $this->label->toArray();
        }

        return $data;
    }
}
