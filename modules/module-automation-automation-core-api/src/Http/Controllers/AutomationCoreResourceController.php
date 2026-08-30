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
use Liberu\Modules\Automation\AutomationCore\Actions\TransitionAutomationCoreResource;
use Liberu\Modules\Automation\AutomationCore\Api\Support\ResourcePayload;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;
use Liberu\Modules\Automation\AutomationCore\Models\AutomationCoreResource;
use Liberu\Modules\Automation\AutomationCore\Models\WorkflowRunRecord;

final class AutomationCoreResourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        $pageSize = max(1, min($request->integer('page.size', $request->integer('per_page', 25)), 100));
        $page = max(1, $request->integer('page.number', 1));

        $resources = AutomationCoreResource::query()->forTeam($teamId)->latest()->paginate($pageSize, ['*'], 'page', $page);

        return response()->json(ResourcePayload::collection($resources));
    }

    public function store(Request $request, CreateAutomationCoreResource $create): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'payload' => ['array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = $create->execute(
            $teamId,
            $data['name'],
            $data['payload'] ?? [],
            $request->header('Idempotency-Key', $data['idempotency_key'] ?? null),
            (string) $request->user()->getAuthIdentifier(),
            $request->header('X-Correlation-ID'),
        );

        return response()->json(['data' => ResourcePayload::one($resource)], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return response()->json(['data' => ResourcePayload::one(AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id))]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'payload' => ['sometimes', 'array'],
        ]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id);

        if ($request->hasHeader('If-Match')) {
            $expectedVersion = trim((string) $request->header('If-Match'));
            abort_if(! ctype_digit($expectedVersion) || (int) $expectedVersion !== $resource->lock_version, 409, 'The resource changed since it was read.');
        }

        $data['lock_version'] = $resource->lock_version + 1;
        $resource->fill($data)->save();

        return response()->json(['data' => ResourcePayload::one($resource->refresh())]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id)->delete();

        return response()->json(status: 204);
    }

    public function transition(Request $request, string $id, TransitionAutomationCoreResource $transition): JsonResponse
    {
        $data = $request->validate(['status' => ['required', 'string', 'in:draft,active,paused,completed,failed,cancelled']]);
        $teamId = $this->teamId($request);
        $resource = AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id);

        $updated = $transition->execute(
            $resource,
            $teamId,
            $data['status'],
            actorId: (string) $request->user()->getAuthIdentifier(),
            correlationId: $request->header('X-Correlation-ID'),
        );

        return response()->json(['data' => ResourcePayload::one($updated)]);
    }

    public function publish(Request $request, string $id, PublishWorkflow $publish): JsonResponse
    {
        $data = $request->validate(['definition' => ['required', 'array']]);
        $teamId = $this->teamId($request);
        $workflow = AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id);
        $version = $publish->execute($workflow, $teamId, WorkflowDefinition::fromArray($data['definition']), (string) $request->user()->getAuthIdentifier(), $request->header('X-Correlation-ID'));

        return response()->json(['data' => $version->toArray()], 201);
    }

    public function run(Request $request, string $id, StartWorkflowRun $start): JsonResponse
    {
        $data = $request->validate(['variables' => ['array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $teamId = $this->teamId($request);
        $workflow = AutomationCoreResource::query()->forTeam($teamId)->findOrFail($id);
        $run = $start->execute($workflow, $teamId, $data['variables'] ?? [], $data['idempotency_key'] ?? $request->header('Idempotency-Key'), (string) $request->user()->getAuthIdentifier(), $request->header('X-Correlation-ID'));

        return response()->json(['data' => $run->toArray()], 202);
    }

    public function cancelRun(Request $request, string $id, string $runId, CancelWorkflowRun $cancel): JsonResponse
    {
        $teamId = $this->teamId($request);
        $run = WorkflowRunRecord::query()->forTeam($teamId)->where('workflow_id', $id)->findOrFail($runId);

        return response()->json(['data' => $cancel->execute($run, $teamId, (string) $request->user()->getAuthIdentifier(), $request->header('X-Correlation-ID'))->toArray()]);
    }

    private function teamId(Request $request): string
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return $teamId;
    }
}
