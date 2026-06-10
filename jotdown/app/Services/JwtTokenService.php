<?php

namespace App\Services;

use App\Services\Interface\IJwtTokenService;
use App\Models\User;

class JwtTokenService implements IJwtTokenService
{
    public function generate(User $user): string
    {
        $now = time();
        $ttl = (int) env('JWT_TTL', 3600);

        $payload = [
            'iss' => config('app.url'),
            'iat' => $now,
            'exp' => $now + $ttl,
            'sub' => $user->Id,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));
        $signature = hash_hmac('sha256', $encodedHeader.'.'.$encodedPayload, $this->secret(), true);

        return $encodedHeader.'.'.$encodedPayload.'.'.$this->base64UrlEncode($signature);
    }

    private function secret(): string
    {
        return (string) env('JWT_SECRET', config('app.key'));
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
