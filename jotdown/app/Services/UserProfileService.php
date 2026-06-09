<?php

namespace App\Services;

use App\DTO\User\Requests\GetUserProfileRequestDto;
use App\DTO\User\Responses\GetUserProfileResponseDto;
use App\Mappings\User\UserAndStatsToGetUserProfileResponseDto;
use App\Repositories\Interface\IUserRepository;
use App\Services\Interface\IUserProfileService;
use Illuminate\Validation\ValidationException;

class UserProfileService implements IUserProfileService
{
    public function __construct(
        private readonly IUserRepository $userRepository,
        private readonly UserAndStatsToGetUserProfileResponseDto $userAndStatsToGetUserProfileResponseDto,
    ) {}

    public function getProfile(GetUserProfileRequestDto $request): GetUserProfileResponseDto
    {
        $profileData = $this->userRepository->getProfileData($request->id);

        if ($profileData === null) {
            throw ValidationException::withMessages([
                'id' => ['User does not exist.'],
            ]);
        }

        return $this->userAndStatsToGetUserProfileResponseDto->transform(
            $profileData['user'],
            $profileData['stats'],
        );
    }
}
