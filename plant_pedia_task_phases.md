# Plant Pedia — Phase & Task Breakdown

**Version:** 1.0
**Date:** 26 November 2026
**Companion to:** `plant_pedia_complete_project_plan.md`, `plant_pedia_ui_tech_plan.md`
**Purpose:** Convert the plan into an executable, trackable task list.

---

## 0. How to use this document

### Task ID format

```text
P<phase>-<workstream>-<number>

P0-PM-01   Phase 0, Product/PM, task 01
P3-UI-14   Phase 3, UI/UX, task 14
P4-BE-22   Phase 4, Backend, task 22
P6-FE-05   Phase 6, Frontend, task 05
```

### Workstream codes

| Code | Workstream |
|---|---|
| `PM` | Product / project management |
| `TL` | Tech lead / architecture |
| `UX` | UI/UX design |
| `BE` | Backend |
| `FE` | Frontend |
| `CT` | Content / editorial |
| `QA` | Quality assurance |
| `DO` | DevOps / infrastructure |

### Status legend

| Symbol | Meaning |
|---|---|
| `[ ]` | Not started |
| `[~]` | In progress |
| `[x]` | Done |
| `[!]` | Blocked |
| `[-]` | Cut / deferred |

### Rules

1. One task = **≤ 3 days** of work for one owner.
2. Every task has a **deliverable** — a PR, a Figma frame, a document, or a test.
3. Dependencies are written as `dep: P1-BE-03`.
4. A phase is not complete until its **exit gate** is signed.
5. Update status weekly; link the PR or Figma file in the tracker.

---

## 1. Phase overview

| Phase | Duration | Theme | Exit gate |
|---|---|---|---|
| **0** | 2 weeks | Discovery & plan hardening | SRS, ERD, search decision, budget signed |
| **1** | 3 weeks | Foundation | Login + admin shell running on staging |
| **2** | 3 weeks | Design system & UI kit | Storybook v1 + tokens approved |
| **3** | 6 weeks | **All page design** | Every screen at hi-fi + handoff |
| **4** | 6 weeks | **Backend: botanical core** | 300 plants importable + API contract |
| **5** | 4 weeks | **Backend: CMS & page builder** | Editor publishes a page without code |
| **6** | 5 weeks | **Frontend: public website** | Lighthouse/SEO/a11y pass |
| **7** | 4 weeks | Community & moderation | Moderation E2E pass |
| **8** | 4 weeks | AI assist | Human approval flow + eval pass |
| **9** | 3 weeks | Hardening & launch | Launch checklist signed |
| **10** | ongoing | Post-launch | 30-day review |

> Phases overlap. Durations are effort windows, not a strictly sequential calendar. Total elapsed time ≈ 34 weeks with the recommended team.

### Quick mapping to your categories

| You asked for | Phase |
|---|---|
| UI design (system, components) | **Phase 2** |
| All page design | **Phase 3** |
| Backend implemented | **Phases 1, 4, 5** |
| Frontend implemented | **Phases 1, 6** |
| Community | **Phase 7** |
| AI | **Phase 8** |
| Launch | **Phase 9** |

---

## 2. Timeline (parallel tracks)

```text
Week:      1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34
P0        ██
P1           ██████
P2           ██████
P3              ████████████
P4                 ████████████
P5                          ████████
P6                             ██████████
P7                                   ████████
P8                                      ████████
P9                                            ██████
Content   ████████████████████████████████████████████████████████████████████████████████████████████
```

---

# Phase 0 — Discovery & Plan Hardening (2 weeks)

**Goal:** remove every unowned assumption before writing product code.

## 0.1 Product & requirements — `PM`

- [ ] `P0-PM-01` Stakeholder kickoff; confirm goals, KPIs, launch constraints — 0.5d
- [ ] `P0-PM-02` Write SRS with requirement IDs (`FR-*`, `NFR-*`) — 2d
- [ ] `P0-PM-03` Build requirement → screen → API → test traceability matrix — 1d
- [ ] `P0-PM-04` Freeze MVP scope in a signed one-page statement — 0.5d
- [ ] `P0-PM-05` Write change-control process — 0.5d
- [ ] `P0-PM-06` Get written answers to the 15 open questions — 1d
- [ ] `P0-PM-07` Role/permission matrix — 1d
- [ ] `P0-PM-08` ERD v1 + table/relationship specification — 3d
- [ ] `P0-PM-09` API contract v1 (endpoint outline, envelope, errors) — 2d
- [ ] `P0-PM-10` UAT plan + acceptance criteria template — 1d

