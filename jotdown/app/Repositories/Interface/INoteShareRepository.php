<?php

namespace App\Repositories\Interface;

use App\DTO\Note\Requests\GetSharedNotesRequestDto;
use Illuminate\Support\Collection;

interface INoteShareRepository
{
    public function getSharedNotes(GetSharedNotesRequestDto $request): Collection;
}
