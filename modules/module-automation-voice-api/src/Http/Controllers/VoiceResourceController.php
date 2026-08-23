<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Liberu\Modules\Automation\Voice\Actions\CreateVoiceResource;
use Liberu\Modules\Automation\Voice\Models\VoiceResource;

final class VoiceResourceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);

        return response()->json(['data' => VoiceResource::query()->forTeam($teamId)->latest()->paginate(min((int) $request->integer('per_page', 25), 100))]);
    }

    public function store(Request $request, CreateVoiceResource $create): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'payload' => ['array'], 'idempotency_key' => ['nullable', 'string', 'max:255']]);
        $teamId = (string) $request->user()->currentTeam?->getKey();
        abort_if($teamId === '', 403);
        $resource = $create->execute($teamId, $data['name'], $data['payload'] ?? [], $data['idempotency_key'] ?? null);

        return response()->json(['data' => $resource->toArray()], 201);
    }
}
