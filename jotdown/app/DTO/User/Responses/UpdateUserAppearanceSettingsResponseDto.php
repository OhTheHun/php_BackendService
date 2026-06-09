<?php

namespace App\DTO\User\Responses;

class UpdateUserAppearanceSettingsResponseDto
{
    public function __construct(
        public readonly string $theme,
        public readonly string $fontSize,
        public readonly string $defaultNoteColor,
    ) {}

    public function toArray(): array
    {
        return [
            'theme' => $this->theme,
            'font_size' => $this->fontSize,
            'default_note_color' => $this->defaultNoteColor,
        ];
    }
}
