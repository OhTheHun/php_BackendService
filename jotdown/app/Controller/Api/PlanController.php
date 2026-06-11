<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\Plan\ListPlansRequest;
use App\Services\Interface\IPlanService;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    public function __construct(
        private readonly IPlanService $planService,
    ) {}

    public function index(ListPlansRequest $request): JsonResponse
    {
        return response()->json($this->planService->list($request->toDto())->toArray());
    }
}
