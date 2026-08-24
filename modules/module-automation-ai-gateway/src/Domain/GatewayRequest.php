<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Domain;

use InvalidArgumentException;

final readonly class GatewayRequest
{
    /** @param array<string, mixed> $input */
    public function __construct(
        public string $capability,
        public string $model,
        public array $input,
        public ?StructuredOutput $structuredOutput = null,
        public ?ToolPolicy $toolPolicy = null,
    ) {
        if ($capability === '' || $model === '' || $input === []) {
            throw new InvalidArgumentException('Gateway requests require a capability, model, and input.');
        }
    }
}
