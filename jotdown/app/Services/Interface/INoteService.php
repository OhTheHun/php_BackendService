<?php

namespace App\Services\Interface;

use App\DTO\Note\Requests\CreateNoteRequestDto;
use App\DTO\Note\Requests\DeleteNoteRequestDto;
use App\DTO\Note\Requests\ListNotesRequestDto;
use App\DTO\Note\Requests\ShowNoteRequestDto;
use App\DTO\Note\Requests\UpdateNoteArchiveRequestDto;
use App\DTO\Note\Requests\UpdateNoteFavoriteRequestDto;
use App\DTO\Note\Requests\UpdateNotePinRequestDto;
use App\DTO\Note\Requests\UpdateNoteProtectionRequestDto;
use App\DTO\Note\Requests\UpdateNoteRequestDto;
use App\DTO\Note\Requests\UpdateNoteShareRequestDto;
use App\DTO\Note\Responses\ListNotesResponseDto;
use App\DTO\Note\Responses\NoteArchiveResponseDto;
use App\DTO\Note\Responses\NoteFavoriteResponseDto;
use App\DTO\Note\Responses\NoteMessageResponseDto;
use App\DTO\Note\Responses\NotePinResponseDto;
use App\DTO\Note\Responses\NoteProtectionResponseDto;
use App\DTO\Note\Responses\NoteShareResponseDto;
use App\DTO\Note\Responses\ShowNoteResponseDto;

interface INoteService
{
    public function list(ListNotesRequestDto $request): ListNotesResponseDto;

    public function show(ShowNoteRequestDto $request): ShowNoteResponseDto;

    public function create(CreateNoteRequestDto $request): NoteMessageResponseDto;

    public function update(UpdateNoteRequestDto $request): NoteMessageResponseDto;

    public function delete(DeleteNoteRequestDto $request): NoteMessageResponseDto;

    public function updatePin(UpdateNotePinRequestDto $request): NotePinResponseDto;

    public function updateFavorite(UpdateNoteFavoriteRequestDto $request): NoteFavoriteResponseDto;

    public function updateArchive(UpdateNoteArchiveRequestDto $request): NoteArchiveResponseDto;

    public function updateProtection(UpdateNoteProtectionRequestDto $request): NoteProtectionResponseDto;

    public function updateShare(UpdateNoteShareRequestDto $request): NoteShareResponseDto;
}
