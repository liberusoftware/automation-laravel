<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Domain;

use InvalidArgumentException;

final readonly class ProcessingRequest
{
    private const OPERATIONS = ['classification', 'extraction', 'summarization', 'translation', 'enrichment', 'redaction'];

    public function __construct(public string $operation, public string $input, public bool $redactSensitive = false)
    {
        if (! in_array($operation, self::OPERATIONS, true) || trim($input) === '') {
            throw new InvalidArgumentException('Processing requests require a supported operation and input.');
        }
    }

    public function requiresRedaction(): bool
    {
        return $this->redactSensitive || $this->operation === 'redaction';
    }
}
