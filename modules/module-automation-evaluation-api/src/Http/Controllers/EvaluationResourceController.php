<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Automation\Evaluation\Actions\CreateEvaluationResource;
use Liberu\Modules\Automation\Evaluation\Models\EvaluationResource;

final class EvaluationResourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return response()->json(['data' => EvaluationResource::query()->forTeam($teamId)->latest()->paginate(min((int) $request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, CreateEvaluationResource $create): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'payload' => ['array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = $create->execute($teamId, $data['name'], $data['payload'] ?? [], $data['idempotency_key'] ?? null);

        return response()->json(['data' => $resource->toArray()], 201);
    }
}
