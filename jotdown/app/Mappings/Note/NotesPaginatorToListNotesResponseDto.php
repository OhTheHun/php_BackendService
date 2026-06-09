<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\ListNotesResponseDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotesPaginatorToListNotesResponseDto
{
    public function __construct(
        private readonly NoteToNoteResponseDto $noteToNoteResponseDto,
    ) {}

    public function transform(LengthAwarePaginator $paginator): ListNotesResponseDto
    {
        return new ListNotesResponseDto(
            notes: collect($paginator->items())
                ->map(fn ($note) => $this->noteToNoteResponseDto->transform($note))
                ->all(),
            currentPage: $paginator->currentPage(),
            perPage: $paginator->perPage(),
            total: $paginator->total(),
            lastPage: $paginator->lastPage(),
        );
    }
}
