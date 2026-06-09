<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', env('SEED_USER_EMAIL', 'user@example.com'))->first();
        if (! $user) {
            $this->command->info('Regular user not found. Skipping payment seeding.');
            return;
        }

        $premiumPlan = Plan::where('name', 'Premium')->first();
        $basicPlan = Plan::where('name', 'Basic')->first();

        if (! $premiumPlan || ! $basicPlan) {
            $this->command->info('Required plans not found. Please seed plans first.');
            return;
        }

        $transactions = [
            [
                'transaction_code' => 'PAY-1001',
                'amount' => 199000,
                'status' => 'pending',
                'payment_method' => 'VNPay',
                'plan_id' => $premiumPlan->Id,
                'user_id' => $user->Id,
            ],
            [
                'transaction_code' => 'PAY-1002',
                'amount' => 99000,
                'status' => 'confirmed',
                'payment_method' => 'MoMo',
                'plan_id' => $basicPlan->Id,
                'user_id' => $user->Id,
                'paid_at' => now(),
            ],
        ];

        foreach ($transactions as $txData) {
            $existing = Payment::where('transaction_code', $txData['transaction_code'])->first();
            if ($existing) {
                $this->command->info("Payment already exists: {$txData['transaction_code']}");
                continue;
            }

            $payment = new Payment();
            $payment->CreatedBy = 'system';
            $payment->UpdatedBy = 'system';
            $payment->user_id = $txData['user_id'];
            $payment->plan_id = $txData['plan_id'];
            $payment->amount = $txData['amount'];
            $payment->status = $txData['status'];
            $payment->payment_method = $txData['payment_method'];
            $payment->transaction_code = $txData['transaction_code'];
            $payment->paid_at = $txData['paid_at'] ?? null;
            $payment->save();

            $this->command->info("Created payment: {$txData['transaction_code']}");
        }
    }
}
