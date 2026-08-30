<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Liberu\ExecutiveInsights\Actions\RegisterMetric;
use Liberu\Modules\Liberu\ExecutiveInsights\Models\MetricRecord;

final class MetricController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => MetricRecord::query()->forTeam($this->teamId($request))->latest()->paginate(min($request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, RegisterMetric $register): JsonResponse
    {
        $data = $request->validate(['key' => ['required', 'string', 'max:255'], 'definition' => ['required', 'array'], 'lineage' => ['array']]);
        $metric = $register->execute($this->teamId($request), $data['key'], $data['definition'], $data['lineage'] ?? []);

        return response()->json(['data' => $metric->toArray()], 201);
    }

    private function teamId(Request $request): string
    {
        $id = (string) $request->user()->currentTeam?->getKey();
        abort_if($id === '', 403);

        return $id;
    }
}
