<?php

namespace App\Services\Interface;

use App\DTO\Auth\Requests\LoginRequestDto;
use App\DTO\Auth\Requests\RegisterRequestDto;
use App\DTO\Auth\Requests\SendResetPasswordOtpRequestDto;
use App\DTO\Auth\Requests\VerifyResetPasswordOtpRequestDto;
use App\DTO\Auth\Responses\AuthResponseDto;
use App\DTO\Auth\Responses\SendResetPasswordOtpResponseDto;
use App\DTO\Auth\Responses\VerifyResetPasswordOtpResponseDto;

interface IAuthService
{
    public function register(RegisterRequestDto $request): AuthResponseDto;

    public function login(LoginRequestDto $request): AuthResponseDto;

    public function sendResetPasswordOtp(SendResetPasswordOtpRequestDto $request): SendResetPasswordOtpResponseDto;

    public function verifyResetPasswordOtp(VerifyResetPasswordOtpRequestDto $request): VerifyResetPasswordOtpResponseDto;
}