## 0.2 Technical spikes — `TL`

- [ ] `P0-TL-01` Build Bengali search corpus (2,000 names) + 200 queries — 2d
- [ ] `P0-TL-02` Run Meilisearch vs OpenSearch vs Typesense spike; write decision report — 2d
- [ ] `P0-TL-03` SSR/admin PoC in React Router 7; confirm per-route rendering — 2d
- [ ] `P0-TL-04` JWT access/refresh/rotation/CSRF PoC — 2d
- [ ] `P0-TL-05` Upload → S3/R2 → imgproxy → CDN PoC — 1d
- [ ] `P0-TL-06` i18n + Bengali font metrics PoC — 1d
- [ ] `P0-TL-07` Load-test baseline on plant detail page — 1d
- [ ] `P0-TL-08` Version compatibility matrix — 1d
- [ ] `P0-TL-09` Dependency licence audit — 1d
- [ ] `P0-TL-10` Write ADRs for D-01 … D-20 — 2d
- [ ] `P0-TL-11` Threat-model workshop (STRIDE) + document — 2d
- [ ] `P0-TL-12` Infrastructure + cost plan — 1d

## 0.3 Design discovery — `UX`

- [ ] `P0-UX-01` Brand workshop + moodboard — 1d
- [ ] `P0-UX-02` Sitemap + user flows — 2d
- [ ] `P0-UX-03` Design brief (principles, tone, references) — 1d
- [ ] `P0-UX-04` Wireframes for 10 key screens — 3d
- [ ] `P0-UX-05` Hi-fi for 5 critical screens — 3d
- [ ] `P0-UX-06` Clickable prototype — 1d
- [ ] `P0-UX-07` Usability test with 5 users (≥2 Bengali-first) — 2d
- [ ] `P0-UX-08` Findings report + design fixes — 1d

## 0.4 Content & data — `CT`

- [ ] `P0-CT-01` Content production plan (300–500 profiles) — 2d
- [ ] `P0-CT-02` Content cost model (per-profile effort, translation, images) — 1d
- [ ] `P0-CT-03` Source and licence register — 1d
- [ ] `P0-CT-04` 50-profile pilot batch — 5d
- [ ] `P0-CT-05` Bengali botanical glossary v1 — 2d
- [ ] `P0-CT-06` Translation workflow definition — 1d

## 0.5 Delivery & risk — `PM`

- [ ] `P0-PM-11` WBS: every phase into ≤3-day tasks — 2d
- [ ] `P0-PM-12` Three-point estimates with the delivery team — 2d
- [ ] `P0-PM-13` Resource-loaded schedule + 25% buffer — 2d
- [ ] `P0-PM-14` Budget workbook + 3-year TCO — 2d
- [ ] `P0-PM-15` Risk register v2 (probability × cost × schedule) — 1d
- [ ] `P0-PM-16` Agree the cut list (10 features to drop first) — 0.5d
- [ ] `P0-PM-17` Phase 0 sign-off pack — 1d

**Exit gate:** SRS, ERD, API contract, search decision, threat model, budget, and risk register signed by the client.

---

# Phase 1 — Foundation (3 weeks)

**Goal:** a deployable skeleton with auth, roles, shells, and CI.

## 1.1 Repository & DevOps — `DO`

- [x] `P1-DO-01` Monorepo structure (`backend/`, `frontend/`, `contracts/`, `infra/`, `docs/`) — 0.5d
- [x] `P1-DO-02` Docker Compose: nginx, web, api, horizon, scheduler, mysql, redis, meilisearch, imgproxy, mailpit — 2d
- [~] `P1-DO-03` Local development guide — 1d
- [ ] `P1-DO-04` GitHub Actions CI: lint, static analysis, tests, build — 2d
- [ ] `P1-DO-05` Staging environment — 2d
- [ ] `P1-DO-06` Production environment + TLS + Cloudflare — 2d
- [ ] `P1-DO-07` Secrets management — 1d
- [ ] `P1-DO-08` Backups + restore drill — 1d
- [ ] `P1-DO-09` Sentry + uptime monitoring — 1d
- [ ] `P1-DO-10` Deploy and rollback runbook — 1d

## 1.2 Backend foundation — `BE`

