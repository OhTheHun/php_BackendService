<?php

namespace App\DTO\User\Responses;

class UpdateUserAppearanceResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly UpdateUserAppearanceSettingsResponseDto $settings,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'settings' => $this->settings->toArray(),
        ];
    }
}
