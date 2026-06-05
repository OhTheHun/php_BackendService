<?php

namespace App\Services;

use App\Services\Interface\IPasswordHasherService;
use Illuminate\Support\Facades\Hash;

class PasswordHasherService implements IPasswordHasherService
{
    public function hash(string $value): string
    {
        return Hash::make($value);
    }

    public function verify(string $plainValue, string $hashedValue): bool
    {
        return Hash::check($plainValue, $hashedValue);
    }
}
