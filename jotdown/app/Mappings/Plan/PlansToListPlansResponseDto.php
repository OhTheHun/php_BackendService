<?php

namespace App\Mappings\Plan;

use App\DTO\Plan\Responses\ListPlansResponseDto;
use Illuminate\Support\Collection;

class PlansToListPlansResponseDto
{
    public function __construct(
        private readonly PlanToPlanResponseDto $planToPlanResponseDto,
    ) {}

    public function transform(Collection $plans): ListPlansResponseDto
    {
        return new ListPlansResponseDto(
            message: 'Lấy danh sách gói dịch vụ thành công.',
            plans: $plans
                ->map(fn ($plan) => $this->planToPlanResponseDto->transform($plan))
                ->all(),
        );
    }
}
