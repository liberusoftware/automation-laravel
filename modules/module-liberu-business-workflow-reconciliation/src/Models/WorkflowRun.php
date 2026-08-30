<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class WorkflowRun extends Model
{
    use HasUuids;

    protected $table = 'liberu_workflow_runs';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['steps' => 'array', 'events' => 'array', 'recovery' => 'array'];
    }

    public function scopeForTeam(Builder $query, ?string $teamId): Builder
    {
        return $query->where('team_id', $teamId);
    }
}
