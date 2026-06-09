<?php

namespace App\Services;

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
use App\Jobs\DeleteCloudinaryImageJob;
use App\Mappings\User\ChangedPasswordToChangeUserPasswordResponseDto;
use App\Mappings\User\CloudinarySignaturePayloadToGetUserAvatarUploadSignatureResponseDto;
use App\Mappings\User\UserToUpdateUserAppearanceResponseDto;
use App\Mappings\User\UserToUpdateUserAvatarResponseDto;
use App\Mappings\User\UserToUpdateUserDisplayNameResponseDto;
use App\Models\User;
use App\Repositories\Interface\IUserRepository;
use App\Services\Interface\ICloudinaryService;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\IPasswordHasherService;
use App\Services\Interface\IUserService;
use Illuminate\Validation\ValidationException;

class UserService implements IUserService
{
    private const AVATAR_FOLDER = 'Jotdown/Avatar';

    public function __construct(
        private readonly IUserRepository $userRepository,
        private readonly IJwtTokenService $jwtTokenService,
        private readonly IPasswordHasherService $passwordHasherService,
        private readonly ICloudinaryService $cloudinaryService,
        private readonly CloudinarySignaturePayloadToGetUserAvatarUploadSignatureResponseDto $cloudinarySignaturePayloadToGetUserAvatarUploadSignatureResponseDto,
        private readonly UserToUpdateUserAvatarResponseDto $userToUpdateUserAvatarResponseDto,
        private readonly UserToUpdateUserDisplayNameResponseDto $userToUpdateUserDisplayNameResponseDto,
        private readonly UserToUpdateUserAppearanceResponseDto $userToUpdateUserAppearanceResponseDto,
        private readonly ChangedPasswordToChangeUserPasswordResponseDto $changedPasswordToChangeUserPasswordResponseDto,
    ) {
    }

    public function getAvatarUploadSignature(GetUserAvatarUploadSignatureRequestDto $request): GetUserAvatarUploadSignatureResponseDto
    {
        $this->authorizeTokenForUserId($request->id, $request->token);

        $payload = $this->cloudinaryService->buildSignaturePayload(self::AVATAR_FOLDER);

        return $this->cloudinarySignaturePayloadToGetUserAvatarUploadSignatureResponseDto->transform($payload);
    }

    public function updateAvatar(UpdateUserAvatarRequestDto $request): UpdateUserAvatarResponseDto
    {
        $user = $this->getAuthorizedUser($request->id, $request->token);

        $this->cloudinaryService->assertValidCloudinaryUrl($request->avatarUrl, self::AVATAR_FOLDER);
        $oldAvatarUrl = $user->avatar_url;

        $updatedUser = $this->userRepository->update($user, [
            'avatar_url' => $request->avatarUrl,
            'UpdatedBy' => $user->email,
        ]);

        if ($oldAvatarUrl !== null && $oldAvatarUrl !== $request->avatarUrl) {
            DeleteCloudinaryImageJob::dispatch($oldAvatarUrl);
        }

        return $this->userToUpdateUserAvatarResponseDto->transform($updatedUser);
    }

    public function updateDisplayName(UpdateUserDisplayNameRequestDto $request): UpdateUserDisplayNameResponseDto
    {
        $user = $this->getAuthorizedUser($request->id, $request->token);

        $updatedUser = $this->userRepository->update($user, [
            'display_name' => $request->displayName,
            'UpdatedBy' => $user->email,
        ]);

        return $this->userToUpdateUserDisplayNameResponseDto->transform($updatedUser);
    }

    public function updateAppearance(UpdateUserAppearanceRequestDto $request): UpdateUserAppearanceResponseDto
    {
        $user = $this->getAuthorizedUser($request->id, $request->token);

        $updatedUser = $this->userRepository->update($user, [
            'theme' => $request->theme,
            'font_size' => $request->fontSize,
            'default_note_color' => $request->defaultNoteColor,
            'UpdatedBy' => $user->email,
        ]);

        return $this->userToUpdateUserAppearanceResponseDto->transform($updatedUser);
    }

    public function changePassword(ChangeUserPasswordRequestDto $request): ChangeUserPasswordResponseDto
    {
        $user = $this->getAuthorizedUser($request->id, $request->token);

        if (!$this->passwordHasherService->verify($request->currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $this->userRepository->update($user, [
            'password' => $this->passwordHasherService->hash($request->password),
            'UpdatedBy' => $user->email,
        ]);

        return $this->changedPasswordToChangeUserPasswordResponseDto->transform();
    }

    private function getAuthorizedUser(string $id, string $token): User
    {
        $this->authorizeTokenForUserId($id, $token);

        $user = $this->userRepository->findById($id);

        if ($user === null) {
            throw ValidationException::withMessages([
                'id' => ['User does not exist.'],
            ]);
        }

        return $user;
    }

    private function authorizeTokenForUserId(string $id, string $token): void
    {
        $payload = $this->jwtTokenService->verify($token);

        if ($payload === null || ($payload['sub'] ?? null) !== $id) {
            throw ValidationException::withMessages([
                'authorization' => ['Invalid or expired token.'],
            ]);
        }
    }
}
