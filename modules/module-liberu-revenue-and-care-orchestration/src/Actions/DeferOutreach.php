<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\RevenueAndCareOrchestration\Actions;

use Illuminate\Validation\ValidationException;
use Liberu\Modules\Liberu\RevenueAndCareOrchestration\Models\HealthSignal;

final class DeferOutreach
{
    public function execute(HealthSignal $signal, \DateTimeInterface $nextContactAt, string $reason): HealthSignal
    {
        if ($signal->consent['withdrawn'] ?? false) {
            throw ValidationException::withMessages(['consent' => 'Outreach is blocked because consent was withdrawn.']);
        } $signal->update(['status' => 'outreach_deferred', 'next_contact_at' => $nextContactAt, 'evidence' => [...($signal->evidence ?? []), ['reason' => $reason, 'at' => now()->toISOString()]]]);

        return $signal->refresh();
    }
}
