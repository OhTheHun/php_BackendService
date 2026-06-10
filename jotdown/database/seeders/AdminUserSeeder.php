<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL', 'admin@example.com');
        $password = env('ADMIN_SEED_PASSWORD', 'admin123');

        $existing = User::where('email', $email)->first();
        if ($existing) {
            $this->command->info("Admin user already exists: {$email}");
        } else {
            $user = new User();
            // Fill minimal required attributes (audit fields are required in this schema)
            $user->CreatedBy = 'system';
            $user->UpdatedBy = 'system';
            $user->display_name = 'Administrator';
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->role = 'admin';
            $user->status = 'active';
            $user->email_verified_at = now();
            $user->save();

            $this->command->info("Created admin user: {$email} with password: {$password}");
        }

        // Also create a second admin for testing if it doesn't exist
        $secondEmail = env('ADMIN_SEED_EMAIL_2', 'superadmin@example.com');
        $secondPassword = env('ADMIN_SEED_PASSWORD_2', 'SuperAdmin123!');
        $existing2 = User::where('email', $secondEmail)->first();
        if (!$existing2) {
            $user2 = new User();
            $user2->CreatedBy = 'system';
            $user2->UpdatedBy = 'system';
            $user2->display_name = 'Super Admin';
            $user2->email = $secondEmail;
            $user2->password = Hash::make($secondPassword);
            $user2->role = 'admin';
            $user2->status = 'active';
            $user2->email_verified_at = now();
            $user2->save();
            $this->command->info("Created admin user: {$secondEmail} with password: {$secondPassword}");
        } else {
            $this->command->info("Admin user already exists: {$secondEmail}");
        }
    }
}
