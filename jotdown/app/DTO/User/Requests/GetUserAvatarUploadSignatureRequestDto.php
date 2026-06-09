<?php

namespace App\DTO\User\Requests;

class GetUserAvatarUploadSignatureRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
    ) {
    }
}
