<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Automation\Connectors\Actions\CreateConnectorsResource;
use Liberu\Modules\Automation\Connectors\Actions\TransitionConnectorsResource;
use Liberu\Modules\Automation\Connectors\Api\Support\ResourcePayload;
use Liberu\Modules\Automation\Connectors\Models\ConnectorsResource;

final class ConnectorsResourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        $pageSize = max(1, min($request->integer('page.size', $request->integer('per_page', 25)), 100));
        $page = max(1, $request->integer('page.number', 1));

        $resources = ConnectorsResource::query()->forTeam($teamId)->latest()->paginate($pageSize, ['*'], 'page', $page);

        return response()->json(ResourcePayload::collection($resources));
    }

    public function store(Request $request, CreateConnectorsResource $create): JsonResponse
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

        return response()->json(['data' => ResourcePayload::one(ConnectorsResource::query()->forTeam($teamId)->findOrFail($id))]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate(['name' => ['sometimes', 'string', 'max:255'], 'payload' => ['sometimes', 'array']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = ConnectorsResource::query()->forTeam($teamId)->findOrFail($id);

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
        ConnectorsResource::query()->forTeam($teamId)->findOrFail($id)->delete();

        return response()->json(status: 204);
    }

    public function transition(Request $request, string $id, TransitionConnectorsResource $transition): JsonResponse
    {
        $data = $request->validate(['status' => ['required', 'string', 'in:draft,active,paused,completed,failed,cancelled']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = ConnectorsResource::query()->forTeam($teamId)->findOrFail($id);

        return response()->json(['data' => ResourcePayload::one($transition->execute(
            $resource,
            $teamId,
            $data['status'],
            actorId: (string) $request->user()->getAuthIdentifier(),
            correlationId: $request->header('X-Correlation-ID'),
        ))]);
    }
}
