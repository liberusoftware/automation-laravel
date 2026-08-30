<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Domain;

use InvalidArgumentException;

final class StreamingSession
{
    /** @var list<TranscriptSegment> */
    private array $segments = [];

    private string $status = 'created';

    public function __construct(public readonly string $id, public readonly string $teamId, public readonly VoiceRequest $request)
    {
        if ($id === '' || $teamId === '' || $request->mode !== 'stream') {
            throw new InvalidArgumentException('Streaming sessions require team context and a stream request.');
        }
    }

    public function start(): void
    {
        $this->transition('active');
    }

    public function interrupt(): void
    {
        if ($this->status !== 'active') {
            throw new InvalidArgumentException('Only active voice sessions can be interrupted.');
        }
        $this->status = 'interrupted';
    }

    public function complete(): void
    {
        $this->transition('completed');
    }

    public function append(TranscriptSegment $segment): void
    {
        $previous = $this->segments === [] ? null : end($this->segments);
        if ($this->status !== 'active' || ($previous !== null && ($segment->sequence <= $previous->sequence || $segment->startSeconds < $previous->endSeconds))) {
            throw new InvalidArgumentException('Only active sessions accept strictly ordered transcript segments.');
        }
        $this->segments[] = $segment;
    }

    public function status(): string
    {
        return $this->status;
    }

    /** @return list<TranscriptSegment> */
    public function transcript(): array
    {
        return $this->segments;
    }

    private function transition(string $status): void
    {
        $allowed = match ($this->status) {
            'created' => ['active'], 'active' => ['completed', 'interrupted'], 'interrupted' => ['active', 'completed'], default => [],
        };
        if (! in_array($status, $allowed, true)) {
            throw new InvalidArgumentException('Voice session transition is not allowed.');
        }
        $this->status = $status;
    }
}