- [x] `P1-BE-01` Laravel 13.x (latest stable) skeleton + module structure — 1d
- [ ] `P1-BE-02` Base migrations: users, roles, permissions, sessions, audit — 2d
- [ ] `P1-BE-03` JWT login / refresh / logout — 3d
- [ ] `P1-BE-04` Refresh rotation + reuse detection — 2d
- [ ] `P1-BE-05` MFA TOTP + recovery codes — 2d
- [ ] `P1-BE-06` Password policy, reset, email verification — 2d
- [ ] `P1-BE-07` RBAC + policies — 2d
- [ ] `P1-BE-08` API envelope, error codes, request ID — 1d
- [ ] `P1-BE-09` Rate limiting — 1d
- [ ] `P1-BE-10` OpenAPI (Scramble) setup — 1d
- [ ] `P1-BE-11` Feature flags (Pennant) — 0.5d
- [ ] `P1-BE-12` Audit log base — 1d
- [ ] `P1-BE-13` Email/SMS providers + bn/en templates — 2d
- [ ] `P1-BE-14` Health check endpoints — 0.5d
- [ ] `P1-BE-15` Testing foundation: Pest, factories, seeders — 2d

## 1.3 Frontend foundation — `FE`

- [ ] `P1-FE-01` React Router 7 app + SSR entry — 2d
- [ ] `P1-FE-02` Tailwind + token pipeline — 2d
- [ ] `P1-FE-03` API client + generated types from OpenAPI — 2d
- [ ] `P1-FE-04` Auth store + refresh interceptor — 2d
- [ ] `P1-FE-05` Route guards: public / user / admin — 1d
- [ ] `P1-FE-06` Public shell: header, footer, language switcher — 2d
- [ ] `P1-FE-07` Admin shell: sidebar, topbar, breadcrumbs — 2d
- [ ] `P1-FE-08` i18n setup + locale routing — 2d
- [ ] `P1-FE-09` Error boundaries + 404/500 — 1d
- [ ] `P1-FE-10` Storybook setup — 1d
- [ ] `P1-FE-11` ESLint, Prettier, Vitest setup — 1d
- [ ] `P1-FE-12` SEO head component + JSON-LD helpers — 1d

## 1.4 QA foundation — `QA`

- [ ] `P1-QA-01` Test strategy document — 1d
- [ ] `P1-QA-02` CI test gates — 1d
- [ ] `P1-QA-03` Playwright setup + first smoke test — 1d
- [ ] `P1-QA-04` axe accessibility setup — 0.5d

**Exit gate:** login, refresh, logout, RBAC, and admin shell work on staging; CI blocks broken merges.

---

# Phase 2 — Design System & UI Kit (3 weeks)

**Goal:** a token-driven component library that both public and admin use.

## 2.1 Foundations — `UX` + `FE`

- [ ] `P2-DS-01` Figma variables: color, type, spacing, radius, shadow — 2d
- [ ] `P2-DS-02` Export `design-tokens.json` to `contracts/` — 0.5d
- [ ] `P2-DS-03` Tailwind theme generated from tokens — 1d
- [ ] `P2-DS-04` Bengali/English typography specimens — 1d
- [ ] `P2-DS-05` Icon set + usage rules (Lucide) — 0.5d
- [ ] `P2-DS-06` Motion tokens — 0.5d
- [ ] `P2-DS-07` Dark-mode tokens (admin, phase 2) — 1d

## 2.2 P0 components — `FE`

- [ ] `P2-DS-08` Button, IconButton — 1d
- [ ] `P2-DS-09` Input, Textarea, Select, Combobox — 2d
- [ ] `P2-DS-10` Checkbox, Radio, Switch, FormField — 2d
- [ ] `P2-DS-11` Badge, Tag, Card, PlantCard — 2d
- [ ] `P2-DS-12` Modal, Drawer, Dropdown, Tooltip, Popover — 3d
- [ ] `P2-DS-13` Tabs, Accordion, Breadcrumb, Pagination — 2d
- [ ] `P2-DS-14` Table, DataTable (sort, filter, select, sticky) — 3d
- [ ] `P2-DS-15` Toast, Alert, Skeleton, EmptyState — 2d
- [ ] `P2-DS-16` SearchBar, LanguageSwitcher, VerificationBadge, SourceCitation, IUCNBadge — 2d

## 2.3 P1 components — `FE`

- [ ] `P2-DS-17` Gallery, Lightbox, FileUpload, DatePicker — 3d
- [ ] `P2-DS-18` CommentThread, ReactionBar, FavoriteButton, ShareButtons — 2d
- [ ] `P2-DS-19` Stat, Chart, Map wrapper — 2d
- [ ] `P2-DS-20` CommandPalette — 1d

