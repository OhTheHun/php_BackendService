<?php

namespace App\DTO\User\Requests;

class UpdateUserAppearanceRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $theme,
        public readonly string $fontSize,
        public readonly string $defaultNoteColor,
        public readonly string $token,
    ) {}
}
