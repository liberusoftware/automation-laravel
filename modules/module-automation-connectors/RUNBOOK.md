# Connectors operations runbook

This runbook covers the provider-neutral `connectors` module and its capabilities: authenticated triggers/actions, webhooks, rate limits, cursor sync, replay, reconciliation.

## Context and safety

Every record is scoped to a team. Resolve the authenticated actor and active team before reading or mutating data; never accept a team identifier from an untrusted payload. API, Filament, Livewire, queue, and console entry points must use the same ownership and lifecycle rules.

Sensitive payloads, credentials, provider responses, generated media, and personal data are untrusted. Keep secrets in the application secret store, redact payloads from logs, and use the module's provenance or metadata fields for non-sensitive correlation data.

## Install and upgrade

Install the core package and run the host application's migrations:

```bash
composer require liberusoftware/connectors
php artisan migrate --force
```

Install an API, Filament, or Livewire adapter only when that surface is explicitly enabled. Disablement is non-destructive: it stops registration while retaining module data. Apply released migrations in order and take a database backup before upgrades. Do not edit an applied migration; add a forward migration for schema changes.

## Lifecycle and recovery

Records start in `draft`. Use the explicit transition operation for lifecycle changes; do not update `status` through a general edit operation. The supported state graph is:

```
draft -> active -> paused -> active
draft -> cancelled
active -> completed | failed | cancelled
paused -> cancelled
failed -> active | cancelled
```

Completed and cancelled records are terminal. A failed operation should preserve its correlation identifier and sanitized failure metadata so an operator can retry or cancel it deliberately. Retryable requests must carry an idempotency key and callers should use `If-Match` when updating a previously read resource.

## Observability

Include the team, actor, resource, operation, and correlation identifiers in structured logs and traces. Never log raw credentials or sensitive input. Investigate a failed operation by locating its correlation ID, checking the resource status and sanitized metadata, and reviewing the provider/queue logs. Escalate repeated failures rather than increasing retry counts without bounds.

## Retention and deletion

Use soft deletion for ordinary record removal and retain audit/provenance evidence for the configured legal-retention period. Exports must be team-scoped and access-controlled. A legal hold or active approval blocks destructive deletion. Purges are explicit maintenance operations and must be logged with actor and scope.

## Verification checklist

- Confirm the active team and authorization before every operation.
- Confirm payload validation and redaction before dispatching external work.
- Confirm idempotency and optimistic-concurrency headers on retryable writes.
- Confirm the transition is allowed and emits the corresponding domain event.
- Confirm failures leave recoverable, operator-visible state.
- Run the module tests, architecture checks, OpenAPI validation, and PHPStan before release.

