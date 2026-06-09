<?php

namespace App\DTO\Note\Requests;

class UpdateNoteFavoriteRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly bool $isFavorite,
    ) {}
}
