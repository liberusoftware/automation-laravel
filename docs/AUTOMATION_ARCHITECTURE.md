# Automation Architecture

## Overview

The automation platform provides governed, event-driven workflows that orchestrate business processes across internal and external systems. It combines a state-machine workflow engine, a rule evaluation system, multi-step approval chains, and provider-neutral AI gateways into a unified automation fabric.

## Workflow Engine

The workflow engine is built on a finite state machine (FSM) pattern with explicit states, transitions, guards, and actions.

### Core Concepts

| Concept | Description |
|---------|-------------|
| **Workflow** | A named process definition with a set of states and transitions |
| **State** | A discrete position in the workflow lifecycle (e.g., `draft`, `pending_approval`, `completed`) |
| **Transition** | A directed edge between two states, optionally guarded |
| **Guard** | A predicate that must evaluate to true for a transition to fire |
| **Action** | Side-effecting logic executed on entry, exit, or during a transition |
| **Context** | A read-only data bag passed through the workflow at runtime |

### State Machine Lifecycle

```
draft → submitted → pending_approval → approved → executing → completed
                  ↘ rejected → draft          ↘ failed → retrying → executing
```

### Transition Guards

Guards are callables registered by name and resolved at runtime:

```php
Workflow::define('content_pipeline')
    ->transition('submit', from: 'draft', to: 'pending_approval')
    ->guard('submit', Guard::class . '@hasRequiredFields')
    ->action('submit', NotifyReviewers::class);
```

### Actions

Actions implement a single `execute(WorkflowContext $context)` method. They are idempotent by contract and may be retried safely.

## Rule Evaluation System

The rule engine evaluates declarative conditions against a runtime context to drive branching, filtering, and gating decisions.

### Expression Language

Rules use a safe expression language supporting:

- **Comparison operators**: `==`, `!=`, `>`, `<`, `>=`, `<=`
- **Logical operators**: `and`, `or`, `not`
- **Membership**: `in`, `not in`
- **Null coalescing**: `??`
- **Dot notation** for nested context access: `user.plan.tier`

### Rule Structure

```php
Rule::make('high_value_deal')
    ->when('deal.value > 10000')
    ->when('deal.currency == "USD"')
    ->then(fn (Context $ctx) => $ctx->set('requires_senior_approval', true));
```

### Context Providers

Context providers are registered services that hydrate the evaluation context from domain objects. Each provider declares a `provides()` key set and a `hydrate()` method.

## Approval Workflows

Multi-step approval chains support sequential, parallel, and conditional routing.

### Approval Chain Definition

```php
ApprovalChain::make('expense_approval')
    ->step('manager', approver: Approver::manager())
    ->step('finance', approver: Approver::role('finance'), when: 'amount > 5000')
    ->step('cfo', approver: Approver::role('cfo'), when: 'amount > 50000');
```

### Delegation

Approvers may delegate their authority for a defined period. Delegations are stored with start/end dates and an optional scope filter.

### Escalation

Escalation policies define time-based escalation when an approval step exceeds its SLA:

| Policy | Trigger | Action |
|--------|---------|--------|
| `remind` | 50% of SLA elapsed | Notify approver |
| `escalate` | 100% of SLA elapsed | Reassign to delegate or manager |
| `auto_approve` | 200% of SLA elapsed | Auto-approve with audit entry |

## AI Gateway Patterns

The AI gateway provides a provider-neutral abstraction over LLM providers.

### Provider Abstraction

```php
interface AiProviderInterface
{
    public function complete(AiRequest $request): AiResponse;
    public function embed(string $text): array;
    public function moderate(string $text): ModerationResult;
}
```

### Prompt Management

Prompts are versioned templates stored in the database with variable interpolation:

```php
Prompt::make('summarize_ticket')
    ->template('Summarize this support ticket: {{ticket.body}}')
    ->version(3)
    ->provider('anthropic');
```

### Evaluation Harness

AI outputs are evaluated against configurable criteria (accuracy, tone, safety) using a combination of rule-based checks and LLM-as-judge scoring.

## Data Processing Pipelines

Pipelines transform, validate, and enrich data as it flows through automation workflows.

### Pipeline Stages

1. **Extract** — Pull data from source connectors
2. **Validate** — Enforce schema and business rules
3. **Transform** — Map, filter, and reshape data
4. **Enrich** — Augment with external data sources
5. **Load** — Persist to destination systems

### Stage Contract

```php
interface PipelineStageInterface
{
    public function process(PipelinePayload $payload): PipelinePayload;
}
```

## Connector Architecture

Connectors integrate with external systems through a standardized interface.

### Connector Types

| Type | Purpose | Examples |
|------|---------|----------|
| **REST** | HTTP-based APIs | Salesforce, HubSpot |
| **GraphQL** | Query-based APIs | Shopify |
| **Database** | Direct DB sync | Legacy ERP |
| **Message Queue** | Async event ingestion | Kafka, SQS |
| **File** | Batch file processing | SFTP, S3 |

### Connector Lifecycle

Each connector implements `connect()`, `authenticate()`, `fetch()`, `push()`, and `disconnect()`. Credentials are encrypted at rest and resolved through a vault service.

## Event-Driven Automation

### Webhooks

Inbound webhooks are registered as route endpoints that validate signatures, deduplicate, and dispatch to the workflow engine.

### Scheduled Triggers

Cron-based triggers are defined declaratively and resolved by a scheduler service that supports timezone-aware recurrence.

### Event Listeners

Domain events published by any module can trigger automation workflows through the event bus:

```php
Event::listen(OrderPlaced::class, StartFulfillmentWorkflow::class);
```

## Module Boundaries and Extension Points

| Boundary | Extension Point | Mechanism |
|----------|----------------|-----------|
| Workflow | Custom states, transitions, guards, actions | Interface + service provider registration |
| Rules | Custom operators, context providers | Expression language extension registry |
| Approvals | Custom approver resolvers | `ApproverResolverInterface` binding |
| AI | Custom providers, prompt templates | `AiProviderInterface` binding + prompt repository |
| Pipelines | Custom stages | `PipelineStageInterface` binding |
| Connectors | Custom connector types | `ConnectorInterface` binding + connector registry |
| Events | Custom event listeners | Laravel event discovery |
