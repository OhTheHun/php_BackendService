<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Repositories\Interface\IPlanRepository;
use Illuminate\Support\Collection;

class PlanRepository implements IPlanRepository
{
    public function getPublicActivePlans(): Collection
    {
        return Plan::query()
            ->where('DeleteFlag', false)
            ->where('status', true)
            ->orderBy('price')
            ->get();
    }
}
