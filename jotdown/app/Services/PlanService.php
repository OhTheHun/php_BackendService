<?php

namespace App\Services;

use App\DTO\Plan\Requests\ListPlansRequestDto;
use App\DTO\Plan\Responses\ListPlansResponseDto;
use App\Mappings\Plan\PlansToListPlansResponseDto;
use App\Repositories\Interface\IPlanRepository;
use App\Services\Interface\IPlanService;

class PlanService implements IPlanService
{
    public function __construct(
        private readonly IPlanRepository $planRepository,
        private readonly PlansToListPlansResponseDto $plansToListPlansResponseDto,
    ) {}

    public function list(ListPlansRequestDto $request): ListPlansResponseDto
    {
        return $this->plansToListPlansResponseDto->transform(
            $this->planRepository->getPublicActivePlans()
        );
    }
}
