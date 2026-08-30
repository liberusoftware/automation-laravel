<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Domain;

use InvalidArgumentException;
use Liberu\Modules\Automation\Rules\Services\RuleEvaluator;

final readonly class DecisionTable
{
    /** @param list<array{conditions: list<array{field:string,operator:string,value:mixed}>, outcome: string}> $rows */
    public function __construct(public string $name, public array $rows)
    {
        if (trim($name) === '' || $rows === [] || count($rows) > 1000) {
            throw new InvalidArgumentException('Decision tables require a name and at least one row.');
        }

        foreach ($rows as $row) {
            if (! is_array($row) || ! is_array($row['conditions'] ?? null) || ($row['conditions'] ?? []) === []) {
                throw new InvalidArgumentException('Decision table rows require conditions and an outcome.');
            }

            foreach ($row['conditions'] as $condition) {
                if (! is_array($condition)) {
                    throw new InvalidArgumentException('Decision table conditions must be arrays.');
                }
                RuleCondition::fromArray($condition);
            }

            if (! is_string($row['outcome'] ?? null) || trim($row['outcome']) === '') {
                throw new InvalidArgumentException('Decision table rows require conditions and an outcome.');
            }
        }
    }

    /** @return list<string> */
    public function outcomesFor(array $context): array
    {
        $evaluator = new RuleEvaluator();

        return array_values(array_map(
            static fn (array $row): string => $row['outcome'],
            array_filter($this->rows, static fn (array $row): bool => $evaluator->all($row['conditions'], $context)),
        ));
    }

    /** @param array<string, mixed> $context */
    public function simulate(array $context): RuleSimulation
    {
        $outcomes = $this->outcomesFor($context);

        return new RuleSimulation($outcomes !== [], $outcomes, $context);
    }
}
