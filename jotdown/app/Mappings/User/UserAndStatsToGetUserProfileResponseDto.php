<?php

namespace App\Mappings\User;

use App\DTO\User\Responses\GetUserProfileResponseDto;
use App\DTO\User\Responses\GetUserProfileStatsResponseDto;
use App\DTO\User\Responses\GetUserProfileUserResponseDto;
use App\Models\User;

class UserAndStatsToGetUserProfileResponseDto
{
    public function transform(User $user, array $stats): GetUserProfileResponseDto
    {
        return new GetUserProfileResponseDto(
            user: new GetUserProfileUserResponseDto(
                id: $user->Id,
                displayName: $user->display_name,
                email: $user->email,
                role: $user->role,
                status: $user->status,
                avatarUrl: $user->avatar_url,
                theme: $user->theme,
                fontSize: $user->font_size,
                defaultNoteColor: $user->default_note_color,
                planId: $user->plan_id,
                planName: $user->plan_name ?? 'Free',
                emailVerifiedAt: $user->email_verified_at?->toISOString(),
                lastLoginAt: $user->last_login_at?->toISOString(),
                createdAt: $user->CreatedTime->toISOString(),
            ),
            stats: new GetUserProfileStatsResponseDto(
                totalNotes: $stats['total_notes'],
                totalWorkspaces: $stats['total_workspaces'],
                totalLabels: $stats['total_labels'],
                sharedNotes: $stats['shared_notes'],
            ),
        );
    }
}
