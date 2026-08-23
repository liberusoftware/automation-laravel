<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Domain;

use InvalidArgumentException;

final readonly class WorkflowDefinition
{
    /**
     * @param  array<string, mixed>  $inputSchema
     * @param  array<string, mixed>  $outputSchema
     * @param  list<array<string, mixed>>  $steps
     */
    private function __construct(
        public string $name,
        public array $inputSchema,
        public array $outputSchema,
        public array $steps,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function fromArray(array $attributes): self
    {
        $name = trim((string) ($attributes['name'] ?? ''));
        $steps = $attributes['steps'] ?? [];

        if ($name === '' || mb_strlen($name) > 255) {
            throw new InvalidArgumentException('A workflow name between 1 and 255 characters is required.');
        }

        if (! is_array($steps) || $steps === []) {
            throw new InvalidArgumentException('A workflow must contain at least one step.');
        }

        foreach ($steps as $step) {
            if (! is_array($step) || trim((string) ($step['type'] ?? '')) === '') {
                throw new InvalidArgumentException('Every workflow step must declare a type.');
            }
        }

        return new self(
            name: $name,
            inputSchema: is_array($attributes['input_schema'] ?? null) ? $attributes['input_schema'] : [],
            outputSchema: is_array($attributes['output_schema'] ?? null) ? $attributes['output_schema'] : [],
            steps: array_values($steps),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'input_schema' => $this->inputSchema,
            'output_schema' => $this->outputSchema,
            'steps' => $this->steps,
        ];
    }
}
