<?php

namespace App\Services;

use App\DTO\Note\Requests\GetSharedNotesRequestDto;
use App\DTO\Note\Responses\GetSharedNotesResponseDto;
use App\Mappings\Note\GetSharedNoteResponseDtosToGetSharedNotesResponseDto;
use App\Mappings\Note\NoteShareToGetSharedNoteResponseDto;
use App\Repositories\Interface\INoteShareRepository;
use App\Services\Interface\INoteShareService;

class NoteShareService implements INoteShareService
{
    public function __construct(
        private readonly INoteShareRepository $noteShareRepository,
        private readonly NoteShareToGetSharedNoteResponseDto $noteShareToGetSharedNoteResponseDto,
        private readonly GetSharedNoteResponseDtosToGetSharedNotesResponseDto $getSharedNoteResponseDtosToGetSharedNotesResponseDto,
    ) {
    }

    public function getSharedNotes(GetSharedNotesRequestDto $request): GetSharedNotesResponseDto
    {
        $startedAt = microtime(true);

        $notes = $this->noteShareRepository
            ->getSharedNotes($request)
            ->map(fn ($noteShare) => $this->noteShareToGetSharedNoteResponseDto->transform($noteShare))
            ->all();

        $elapsedMs = round((microtime(true) - $startedAt) * 1000, 2);

        return $this->getSharedNoteResponseDtosToGetSharedNotesResponseDto->transform($notes, $elapsedMs);
    }
}
