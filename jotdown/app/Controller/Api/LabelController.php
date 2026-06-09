<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\Label\CreateLabelRequest;
use App\Requests\Label\DeleteLabelRequest;
use App\Requests\Label\ListLabelsRequest;
use App\Requests\Label\UpdateLabelRequest;
use App\Services\Interface\ILabelService;
use Illuminate\Http\JsonResponse;

class LabelController extends Controller
{
    public function __construct(
        private readonly ILabelService $labelService,
    ) {}

    public function index(ListLabelsRequest $request): JsonResponse
    {
        return response()->json($this->labelService->list($request->toDto())->toArray());
    }

    public function store(CreateLabelRequest $request): JsonResponse
    {
        return response()->json($this->labelService->create($request->toDto())->toArray(), 201);
    }

    public function update(UpdateLabelRequest $request): JsonResponse
    {
        return response()->json($this->labelService->update($request->toDto())->toArray());
    }

    public function destroy(DeleteLabelRequest $request): JsonResponse
    {
        return response()->json($this->labelService->delete($request->toDto())->toArray());
    }
}
