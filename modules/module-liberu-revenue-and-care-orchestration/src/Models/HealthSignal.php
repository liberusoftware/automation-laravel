<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\RevenueAndCareOrchestration\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class HealthSignal extends Model
{
    use HasUuids;

    protected $table = 'liberu_health_signals';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['observation' => 'array', 'evidence' => 'array', 'consent' => 'array', 'next_contact_at' => 'datetime', 'expires_at' => 'datetime'];
    }

    public function scopeForTeam(Builder $query, ?string $teamId): Builder
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeFresh(Builder $query): Builder
    {
        return $query->where(fn (Builder $q): Builder => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }
}
