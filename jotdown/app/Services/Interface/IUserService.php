<?php

namespace App\Services\Interface;

use App\DTO\User\Requests\ChangeUserPasswordRequestDto;
use App\DTO\User\Requests\GetUserAvatarUploadSignatureRequestDto;
use App\DTO\User\Requests\UpdateUserAppearanceRequestDto;
use App\DTO\User\Requests\UpdateUserAvatarRequestDto;
use App\DTO\User\Requests\UpdateUserDisplayNameRequestDto;
use App\DTO\User\Responses\ChangeUserPasswordResponseDto;
use App\DTO\User\Responses\GetUserAvatarUploadSignatureResponseDto;
use App\DTO\User\Responses\UpdateUserAppearanceResponseDto;
use App\DTO\User\Responses\UpdateUserAvatarResponseDto;
use App\DTO\User\Responses\UpdateUserDisplayNameResponseDto;

interface IUserService
{
    public function getAvatarUploadSignature(GetUserAvatarUploadSignatureRequestDto $request): GetUserAvatarUploadSignatureResponseDto;

    public function updateAvatar(UpdateUserAvatarRequestDto $request): UpdateUserAvatarResponseDto;

    public function updateDisplayName(UpdateUserDisplayNameRequestDto $request): UpdateUserDisplayNameResponseDto;

    public function updateAppearance(UpdateUserAppearanceRequestDto $request): UpdateUserAppearanceResponseDto;

    public function changePassword(ChangeUserPasswordRequestDto $request): ChangeUserPasswordResponseDto;
}
