<?php

namespace App\Mappings\Plan;

use App\DTO\Plan\Responses\PlanResponseDto;
use App\Models\Plan;

class PlanToPlanResponseDto
{
    public function transform(Plan $plan): PlanResponseDto
    {
        $price = (float) $plan->price;

        return new PlanResponseDto(
            id: $plan->Id,
            name: $plan->name,
            price: fmod($price, 1.0) === 0.0 ? (int) $price : $price,
            maxNotes: $plan->max_notes,
            maxWorkspaces: $plan->max_workspaces,
            maxAttachmentSize: $plan->max_attachment_size,
            canExport: (bool) $plan->can_export,
            status: (bool) $plan->status,
            createdTime: $plan->CreatedTime?->toISOString(),
            updatedTime: $plan->UpdatedTime?->toISOString(),
        );
    }
}
