<?php

namespace App\Mappings\User;

use App\DTO\User\Responses\UpdateUserDisplayNameResponseDto;
use App\Models\User;

class UserToUpdateUserDisplayNameResponseDto
{
    public function __construct(
        private readonly UserToUpdatedUserSummaryResponseDto $userToUpdatedUserSummaryResponseDto,
    ) {}

    public function transform(User $user): UpdateUserDisplayNameResponseDto
    {
        return new UpdateUserDisplayNameResponseDto(
            message: 'Display name updated successfully.',
            user: $this->userToUpdatedUserSummaryResponseDto->transform($user),
        );
    }
}
