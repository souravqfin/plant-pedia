# Plant Pedia

A Bangladesh-first, bilingual (বাংলা / English) botanical encyclopedia and community platform.

**Status:** Phase 0 — Discovery & Plan Hardening
**Stack:** Laravel 13.x (latest stable) · React 19 · TypeScript · MySQL 8.4 · Redis · Meilisearch/OpenSearch · Tailwind CSS 4

---

## Documentation

| Document | Purpose |
|---|---|
| [`plant_pedia_complete_project_plan.md`](plant_pedia_complete_project_plan.md) | Product vision and full requirements |
| [`plant_pedia_ui_tech_plan.md`](plant_pedia_ui_tech_plan.md) | UI design system, tech stack, architecture, delivery |
| [`plant_pedia_task_phases.md`](plant_pedia_task_phases.md) | Phase and task breakdown |
| [`DEVELOPMENT_GUIDELINES.md`](DEVELOPMENT_GUIDELINES.md) | Coding, review, testing, and release rules |
| [`docs/PROGRESS.md`](docs/PROGRESS.md) | Current status and next actions |
| [`docs/phase-0/01-srs.md`](docs/phase-0/01-srs.md) | Software Requirements Specification |
| [`docs/phase-0/04-erd-v1.md`](docs/phase-0/04-erd-v1.md) | Data model |
| [`docs/phase-0/05-api-contract-v1.md`](docs/phase-0/05-api-contract-v1.md) | API contract |
| [`docs/phase-0/07-adrs.md`](docs/phase-0/07-adrs.md) | Architecture decisions |
| [`docs/phase-0/08-threat-model.md`](docs/phase-0/08-threat-model.md) | STRIDE threat model |
| [`docs/phase-0/09-test-strategy.md`](docs/phase-0/09-test-strategy.md) | Test strategy |
| [`docs/phase-0/10-uat-plan.md`](docs/phase-0/10-uat-plan.md) | UAT scenarios |
| [`docs/phase-0/11-content-plan.md`](docs/phase-0/11-content-plan.md) | Content production |
| [`docs/phase-0/12-risk-register.md`](docs/phase-0/12-risk-register.md) | Risks |
| [`docs/phase-0/13-estimation-budget.md`](docs/phase-0/13-estimation-budget.md) | Estimates and budget |
| [`docs/phase-0/14-stack-compatibility.md`](docs/phase-0/14-stack-compatibility.md) | Version matrix |
| [`docs/phase-0/15-licence-audit.md`](docs/phase-0/15-licence-audit.md) | Licence audit |

---

## Repository structure

```text
plant-pedia/
├── backend/              Laravel 13.x (latest stable) API
├── frontend/             React 19 app (public + admin)
├── contracts/            OpenAPI schema, TS types, design tokens
├── infra/                Docker, Nginx, deploy scripts
├── docs/                 SRS, ERD, ADRs, runbooks, progress
├── docker-compose.yml    Local and production parity
└── README.md
```

---

## Getting started

The backend is scaffolded with **Laravel 13.44.1**. The frontend lands in Phase 1.

```bash
cp .env.example .env
cp backend/.env.example backend/.env
docker compose up -d --build
docker compose exec api composer install
docker compose exec api php artisan key:generate
docker compose exec api php artisan migrate --seed
```

Services:

| Service | URL |
|---|---|
| App (via Nginx) | http://localhost:8080 |
| Frontend dev server | http://localhost:5173 |
| Mailpit | http://localhost:8025 |
| Meilisearch | http://localhost:7700 |
| imgproxy | http://localhost:8081 |

---

## Conventions

- Branch from `main`; PRs ≤ 400 lines; squash merge.
- Conventional Commits with task IDs.
- Every endpoint has a feature test; every component has a Storybook story.
- Bengali and English strings ship together.
- No secrets in the repository.

See [`DEVELOPMENT_GUIDELINES.md`](DEVELOPMENT_GUIDELINES.md).

---

## Current blockers

- Client decision: Bootstrap vs Tailwind.
- Brand assets.
- Content ownership and budget.
- SMS gateway account.
- Hosting region.
- Legal documents.
- Launch date and budget.
- Expert reviewers.

---

## Next actions

1. Bengali search spike.
2. SSR/admin PoC.
3. JWT PoC.
4. 10 wireframes.
5. Client answers to the blocking questions.

---

*Private and proprietary. All rights reserved.*
# plant-pedia
