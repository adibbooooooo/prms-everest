# Spaceship X26 PRMS — Design Spec (`main`)

## Goal

Passenger Resource Management System for the Everest take-home: crew leads administer passengers and ship resources; passengers discover and use tier-allowed resources; audit and reports satisfy PDF Levels 1–3.

## Stack

- Laravel 13, Breeze (session auth), Inertia.js, Vue 3
- MySQL for local development; SQLite for `phpunit.xml` and `.env.example` reviewer path
- Pest for tests; custom audit tables (no Spatie packages)
- Git: `main` = submission; `full-simulator` branched later for UI/seed polish only

## Roles

| Role | Cap | Permissions |
|------|-----|-------------|
| `crew_lead` | Exactly 3 users system-wide | Manage passengers, tiers, resources; view ship-wide reports |
| `passenger` | Unlimited | View accessible resources, use resource, view own usage history |

Crew leads have `tier = null`. Passengers have `tier ∈ {silver, gold, platinum}`.

## Tier access

Higher tier inherits lower: `platinum ≥ gold ≥ silver`.

A passenger may use a resource when `passenger.tier >= resource.minimum_tier` (ordered enum ranks).

## Data model

### `users` (extend Breeze user)

- `role`: `crew_lead` | `passenger`
- `tier`: `silver` | `gold` | `platinum` | null

### `resources`

- `name`, `slug`, `description` (nullable)
- `minimum_tier`: `silver` | `gold` | `platinum`
- `is_active`: boolean (decommission = false)

### `resource_usages` (audit + passenger history)

- `user_id`, `resource_id`, `action` (default `used`), `created_at`

### `membership_changes`

- `user_id`, `old_tier`, `new_tier`, `changed_by` (crew user id), `created_at`

## Core services

- `App\Enums\Tier` — rank + `canAccess(Tier $required): bool`
- `App\Enums\Role`
- `App\Services\Access\ResourceAccessService` — `ensureCanUse(User $passenger, Resource $resource)`
- `App\Services\CrewLeadService` — enforce **exactly** 3 crew leads (no 4th account, no crew delete, `assertMissionReady()` before PRMS operations when roster ≠ 3)
- `App\Services\ResourceUsageService` — record use after access check
- `App\Policies\ResourcePolicy` — `use`, `manage` (crew only)

## PDF level mapping

### Level 1

- Exactly 3 crew leads: block 4th account, block crew delete, lock PRMS until roster count is 3
- Crew CRUD resources (create/deactivate)
- Crew CRUD passengers (create with tier)
- Passenger lists only accessible active resources

### Level 2

- Permission check before use
- Crew upgrade/downgrade tier + `membership_changes` row
- Every use creates `resource_usages` row

### Level 3

- Passenger: paginated own `resource_usages`
- Crew: usage aggregated by tier and by resource (top resources)

## UI (`main` — functional, not full simulator)

| Area | Pages |
|------|--------|
| Auth | Breeze login (registration disabled in production routes) |
| Passenger | Dashboard, resource list with use action, usage history |
| Crew | Dashboard, passengers, resources, activity feed, reports |

Reusable Vue: `ResourceCard.vue`, `TierBadge.vue`, layouts `PassengerLayout.vue`, `CrewLayout.vue`.

## Seeding (`main`)

- 3 crew lead users
- Sample passengers per tier
- 6–8 resources spanning silver/gold/platinum minimum tiers

## Testing strategy

TDD after scaffold: unit tests for `Tier::canAccess`, then services, then Inertia feature tests with `actingAs()`.

## Out of scope on `main`

- Spatie packages
- Supabase / external BaaS
- `full-simulator` branch work (themed UI, extra seed rows)

## Assumptions

- Single-tenant ship; no multi-ship
- Demo auth via seeded users; public registration off
- “Decommission resource” = `is_active = false`, hidden from passenger discovery
