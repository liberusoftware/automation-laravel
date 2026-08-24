<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\RevenueAndCareOrchestration\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Liberu\RevenueAndCareOrchestration\Actions\ObserveHealthSignal;
use Liberu\Modules\Liberu\RevenueAndCareOrchestration\Models\HealthSignal;

final class HealthSignalController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => HealthSignal::query()->forTeam($this->teamId($request))->fresh()->latest()->paginate(min($request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, ObserveHealthSignal $observe): JsonResponse
    {
        $data = $request->validate(['customer_id' => ['required', 'string', 'max:255'], 'kind' => ['required', 'string', 'max:100'], 'observation' => ['required', 'array'], 'evidence' => ['array'], 'expires_at' => ['nullable', 'date']]);
        $signal = $observe->execute($this->teamId($request), $data['customer_id'], $data['kind'], $data['observation'], $data['evidence'] ?? [], $data['expires_at'] ?? null);

        return response()->json(['data' => $signal->toArray()], 201);
    }

    private function teamId(Request $request): string
    {
        $id = (string) $request->user()->currentTeam?->getKey();
        abort_if($id === '', 403);

        return $id;
    }
}
