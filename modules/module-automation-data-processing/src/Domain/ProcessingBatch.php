<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Domain;

use InvalidArgumentException;

final readonly class ProcessingBatch
{
    /** @param list<ProcessingRequest> $requests */
    public function __construct(public string $id, public array $requests)
    {
        if (trim($id) === '' || $requests === [] || count($requests) > 100 || array_filter($requests, static fn (mixed $request): bool => ! $request instanceof ProcessingRequest) !== []) {
            throw new InvalidArgumentException('Processing batches require an identifier and between 1 and 100 requests.');
        }
    }

    /** @return list<string> */
    public function operations(): array
    {
        return array_values(array_map(static fn (ProcessingRequest $request): string => $request->operation, $this->requests));
    }
}
