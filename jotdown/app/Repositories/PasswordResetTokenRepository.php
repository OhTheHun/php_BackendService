<?php

namespace App\Repositories;

use App\Models\PasswordResetToken;
use App\Repositories\Interface\IPasswordResetTokenRepository;

class PasswordResetTokenRepository implements IPasswordResetTokenRepository
{
    public function save(array $data): PasswordResetToken
    {
        return PasswordResetToken::query()->updateOrCreate(
            ['email' => $data['email']],
            $data
        );
    }

    public function findByEmail(string $email): ?PasswordResetToken
    {
        return PasswordResetToken::query()
            ->where('email', $email)
            ->first();
    }

    public function update(PasswordResetToken $passwordResetToken, array $data): PasswordResetToken
    {
        $passwordResetToken->fill($data);
        $passwordResetToken->save();

        return $passwordResetToken;
    }
}
