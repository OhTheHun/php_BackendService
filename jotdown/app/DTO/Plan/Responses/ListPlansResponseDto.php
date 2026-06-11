<?php

namespace App\DTO\Plan\Responses;

class ListPlansResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly array $plans,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'data' => array_map(fn (PlanResponseDto $plan): array => $plan->toArray(), $this->plans),
        ];
    }
}