## 2.4 Layouts — `FE`

- [ ] `P2-DS-21` Public header/footer responsive — 2d
- [ ] `P2-DS-22` Admin sidebar/topbar responsive — 2d
- [ ] `P2-DS-23` Account layout — 1d
- [ ] `P2-DS-24` Page templates: list, detail, form, dashboard — 2d

## 2.5 Documentation & QA — `UX` + `QA`

- [ ] `P2-DS-25` Storybook stories for all P0 components — 2d
- [ ] `P2-DS-26` axe checks on every story — 1d
- [ ] `P2-DS-27` Component usage documentation — 1d
- [ ] `P2-DS-28` Design QA checklist — 0.5d
- [ ] `P2-DS-29` Responsive specs at 375/768/1280/1440 — 1d

**Exit gate:** Storybook v1 deployed; tokens, components, and layouts approved by design and engineering.

---

# Phase 3 — UI Design: All Pages (6 weeks)

**Goal:** every public, account, and admin screen designed at hi-fi, with states and responsive variants.

## 3.1 Public pages — `UX`

- [ ] `P3-UI-01` Home — wireframe — 1d
- [ ] `P3-UI-02` Home — hi-fi desktop — 2d
- [ ] `P3-UI-03` Home — hi-fi mobile — 1d
- [ ] `P3-UI-04` Plant directory — wireframe — 1d
- [ ] `P3-UI-05` Plant directory — hi-fi, filters, list/grid — 2d
- [ ] `P3-UI-06` Plant detail — wireframe — 1d
- [ ] `P3-UI-07` Plant detail — hi-fi desktop — 3d
- [ ] `P3-UI-08` Plant detail — hi-fi mobile + sticky actions — 1d
- [ ] `P3-UI-09` Search — wireframe — 1d
- [ ] `P3-UI-10` Search — hi-fi, facets, suggestions, no-results — 2d
- [ ] `P3-UI-11` Categories index + detail — 2d
- [ ] `P3-UI-12` Taxonomy browser + family page — 2d
- [ ] `P3-UI-13` Articles index + detail — 2d
- [ ] `P3-UI-14` About, contributors, experts, sources, contact — 2d
- [ ] `P3-UI-15` Privacy, terms, accessibility statement — 1d
- [ ] `P3-UI-16` 404, 500, offline — 1d
- [ ] `P3-UI-17` Community hub — 2d
- [ ] `P3-UI-18` Identify (later) — 2d
- [ ] `P3-UI-19` Plant map (later) — 2d
- [ ] `P3-UI-20` Seasonal calendar (later) — 2d
- [ ] `P3-UI-21` Nurseries (later) — 2d

## 3.2 Auth & account — `UX`

- [ ] `P3-UI-22` Login + register — 2d
- [ ] `P3-UI-23` Phone OTP verification — 1d
- [ ] `P3-UI-24` Forgot / reset password — 1d
- [ ] `P3-UI-25` Email verification — 0.5d
- [ ] `P3-UI-26` Account profile — 1d
- [ ] `P3-UI-27` Favorites + collections — 2d
- [ ] `P3-UI-28` My submissions — 2d
- [ ] `P3-UI-29` Settings: language, security, devices — 2d

## 3.3 Admin pages — `UX`

- [ ] `P3-UI-30` Dashboard — 2d
- [ ] `P3-UI-31` Plants list + saved views + bulk actions — 2d
- [ ] `P3-UI-32` Plant editor (tabs + meta panel) — 3d
- [ ] `P3-UI-33` Taxonomy manager (tree, synonyms) — 2d
- [ ] `P3-UI-34` Categories & attributes — 1d
- [ ] `P3-UI-35` Countries & regions — 1d
- [ ] `P3-UI-36` Languages & translations (completeness matrix) — 2d
- [ ] `P3-UI-37` Media library — 2d
- [ ] `P3-UI-38` Sources & references — 1d
- [ ] `P3-UI-39` Submissions queue — 1d
- [ ] `P3-UI-40` Moderation queue — 1d
- [ ] `P3-UI-41` Users & roles — 1d
- [ ] `P3-UI-42` CMS pages list — 2d
- [ ] `P3-UI-43` Page builder (palette, canvas, settings) — 3d
- [ ] `P3-UI-44` Menus — 1d
- [ ] `P3-UI-45` SEO & redirects — 1d
- [ ] `P3-UI-46` AI research + review queue — 2d
- [ ] `P3-UI-47` Settings & audit log — 1d

