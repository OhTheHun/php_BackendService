<?php

namespace App\Repositories;

use App\DTO\Note\Requests\ListNotesRequestDto;
use App\Models\Note;
use App\Repositories\Interface\INoteRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NoteRepository implements INoteRepository
{
    public function paginateByUser(string $userId, ListNotesRequestDto $request): LengthAwarePaginator
    {
        $sortColumn = match ($request->sort) {
            'created_at' => 'CreatedTime',
            'title' => 'title',
            default => 'UpdatedTime',
        };

        return Note::query()
            ->with([
                'labels' => fn ($query) => $query->where('labels.DeleteFlag', false),
                'attachments' => fn ($query) => $query->where('DeleteFlag', false),
            ])
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->when($request->workspaceId !== null, fn ($query) => $query->where('workspace_id', $request->workspaceId))
            ->when($request->folderId !== null, fn ($query) => $query->where('folder_id', $request->folderId))
            ->when($request->labelId !== null, fn ($query) => $query->whereHas('labels', fn ($labelQuery) => $labelQuery->where('labels.Id', $request->labelId)))
            ->when($request->visibility !== null, fn ($query) => $query->where('visibility', $request->visibility))
            ->when($request->isPinned !== null, fn ($query) => $query->where('is_pinned', $request->isPinned))
            ->when($request->isFavorite !== null, fn ($query) => $query->where('is_favorite', $request->isFavorite))
            ->when($request->isProtected !== null, fn ($query) => $query->where('is_protected', $request->isProtected))
            ->when($request->archived !== null, fn ($query) => $request->archived ? $query->whereNotNull('archived_at') : $query->whereNull('archived_at'))
            ->when($request->search !== null, fn ($query) => $query->where(fn ($searchQuery) => $searchQuery
                ->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('content', 'like', '%'.$request->search.'%')))
            ->orderByDesc('is_pinned')
            ->orderByDesc('pinned_at')
            ->orderBy($sortColumn, $request->direction)
            ->paginate($request->perPage, ['*'], 'page', $request->page);
    }

    public function findOwnedById(string $id, string $userId): ?Note
    {
        return Note::query()
            ->with([
                'workspace',
                'folder',
                'labels' => fn ($query) => $query->where('labels.DeleteFlag', false),
                'attachments' => fn ($query) => $query->where('DeleteFlag', false),
            ])
            ->where('Id', $id)
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->first();
    }

    public function findOwnedBaseById(string $id, string $userId): ?Note
    {
        return Note::query()
            ->where('Id', $id)
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->first();
    }

    public function countActiveByUser(string $userId): int
    {
        return Note::query()
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->count();
    }

    public function create(array $data, array $labelIds): Note
    {
        return DB::transaction(function () use ($data, $labelIds): Note {
            $note = Note::query()->create($data);
            $note->labels()->sync($labelIds);

            return $note->load([
                'labels' => fn ($query) => $query->where('labels.DeleteFlag', false),
                'attachments' => fn ($query) => $query->where('DeleteFlag', false),
            ]);
        });
    }

    public function update(Note $note, array $data, ?array $labelIds): Note
    {
        return DB::transaction(function () use ($note, $data, $labelIds): Note {
            $note->fill($data);
            $note->save();

            if ($labelIds !== null) {
                $note->labels()->sync($labelIds);
            }

            return $note->load([
                'labels' => fn ($query) => $query->where('labels.DeleteFlag', false),
                'attachments' => fn ($query) => $query->where('DeleteFlag', false),
            ]);
        });
    }

    public function softDelete(Note $note, string $updatedBy): void
    {
        $note->fill([
            'DeleteFlag' => true,
            'UpdatedBy' => $updatedBy,
        ]);
        $note->save();
    }

    public function updatePin(Note $note, bool $isPinned, string $updatedBy): Note
    {
        $note->fill([
            'is_pinned' => $isPinned,
            'pinned_at' => $isPinned ? now() : null,
            'UpdatedBy' => $updatedBy,
        ]);
        $note->save();

        return $note;
    }

    public function updateFavorite(Note $note, bool $isFavorite, string $updatedBy): Note
    {
        $note->fill([
            'is_favorite' => $isFavorite,
            'UpdatedBy' => $updatedBy,
        ]);
        $note->save();

        return $note;
    }

    public function updateArchive(Note $note, bool $archived, string $updatedBy): Note
    {
        $note->fill([
            'archived_at' => $archived ? now() : null,
            'UpdatedBy' => $updatedBy,
        ]);
        $note->save();

        return $note;
    }

    public function updateProtection(Note $note, bool $isProtected, ?string $hashedPassword, string $updatedBy): Note
    {
        $note->fill([
            'is_protected' => $isProtected,
            'note_password' => $hashedPassword,
            'UpdatedBy' => $updatedBy,
        ]);
        $note->save();

        return $note;
    }

    public function updateShare(Note $note, string $visibility, string $updatedBy): Note
    {
        $note->fill([
            'visibility' => $visibility,
            'UpdatedBy' => $updatedBy,
        ]);
        $note->save();

        return $note;
    }
}
