<?php

namespace App\Repositories\Interface;

use App\DTO\Note\Requests\GetSharedNotesRequestDto;
use App\Models\NoteShare;
use Illuminate\Support\Collection;

interface INoteShareRepository
{
    public function getSharedNotes(GetSharedNotesRequestDto $request): Collection;

    public function create(array $data): NoteShare;
}
