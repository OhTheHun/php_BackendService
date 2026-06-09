<?php

namespace App\Mappings\User;

use App\DTO\User\Responses\UpdatedUserSummaryResponseDto;
use App\Models\User;

class UserToUpdatedUserSummaryResponseDto
{
    public function transform(User $user): UpdatedUserSummaryResponseDto
    {
        return new UpdatedUserSummaryResponseDto(
            id: $user->Id,
            displayName: $user->display_name,
            email: $user->email,
            avatarUrl: $user->avatar_url,
        );
    }
}
