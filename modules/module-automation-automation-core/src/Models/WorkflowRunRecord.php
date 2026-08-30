<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class WorkflowRunRecord extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'automation_workflow_runs';

    protected $guarded = ['id'];

    protected $casts = [
        'variables' => 'array',
        'metadata' => 'array',
        'version' => 'integer',
        'lock_version' => 'integer',
        'attempts' => 'integer',
        'cancel_requested' => 'boolean',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function scopeForTeam($query, string $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
