<?php

namespace App\Services\Interface;

interface ICloudinaryService
{
    public function buildSignaturePayload(string $folder): array;

    public function assertValidCloudinaryUrl(string $url, string $folder): void;

    public function deleteImageByUrl(?string $imageUrl): void;
}
