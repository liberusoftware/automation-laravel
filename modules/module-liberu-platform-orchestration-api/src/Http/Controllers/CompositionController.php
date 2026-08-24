<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Liberu\PlatformOrchestration\Actions\RegisterComposition;
use Liberu\Modules\Liberu\PlatformOrchestration\Models\CompositionRecord;

final class CompositionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teamId = $this->teamId($request);

        return response()->json(['data' => CompositionRecord::query()->forTeam($teamId)->latest()->paginate(min($request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, RegisterComposition $register): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'manifest' => ['required', 'array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $record = $register->execute($this->teamId($request), $data['name'], $data['manifest'], $data['idempotency_key'] ?? null);

        return response()->json(['data' => $record->toArray()], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $record = CompositionRecord::query()->forTeam($this->teamId($request))->findOrFail($id);

        return response()->json(['data' => $record->toArray()]);
    }

    private function teamId(Request $request): string
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return $teamId;
    }
}