## 3.4 States, responsive, accessibility — `UX`

- [ ] `P3-UI-48` Loading / empty / error / denied states — public — 2d
- [ ] `P3-UI-49` Loading / empty / error / denied states — admin — 2d
- [ ] `P3-UI-50` Responsive variants: 375 / 768 / 1280 / 1440 — 2d
- [ ] `P3-UI-51` Accessibility annotations (focus, labels, contrast) — 2d
- [ ] `P3-UI-52` Keyboard and focus specifications — 1d
- [ ] `P3-UI-53` Bengali layout and line-height review — 1d

## 3.5 Content matrix — `UX` + `CT`

- [ ] `P3-UI-54` bn/en copy for all public screens — 2d
- [ ] `P3-UI-55` bn/en copy for all admin screens — 2d
- [ ] `P3-UI-56` Error, validation, and empty-state copy — 1d
- [ ] `P3-UI-57` Design handoff pack (Figma links, specs, tokens) — 1d

**Exit gate:** every screen has wireframe → hi-fi → states → responsive → a11y annotation → bn/en copy; handoff pack delivered.

---

# Phase 4 — Backend: Botanical Core (6 weeks)

**Goal:** the botanical data model, media, evidence, and search — with a stable API.

## 4.1 Taxonomy — `BE`

- [ ] `P4-BE-01` `taxonomic_ranks` table — 0.5d
- [ ] `P4-BE-02` `taxa` table + tree structure — 2d
- [ ] `P4-BE-03` `taxon_names` (accepted, basionym, synonyms) — 2d
- [ ] `P4-BE-04` Author citation, year, nomenclatural status — 1d
- [ ] `P4-BE-05` `taxon_identifiers` (IPNI, POWO, GBIF, WFO) — 1d
- [ ] `P4-BE-06` `taxon_relationships` — 1d
- [ ] `P4-BE-07` `taxon_change_logs` — 1d
- [ ] `P4-BE-08` Taxonomy CRUD API — 2d
- [ ] `P4-BE-09` Tree endpoint + cycle guard — 1d
- [ ] `P4-BE-10` Synonym manager API — 1d
- [ ] `P4-BE-11` Taxonomy tests — 1d

## 4.2 Plants — `BE`

- [ ] `P4-BE-12` `plants` + `plant_profiles` schema — 2d
- [ ] `P4-BE-13` `plant_translations` — 1d
- [ ] `P4-BE-14` `plant_names` + `plant_local_names` — 1d
- [ ] `P4-BE-15` Categories + `plant_category_map` — 1d
- [ ] `P4-BE-16` Attributes + characteristics — 2d
- [ ] `P4-BE-17` Uses, care, propagation, seasonality — 2d
- [ ] `P4-BE-18` Conservation records (IUCN + national) — 1d
- [ ] `P4-BE-19` Slug history + redirects — 1d
- [ ] `P4-BE-20` Plant CRUD API — 3d
- [ ] `P4-BE-21` Filters, sorting, sparse fields — 2d
- [ ] `P4-BE-22` Plant tests — 2d

## 4.3 Media & evidence — `BE`

- [ ] `P4-BE-23` Media schema + licenses + attributions — 1d
- [ ] `P4-BE-24` Presigned upload flow — 1d
- [ ] `P4-BE-25` Validation, EXIF strip, re-encode — 2d
- [ ] `P4-BE-26` Variants + imgproxy integration — 1d
- [ ] `P4-BE-27` Virus scan (ClamAV) — 0.5d
- [ ] `P4-BE-28` `plant_media` linking — 1d
- [ ] `P4-BE-29` Sources + references — 2d
- [ ] `P4-BE-30` Fact-level citations — 2d
- [ ] `P4-BE-31` Verification states + reviewer records — 1d
- [ ] `P4-BE-32` Media/evidence tests — 1d

## 4.4 Geography & distribution — `BE`

- [ ] `P4-BE-33` Countries + regions (Bangladesh divisions/districts) — 2d
- [ ] `P4-BE-34` `country_languages` — 1d
- [ ] `P4-BE-35` `plant_distributions` + `establishment_means` — 2d
- [ ] `P4-BE-36` Sensitive-location masking — 1d
- [ ] `P4-BE-37` Geography tests — 1d

## 4.5 Search & import — `BE`

