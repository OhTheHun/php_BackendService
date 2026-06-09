<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\User\ChangeUserPasswordRequest;
use App\Requests\User\GetUserAvatarUploadSignatureRequest;
use App\Requests\User\GetUserProfileRequest;
use App\Requests\User\UpdateUserAppearanceRequest;
use App\Requests\User\UpdateUserAvatarRequest;
use App\Requests\User\UpdateUserDisplayNameRequest;
use App\Services\Interface\IUserProfileService;
use App\Services\Interface\IUserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly IUserProfileService $userProfileService,
        private readonly IUserService $userService,
    ) {
    }

    public function getProfile(GetUserProfileRequest $request): JsonResponse
    {
        $responseDto = $this->userProfileService->getProfile($request->toDto());

        return response()->json($responseDto->toArray());
    }

    public function getAvatarUploadSignature(GetUserAvatarUploadSignatureRequest $request): JsonResponse
    {
        $responseDto = $this->userService->getAvatarUploadSignature($request->toDto());

        return response()->json($responseDto->toArray());
    }

    public function updateAvatar(UpdateUserAvatarRequest $request): JsonResponse
    {
        $responseDto = $this->userService->updateAvatar($request->toDto());

        return response()->json($responseDto->toArray());
    }

    public function updateDisplayName(UpdateUserDisplayNameRequest $request): JsonResponse
    {
        $responseDto = $this->userService->updateDisplayName($request->toDto());

        return response()->json($responseDto->toArray());
    }

    public function updateAppearance(UpdateUserAppearanceRequest $request): JsonResponse
    {
        $responseDto = $this->userService->updateAppearance($request->toDto());

        return response()->json($responseDto->toArray());
    }

    public function changePassword(ChangeUserPasswordRequest $request): JsonResponse
    {
        $responseDto = $this->userService->changePassword($request->toDto());

        return response()->json($responseDto->toArray());
    }
}
