<?php

namespace App\Repositories;

use App\DTO\Note\Requests\GetSharedNotesRequestDto;
use App\Models\NoteShare;
use App\Repositories\Interface\INoteShareRepository;
use Illuminate\Support\Collection;

class NoteShareRepository implements INoteShareRepository
{
    public function getSharedNotes(GetSharedNotesRequestDto $request): Collection
    {
        return NoteShare::query()
            ->with('note')
            ->where('DeleteFlag', false)
            ->whereNull('revoked_at')
            ->whereHas('note', function ($query): void {
                $query->where('DeleteFlag', false);
            })
            ->when($request->sharedWithEmail !== null, function ($query) use ($request): void {
                $query->where('shared_with_email', $request->sharedWithEmail);
            })
            ->when($request->permission !== null, function ($query) use ($request): void {
                $query->where('permission', $request->permission);
            })
            ->when(! $request->includeExpired, function ($query): void {
                $query->where(function ($expiredQuery): void {
                    $expiredQuery
                        ->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                });
            })
            ->orderByDesc('CreatedTime')
            ->get();
    }

    public function create(array $data): NoteShare
    {
        return NoteShare::query()->create($data);
    }
}
