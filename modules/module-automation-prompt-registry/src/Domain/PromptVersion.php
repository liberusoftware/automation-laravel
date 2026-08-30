<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Domain;

use InvalidArgumentException;

final readonly class PromptVersion
{
    /** @param list<string> $variables */
    public function __construct(
        public string $key,
        public int $version,
        public string $template,
        public array $variables = [],
    ) {
        if ($key === '' || $version < 1 || trim($template) === '') {
            throw new InvalidArgumentException('A prompt key, positive version, and template are required.');
        }

        if (count($variables) !== count(array_unique($variables)) || array_filter($variables, static fn (mixed $variable): bool => ! is_string($variable) || preg_match('/^[a-zA-Z][a-zA-Z0-9_.-]*$/', $variable) !== 1) !== []) {
            throw new InvalidArgumentException('Prompt variables must be unique, named, and safe identifiers.');
        }

        preg_match_all('/\{\{\s*([a-zA-Z0-9_.-]+)\s*\}\}/', $template, $matches);
        if (array_diff(array_unique($matches[1]), $variables) !== []) {
            throw new InvalidArgumentException('Prompt templates may only reference declared variables.');
        }
    }

    /** @param array<string, scalar> $values */
    public function render(array $values): string
    {
        foreach ($this->variables as $variable) {
            if (! array_key_exists($variable, $values)) {
                throw new InvalidArgumentException("Missing prompt variable: {$variable}");
            }
            if (! is_scalar($values[$variable]) && $values[$variable] !== null) {
                throw new InvalidArgumentException("Prompt variable must be scalar: {$variable}");
            }
        }

        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_.-]+)\s*\}\}/', static function (array $match) use ($values): string {
            return (string) ($values[$match[1]] ?? $match[0]);
        }, $this->template) ?? $this->template;
    }

    public function approvedForRelease(string $approverId, string $authorId): PromptApproval
    {
        return PromptApproval::approve($this->key, $this->version, $approverId, $authorId);
    }
}
