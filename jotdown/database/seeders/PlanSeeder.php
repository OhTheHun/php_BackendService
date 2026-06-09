<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'price' => 0,
                'max_notes' => 50,
                'max_workspaces' => 3,
                'max_attachment_size' => 5,
                'can_export' => false,
                'status' => true,
            ],
            [
                'name' => 'Basic',
                'price' => 99000,
                'max_notes' => 500,
                'max_workspaces' => 10,
                'max_attachment_size' => 50,
                'can_export' => true,
                'status' => true,
            ],
            [
                'name' => 'Premium',
                'price' => 199000,
                'max_notes' => null,
                'max_workspaces' => null,
                'max_attachment_size' => 200,
                'can_export' => true,
                'status' => true,
            ],
        ];

        foreach ($plans as $planData) {
            $plan = Plan::where('name', $planData['name'])->first();
            if ($plan) {
                $this->command->info("Plan already exists: {$planData['name']}");
                continue;
            }

            $newPlan = new Plan();
            $newPlan->CreatedBy = 'system';
            $newPlan->UpdatedBy = 'system';
            $newPlan->name = $planData['name'];
            $newPlan->price = $planData['price'];
            $newPlan->max_notes = $planData['max_notes'];
            $newPlan->max_workspaces = $planData['max_workspaces'];
            $newPlan->max_attachment_size = $planData['max_attachment_size'];
            $newPlan->can_export = $planData['can_export'];
            $newPlan->status = $planData['status'];
            $newPlan->save();

            $this->command->info("Created plan: {$planData['name']}");
        }
    }
}
