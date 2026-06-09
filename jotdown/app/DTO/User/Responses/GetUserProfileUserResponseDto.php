<?php

namespace App\DTO\User\Responses;

class GetUserProfileUserResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $displayName,
        public readonly string $email,
        public readonly string $role,
        public readonly string $status,
        public readonly ?string $avatarUrl,
        public readonly ?string $theme,
        public readonly ?string $fontSize,
        public readonly ?string $defaultNoteColor,
        public readonly ?string $planId,
        public readonly string $planName,
        public readonly ?string $emailVerifiedAt,
        public readonly ?string $lastLoginAt,
        public readonly string $createdAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'display_name' => $this->displayName,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'avatar_url' => $this->avatarUrl,
            'theme' => $this->theme,
            'font_size' => $this->fontSize,
            'default_note_color' => $this->defaultNoteColor,
            'plan_id' => $this->planId,
            'plan_name' => $this->planName,
            'email_verified_at' => $this->emailVerifiedAt,
            'last_login_at' => $this->lastLoginAt,
            'created_at' => $this->createdAt,
        ];
    }
}
