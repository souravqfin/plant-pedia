# Plant Pedia — Development Guidelines

**Version:** 1.0
**Date:** 26 November 2026
**Applies to:** Backend (Laravel), Frontend (React), content tooling, infrastructure
**Status:** Active — every contributor must follow this document

---

## 1. Purpose

This document defines how we write, review, test, and ship Plant Pedia. It exists so that any developer can open the repository and produce work that is consistent, safe, accessible, and reviewable.

If a rule here conflicts with a client requirement, raise it in an ADR; do not silently deviate.

---

## 2. Non-negotiable rules

1. **The API is the only backend.** The frontend never touches the database.
2. **Server-side authorization is mandatory.** Hiding a button is not security.
3. **No secrets in the repository.** Ever.
4. **No hard-coded user-facing copy.** Bengali and English live in locale files.
5. **No untested business rule.** Every domain action has a test.
6. **No direct push to `main`.** All work goes through a pull request.
7. **No merge with a red CI pipeline.**
8. **No `any` in TypeScript** without a written reason and a `// eslint-disable` comment.
9. **No raw SQL with user input.** Use Eloquent or bound parameters.
10. **No feature is done until it works in Bengali, English, keyboard-only, and on a 375 px screen.**

---

## 3. Repository layout

```text
plant-pedia/
├── backend/              Laravel 13.x (latest stable) API
├── frontend/             React 19 app (public + admin)
├── contracts/            OpenAPI schema, generated TS types, design tokens
├── infra/                Docker, Nginx, deployment scripts
├── docs/                 ADRs, SRS, ERD, runbooks
├── .github/              CI workflows, PR templates
├── docker-compose.yml
└── README.md
```

Rules:

- Backend and frontend are independently buildable.
- `contracts/` is generated where possible; never hand-edit generated files.
- `docs/` is the source of truth for decisions. If code and docs disagree, fix one immediately.

---

## 4. Environment setup

### Requirements

| Tool | Version |
|---|---|
| PHP | 8.4+ (8.5 acceptable) |
| Composer | 2.x |
| Node.js | 22 LTS |
| npm | 10+ |
| Docker | 27+ |
| Docker Compose | v2 |
| Git | 2.40+ |

### First run

```bash
cp backend/.env.example backend/.env
docker compose up -d
docker compose exec api composer install
docker compose exec api php artisan key:generate
docker compose exec api php artisan migrate --seed
docker compose exec web npm install
docker compose exec web npm run dev
```

### Daily commands

```bash
# backend
docker compose exec api php artisan test
docker compose exec api ./vendor/bin/pint
docker compose exec api ./vendor/bin/phpstan analyse

# frontend
docker compose exec web npm run lint
docker compose exec web npm run typecheck
docker compose exec web npm run test
docker compose exec web npm run build
```

Every developer runs the full check suite before opening a PR.

---

## 5. Git workflow

### Branching

Trunk-based development with short-lived branches.

```text
main
├── feat/plant-detail-page
├── fix/refresh-token-rotation
├── chore/update-dependencies
└── docs/api-contract
```

- Branch from `main`, merge back within **3 days**.
- Rebase on `main` before requesting review; do not merge `main` into a feature branch.
- Delete the branch after merge.

### Commit messages — Conventional Commits

```text
<type>(<scope>): <subject>

<body>

<footer>
```

Allowed types: `feat`, `fix`, `refactor`, `test`, `docs`, `chore`, `perf`, `ci`, `build`, `revert`.

Examples:

```text
feat(plants): add Bengali name search
fix(auth): revoke refresh token family on reuse
docs(adr): record search engine decision
```

Rules:

- Subject in imperative mood, ≤ 72 characters, no trailing period.
- One logical change per commit.
- Reference the task ID in the footer: `Refs: P4-BE-20`.

### Pull requests

- Title follows the commit convention.
- Description includes: what, why, how to test, screenshots (UI), and the task ID.
- **≤ 400 changed lines** excluding generated files and lockfiles. Split larger work.
- One approval required; two for auth, permissions, payments, or migrations.
- CI must be green.
- Squash merge only.

