<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Domain;

use InvalidArgumentException;

final readonly class ProcessingRequest
{
    private const OPERATIONS = ['classification', 'extraction', 'summarization', 'translation', 'enrichment', 'redaction'];

    /** @param array<string, mixed> $options */
    public function __construct(public string $operation, public string $input, public bool $redactSensitive = false, public array $options = [])
    {
        if (! in_array($operation, self::OPERATIONS, true) || trim($input) === '' || strlen($input) > 100000) {
            throw new InvalidArgumentException('Processing requests require a supported operation and input.');
        }

        if ($operation === 'translation' && preg_match('/^[a-z]{2,3}(?:-[A-Z][a-z]{3})?(?:-[A-Z]{2}|-[0-9]{3})?$/', (string) ($options['target_locale'] ?? '')) !== 1) {
            throw new InvalidArgumentException('Translation requests require a target locale.');
        }

        if ($operation === 'extraction' && isset($options['schema']) && ! is_array($options['schema'])) {
            throw new InvalidArgumentException('Extraction schemas must be structured arrays.');
        }
    }

    public function requiresRedaction(): bool
    {
        return $this->redactSensitive || $this->operation === 'redaction';
    }

    public function targetLocale(): ?string
    {
        $locale = $this->options['target_locale'] ?? null;

        return is_string($locale) && $locale !== '' ? $locale : null;
    }
}
