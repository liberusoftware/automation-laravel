<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class AutomationCoreResource extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'automation_automation_core_resources';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'metadata' => 'array',
            'lock_version' => 'integer',
        ];
    }

    public function scopeForTeam($query, ?string $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