### PR checklist

```markdown
- [ ] Task ID linked
- [ ] Acceptance criteria met
- [ ] Tests added/updated
- [ ] bn/en strings added
- [ ] Loading/empty/error states handled
- [ ] Keyboard + screen reader checked
- [ ] Responsive at 375/768/1280
- [ ] No new security warnings
- [ ] Docs/ADR updated if a decision changed
```

---

## 6. Code review

### Reviewer responsibilities

- Correctness and edge cases.
- Security: authorization, validation, injection, secrets.
- Performance: N+1 queries, unbounded lists, heavy re-renders.
- Accessibility and i18n.
- Naming and consistency with this guide.

### Author responsibilities

- Keep PRs small and self-explanatory.
- Respond to every comment (fix or explain).
- Do not resolve a comment you did not address.

### Review SLA

- First response within **1 business day**.
- Blocking comments prefixed `blocking:`; non-blocking `nit:`.
- If a review stalls, escalate to the tech lead.

---

## 7. Backend standards (Laravel)

### 7.1 Module structure

```text
app/Modules/Plants/
├── Actions/          one use case per class
├── Data/             DTOs
├── Models/
├── Policies/
├── Queries/          reusable query objects
├── Services/         cross-entity domain logic
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Jobs/
├── Events/
└── Tests/
```

Rules:

- A module owns its models, actions, policies, requests, resources, jobs, and tests.
- Cross-module calls go through a service or action, not direct model queries.
- Do not create a `Helpers.php` dumping ground.

### 7.2 Controllers

Thin. Validate → authorize → call action → return resource.

```php
public function store(StorePlantRequest $request, CreatePlant $action): PlantResource
{
    $this->authorize('create', Plant::class);

    return new PlantResource($action->handle($request->validated()));
}
```

- No business logic in controllers.
- No queries in controllers.
- One resource per response.

### 7.3 Actions and services

- An action is a single use case with an `handle()` method.
- Actions are transaction-safe where they write multiple tables.
- Services hold logic shared by several actions.
- Return domain objects or DTOs, not arrays.

```php
final class CreatePlant
{
    public function __construct(private readonly PlantRepository $plants) {}

    public function handle(array $data): Plant
    {
        return DB::transaction(fn () => $this->plants->create($data));
    }
}
```

### 7.4 Validation

- Always use Form Requests.
- Authorization in `authorize()`, validation in `rules()`.
- Use custom rules for domain constraints (valid taxon rank, valid locale).
- Never trust `$request->all()`.

### 7.5 API resources

- Every endpoint returns an API Resource or ResourceCollection.
- Never return Eloquent models directly.
- Use `whenLoaded()` for relationships.
- Keep field names stable; changing them is a breaking API change.

### 7.6 Models

- `$fillable` or `$guarded` explicitly set.
- Casts for dates, enums, booleans, JSON.
- Enums for status, rank, verification state, establishment means.
- Scopes for reusable filters.
- No queries in accessors.

### 7.7 Database

- Table names: plural snake_case (`plant_distributions`).
- Columns: snake_case (`scientific_name`).
- Foreign keys: `{singular}_id` (`taxon_id`).
- Pivot tables: alphabetical singular (`plant_category`).
- Every foreign key has an index.
- Use `restrictOnDelete` by default; `cascadeOnDelete` only for owned children.
- Soft deletes only where recovery matters (plants, pages, users); not for logs.
- Migrations are reversible and never destructive in the same release as the code that depends on them.

### 7.8 Queues and jobs

- Jobs are idempotent.
- Set `$tries`, `$backoff`, and `$timeout`.
- Use `afterCommit()` for side effects.
- Name jobs as verbs: `IndexPlant`, `SendSubmissionEmail`.
- Long jobs report progress; no unbounded loops.

### 7.9 Errors and logging

