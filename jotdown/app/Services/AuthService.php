<?php

namespace App\Services;

use App\Enums\EmailTemplateType;
use App\DTO\Auth\Requests\LoginRequestDto;
use App\DTO\Auth\Requests\RegisterRequestDto;
use App\DTO\Auth\Requests\SendResetPasswordOtpRequestDto;
use App\DTO\Auth\Requests\VerifyResetPasswordOtpRequestDto;
use App\DTO\Auth\Responses\AuthResponseDto;
use App\DTO\Auth\Responses\SendResetPasswordOtpResponseDto;
use App\DTO\Auth\Responses\VerifyResetPasswordOtpResponseDto;
use App\Mappings\Auth\RegisterRequestDtoToUser;
use App\Mappings\Auth\ResetPasswordOtpToPasswordResetToken;
use App\Mappings\Auth\ResetPasswordOtpToSendResetPasswordOtpResponseDto;
use App\Mappings\Auth\UserAndTokenToAuthResponseDto;
use App\Mappings\Auth\VerifiedResetPasswordOtpToVerifyResetPasswordOtpResponseDto;
use App\Repositories\Interface\IPasswordResetTokenRepository;
use App\Repositories\Interface\IUserRepository;
use App\Services\Interface\IAuthService;
use App\Services\Interface\IEmailTemplateService;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\IPasswordHasherService;
use App\Services\Interface\ISMTPEmailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthService implements IAuthService
{
    public function __construct(
        private readonly IUserRepository $userRepository,
        private readonly IPasswordResetTokenRepository $passwordResetTokenRepository,
        private readonly IPasswordHasherService $passwordHasherService,
        private readonly IJwtTokenService $jwtTokenService,
        private readonly IEmailTemplateService $emailTemplateService,
        private readonly ISMTPEmailService $smtpEmailService,
        private readonly RegisterRequestDtoToUser $registerRequestDtoToUser,
        private readonly UserAndTokenToAuthResponseDto $userAndTokenToAuthResponseDto,
        private readonly ResetPasswordOtpToPasswordResetToken $resetPasswordOtpToPasswordResetToken,
        private readonly ResetPasswordOtpToSendResetPasswordOtpResponseDto $resetPasswordOtpToSendResetPasswordOtpResponseDto,
        private readonly VerifiedResetPasswordOtpToVerifyResetPasswordOtpResponseDto $verifiedResetPasswordOtpToVerifyResetPasswordOtpResponseDto,
    ) {
    }

    public function register(RegisterRequestDto $request): AuthResponseDto
    {
        if ($this->userRepository->findByEmail($request->email) !== null) {
            throw ValidationException::withMessages([
                'email' => ['Email already exists.'],
            ]);
        }

        $hashedPassword = $this->passwordHasherService->hash($request->password);
        $userData = $this->registerRequestDtoToUser->transform($request, $hashedPassword);

        $user = $this->userRepository->create($userData);

        $token = $this->jwtTokenService->generate($user);

        return $this->userAndTokenToAuthResponseDto->transform($user, $token);
    }

    public function login(LoginRequestDto $request): AuthResponseDto
    {
        $user = $this->userRepository->findByEmail($request->email);

        if ($user === null || !$this->passwordHasherService->verify($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['This account is not active.'],
            ]);
        }

        defer(fn () => $this->userRepository->update($user, [
            'last_login_at' => now(),
            'UpdatedBy' => $user->email,
        ]));

        $token = $this->jwtTokenService->generate($user);

        return $this->userAndTokenToAuthResponseDto->transform($user, $token);
    }

    public function sendResetPasswordOtp(SendResetPasswordOtpRequestDto $request): SendResetPasswordOtpResponseDto
    {
        $user = $this->userRepository->findByEmail($request->email);

        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => ['Email does not exist.'],
            ]);
        }

        $otp = (string) random_int(100000, 999999);
        $plainToken = bin2hex(random_bytes(32));
        $expiresAt = now()->addMinutes(10);

        $resetTokenData = $this->resetPasswordOtpToPasswordResetToken->transform(
            $user->email,
            $this->passwordHasherService->hash($otp),
            $this->passwordHasherService->hash($plainToken),
            $expiresAt
        );

        $this->passwordResetTokenRepository->save($resetTokenData);

        $this->smtpEmailService->send(
            $user->email,
            'JotDown reset password OTP',
            $this->emailTemplateService->getEmailTemplate(EmailTemplateType::ResetPassword, [
                'otp' => $otp,
                'expires_in_minutes' => 10,
            ])
        );

        return $this->resetPasswordOtpToSendResetPasswordOtpResponseDto->transform($expiresAt);
    }

    public function verifyResetPasswordOtp(VerifyResetPasswordOtpRequestDto $request): VerifyResetPasswordOtpResponseDto
    {
        $user = $this->userRepository->findByEmail($request->email);
        $resetToken = $this->passwordResetTokenRepository->findByEmail($request->email);

        if ($user === null || $resetToken === null) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid OTP.'],
            ]);
        }

        if ($resetToken->is_used || now()->greaterThan($resetToken->expires_at)) {
            throw ValidationException::withMessages([
                'otp' => ['OTP is expired or already used.'],
            ]);
        }

        if (!$this->passwordHasherService->verify($request->otp, $resetToken->otp)) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid OTP.'],
            ]);
        }

        DB::transaction(function () use ($request, $user, $resetToken): void {
            $this->userRepository->update($user, [
                'password' => $this->passwordHasherService->hash($request->password),
                'UpdatedBy' => $user->email,
            ]);

            $this->passwordResetTokenRepository->update($resetToken, [
                'is_used' => true,
            ]);
        });

        return $this->verifiedResetPasswordOtpToVerifyResetPasswordOtpResponseDto->transform();
    }
}
