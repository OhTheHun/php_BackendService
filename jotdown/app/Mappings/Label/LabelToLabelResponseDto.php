<?php

namespace App\Mappings\Label;

use App\DTO\Label\Responses\LabelResponseDto;
use App\Models\Label;

class LabelToLabelResponseDto
{
    public function transform(Label $label): LabelResponseDto
    {
        return new LabelResponseDto(
            id: $label->Id,
            userId: $label->user_id,
            name: $label->name,
            color: $label->color ?? '#cccccc',
            notesCount: (int) ($label->notes_count ?? $label->notes()->where('notes.DeleteFlag', false)->count()),
            createdAt: $label->CreatedTime?->toISOString(),
            updatedAt: $label->UpdatedTime?->toISOString(),
        );
    }
}
