<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class MetricRecord extends Model
{
    use HasUuids;

    protected $table = 'liberu_executive_metrics';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['definition' => 'array', 'lineage' => 'array', 'snapshot' => 'array'];
    }

    public function scopeForTeam(Builder $query, ?string $teamId): Builder
    {
        return $query->where('team_id', $teamId);
    }
}
