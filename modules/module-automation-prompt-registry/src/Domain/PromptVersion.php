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
    }

    /** @param array<string, scalar> $values */
    public function render(array $values): string
    {
        foreach ($this->variables as $variable) {
            if (! array_key_exists($variable, $values)) {
                throw new InvalidArgumentException("Missing prompt variable: {$variable}");
            }
        }

        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_.-]+)\s*\}\}/', static function (array $match) use ($values): string {
            return (string) ($values[$match[1]] ?? $match[0]);
        }, $this->template) ?? $this->template;
    }
}
