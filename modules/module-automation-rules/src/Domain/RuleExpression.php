<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Domain;

use InvalidArgumentException;

final readonly class RuleExpression
{
    /** @param list<self|RuleCondition> $children */
    private function __construct(public string $operator, public array $children) {}

    /** @param array<string, mixed> $expression */
    public static function fromArray(array $expression): self|RuleCondition
    {
        if (isset($expression['field'])) {
            return RuleCondition::fromArray($expression);
        }

        $operator = (string) ($expression['operator'] ?? '');
        if (! in_array($operator, ['all', 'any', 'not'], true)) {
            throw new InvalidArgumentException('Rule expressions require all, any, or not operators.');
        }

        $items = $expression['conditions'] ?? [];
        if (! is_array($items) || $items === [] || ($operator === 'not' && count($items) !== 1)) {
            throw new InvalidArgumentException('Rule expression children are invalid.');
        }

        return new self($operator, array_map(static fn (mixed $item): self|RuleCondition => is_array($item) ? self::fromArray($item) : throw new InvalidArgumentException('Rule expression children must be arrays.'), $items));
    }

    /** @param array<string, mixed> $context */
    public function matches(array $context): bool
    {
        $results = array_map(static fn (self|RuleCondition $child): bool => $child instanceof self ? $child->matches($context) : $child->matches($context), $this->children);

        return match ($this->operator) {
            'all' => ! in_array(false, $results, true),
            'any' => in_array(true, $results, true),
            'not' => ! $results[0],
        };
    }
}