- Throw domain exceptions; map them to API errors in one handler.
- Log with context: `request_id`, `user_id`, entity ID.
- Never log secrets, tokens, passwords, or full request bodies with PII.
- Use structured logs (JSON) in production.

### 7.10 Configuration

- `config()` only; never `env()` outside config files.
- Every new env var is added to `.env.example` with a comment.
- Feature flags via Pennant, not `if (env(...))`.

---

## 8. Frontend standards (React)

### 8.1 Components

- Function components only.
- One component per file; file name matches the component (`PlantCard.tsx`).
- Named exports; default export only for route modules.
- Props typed with an explicit `interface` or `type`.
- No business logic in JSX; extract hooks or helpers.
- Components ≤ 200 lines; split when larger.

```tsx
interface PlantCardProps {
  plant: PlantSummary;
  onFavorite?: (id: string) => void;
}

export function PlantCard({ plant, onFavorite }: PlantCardProps) {
  // ...
}
```

### 8.2 Hooks

- Prefix with `use`.
- One responsibility per hook.
- Return stable objects; memoize callbacks passed to children.
- No hooks inside conditions or loops.
- Server data belongs to TanStack Query, not `useState`.

### 8.3 State

| State type | Tool |
|---|---|
| Server data | TanStack Query |
| Form state | React Hook Form |
| URL state (filters, pagination) | Router search params |
| Local UI state | `useState` |
| Cross-page UI state | Zustand (small, explicit) |

Never copy server data into local state.

### 8.4 Data fetching

- All requests go through `lib/api`.
- Query keys are centralized and typed.
- Mutations invalidate by prefix.
- Optimistic updates only for favorites and reactions.
- Every query has loading, error, and empty handling.

### 8.5 Forms

- React Hook Form + Zod.
- Schema shared with `contracts/` where possible.
- Server errors mapped by field name and error code.
- Disable submit while pending; show a spinner and prevent double submit.
- Unsaved-changes guard on editors.

### 8.6 Styling

- Tailwind utilities + design tokens.
- No hard-coded hex values; use CSS variables.
- Variants via CVA.
- Class merging via `cn()`.
- No inline `style` except dynamic values (map coordinates, progress width).
- Mobile-first; test at 375, 768, 1280, 1440.

### 8.7 Accessibility

- Semantic HTML first; ARIA only when necessary.
- Every input has a label.
- Every icon-only button has an `aria-label`.
- Focus is visible and trapped in modals.
- Errors announced via `aria-live`.
- Color is never the only signal.
- Respect `prefers-reduced-motion`.

### 8.8 Internationalization

- All copy through `t('namespace.key')`.
- No string concatenation for sentences; use interpolation.
- Dates, numbers, and currency via `Intl`.
- Scientific names are never translated.
- Add keys to both `bn` and `en` in the same PR.
- Missing keys fail CI in development.

### 8.9 SEO

- Every public route exports `meta` and `links`.
- Canonical URL on every public page.
- One `h1` per page.
- Structured data via the `JsonLd` component.
- Images have meaningful `alt` text.
- Admin and account routes are `noindex`.

### 8.10 Performance

- Route-level code splitting.
- Images use `srcset`, `loading="lazy"` except LCP, and explicit dimensions.
- No large libraries in the public bundle without an ADR.
- Bundle-size CI fails above the budget.
- Avoid waterfalls: prefetch on hover for plant links.

---

## 9. API standards

- Base paths: `/api/v1` (public) and `/api/admin/v1` (admin).
- Success envelope: `{ "data": ..., "meta": ... }`.
- Error envelope: `{ "errors": [{ "code", "message", "field" }], "meta": { "request_id" } }`.
- Stable machine-readable error codes; localized human messages.
- Pagination: page-based for admin, cursor for feeds.
- Filtering: `filter[family]=...`; sorting: `sort=-created_at`.
- Sparse fields: `fields=id,slug,name`.
- `Idempotency-Key` on POST where retries are possible.
- `ETag` + `If-Match` on admin updates.
- Versioning: URL; deprecation window 6 months.
- OpenAPI is generated and committed; contract tests run in CI.

