<?php

namespace App\Mappings\User;

use App\DTO\User\Responses\GetUserAvatarUploadSignatureResponseDto;

class CloudinarySignaturePayloadToGetUserAvatarUploadSignatureResponseDto
{
    public function transform(array $payload): GetUserAvatarUploadSignatureResponseDto
    {
        return new GetUserAvatarUploadSignatureResponseDto(
            signature: $payload['signature'],
            timestamp: $payload['timestamp'],
            apiKey: $payload['api_key'],
            cloudName: $payload['cloud_name'],
            folder: $payload['folder'],
            uploadUrl: $payload['upload_url'],
        );
    }
}
