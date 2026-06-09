<?php

namespace App\DTO\Label\Requests;

class CreateLabelRequestDto
{
    public function __construct(
        public readonly string $token,
        public readonly string $name,
        public readonly ?string $color,
    ) {}
}
