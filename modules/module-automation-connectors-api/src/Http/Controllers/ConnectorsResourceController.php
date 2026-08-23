<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Automation\Connectors\Actions\CreateConnectorsResource;
use Liberu\Modules\Automation\Connectors\Models\ConnectorsResource;

final class ConnectorsResourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return response()->json(['data' => ConnectorsResource::query()->forTeam($teamId)->latest()->paginate(min((int) $request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, CreateConnectorsResource $create): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'payload' => ['array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = $create->execute($teamId, $data['name'], $data['payload'] ?? [], $data['idempotency_key'] ?? null);

        return response()->json(['data' => $resource->toArray()], 201);
    }
}
