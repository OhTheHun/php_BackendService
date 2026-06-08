<?php

namespace App\Repositories\Interface;

use App\Models\PasswordResetToken;

interface IPasswordResetTokenRepository
{
    public function save(array $data): PasswordResetToken;

    public function findByEmail(string $email): ?PasswordResetToken;

    public function update(PasswordResetToken $passwordResetToken, array $data): PasswordResetToken;
}
