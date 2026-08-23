<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Domain;

use InvalidArgumentException;

final readonly class RuleCondition
{
    private const OPERATORS = [
        'equals',
        'not_equals',
        'contains',
        'greater_than',
        'less_than',
        'exists',
    ];

    private function __construct(
        public string $field,
        public string $operator,
        public mixed $expected,
    ) {}

    /** @param array<string, mixed> $condition */
    public static function fromArray(array $condition): self
    {
        $field = trim((string) ($condition['field'] ?? ''));
        $operator = (string) ($condition['operator'] ?? '');

        if ($field === '' || ! in_array($operator, self::OPERATORS, true)) {
            throw new InvalidArgumentException('A rule condition requires a field and supported operator.');
        }

        if ($operator !== 'exists' && ! array_key_exists('value', $condition)) {
            throw new InvalidArgumentException('This rule condition requires a comparison value.');
        }

        return new self($field, $operator, $condition['value'] ?? null);
    }

    /** @param array<string, mixed> $context */
    public function matches(array $context): bool
    {
        $exists = array_key_exists($this->field, $context);
        $actual = $context[$this->field] ?? null;

        return match ($this->operator) {
            'equals' => $exists && $actual === $this->expected,
            'not_equals' => ! $exists || $actual !== $this->expected,
            'contains' => $exists && is_string($actual) && is_string($this->expected)
                && str_contains($actual, $this->expected),
            'greater_than' => $exists && is_numeric($actual) && is_numeric($this->expected)
                && $actual > $this->expected,
            'less_than' => $exists && is_numeric($actual) && is_numeric($this->expected)
                && $actual < $this->expected,
            'exists' => $exists === (bool) $this->expected,
        };
    }
}
