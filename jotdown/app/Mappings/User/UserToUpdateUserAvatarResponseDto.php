<?php

namespace App\Mappings\User;

use App\DTO\User\Responses\UpdateUserAvatarResponseDto;
use App\Models\User;

class UserToUpdateUserAvatarResponseDto
{
    public function __construct(
        private readonly UserToUpdatedUserSummaryResponseDto $userToUpdatedUserSummaryResponseDto,
    ) {}

    public function transform(User $user): UpdateUserAvatarResponseDto
    {
        return new UpdateUserAvatarResponseDto(
            message: 'Avatar updated successfully.',
            user: $this->userToUpdatedUserSummaryResponseDto->transform($user),
        );
    }
}
