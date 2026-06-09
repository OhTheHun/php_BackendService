<?php

namespace App\Controller\Api;

use App\Controller\Controller;
use App\Requests\Note\CreateNoteRequest;
use App\Requests\Note\DeleteNoteRequest;
use App\Requests\Note\ListNotesRequest;
use App\Requests\Note\ShowNoteRequest;
use App\Requests\Note\UpdateNoteArchiveRequest;
use App\Requests\Note\UpdateNoteFavoriteRequest;
use App\Requests\Note\UpdateNotePinRequest;
use App\Requests\Note\UpdateNoteProtectionRequest;
use App\Requests\Note\UpdateNoteRequest;
use App\Requests\Note\UpdateNoteShareRequest;
use App\Services\Interface\INoteService;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    public function __construct(
        private readonly INoteService $noteService,
    ) {}

    public function index(ListNotesRequest $request): JsonResponse
    {
        return response()->json($this->noteService->list($request->toDto())->toArray());
    }

    public function show(ShowNoteRequest $request): JsonResponse
    {
        return response()->json($this->noteService->show($request->toDto())->toArray());
    }

    public function store(CreateNoteRequest $request): JsonResponse
    {
        return response()->json($this->noteService->create($request->toDto())->toArray(), 201);
    }

    public function update(UpdateNoteRequest $request): JsonResponse
    {
        return response()->json($this->noteService->update($request->toDto())->toArray());
    }

    public function destroy(DeleteNoteRequest $request): JsonResponse
    {
        return response()->json($this->noteService->delete($request->toDto())->toArray());
    }

    public function updatePin(UpdateNotePinRequest $request): JsonResponse
    {
        return response()->json($this->noteService->updatePin($request->toDto())->toArray());
    }

    public function updateFavorite(UpdateNoteFavoriteRequest $request): JsonResponse
    {
        return response()->json($this->noteService->updateFavorite($request->toDto())->toArray());
    }

    public function updateArchive(UpdateNoteArchiveRequest $request): JsonResponse
    {
        return response()->json($this->noteService->updateArchive($request->toDto())->toArray());
    }

    public function updateProtection(UpdateNoteProtectionRequest $request): JsonResponse
    {
        return response()->json($this->noteService->updateProtection($request->toDto())->toArray());
    }

    public function updateShare(UpdateNoteShareRequest $request): JsonResponse
    {
        return response()->json($this->noteService->updateShare($request->toDto())->toArray());
    }
}
