<?php

namespace App\Services\Interface;

use App\DTO\Note\Requests\CreateNoteShareRequestDto;
use App\DTO\Note\Requests\GetSharedNotesRequestDto;
use App\DTO\Note\Responses\CreateNoteShareResponseDto;
use App\DTO\Note\Responses\GetSharedNotesResponseDto;

interface INoteShareService
{
    public function getSharedNotes(GetSharedNotesRequestDto $request): GetSharedNotesResponseDto;

    public function createShare(CreateNoteShareRequestDto $request): CreateNoteShareResponseDto;
}
