<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class ConnectorsResource extends Model
{
    use HasUuids;

    protected $table = 'automation_connectors_resources';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    public function scopeForTeam($query, ?string $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
