<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Domain;

use InvalidArgumentException;

final readonly class ProviderContract
{
    /** @param list<string> $models */
    public function __construct(
        public string $provider,
        public array $models,
        public bool $supportsStructuredOutput = false,
        public bool $supportsTools = false,
    ) {
        if ($provider === '' || $models === []) {
            throw new InvalidArgumentException('A provider and at least one model are required.');
        }

        if (array_filter($models, static fn (string $model): bool => $model === '') !== []) {
            throw new InvalidArgumentException('Provider models cannot be empty.');
        }
    }

    public function supports(string $model): bool
    {
        return in_array($model, $this->models, true);
    }

    public function validateRequest(GatewayRequest $request): void
    {
        if (! $this->supports($request->model)) {
            throw new InvalidArgumentException('The provider does not support the requested model.');
        }

        if ($request->structuredOutput !== null && ! $this->supportsStructuredOutput) {
            throw new InvalidArgumentException('The provider does not support structured output.');
        }

        if ($request->toolPolicy !== null && ! $this->supportsTools) {
            throw new InvalidArgumentException('The provider does not support tools.');
        }
    }
}
