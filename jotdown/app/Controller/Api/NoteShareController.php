<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\Note\GetSharedNotesRequest;
use App\Services\Interface\INoteShareService;
use Illuminate\Http\JsonResponse;

class NoteShareController extends Controller
{
    public function __construct(
        private readonly INoteShareService $noteShareService,
    ) {
    }

    public function getSharedNotes(GetSharedNotesRequest $request): JsonResponse
    {
        $responseDto = $this->noteShareService->getSharedNotes($request->toDto());

        return response()->json($responseDto->toArray());
    }
}
