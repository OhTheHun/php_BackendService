<?php

namespace App\DTO\Label\Requests;

class DeleteLabelRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
    ) {}
}
