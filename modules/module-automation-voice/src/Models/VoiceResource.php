<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class VoiceResource extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'automation_voice_resources';

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
