<?php

namespace App\DTO\User\Responses;

class GetUserAvatarUploadSignatureResponseDto
{
    public function __construct(
        public readonly string $signature,
        public readonly int $timestamp,
        public readonly string $apiKey,
        public readonly string $cloudName,
        public readonly string $folder,
        public readonly string $uploadUrl,
    ) {
    }

    public function toArray(): array
    {
        return [
            'data' => [
                'signature' => $this->signature,
                'timestamp' => $this->timestamp,
                'api_key' => $this->apiKey,
                'cloud_name' => $this->cloudName,
                'folder' => $this->folder,
                'upload_url' => $this->uploadUrl,
            ],
        ];
    }
}
