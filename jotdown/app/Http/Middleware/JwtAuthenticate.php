<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JwtAuthenticate
{
    private function base64UrlDecode(string $value): string
    {
        $remainder = strlen($value) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $value .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($value, '-_', '+/')) ?: '';
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $token = substr($authHeader, 7);
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        try {
            $payloadJson = $this->base64UrlDecode($encodedPayload);
            $payload = json_decode($payloadJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid token payload.'], 401);
        }

        $secret = (string) env('JWT_SECRET', config('app.key'));
        $expectedSig = hash_hmac('sha256', $encodedHeader.'.'.$encodedPayload, $secret, true);
        $expectedEncoded = $this->base64UrlEncode($expectedSig);

        if (! hash_equals($expectedEncoded, $encodedSignature)) {
            return response()->json(['message' => 'Invalid token signature.'], 401);
        }

        if (isset($payload['exp']) && time() > (int) $payload['exp']) {
            return response()->json(['message' => 'Token expired.'], 401);
        }

        if (empty($payload['sub'])) {
            return response()->json(['message' => 'Invalid token subject.'], 401);
        }

        $user = User::find($payload['sub']);
        if ($user === null) {
            return response()->json(['message' => 'User not found.'], 401);
        }

        // set user resolver so controllers and request()->user() work
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
