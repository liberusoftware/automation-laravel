# Image

Provider-neutral Liberu Automation module for **generation requests, editing requests, source assets, moderation, provenance, variants, delivery**.

The core package owns domain state, validation, persistence, policies, and lifecycle events. API, Filament, and Livewire adapters are optional one-to-one packages and contain no domain rules.

## Compatibility

PHP 8.5 · Laravel 13 · Composer 2 · Pest 5

## Installation

```bash
composer require liberusoftware/automation-image
php artisan migrate
```

The module uses explicit team context and idempotency keys. Disabling it never deletes its data.

See [RUNBOOK.md](RUNBOOK.md) for lifecycle operations, recovery, observability, and upgrade guidance.
