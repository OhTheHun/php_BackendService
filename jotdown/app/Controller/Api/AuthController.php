<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\Auth\LoginRequest;
use App\Requests\Auth\RegisterRequest;
use App\Requests\Auth\SendResetPasswordOtpRequest;
use App\Requests\Auth\VerifyResetPasswordOtpRequest;
use App\Services\Interface\IAuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly IAuthService $authService,
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $responseDto = $this->authService->register($request->toDto());

        return response()->json($responseDto->toArray(), 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $responseDto = $this->authService->login($request->toDto());

        return response()->json($responseDto->toArray());
    }


    public function sendResetPasswordOtp(SendResetPasswordOtpRequest $request): JsonResponse
    {
        $responseDto = $this->authService->sendResetPasswordOtp($request->toDto());

        return response()->json($responseDto->toArray());
    }

    public function verifyResetPasswordOtp(VerifyResetPasswordOtpRequest $request): JsonResponse
    {
        $responseDto = $this->authService->verifyResetPasswordOtp($request->toDto());

        return response()->json($responseDto->toArray());
    }
}
