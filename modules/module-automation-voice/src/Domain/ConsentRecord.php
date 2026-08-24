<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Domain;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

final class ConsentRecord
{
    public function __construct(
        public readonly string $id,
        public readonly string $actorId,
        public readonly string $purpose,
        public readonly CarbonImmutable $grantedAt,
        private ?CarbonImmutable $revokedAt = null,
    ) {
        if ($id === '' || $actorId === '' || $purpose === '') {
            throw new InvalidArgumentException('Voice consent requires an actor, purpose, and identifier.');
        }
    }

    public function revoke(CarbonImmutable $at): void
    {
        if ($at->lessThan($this->grantedAt)) {
            throw new InvalidArgumentException('Consent cannot be revoked before it was granted.');
        }
        $this->revokedAt = $at;
    }

    public function isActive(CarbonImmutable $at): bool
    {
        return $this->revokedAt === null || $this->revokedAt->greaterThan($at);
    }
}
