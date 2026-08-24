<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Domain;

use InvalidArgumentException;

final readonly class ModelDefinition
{
    public function __construct(
        public string $provider,
        public string $model,
        public string $capability,
        public int $contextWindow,
        public int $inputCostMicros = 0,
        public int $outputCostMicros = 0,
        public bool $enabled = true,
    ) {
        if ($provider === '' || $model === '' || $capability === '' || $contextWindow < 1 || $inputCostMicros < 0 || $outputCostMicros < 0) {
            throw new InvalidArgumentException('Models require provider, name, capability, and non-negative bounded metadata.');
        }
    }

    public function supportsContext(int $tokens): bool
    {
        return $this->enabled && $tokens >= 0 && $tokens <= $this->contextWindow;
    }
}
