<?php

namespace App\Services;

use App\DTO\Label\Requests\CreateLabelRequestDto;
use App\DTO\Label\Requests\DeleteLabelRequestDto;
use App\DTO\Label\Requests\ListLabelsRequestDto;
use App\DTO\Label\Requests\UpdateLabelRequestDto;
use App\DTO\Label\Responses\LabelMessageResponseDto;
use App\DTO\Label\Responses\ListLabelsResponseDto;
use App\Mappings\Label\LabelToLabelResponseDto;
use App\Models\Label;
use App\Repositories\Interface\ILabelRepository;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\ILabelService;
use Illuminate\Validation\ValidationException;

class LabelService implements ILabelService
{
    public function __construct(
        private readonly ILabelRepository $labelRepository,
        private readonly IJwtTokenService $jwtTokenService,
        private readonly LabelToLabelResponseDto $labelToLabelResponseDto,
    ) {}

    public function list(ListLabelsRequestDto $request): ListLabelsResponseDto
    {
        $user = $this->authorize($request->token);

        $labels = $this->labelRepository
            ->getByUser($user['id'], $request)
            ->map(fn ($label) => $this->labelToLabelResponseDto->transform($label))
            ->all();

        return new ListLabelsResponseDto($labels);
    }

    public function create(CreateLabelRequestDto $request): LabelMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $this->assertUniqueName($user['id'], $request->name);

        $label = $this->labelRepository->create([
            'CreatedBy' => $user['email'],
            'user_id' => $user['id'],
            'name' => $request->name,
            'color' => $request->color ?? '#cccccc',
            'DeleteFlag' => false,
        ]);

        return new LabelMessageResponseDto(
            message: 'Label created successfully.',
            label: $this->labelToLabelResponseDto->transform($label),
        );
    }

    public function update(UpdateLabelRequestDto $request): LabelMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $label = $this->findOwnedLabel($request->id, $user['id']);

        if ($request->name !== null) {
            $this->assertUniqueName($user['id'], $request->name, $label->Id);
        }

        $updatedLabel = $this->labelRepository->update($label, array_filter([
            'name' => $request->name,
            'color' => $request->color,
            'UpdatedBy' => $user['email'],
        ], fn ($value) => $value !== null));

        return new LabelMessageResponseDto(
            message: 'Label updated successfully.',
            label: $this->labelToLabelResponseDto->transform($updatedLabel),
        );
    }

    public function delete(DeleteLabelRequestDto $request): LabelMessageResponseDto
    {
        $user = $this->authorize($request->token);
        $label = $this->findOwnedLabel($request->id, $user['id']);

        if ((int) ($label->notes_count ?? 0) > 0) {
            throw ValidationException::withMessages([
                'label_id' => ['Label is currently in use.'],
            ]);
        }

        $this->labelRepository->softDelete($label, $user['email']);

        return new LabelMessageResponseDto('Label deleted successfully.');
    }

    private function findOwnedLabel(string $id, string $userId): Label
    {
        $label = $this->labelRepository->findOwnedById($id, $userId);

        if ($label === null) {
            throw ValidationException::withMessages(['id' => ['Label does not exist.']]);
        }

        return $label;
    }

    private function assertUniqueName(string $userId, string $name, ?string $exceptId = null): void
    {
        if ($this->labelRepository->existsByNameForUser($userId, $name, $exceptId)) {
            throw ValidationException::withMessages(['name' => ['The name has already been taken.']]);
        }
    }

    private function authorize(string $token): array
    {
        $payload = $this->jwtTokenService->verify($token);

        if ($payload === null || ! isset($payload['sub'], $payload['email'])) {
            throw ValidationException::withMessages(['authorization' => ['Invalid or expired token.']]);
        }

        return ['id' => $payload['sub'], 'email' => $payload['email']];
    }
}
