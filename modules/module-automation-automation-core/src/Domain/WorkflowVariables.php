<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Domain;

use InvalidArgumentException;

final readonly class WorkflowVariables
{
    /** @param array<string, mixed> $values */
    private function __construct(public array $values) {}

    /** @param array<string, mixed> $values @param array<string, mixed> $schema */
    public static function validate(array $values, array $schema = []): self
    {
        foreach ($schema as $name => $definition) {
            if (! is_string($name) || preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name) !== 1 || ! is_array($definition)) {
                throw new InvalidArgumentException('Workflow schemas require named object definitions.');
            }
            if (is_array($definition) && ($definition['required'] ?? false) && ! array_key_exists($name, $values)) {
                throw new InvalidArgumentException("Missing required workflow variable: {$name}");
            }
        }

        foreach ($values as $name => $value) {
            if (! is_string($name) || trim($name) === '' || str_contains($name, '.')) {
                throw new InvalidArgumentException('Workflow variable names must be non-empty dot-free strings.');
            }
            if (isset($schema[$name]) && ! is_array($schema[$name])) {
                throw new InvalidArgumentException("Workflow variable schema is invalid: {$name}");
            }
            $expected = $schema[$name]['type'] ?? null;
            if ($expected !== null && ! self::matchesType($value, (string) $expected)) {
                throw new InvalidArgumentException("Workflow variable {$name} has an invalid type.");
            }
        }

        return new self($values);
    }

    private static function matchesType(mixed $value, string $type): bool
    {
        return match ($type) {
            'string' => is_string($value), 'integer' => is_int($value), 'number' => is_int($value) || is_float($value),
            'boolean' => is_bool($value), 'array', 'object' => is_array($value), default => throw new InvalidArgumentException('Unsupported workflow variable type.'),
        };
    }
}
