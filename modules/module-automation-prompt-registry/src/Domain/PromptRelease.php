<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Domain;

use InvalidArgumentException;

final class PromptRelease
{
    private ?PromptVersion $active = null;

    /** @param list<PromptVersion> $versions */
    public function __construct(public readonly string $key, private array $versions)
    {
        $versionNumbers = array_map(static fn (PromptVersion $version): int => $version->version, $versions);
        if ($key === '' || $versions === [] || count($versionNumbers) !== count(array_unique($versionNumbers)) || array_filter($versions, fn (PromptVersion $version): bool => $version->key !== $key) !== []) {
            throw new InvalidArgumentException('Prompt releases require versions with one matching key.');
        }
    }

    public function publish(PromptVersion $version, PromptApproval $approval): void
    {
        if ($version->key !== $this->key || $approval->key !== $this->key || $approval->version !== $version->version) {
            throw new InvalidArgumentException('Only an approved matching prompt version can be published.');
        }
        if (! $this->hasVersion($version->version)) {
            throw new InvalidArgumentException('Only a registered prompt version can be published.');
        }
        $this->active = $version;
    }

    public function rollbackTo(int $version): void
    {
        foreach ($this->versions as $candidate) {
            if ($candidate->version === $version) {
                $this->active = $candidate;

                return;
            }
        }
        throw new InvalidArgumentException('The requested prompt version does not exist.');
    }

    public function active(): ?PromptVersion
    {
        return $this->active;
    }

    public function hasVersion(int $version): bool
    {
        return array_any($this->versions, static fn (PromptVersion $candidate): bool => $candidate->version === $version);
    }
}
