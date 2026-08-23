<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Services;

use Liberu\Modules\Automation\Rules\Domain\RuleCondition;

final class RuleEvaluator
{
    /**
     * @param  list<array<string, mixed>>  $conditions
     * @param  array<string, mixed>  $context
     */
    public function all(array $conditions, array $context): bool
    {
        return array_all($conditions, fn (array $condition): bool => RuleCondition::fromArray($condition)->matches($context));
    }

    /**
     * @param  list<array<string, mixed>>  $conditions
     * @param  array<string, mixed>  $context
     */
    public function any(array $conditions, array $context): bool
    {
        return array_any($conditions, fn (array $condition): bool => RuleCondition::fromArray($condition)->matches($context));
    }
}
