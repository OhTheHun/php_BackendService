<?php

namespace App\Services;

use App\Models\User;
use App\Services\Interface\IJwtTokenService;

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

    public function verify(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $encodedHeader.'.'.$encodedPayload, $this->secret(), true)
        );

        if (! hash_equals($expectedSignature, $encodedSignature)) {
            return null;
        }

        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);

        if (! is_array($payload) || ! isset($payload['exp']) || time() >= (int) $payload['exp']) {
            return null;
        }

        return $payload;
    }

    private function secret(): string
    {
        return (string) env('JWT_SECRET', config('app.key'));
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        return base64_decode(strtr($value, '-_', '+/')) ?: '';
    }
}
