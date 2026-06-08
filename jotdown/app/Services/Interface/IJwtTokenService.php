<?php

namespace App\Services\Interface;

use App\Models\User;

interface IJwtTokenService
{
    public function generate(User $user): string;
}
