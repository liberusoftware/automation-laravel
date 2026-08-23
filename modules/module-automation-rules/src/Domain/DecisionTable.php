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
        if ($name === '' || $rows === []) {
            throw new InvalidArgumentException('Decision tables require a name and at least one row.');
        }

        foreach ($rows as $row) {
            if (($row['conditions'] ?? []) === [] || trim($row['outcome'] ?? '') === '') {
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
}
