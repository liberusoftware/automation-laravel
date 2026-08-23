<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class WorkflowRunRecord extends Model
{
    use HasUuids;

    protected $table = 'automation_workflow_runs';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['variables' => 'array', 'version' => 'integer'];
    }

    public function scopeForTeam($query, string $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
