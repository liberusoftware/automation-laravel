<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Api\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

final class ResourcePayload
{
    public static function one(Model $resource): array
    {
        return [
            'id' => (string) $resource->getKey(),
            'type' => 'automation-ai-gateway',
            'attributes' => Arr::except($resource->toArray(), [
                'id',
                'team_id',
                'actor_id',
                'deleted_at',
            ]),
        ];
    }

    public static function collection(LengthAwarePaginator $resources): array
    {
        return [
            'data' => $resources->getCollection()
                ->map(static fn (Model $resource): array => self::one($resource))
                ->values()
                ->all(),
            'links' => [
                'first' => $resources->url(1),
                'last' => $resources->url($resources->lastPage()),
                'prev' => $resources->previousPageUrl(),
                'next' => $resources->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $resources->currentPage(),
                'from' => $resources->firstItem(),
                'last_page' => $resources->lastPage(),
                'per_page' => $resources->perPage(),
                'to' => $resources->lastItem(),
                'total' => $resources->total(),
            ],
        ];
    }
}
