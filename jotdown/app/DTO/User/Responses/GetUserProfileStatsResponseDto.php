<?php

namespace App\DTO\User\Responses;

class GetUserProfileStatsResponseDto
{
    public function __construct(
        public readonly int $totalNotes,
        public readonly int $totalWorkspaces,
        public readonly int $totalLabels,
        public readonly int $sharedNotes,
    ) {
    }

    public function toArray(): array
    {
        return [
            'total_notes' => $this->totalNotes,
            'total_workspaces' => $this->totalWorkspaces,
            'total_labels' => $this->totalLabels,
            'shared_notes' => $this->sharedNotes,
        ];
    }
}
