<?php

namespace App\Repositories\Interface;

use App\DTO\Label\Requests\ListLabelsRequestDto;
use App\Models\Label;
use Illuminate\Support\Collection;

interface ILabelRepository
{
    public function getByUser(string $userId, ListLabelsRequestDto $request): Collection;

    public function findOwnedById(string $id, string $userId): ?Label;

    public function existsByNameForUser(string $userId, string $name, ?string $exceptId = null): bool;

    public function create(array $data): Label;

    public function update(Label $label, array $data): Label;

    public function softDelete(Label $label, string $updatedBy): void;
}
