<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PlanSeeder;
use Database\Seeders\PaymentSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

   
    public function run(): void
    {
        
        $existing = User::where('email', 'test@example.com')->first();
        if (!$existing) {
            $test = new User();
            $test->CreatedBy = 'system';
            $test->UpdatedBy = 'system';
            $test->display_name = 'Test User';
            $test->email = 'test@example.com';
            $test->password = Hash::make('password');
            $test->role = 'user';
            $test->status = 'active';
            $test->email_verified_at = now();
            $test->save();
            $this->command->info('Created test user: test@example.com with password: password');
        }

      
        $email = env('SEED_USER_EMAIL', 'user@example.com');
        $password = env('SEED_USER_PASSWORD', 'User123!');
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $this->command->info("User already exists: {$email}");
        } else {
            $user = new User();
            $user->CreatedBy = 'system';
            $user->UpdatedBy = 'system';
            $user->display_name = 'Regular User';
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->role = 'user';
            $user->status = 'active';
            $user->email_verified_at = now();
            $user->save();
            $this->command->info("Created user: {$email} with password: {$password}");
        }

        $this->call([AdminUserSeeder::class, PlanSeeder::class, PaymentSeeder::class]);
    }
}
