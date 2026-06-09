<?php

namespace App\Services;

use App\Services\Interface\ICloudinaryService;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class CloudinaryService implements ICloudinaryService
{
    public function buildSignaturePayload(string $folder): array
    {
        if ($this->cloudName() === '' || $this->apiKey() === '' || $this->apiSecret() === '') {
            throw ValidationException::withMessages([
                'cloudinary' => ['Cloudinary is not fully configured on the server.'],
            ]);
        }

        $timestamp = time();

        return [
            'signature' => $this->signature([
                'folder' => $folder,
                'timestamp' => $timestamp,
            ]),
            'timestamp' => $timestamp,
            'api_key' => $this->apiKey(),
            'cloud_name' => $this->cloudName(),
            'folder' => $folder,
            'upload_url' => $this->apiUrl('image/upload'),
        ];
    }

    public function assertValidCloudinaryUrl(string $url, string $folder): void
    {
        $prefix = sprintf('https://res.cloudinary.com/%s/image/upload/', $this->cloudName());

        if (!str_starts_with($url, $prefix)) {
            throw ValidationException::withMessages([
                'avatar_url' => ['Avatar URL is invalid or does not belong to Cloudinary.'],
            ]);
        }

        if (!str_contains($url, '/'.$folder.'/')) {
            throw ValidationException::withMessages([
                'avatar_url' => ['Avatar image does not belong to the allowed Cloudinary folder.'],
            ]);
        }
    }

    public function deleteImageByUrl(?string $imageUrl): void
    {
        $publicId = $this->extractPublicId($imageUrl);

        if ($publicId === null) {
            return;
        }

        $timestamp = time();

        Http::asForm()->post($this->apiUrl('image/destroy'), [
            'api_key' => $this->apiKey(),
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'invalidate' => 'true',
            'signature' => $this->signature([
                'invalidate' => 'true',
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ]),
        ]);
    }

    private function extractPublicId(?string $imageUrl): ?string
    {
        if ($imageUrl === null || !str_contains($imageUrl, 'res.cloudinary.com')) {
            return null;
        }

        $path = parse_url($imageUrl, PHP_URL_PATH);

        if (!is_string($path) || !str_contains($path, '/upload/')) {
            return null;
        }

        $publicId = explode('/upload/', $path, 2)[1];
        $publicId = preg_replace('/^v\d+\//', '', $publicId);
        $publicId = preg_replace('/\.[a-zA-Z0-9]+$/', '', $publicId);

        return $publicId ?: null;
    }

    private function apiUrl(string $path): string
    {
        return sprintf('https://api.cloudinary.com/v1_1/%s/%s', $this->cloudName(), $path);
    }

    private function signature(array $params): string
    {
        ksort($params);

        $payload = collect($params)
            ->map(fn ($value, string $key): string => $key.'='.$value)
            ->implode('&');

        return sha1($payload.$this->apiSecret());
    }

    private function cloudName(): string
    {
        return (string) config('services.cloudinary.cloud_name');
    }

    private function apiKey(): string
    {
        return (string) config('services.cloudinary.api_key');
    }

    private function apiSecret(): string
    {
        return (string) config('services.cloudinary.api_secret');
    }
}
