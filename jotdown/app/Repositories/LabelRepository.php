<?php

namespace App\Repositories;

use App\DTO\Label\Requests\ListLabelsRequestDto;
use App\Models\Label;
use App\Repositories\Interface\ILabelRepository;
use Illuminate\Support\Collection;

class LabelRepository implements ILabelRepository
{
    public function getByUser(string $userId, ListLabelsRequestDto $request): Collection
    {
        return Label::query()
            ->withCount(['notes' => fn ($query) => $query->where('notes.DeleteFlag', false)])
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->orderBy('name')
            ->get();
    }

    public function findOwnedById(string $id, string $userId): ?Label
    {
        return Label::query()
            ->withCount(['notes' => fn ($query) => $query->where('notes.DeleteFlag', false)])
            ->where('Id', $id)
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->first();
    }

    public function existsByNameForUser(string $userId, string $name, ?string $exceptId = null): bool
    {
        return Label::query()
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->when($exceptId !== null, fn ($query) => $query->where('Id', '!=', $exceptId))
            ->exists();
    }

    public function create(array $data): Label
    {
        $label = Label::query()->create($data);

        return $label->loadCount(['notes' => fn ($query) => $query->where('notes.DeleteFlag', false)]);
    }

    public function update(Label $label, array $data): Label
    {
        $label->fill($data);
        $label->save();

        return $label->loadCount(['notes' => fn ($query) => $query->where('notes.DeleteFlag', false)]);
    }

    public function softDelete(Label $label, string $updatedBy): void
    {
        $label->fill([
            'DeleteFlag' => true,
            'UpdatedBy' => $updatedBy,
        ]);
        $label->save();
    }
}
