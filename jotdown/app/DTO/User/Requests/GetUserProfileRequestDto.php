<?php

namespace App\DTO\User\Requests;

class GetUserProfileRequestDto
{
    public function __construct(
        public readonly string $id,
    ) {}
}
