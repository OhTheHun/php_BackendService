<?php

namespace App\Services\Interface;

interface IPasswordHasherService
{
    public function hash(string $value): string;

    public function verify(string $plainValue, string $hashedValue): bool;
}
