<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Liberu\Modules\Automation\Voice\Domain\ConsentRecord;
use Liberu\Modules\Automation\Voice\Domain\SpeechSynthesisRequest;
use Liberu\Modules\Automation\Voice\Domain\StreamingSession;
use Liberu\Modules\Automation\Voice\Domain\TranscriptSegment;
use Liberu\Modules\Automation\Voice\Domain\VoiceRequest;

it('requires consent for voice processing', function (): void {
    expect((new VoiceRequest('stream', 'en-GB', true))->locale)->toBe('en-GB');
    expect(fn () => new VoiceRequest('stream', 'en-GB', false))->toThrow(InvalidArgumentException::class);
});

it('governs streaming interruptions, transcripts, synthesis, and revocable consent', function (): void {
    $now = CarbonImmutable::parse('2026-08-24T00:00:00Z');
    $consent = new ConsentRecord('consent-1', 'actor-1', 'support-call', $now);
    $session = new StreamingSession('stream-1', 'team-1', new VoiceRequest('stream', 'en-GB', true));
    $session->start();
    $session->append(new TranscriptSegment(0, 'speaker-1', 'Hello', 0, 0.8));
    $session->interrupt();
    $session->start();
    $session->complete();
    $consent->revoke($now->addMinute());

    expect($session->status())->toBe('completed')
        ->and($session->transcript())->toHaveCount(1)
        ->and($consent->isActive($now->addMinutes(2)))->toBeFalse()
        ->and(new SpeechSynthesisRequest('Hello', 'alloy'))->toBeInstanceOf(SpeechSynthesisRequest::class);

    expect(fn () => $session->append(new TranscriptSegment(1, 'speaker-1', 'late', 1, 2)))->toThrow(InvalidArgumentException::class);
});
