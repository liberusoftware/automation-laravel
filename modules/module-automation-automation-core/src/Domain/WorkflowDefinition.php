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
        public array $triggers,
        public ?WorkflowSchedule $schedule,
        public RetryPolicy $retryPolicy,
        public array $compensation,
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

        if (! is_array($steps) || $steps === [] || count($steps) > 1000) {
            throw new InvalidArgumentException('A workflow must contain at least one step.');
        }

        foreach ($steps as $step) {
            if (! is_array($step) || trim((string) ($step['type'] ?? '')) === '') {
                throw new InvalidArgumentException('Every workflow step must declare a type.');
            }
        }

        $triggers = $attributes['triggers'] ?? [];
        if (! is_array($triggers)) {
            throw new InvalidArgumentException('Workflow triggers must be an array.');
        }

        foreach ($triggers as $trigger) {
            if (! is_array($trigger)) {
                throw new InvalidArgumentException('Workflow triggers must be structured arrays.');
            }
            new WorkflowTrigger(
                (string) ($trigger['type'] ?? ''),
                (string) ($trigger['event'] ?? ''),
                (bool) ($trigger['enabled'] ?? true),
            );
        }

        if (isset($attributes['schedule']) && ! is_array($attributes['schedule'])) {
            throw new InvalidArgumentException('Workflow schedules must be structured arrays.');
        }
        $schedule = isset($attributes['schedule']) ? WorkflowSchedule::fromArray($attributes['schedule']) : null;
        $retryPolicy = RetryPolicy::fromArray($attributes['retry'] ?? []);
        $compensation = $attributes['compensation'] ?? [];
        if (! is_array($compensation)) {
            throw new InvalidArgumentException('Workflow compensation must be an array.');
        }
        foreach ($compensation as $step) {
            if (! is_array($step) || trim((string) ($step['type'] ?? '')) === '') {
                throw new InvalidArgumentException('Every compensation step must declare a type.');
            }
        }

        return new self(
            name: $name,
            inputSchema: self::schema($attributes['input_schema'] ?? []),
            outputSchema: self::schema($attributes['output_schema'] ?? []),
            steps: array_values($steps),
            triggers: array_values($triggers),
            schedule: $schedule,
            retryPolicy: $retryPolicy,
            compensation: array_values($compensation),
        );
    }

    /** @return array<string, mixed> */
    private static function schema(mixed $schema): array
    {
        if (! is_array($schema)) {
            throw new InvalidArgumentException('Workflow schemas must be structured arrays.');
        }

        return $schema;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'input_schema' => $this->inputSchema,
            'output_schema' => $this->outputSchema,
            'steps' => $this->steps,
            'triggers' => $this->triggers,
            'schedule' => $this->schedule?->toArray(),
            'retry' => $this->retryPolicy->toArray(),
            'compensation' => $this->compensation,
        ];
    }
}
