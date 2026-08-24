<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Actions\StartWorkflowRun;
use Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Models\WorkflowRun;

final class WorkflowRunController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => WorkflowRun::query()->forTeam($this->teamId($request))->latest()->paginate(min($request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, StartWorkflowRun $start): JsonResponse
    {
        $data = $request->validate(['workflow' => ['required', 'string', 'max:255'], 'correlation_id' => ['required', 'string', 'max:255'], 'steps' => ['array']]);
        $run = $start->execute($this->teamId($request), $data['workflow'], $data['correlation_id'], $data['steps'] ?? []);

        return response()->json(['data' => $run->toArray()], 201);
    }

    private function teamId(Request $request): string
    {
        $id = (string) $request->user()->currentTeam?->getKey();
        abort_if($id === '', 403);

        return $id;
    }
}