- [ ] `P4-BE-38` Search index schema — 1d
- [ ] `P4-BE-39` Indexing jobs (create/update/delete) — 2d
- [ ] `P4-BE-40` Synonyms + Banglish transliteration table — 2d
- [ ] `P4-BE-41` Search endpoint + facets — 2d
- [ ] `P4-BE-42` Zero-downtime reindex command — 1d
- [ ] `P4-BE-43` CSV/Excel import + dry-run validation — 3d
- [ ] `P4-BE-44` Duplicate detection + merge tool — 2d
- [ ] `P4-BE-45` Import/merge tests — 1d

## 4.6 API & seed data — `BE`

- [ ] `P4-BE-46` API resources for all core entities — 1d
- [ ] `P4-BE-47` Admin plant endpoints — 2d
- [ ] `P4-BE-48` Contract tests — 1d
- [ ] `P4-BE-49` Factories + seeders — 1d
- [ ] `P4-BE-50` 50-plant demo dataset — 1d

**Exit gate:** 300 plants importable; plant detail API stable; search returns Bengali, English, scientific, and Banglish results; contract tests green.

---

# Phase 5 — Backend: CMS & Page Builder (4 weeks)

**Goal:** editors can build and publish pages without code.

## 5.1 CMS data & API — `BE`

- [ ] `P5-BE-01` `pages`, `page_translations`, `page_sections` — 2d
- [ ] `P5-BE-02` 8 section types + JSON validation schemas — 2d
- [ ] `P5-BE-03` Templates + layouts — 1d
- [ ] `P5-BE-04` Revisions + rollback — 2d
- [ ] `P5-BE-05` Scheduled publishing — 1d
- [ ] `P5-BE-06` Signed preview tokens — 1d
- [ ] `P5-BE-07` Menus + menu items (per locale) — 1d
- [ ] `P5-BE-08` SEO metadata per page — 1d
- [ ] `P5-BE-09` Redirect manager — 1d
- [ ] `P5-BE-10` Sitemap generator (index + image) — 1d
- [ ] `P5-BE-11` CDN purge hooks on publish — 1d
- [ ] `P5-BE-12` CMS API + tests — 2d

## 5.2 Page builder UI — `FE`

- [ ] `P5-FE-01` Section palette — 2d
- [ ] `P5-FE-02` Canvas with reorder, duplicate, delete — 3d
- [ ] `P5-FE-03` Settings panel per section type — 3d
- [ ] `P5-FE-04` Live preview — 2d
- [ ] `P5-FE-05` Revision diff + rollback — 2d
- [ ] `P5-FE-06` Menus UI — 2d
- [ ] `P5-FE-07` SEO UI — 2d
- [ ] `P5-FE-08` E2E publish flow — 1d

**Exit gate:** an editor creates, previews, schedules, publishes, and rolls back a page in staging without a developer.

---

# Phase 6 — Frontend: Public Website (5 weeks)

**Goal:** the SSR public site, SEO-complete and fast on 3G.

## 6.1 Page implementation — `FE`

- [ ] `P6-FE-01` Home — 3d
- [ ] `P6-FE-02` Plant directory + filters — 3d
- [ ] `P6-FE-03` Plant detail — 4d
- [ ] `P6-FE-04` Search + facets + suggestions — 3d
- [ ] `P6-FE-05` Categories index + detail — 2d
- [ ] `P6-FE-06` Taxonomy + family — 2d
- [ ] `P6-FE-07` Articles index + detail — 2d
- [ ] `P6-FE-08` Static/legal pages — 1d
- [ ] `P6-FE-09` Auth pages (login, register, OTP, reset) — 3d
- [ ] `P6-FE-10` Account pages — 3d
- [ ] `P6-FE-11` Community hub shell — 1d
- [ ] `P6-FE-12` 404 / 500 / offline — 1d

## 6.2 SEO & performance — `FE`

- [ ] `P6-FE-13` Per-route metadata + canonical — 2d
- [ ] `P6-FE-14` JSON-LD: Organization, WebSite, Breadcrumb, Article, FAQ, Bioschemas Taxon — 2d
- [ ] `P6-FE-15` OG image generation + fallback — 2d
- [ ] `P6-FE-16` Sitemap + robots + AI-crawler policy — 1d
- [ ] `P6-FE-17` hreflang + locale alternates — 1d
- [ ] `P6-FE-18` SSR caching + tag purge — 2d
- [ ] `P6-FE-19` Responsive images + srcset + AVIF/WebP — 1d
- [ ] `P6-FE-20` Font subsetting + preload — 1d
- [ ] `P6-FE-21` Lighthouse CI budgets — 1d

## 6.3 QA — `QA`

