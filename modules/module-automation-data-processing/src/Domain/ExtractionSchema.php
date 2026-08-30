<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Domain;

use InvalidArgumentException;

final readonly class ExtractionSchema
{
    /** @param array<string, string> $fields */
    public function __construct(public array $fields)
    {
        if ($fields === [] || count($fields) > 100 || array_filter($fields, static fn (mixed $type, mixed $field): bool => ! is_string($field) || preg_match('/^[a-zA-Z][a-zA-Z0-9_.-]*$/', $field) !== 1 || ! in_array($type, ['string', 'integer', 'number', 'boolean'], true), ARRAY_FILTER_USE_BOTH) !== []) {
            throw new InvalidArgumentException('Extraction schemas require at least one supported typed field.');
        }
    }

    /** @param array<string, mixed> $value */
    public function validate(array $value): void
    {
        foreach ($this->fields as $field => $type) {
            if (! array_key_exists($field, $value)) {
                throw new InvalidArgumentException("Extraction result is missing field: {$field}");
            }

            $valid = match ($type) {
                'string' => is_string($value[$field]), 'integer' => is_int($value[$field]), 'number' => is_int($value[$field]) || is_float($value[$field]), 'boolean' => is_bool($value[$field]),
            };
            if (! $valid) {
                throw new InvalidArgumentException("Extraction result has an invalid field type: {$field}");
            }
        }
    }
}