---

## 10. Security standards

- Validate and authorize every request.
- Policies for every write and sensitive read.
- Argon2id for passwords; minimum 12 characters; breach check.
- JWT: short-lived access, rotating refresh, reuse detection, key rotation.
- CSRF double-submit for cookie-based endpoints.
- CSP with nonce; no `unsafe-inline` in production.
- Sanitize rich text server-side (HTMLPurifier).
- Uploads: MIME sniff, extension allowlist, size limit, re-encode, EXIF strip, virus scan.
- SSRF: allowlist outbound hosts; block private IPs.
- Rate limit auth, search, uploads, comments, AI.
- Secrets in environment/secret manager; rotate quarterly.
- Dependency audit and container scan in CI.
- Audit every admin write and permission change.

---

## 11. Testing standards

| Layer | Tool | Rule |
|---|---|---|
| Backend unit | Pest | Every action/service has a test |
| Backend feature | Pest | Every endpoint: happy path, validation, auth, forbidden |
| Contract | OpenAPI schema | Every endpoint validated |
| Frontend unit | Vitest + RTL | Components and hooks |
| Frontend integration | MSW | Feature flows |
| E2E | Playwright | Critical journeys |
| Accessibility | axe + manual | Every page |
| Performance | Lighthouse CI | Budgets |
| Security | ZAP + audits | Staging |

Rules:

- Bug fixes start with a failing test.
- No merge with failing or skipped tests without a linked issue.
- Flaky tests are fixed or quarantined within 48 hours.
- Test names describe behaviour: `it('rejects a duplicate scientific name')`.

---

## 12. Documentation standards

- **ADR** for every architectural decision: context, decision, consequences, alternatives.
- **OpenAPI** is the API contract; keep it current.
- **Storybook** documents every shared component.
- **Runbooks** for deploy, rollback, restore, reindex, incident.
- **README** in each top-level folder: purpose, setup, commands.
- Comments explain *why*, not *what*.

---

## 13. Definition of done

A task is done only when:

- [ ] Acceptance criteria met and demonstrated in staging.
- [ ] Tests written and passing.
- [ ] bn/en strings complete.
- [ ] Loading, empty, error, and denied states handled.
- [ ] Keyboard and screen-reader check passed.
- [ ] Responsive at 375 / 768 / 1280 / 1440.
- [ ] Performance budget respected.
- [ ] Authorization enforced server-side.
- [ ] Audit log entry for writes.
- [ ] Docs/ADR updated.
- [ ] Peer reviewed and approved.
- [ ] Product owner accepted.

---

## 14. Release and deployment

- `main` is always deployable.
- Every merge deploys to staging automatically.
- Production deploys are manual approvals from `main`.
- Migrations are backward-compatible: expand → deploy → backfill → contract.
- Rollback is redeploying the previous image; never roll back a migration destructively.
- Release notes list user-visible changes and known issues.
- Post-deploy smoke test: home, plant detail, search, login, admin login.

---

## 15. Incident response

1. Acknowledge the alert.
2. Assign an incident lead.
3. Mitigate first (rollback, feature flag off, scale up).
4. Communicate status every 30 minutes.
5. Write a blameless postmortem within 5 business days.
6. Add a regression test and a prevention task.

Severity:

| Level | Meaning | Response |
|---|---|---|
| SEV-1 | Site down or data loss | Immediate, all hands |
| SEV-2 | Major feature broken | Within 1 hour |
| SEV-3 | Minor feature degraded | Within 1 business day |
| SEV-4 | Cosmetic | Next sprint |

---

## 16. Collaboration

- Ask early; do not sit on a blocker for more than half a day.
- Disagree in the PR, not in DMs; record the outcome in an ADR.
- Prefer clarity over cleverness.
- Leave the codebase better than you found it.
- Assume good intent; critique the code, not the person.

---

*End of guidelines.*
