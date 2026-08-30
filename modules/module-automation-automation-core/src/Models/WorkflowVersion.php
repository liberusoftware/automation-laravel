<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class WorkflowVersion extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'automation_workflow_versions';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['definition' => 'array', 'metadata' => 'array', 'version' => 'integer'];
    }

    public function scopeForTeam($query, string $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
