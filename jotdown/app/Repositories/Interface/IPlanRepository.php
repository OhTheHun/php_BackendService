<?php

namespace App\Repositories\Interface;

use Illuminate\Support\Collection;

interface IPlanRepository
{
    public function getPublicActivePlans(): Collection;
}
