<?php

namespace App\Repositories\Interface;

use App\DTO\Note\Requests\ListNotesRequestDto;
use App\Models\Note;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface INoteRepository
{
    public function paginateByUser(string $userId, ListNotesRequestDto $request): LengthAwarePaginator;

    public function findOwnedById(string $id, string $userId): ?Note;

    public function findOwnedBaseById(string $id, string $userId): ?Note;

    public function countActiveByUser(string $userId): int;

    public function create(array $data, array $labelIds): Note;

    public function update(Note $note, array $data, ?array $labelIds): Note;

    public function softDelete(Note $note, string $updatedBy): void;

    public function updatePin(Note $note, bool $isPinned, string $updatedBy): Note;

    public function updateFavorite(Note $note, bool $isFavorite, string $updatedBy): Note;

    public function updateArchive(Note $note, bool $archived, string $updatedBy): Note;

    public function updateProtection(Note $note, bool $isProtected, ?string $hashedPassword, string $updatedBy): Note;

    public function updateShare(Note $note, string $visibility, string $updatedBy): Note;
}
