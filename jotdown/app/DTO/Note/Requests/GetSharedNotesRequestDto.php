<?php

namespace App\DTO\Note\Requests;

class GetSharedNotesRequestDto
{
    public function __construct(
        public readonly ?string $sharedWithEmail,
        public readonly ?string $permission,
        public readonly bool $includeExpired,
    ) {
    }
}
