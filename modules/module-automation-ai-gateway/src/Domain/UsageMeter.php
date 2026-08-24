<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Domain;

use InvalidArgumentException;

final class UsageMeter
{
    private int $inputTokens = 0;

    private int $outputTokens = 0;

    private int $requests = 0;

    public function record(int $inputTokens, int $outputTokens, ModelDefinition $model): void
    {
        if ($inputTokens < 0 || $outputTokens < 0 || ! $model->enabled) {
            throw new InvalidArgumentException('Usage must be non-negative and recorded against an enabled model.');
        }

        $this->inputTokens += $inputTokens;
        $this->outputTokens += $outputTokens;
        $this->requests++;
    }

    /** @return array<string, int> */
    public function totals(): array
    {
        return ['requests' => $this->requests, 'input_tokens' => $this->inputTokens, 'output_tokens' => $this->outputTokens];
    }

    public function estimatedCostMicros(ModelDefinition $model): int
    {
        return $this->inputTokens * $model->inputCostMicros + $this->outputTokens * $model->outputCostMicros;
    }
}
