<?php

namespace App\Services\Interface;

use App\Models\User;

interface IJwtTokenService
{
    public function generate(User $user): string;

    public function verify(string $token): ?array;
}
