<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class CompositionRecord extends Model
{
    use HasUuids;

    protected $table = 'liberu_platform_compositions';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['manifest' => 'array', 'capabilities' => 'array', 'evidence' => 'array'];
    }

    public function scopeForTeam(Builder $query, ?string $teamId): Builder
    {
        return $query->where('team_id', $teamId);
    }
}
