<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Automation\AutomationCore\Actions\CancelWorkflowRun;
use Liberu\Modules\Automation\AutomationCore\Actions\CreateAutomationCoreResource;
use Liberu\Modules\Automation\AutomationCore\Actions\PublishWorkflow;
use Liberu\Modules\Automation\AutomationCore\Actions\StartWorkflowRun;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;
use Liberu\Modules\Automation\AutomationCore\Models\AutomationCoreResource;
use Liberu\Modules\Automation\AutomationCore\Models\WorkflowRunRecord;

final class AutomationCoreResourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return response()->json(['data' => AutomationCoreResource::query()->forTeam($teamId)->latest()->paginate(min((int) $request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, CreateAutomationCoreResource $create): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'payload' => ['array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = $create->execute($teamId, $data['name'], $data['payload'] ?? [], $data['idempotency_key'] ?? null);

        return response()->json(['data' => $resource->toArray()], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return response()->json(['data' => AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id)->toArray()]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate(['name' => ['sometimes', 'string', 'max:255'], 'payload' => ['sometimes', 'array'], 'status' => ['sometimes', 'string', 'max:32']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id);
        $resource->update($data);

        return response()->json(['data' => $resource->refresh()->toArray()]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id)->delete();

        return response()->json(status: 204);
    }

    public function publish(Request $request, string $id, PublishWorkflow $publish): JsonResponse
    {
        $data = $request->validate(['definition' => ['required', 'array']]);
        $teamId = $this->teamId($request);
        $workflow = AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id);
        $version = $publish->execute($workflow, $teamId, WorkflowDefinition::fromArray($data['definition']));

        return response()->json(['data' => $version->toArray()], 201);
    }

    public function run(Request $request, string $id, StartWorkflowRun $start): JsonResponse
    {
        $data = $request->validate(['variables' => ['array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $teamId = $this->teamId($request);
        $workflow = AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id);
        $run = $start->execute($workflow, $teamId, $data['variables'] ?? [], $data['idempotency_key'] ?? $request->header('Idempotency-Key'));

        return response()->json(['data' => $run->toArray()], 202);
    }

    public function cancelRun(Request $request, string $id, string $runId, CancelWorkflowRun $cancel): JsonResponse
    {
        $teamId = $this->teamId($request);
        $run = WorkflowRunRecord::query()->forTeam($teamId)->where('workflow_id', $id)->findOrFail($runId);

        return response()->json(['data' => $cancel->execute($run, $teamId)->toArray()]);
    }

    private function teamId(Request $request): string
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return $teamId;
    }
}