- [ ] `P6-QA-01` Public E2E journeys — 2d
- [ ] `P6-QA-02` Accessibility audit (axe + manual) — 2d
- [ ] `P6-QA-03` Bengali rendering QA — 1d
- [ ] `P6-QA-04` Cross-browser (Chrome, Firefox, Safari, Edge) — 1d
- [ ] `P6-QA-05` SEO validation (sitemap, canonical, hreflang) — 1d
- [ ] `P6-QA-06` OG preview validation (Facebook, X, LinkedIn, WhatsApp, Telegram) — 1d

**Exit gate:** Lighthouse ≥90 performance and 100 a11y/SEO on key pages; social previews verified; Bengali rendering correct.

---

# Phase 7 — Community & Moderation (4 weeks)

**Goal:** comments, reactions, favorites, submissions, and a working moderation loop.

## 7.1 Backend — `BE`

- [ ] `P7-BE-01` Comments + replies schema/API — 2d
- [ ] `P7-BE-02` Reactions (like/helpful) — 1d
- [ ] `P7-BE-03` Favorites + collections — 2d
- [ ] `P7-BE-04` Submissions + revisions — 2d
- [ ] `P7-BE-05` Moderation queue + actions — 2d
- [ ] `P7-BE-06` Reports + appeals — 1d
- [ ] `P7-BE-07` Spam controls + rate limits — 1d
- [ ] `P7-BE-08` Email notifications + preferences — 2d
- [ ] `P7-BE-09` Community tests — 2d

## 7.2 Frontend — `FE`

- [ ] `P7-FE-01` Comment thread UI — 2d
- [ ] `P7-FE-02` Reaction bar — 1d
- [ ] `P7-FE-03` Favorites + collections UI — 2d
- [ ] `P7-FE-04` Submission form + status — 2d
- [ ] `P7-FE-05` Moderation UI — 2d
- [ ] `P7-FE-06` My submissions page — 1d
- [ ] `P7-FE-07` Notification preferences — 1d

## 7.3 QA — `QA`

- [ ] `P7-QA-01` Community E2E (comment, react, favorite, submit) — 2d
- [ ] `P7-QA-02` Moderation E2E (report, approve, reject, ban) — 1d

**Exit gate:** a user can submit a correction; a moderator can approve or reject it; audit trail is complete.

---

# Phase 8 — AI Assist (4 weeks)

**Goal:** AI drafts and field suggestions with mandatory human review.

## 8.1 Backend — `BE`

- [ ] `P8-BE-01` Provider adapter interface — 2d
- [ ] `P8-BE-02` Source registry + licence fields — 2d
- [ ] `P8-BE-03` Ingestion jobs — 2d
- [ ] `P8-BE-04` Extraction + normalization — 2d
- [ ] `P8-BE-05` Existing-plant matching — 2d
- [ ] `P8-BE-06` Draft generation (bn/en) — 2d
- [ ] `P8-BE-07` Field-level suggestions — 2d
- [ ] `P8-BE-08` Cost + token tracking — 1d
- [ ] `P8-BE-09` Prompt versioning — 1d
- [ ] `P8-BE-10` Evaluation set (50 facts) — 1d
- [ ] `P8-BE-11` Kill switch + caps — 0.5d
- [ ] `P8-BE-12` AI tests — 2d

## 8.2 Frontend — `FE`

- [ ] `P8-FE-01` Review queue — 2d
- [ ] `P8-FE-02` Field diff view — 2d
- [ ] `P8-FE-03` Source run monitor — 1d
- [ ] `P8-FE-04` Cost dashboard — 1d

## 8.3 QA — `QA`

- [ ] `P8-QA-01` Human approval E2E — 1d
- [ ] `P8-QA-02` Prompt-injection test — 1d

**Exit gate:** AI never publishes directly; every draft has provenance, cost, and a human decision.

---

# Phase 9 — Hardening & Launch (3 weeks)

**Goal:** production-ready, verified, and handed over.

- [ ] `P9-QA-01` Full regression suite — 2d
- [ ] `P9-QA-02` Client UAT — 3d
- [ ] `P9-QA-03` Performance tuning — 2d
- [ ] `P9-QA-04` Security fixes — 2d
- [ ] `P9-QA-05` External pen test + remediation — 3d
- [ ] `P9-QA-06` Accessibility audit — 2d
- [ ] `P9-QA-07` Content QA: 300 verified profiles — 3d
- [ ] `P9-DO-01` Backup + restore drill — 1d
- [ ] `P9-DO-02` Runbooks (deploy, rollback, reindex, incident) — 1d
- [ ] `P9-DO-03` Monitoring + alerts — 1d
- [ ] `P9-DO-04` Load test at target concurrency — 1d
- [ ] `P9-PM-01` Admin training — 1d
- [ ] `P9-PM-02` Handover documentation — 1d
- [ ] `P9-PM-03` Launch checklist — 1d
- [ ] `P9-PM-04` Go-live — 1d
- [ ] `P9-PM-05` Post-launch monitoring (48 h) — 1d

