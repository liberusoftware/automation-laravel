<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Video\Domain\CaptionCue;
use Liberu\Modules\Automation\Video\Domain\CaptionTrack;
use Liberu\Modules\Automation\Video\Domain\VideoDelivery;
use Liberu\Modules\Automation\Video\Domain\VideoJob;
use Liberu\Modules\Automation\Video\Domain\VideoModerationDecision;
use Liberu\Modules\Automation\Video\Domain\VideoProvenance;
use Liberu\Modules\Automation\Video\Domain\VideoRequest;

it('tracks video audio requirements', function (): void {
    expect((new VideoRequest('A product demo', true, 'audio-1'))->requiresAudio())->toBeTrue();
    expect(fn () => new VideoRequest(''))->toThrow(InvalidArgumentException::class);
});

it('governs video jobs, captions, moderation, provenance, and delivery', function (): void {
    $request = new VideoRequest('A product demo', true, 'audio-1');
    $job = new VideoJob('job-1', 'team-1', $request);
    $job->start();
    $job->fail();
    $job->retry();
    $job->start();
    $job->complete();

    expect($job->status())->toBe('completed')->and($job->attempts())->toBe(2)
        ->and(new CaptionTrack('en-GB', [new CaptionCue(0, 'Hello', 0, 1.2)]))->toBeInstanceOf(CaptionTrack::class)
        ->and((new VideoModerationDecision('approved', 'policy-1'))->mayDeliver())->toBeTrue()
        ->and(new VideoProvenance('generated', 'actor-1', str_repeat('a', 64)))->toBeInstanceOf(VideoProvenance::class)
        ->and(new VideoDelivery('https://cdn.example.test/video.mp4', 'mp4', time() + 3600))->toBeInstanceOf(VideoDelivery::class);

    expect(fn () => new CaptionTrack('en-GB', [new CaptionCue(1, 'Invalid sequence', 0, 1)]))->toThrow(InvalidArgumentException::class)
        ->and(fn () => new VideoModerationDecision('rejected', 'policy-1'))->toThrow(InvalidArgumentException::class);
});
