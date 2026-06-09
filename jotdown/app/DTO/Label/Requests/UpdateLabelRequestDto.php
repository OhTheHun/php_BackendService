<?php

namespace App\DTO\Label\Requests;

class UpdateLabelRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly ?string $name,
        public readonly ?string $color,
    ) {}
}
