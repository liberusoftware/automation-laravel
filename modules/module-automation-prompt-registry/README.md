# PromptRegistry

Provider-neutral Liberu Automation module for **versioned prompts, variables, evaluation sets, tenant overrides, approvals, rollback**.

The core package owns domain state, validation, persistence, policies, and lifecycle events. API, Filament, and Livewire adapters are optional one-to-one packages and contain no domain rules.

## Compatibility

PHP 8.5 · Laravel 13 · Composer 2 · Pest 5

## Installation

```bash
composer require liberusoftware/module-automation-prompt-registry
php artisan migrate
```

The module uses explicit team context and idempotency keys. Disabling it never deletes its data.

