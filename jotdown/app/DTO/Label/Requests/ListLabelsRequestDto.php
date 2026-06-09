<?php

namespace App\DTO\Label\Requests;

class ListLabelsRequestDto
{
    public function __construct(
        public readonly string $token,
    ) {}
}
