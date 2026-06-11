<?php

namespace App\Services\Interface;

use App\DTO\Plan\Requests\ListPlansRequestDto;
use App\DTO\Plan\Responses\ListPlansResponseDto;

interface IPlanService
{
    public function list(ListPlansRequestDto $request): ListPlansResponseDto;
}
