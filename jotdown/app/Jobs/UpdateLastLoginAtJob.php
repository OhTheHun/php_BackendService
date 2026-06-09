<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateLastLoginAtJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $userId,
        private readonly string $email,
    ) {
    }

    public function handle(): void
    {
        User::query()
            ->where('Id', $this->userId)
            ->update([
                'last_login_at' => now(),
                'UpdatedBy' => $this->email,
                'UpdatedTime' => now(),
            ]);
    }
}