**Exit gate:** launch checklist signed; no open high/critical security findings; backups restored successfully; team trained.

---

# Phase 10 — Post-launch (ongoing)

- [ ] `P10-01` 7-day and 30-day review (errors, RUM, search quality) — 2d
- [ ] `P10-02` Content expansion to 500 profiles — ongoing
- [ ] `P10-03` PWA / offline mode — later
- [ ] `P10-04` Plant map + seasonal calendar — later
- [ ] `P10-05` AI plant identification — later
- [ ] `P10-06` Nursery directory — later
- [ ] `P10-07` Marketplace + payments — later
- [ ] `P10-08` Additional countries/languages — later
- [ ] `P10-09` Third-party public API — later

---

## 3. Milestones

| Milestone | Week | Demo |
|---|---|---|
| M1 — Plan signed | 2 | SRS, ERD, search decision, budget |
| M2 — Admin login | 5 | Auth, roles, shell |
| M3 — Design system | 8 | Storybook + tokens |
| M4 — All pages designed | 14 | Figma handoff pack |
| M5 — Botanical core | 20 | 300 plants, search, media |
| M6 — CMS live | 24 | Page builder + publish |
| M7 — Public beta | 29 | SSR site, SEO, social previews |
| M8 — Community | 33 | Comments, submissions, moderation |
| M9 — AI assist | 37 | Draft + review queue |
| M10 — Launch | 40 | Production, runbooks, training |

> Weeks shift if Phase 0 reveals a larger scope. Re-baseline at M1.

---

## 4. Critical path

```text
P0 → P1 → P4 → P6 → P9
        ↘ P2 ↗      ↗
         P3 ────────┘
P5 runs after P4.
P7 and P8 run after P4/P5 in parallel with P6/P9.
Content runs from P0 to launch, in parallel with everything.
```

**Do not start Phase 6 before Phase 4's API contract is frozen.** Frontend churn caused by API changes is the most common schedule killer on this kind of project.

---

## 5. Cut list (if the schedule slips)

Drop in this order:

1. AI assist (Phase 8) → move to v1.1
2. Community posts/feed → keep comments + submissions only
3. Plant map + calendar → v1.1
4. Page builder → replace with 5 fixed templates
5. Dark mode
6. PWA
7. Third-party public API
8. Nursery directory
9. Advanced search facets → basic search only
10. Multi-country admin UI → Bangladesh only

Never cut: auth, RBAC, plant core, CMS pages, SEO/SSR, moderation, backups, tests.

---

## 6. Weekly operating rhythm

| Day | Activity |
|---|---|
| Monday | Sprint planning / task pull |
| Daily | 15-min standup; blockers flagged |
| Wednesday | Design + engineering review |
| Thursday | Demo prep; staging deploy |
| Friday | Demo, status report, risk update |

**Status report fields:** done, next, blocked, decisions needed, budget/schedule variance.

---

## 7. Progress tracking template

```text
Phase: 4 — Backend: Botanical Core
Owner: <name>
Dates: <start> → <end>

Tasks:        50
Done:         32
In progress:   6
Blocked:       2  (P4-BE-25, P4-BE-43)
Cut:           0

Exit gate:    300 plants importable; contract tests green
Confidence:   Green / Amber / Red
Notes:       <risks, decisions, help needed>
```

---

## 8. Definition of done (applies to every task)

- [ ] Deliverable exists and is linked (PR / Figma / doc).
- [ ] Acceptance criteria met.
- [ ] Tests added (backend feature, frontend unit, or E2E).
- [ ] bn/en strings complete; no hard-coded copy.
- [ ] Loading, empty, error, and denied states handled.
- [ ] Keyboard + screen-reader check passed.
- [ ] Responsive at 375 / 768 / 1280 / 1440.
- [ ] Performance budget respected.
- [ ] Permission checked server-side.
- [ ] Audit log entry for writes.
- [ ] Reviewed by one peer.
- [ ] Accepted in staging by the product owner.

---

*End of task breakdown.*
