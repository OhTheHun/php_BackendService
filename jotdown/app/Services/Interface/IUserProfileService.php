<?php

namespace App\Services\Interface;

use App\DTO\User\Requests\GetUserProfileRequestDto;
use App\DTO\User\Responses\GetUserProfileResponseDto;

interface IUserProfileService
{
    public function getProfile(GetUserProfileRequestDto $request): GetUserProfileResponseDto;
}
