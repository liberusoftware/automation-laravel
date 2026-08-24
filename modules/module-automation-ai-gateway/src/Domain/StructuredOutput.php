<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Domain;

use InvalidArgumentException;

final readonly class StructuredOutput
{
    /** @param array<string, mixed> $schema */
    private function __construct(public array $schema) {}

    /** @param array<string, mixed> $schema */
    public static function fromSchema(array $schema): self
    {
        if (($schema['type'] ?? null) !== 'object' || ! is_array($schema['properties'] ?? null)) {
            throw new InvalidArgumentException('Structured output requires an object schema with properties.');
        }

        return new self($schema);
    }

    /** @param array<string, mixed> $value */
    public function validate(array $value): void
    {
        foreach ((array) ($this->schema['required'] ?? []) as $field) {
            if (! array_key_exists((string) $field, $value)) {
                throw new InvalidArgumentException("Structured output is missing required field: {$field}");
            }
        }

        foreach ($value as $field => $item) {
            $definition = $this->schema['properties'][$field] ?? null;
            if (! is_array($definition) || ! $this->matchesType($item, (string) ($definition['type'] ?? ''))) {
                throw new InvalidArgumentException("Structured output field has an invalid type: {$field}");
            }
        }
    }

    private function matchesType(mixed $value, string $type): bool
    {
        return match ($type) {
            'string' => is_string($value), 'integer' => is_int($value), 'number' => is_int($value) || is_float($value),
            'boolean' => is_bool($value), 'array', 'object' => is_array($value), default => false,
        };
    }
}
