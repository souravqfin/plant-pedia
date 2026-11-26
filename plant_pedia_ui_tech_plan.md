# Plant Pedia — UI Design & Technology Implementation Plan

**Version:** 1.0 (Draft for client review)
**Date:** 26 November 2026
**Companion to:** `plant_pedia_complete_project_plan.md`
**Owner:** Engineering & Design
**Status:** Proposed — decisions marked **[sign-off]** require client approval

---

## Table of contents

1. [How to read this document](#1-how-to-read-this-document)
2. [Executive summary](#2-executive-summary)
3. [Changes from the master plan](#3-changes-from-the-master-plan)
4. [Decision log](#4-decision-log)
5. [Technology stack](#5-technology-stack)
6. [System architecture](#6-system-architecture)
7. [UI/UX design plan](#7-uiux-design-plan)
8. [Frontend implementation plan](#8-frontend-implementation-plan)
9. [Backend implementation plan](#9-backend-implementation-plan)
10. [Data & content plan](#10-data--content-plan)
11. [Security plan](#11-security-plan)
12. [Testing & QA plan](#12-testing--qa-plan)
13. [DevOps, environments & observability](#13-devops-environments--observability)
14. [Delivery plan](#14-delivery-plan)
15. [Team & responsibilities](#15-team--responsibilities)
16. [Budget estimate](#16-budget-estimate)
17. [Risk register](#17-risk-register)
18. [Definition of done & acceptance criteria](#18-definition-of-done--acceptance-criteria)
19. [Open questions for the client](#19-open-questions-for-the-client)
20. [Appendices](#20-appendices)

---

## 1. How to read this document

- The **master plan** defines *what* Plant Pedia must do.
- **This document** defines *how* we design and build it, and resolves the open technical decisions.
- Decision IDs (`D-01`, `D-02`, …) are referenced throughout and tracked in the [decision log](#4-decision-log).
- Items marked **[sign-off]** change something the client may consider contractual (for example, Bootstrap 5). They must be approved before Phase 1.
- Anything marked **Later** is intentionally outside the first release.

**Scope of this plan:** UI design system, final tech stack, architecture, implementation standards, testing, DevOps, delivery timeline, team, budget, and risks.

**Out of scope of this plan:** writing the actual botanical content, marketplace/payments, native mobile apps, and third-party public API billing. Those remain governed by the master plan.

---

## 2. Executive summary

Plant Pedia will be built as an **API-first modular monolith**: a Laravel 13.x (latest stable) API, a single React 19 + TypeScript application with server-side rendering for public pages and a client-rendered admin, MySQL 8.4, Redis, and a Bengali-capable search engine.

Key positions:

| Area | Decision |
|---|---|
| Frontend | React 19 + TypeScript + Vite 7 + React Router 7 (framework mode) |
| Styling | Tailwind CSS 4 + shadcn/ui (Radix primitives) **[sign-off]** |
| Rendering | SSR for public routes; CSR for `/admin` and `/account`; one codebase |
| Backend | Laravel 13.x (latest stable), PHP 8.4, modular monolith |
| Auth | JWT access token + rotating refresh token in HttpOnly cookie + CSRF |
| Database | MySQL 8.4 LTS, Redis 7.4 |
| Search | Meilisearch, validated by a 1-week Bengali search spike; OpenSearch as fallback |
| Media | S3-compatible storage + imgproxy + Cloudflare CDN |
| Design | Token-driven design system, Figma + Storybook, WCAG 2.2 AA |
| Delivery | 34 weeks with the recommended team (≈30–34 calendar weeks with parallel work) |

**The three risks that decide success:**

1. **Bengali search quality** — must be proven before the public site is built.
2. **Content volume and licensing** — 300–500 verified Bangladesh species are needed at launch.
3. **Scope** — the master plan's "MVP" is not an MVP; this plan sequences it into shippable phases.

---

## 3. Changes from the master plan

This plan refines several master-plan choices. Each change is deliberate and reversible.

| # | Master plan | This plan | Why | Status |
|---|---|---|---|---|
| C-01 | Bootstrap 5 | Tailwind CSS 4 + shadcn/ui | Distinctive botanical brand, accessible Radix primitives, no Bootstrap-JS/React conflicts | **[sign-off]** |
| C-02 | "React SSR or prerendering" | React Router 7 framework mode, SSR public + CSR admin | One router, one codebase, Vite-native, no second framework | Decided |
| C-03 | Meilisearch (optionally Scout) | Meilisearch after a Bengali spike; OpenSearch fallback | Meilisearch has weak Bengali stemming; Lucene has a native Bengali analyzer | Spike |
| C-04 | `/internal/admin/` | `/api/admin/v1/` + `/admin/*` frontend | The admin API is browser-reachable; "internal" is misleading and unversioned | Decided |
| C-05 | JWT + cookies + CSRF (unresolved) | Access JWT in memory, refresh in HttpOnly cookie, CSRF double-submit | One concrete, secure flow for web and mobile | Decided |
| C-06 | Reviews in MVP | Deferred until nurseries exist | No reviewable entity at launch | Decided |
| C-07 | Likes and dislikes | Likes/helpful only | Removes negativity and moderation cost | Decided |
| C-08 | Multi-country admin UI | Global schema, Bangladesh-only UI at launch | Avoids building unused screens | Decided |
| C-09 | 16 page-builder section types | 8 at launch, rest later | Build, test, and design quality | Decided |
| C-10 | Laravel 13.x (latest stable) | Laravel 13.x (latest stable), pinned minor, PHP 8.4 | Reproducible builds | Decided |

---

## 4. Decision log

| ID | Area | Decision | Rationale | Status |
|---|---|---|---|---|
| D-01 | Frontend framework | React 19 + TypeScript 5 + Vite 7 + React Router 7 framework mode | SSR and SPA in one router; Vite-native; strong ecosystem | Decided |
| D-02 | Styling | Tailwind CSS 4 + shadcn/ui + Radix + CVA | Design control, accessibility, no Bootstrap JS conflicts | **[sign-off]** |
| D-03 | Rendering | SSR public, CSR admin/account, one app, route-level code splitting | SEO + social previews without a second frontend | Decided |
| D-04 | Auth | JWT access (15 min) + rotating refresh (30 days) in HttpOnly/Secure/SameSite=Lax cookie + CSRF double-submit | Meets JWT requirement; safer than localStorage | Decided |
| D-05 | Database | MySQL 8.4 LTS | Stated requirement; sufficient for v1 | Decided |
| D-06 | Search | Meilisearch v1.x after spike; OpenSearch 2.x fallback | Bengali tokenization must be proven | Spike (Phase 0) |
| D-07 | Maps | MapLibre GL JS + OpenStreetMap tiles | Vector tiles, clustering, no vendor lock-in | Decided |
| D-08 | Media | S3-compatible (Cloudflare R2 / Wasabi) + imgproxy + Cloudflare CDN | Cost, on-the-fly variants, cache | Decided |
| D-09 | Rich text | TipTap 3 (ProseMirror) | React-native, extensible, good Bengali input | Decided |
| D-10 | i18n | i18next + locale-prefixed URLs, Bengali default | SEO, shareable links, fallback control | Decided |
| D-11 | Admin security | Separate route group, `no-store`, RBAC, mandatory MFA, audit log | Defense in depth | Decided |
| D-12 | AI | Provider adapter, queued jobs, human review, feature flags, cost caps | Vendor flexibility and safety | Decided |
| D-13 | Deployment | Docker Compose on 2 VPS + managed MySQL/Redis initially | Simple, affordable, portable | Decided |
| D-14 | Repository | `backend/`, `frontend/`, `contracts/`, `infra/`, `docs/` | Clear ownership, generated API types | Decided |
| D-15 | Testing | Pest, Vitest + Testing Library, Playwright, axe | Coverage across layers | Decided |
| D-16 | Design system | Figma variables → JSON tokens → Tailwind theme; Storybook | Single source of truth | Decided |
| D-17 | Email/SMS | Amazon SES + local Bangladesh SMS gateway | Deliverability and OTP reach | Decided |
| D-18 | Analytics | Self-hosted Plausible or Umami | Privacy, low cost | Decided |
| D-19 | Feature flags | Laravel Pennant + `/api/v1/config/flags` | Safe rollout and kill switches | Decided |
| D-20 | Observability | Sentry + OpenTelemetry + Better Stack/Grafana | Errors, traces, uptime | Decided |

---

## 5. Technology stack

### 5.1 Runtime and tooling

| Layer | Technology | Version | Notes |
|---|---|---|---|
| PHP | PHP | 8.4 | 8.3 fallback if a dependency lags |
| Node | Node.js | 22 LTS | SSR server + build |
| Package managers | Composer 2, pnpm 9 | latest | pnpm for speed and workspace discipline |
| Containers | Docker 27, Docker Compose v2 | latest | Local and production parity |
| Web server | Nginx 1.27 | stable | TLS termination at Cloudflare |
| OS | Ubuntu 24.04 LTS | — | Server images |

### 5.2 Backend

| Concern | Package / tool | Purpose |
|---|---|---|
| Framework | `laravel/framework` 13.x | API, queues, scheduler |
| Auth | `lcobucci/jwt` + custom guard | JWT access/refresh lifecycle |
| Permissions | `spatie/laravel-permission` | Roles and permissions |
| Media | `spatie/laravel-medialibrary` + imgproxy | Uploads, conversions, variants |
| Audit | `spatie/laravel-activitylog` | Admin and scientific audit trail |
| Feature flags | `laravel/pennant` | Gradual rollout, kill switches |
| Queues | Redis + Laravel Horizon | AI, imports, emails, indexing |
| Search | Meilisearch SDK / OpenSearch client | Indexing and querying |
| OpenAPI | `dedoc/scramble` | Generated API docs |
| HTML sanitizing | `mews/purifier` (HTMLPurifier) | CMS and rich-text safety |
| Images | `intervention/image` | Validation, EXIF stripping, re-encode |
| Testing | `pestphp/pest`, `larastan/larastan` | Tests and static analysis |
| Style | `laravel/pint` | Code style |
| API resources | Laravel API Resources | Response serialization |
| Scheduling | Laravel Scheduler | Source sync, cleanup, sitemaps |

### 5.3 Frontend

| Concern | Package / tool | Purpose |
|---|---|---|
| UI | React 19 | Component model |
| Language | TypeScript 5.x (strict) | Type safety |
| Build | Vite 7 | Dev server and bundling |
| Routing/SSR | React Router 7 framework mode | Public SSR + admin SPA |
| Server state | TanStack Query 5 | Caching, retries, mutations |
| Tables | TanStack Table 8 | Admin data grids |
| Forms | React Hook Form 7 + Zod | Validation shared with contracts |
| Styling | Tailwind CSS 4 | Utility-first design system |
| Components | shadcn/ui + Radix | Accessible primitives |
| Variants | class-variance-authority + tailwind-merge | Consistent component APIs |
| Icons | Lucide React | Consistent icon set |
| Rich text | TipTap 3 | Articles, CMS, descriptions |
| Charts | Recharts | Admin dashboards |
| Maps | MapLibre GL JS + react-map-gl | Distribution maps |
| i18n | i18next + react-i18next | bn/en UI strings |
| Dates/numbers | `Intl` + date-fns | Locale-aware formatting |
| Client state | Zustand | UI state only (not server data) |
| Toasts | Sonner | Notifications |
| SEO | React Router `meta`/`links` + JSON-LD helpers | Per-route metadata |
| Testing | Vitest, Testing Library, MSW, Playwright, axe | Unit → E2E |
| Docs | Storybook 9 | Component catalogue and visual review |

### 5.4 Infrastructure and services

| Concern | Technology |
|---|---|
| CDN / WAF / DNS | Cloudflare |
| Object storage | Cloudflare R2 or Wasabi (S3 API) |
| Image processing | imgproxy |
| Email | Amazon SES (Postmark fallback) |
| SMS / OTP | Local Bangladesh gateway (SSL Wireless / Bulk SMS BD) with Twilio fallback |
| Error tracking | Sentry |
| Tracing/metrics | OpenTelemetry → Grafana Cloud |
| Uptime | Better Stack or UptimeRobot |
| Analytics | Self-hosted Plausible or Umami |
| CI/CD | GitHub Actions |
| Secrets | Server env + SOPS-encrypted files (or cloud secret manager) |

### 5.5 Why not the alternatives

| Alternative | Why not now |
|---|---|
| Next.js | Excellent SSR, but adds React Server Components and a second routing model; React Router 7 keeps one router for public and admin and matches the Vite stack. |
| Bootstrap 5 | Fast for CRUD, but Bootstrap's JS plugins fight React state and it is harder to achieve a distinctive botanical brand. Fallback: React-Bootstrap. |
| Inertia.js | Couples the frontend to Laravel and weakens the "one common API for mobile and external clients" requirement. |
| Sanctum-only | No clean token story for future mobile and third-party clients; JWT is a stated requirement. |
| PostgreSQL + PostGIS | Better geospatial, but MySQL is the stated database and sufficient for v1. Revisit if maps become a core product. |
| Microservices | Premature; a modular monolith is faster, cheaper, and easier to operate. |
| GraphQL | REST + OpenAPI is simpler for mobile, caching, and external clients. |

---

## 6. System architecture

### 6.1 High-level topology

```text
                    Browser / Mobile app / External client
                                   |
                        Cloudflare (CDN, WAF, TLS)
                                   |
                                 Nginx
                          /                  \
                 React SSR (Node)        Laravel API (PHP-FPM)
                 public routes           /api/v1, /api/admin/v1
                          \                  /
                    Redis  MySQL  Meilisearch  S3/R2
                          \                  /
                    Horizon workers + Scheduler
                                   |
                  AI providers / data sources / email / SMS
```

### 6.2 Request flow

1. Public request hits Cloudflare. Cached HTML is served immediately when fresh.
2. On a cache miss, Nginx routes to the React SSR server.
3. The SSR loader calls the Laravel public API with a short-lived internal token.
4. Laravel reads MySQL/Redis/Meilisearch and returns a JSON resource.
5. React renders HTML + metadata; Cloudflare caches it by tag.
6. The browser hydrates; personalized fragments (favorites, comments) load client-side.
7. Admin requests bypass the CDN, use `no-store`, and require an admin-audience JWT + MFA session.

### 6.3 Rendering and caching matrix

| Route group | Rendering | Cache policy | Auth |
|---|---|---|---|
| `/`, `/plants`, `/categories`, `/articles`, CMS pages | SSR | CDN `s-maxage=300`, `stale-while-revalidate=86400`; purge by tag on publish | Public |
| `/plants/{slug}` | SSR | CDN `s-maxage=600`; purge tag `plant:{id}` | Public |
| `/search` | SSR shell + client fetch | No CDN cache; browser 60 s | Public |
| `/account/*` | CSR | `no-store` | User JWT |
| `/admin/*` | CSR | `no-store`, `X-Robots-Tag: noindex` | Admin JWT + MFA |
| `/login`, `/register` | SSR shell | `no-store` | Guest |
| `/sitemap.xml`, `/robots.txt` | Server-generated | CDN 1 h | Public |
| API | JSON | Per-endpoint ETag / short cache | Varies |

### 6.4 Environments

| Environment | Purpose | Data |
|---|---|---|
| Local | Docker Compose, seeded fixtures | Synthetic |
| CI | Automated tests, ephemeral DB | Synthetic |
| Staging | Client UAT, content rehearsal | Anonymized copy |
| Production | Live | Real |

Staging is protected by basic auth + IP allowlist and is `noindex`.

### 6.5 Repository layout

```text
plant-pedia/
├── backend/                 Laravel 13.x (latest stable) API
├── frontend/                React 19 app (public + admin)
├── contracts/               Generated OpenAPI schema + TypeScript types
├── infra/                   Docker, Nginx, deploy scripts
├── docs/                    ADRs, runbooks, API guides
└── docker-compose.yml
```

---

## 7. UI/UX design plan

### 7.1 Design principles

1. **Trust first.** Scientific names, sources, and verification states are visually prominent, never buried.
2. **Calm, botanical, editorial.** Generous whitespace, warm neutrals, deep greens, serif display type.
3. **Bengali is a first-class citizen.** Not a translated afterthought: tested line heights, fonts, and layouts.
4. **Progressive disclosure.** A plant page is scannable at a glance and deep on demand.
5. **Accessible by default.** WCAG 2.2 AA is the baseline, not a retrofit.
6. **Fast on a mid-range Android phone on 3G.** Performance is a design constraint.
7. **Admin is a tool, not a showcase.** Density, keyboard speed, and clear state.

### 7.2 Brand and visual direction

**Concept:** *"A field guide for the modern web."* Scientific credibility meets the warmth of a botanical garden.

| Element | Direction |
|---|---|
| Mood | Calm, natural, trustworthy, curious |
| Photography | Real plants, natural light, consistent color grading, no stock clichés |
| Illustration | Fine-line botanical drawings for empty states and section dividers |
| Shape | Soft 8–16 px radii, restrained shadows, thin 1 px borders |
| Density | Airy on public pages; compact in admin |
| Motion | Subtle fades and 150–250 ms transitions; no decorative motion |

### 7.3 Color tokens

| Token | Hex | Use |
|---|---|---|
| `primary-900` | `#0B3D2E` | Headings, dark surfaces |
| `primary-700` | `#14532D` | Primary buttons, links |
| `primary-600` | `#166534` | Hover states |
| `primary-500` | `#15803D` | Success, active states |
| `leaf-500` | `#4D7C0F` | Secondary accents, tags |
| `sage-200` | `#D9E5D6` | Soft backgrounds, chips |
| `cream-50` | `#FBFAF6` | Page background |
| `stone-900` | `#1C1917` | Body text |
| `stone-600` | `#57534E` | Secondary text |
| `stone-300` | `#D6D3D1` | Borders |
| `amber-500` | `#D97706` | Highlights, flowering season |
| `terracotta-500` | `#C2410C` | Warnings, conservation |
| `danger-600` | `#B91C1C` | Errors, destructive actions |
| `info-600` | `#0369A1` | Informational states |

Rules:

- Body text on cream must reach **4.5:1**; large text **3:1**.
- Never use color alone to convey verification, toxicity, or conservation status.
- Dark mode is token-ready; ship light mode first, admin dark mode in Phase 2.

### 7.4 Typography

| Role | English | Bengali |
|---|---|---|
| Display / headings | **Fraunces** (variable serif) | **Noto Serif Bengali** |
| Body / UI | **Inter** | **Hind Siliguri** (fallback: Noto Sans Bengali) |
| Scientific names | Fraunces italic | — (scientific names stay Latin) |
| Code / identifiers | JetBrains Mono | — |

Scale (px): `12, 14, 16, 18, 20, 24, 30, 36, 48, 60`.

Rules:

- Bengali body line-height **1.8**; English **1.6**; headings **1.2**.
- Bengali body size is **+1 px** versus English for equivalent legibility.
- Never apply faux italics to Bengali; use weight and color for emphasis.
- Self-host WOFF2, subset Bengali + Latin, `font-display: swap`, preload the body face only.
- Use `Intl.NumberFormat('bn-BD')` for Bengali numerals in editorial UI; keep Western digits for scientific measurements and coordinates.

### 7.5 Spacing, layout, breakpoints

- Base spacing unit: **4 px**; scale `4, 8, 12, 16, 24, 32, 48, 64, 96`.
- Public container: **1280 px** max, 24 px gutters desktop / 16 px mobile.
- Admin container: **1440 px** max, 24 px gutters.
- Grid: 12 columns.
- Breakpoints: `480, 640, 768, 1024, 1280, 1536`.
- Radii: `4, 8, 12, 16, 999`.
- Shadows: `sm`, `md`, `lg` only; no heavy drop shadows.

### 7.6 Iconography, imagery, illustration

- **Icons:** Lucide, 1.5 px stroke, 20/24 px sizes.
- **Plant images:** 4:3 default card, 1:1 thumbnails, 16:9 hero. AVIF/WebP with JPEG fallback.
- **Attribution:** photographer + license visible on hover/tap and in the lightbox.
- **OG images:** 1200×630 template with plant photo, Bengali/English name, scientific name, and logo.
- **Illustrations:** single-color fine-line drawings; no cartoon mascots.

### 7.7 Motion

- Durations: 150 ms (micro), 250 ms (panels), 350 ms (page transitions).
- Easing: `ease-out` for entrances, `ease-in` for exits.
- Respect `prefers-reduced-motion`; disable parallax and auto-play.
- No motion on data tables or form validation.

### 7.8 Component inventory

| Priority | Components |
|---|---|
| **P0 (launch)** | Button, IconButton, Input, Textarea, Select, Combobox, Checkbox, Radio, Switch, FormField, Badge, Tag, Card, PlantCard, ArticleCard, Modal, Drawer, Dropdown, Tabs, Table, Pagination, Toast, Skeleton, EmptyState, Alert, Breadcrumb, SearchBar, LanguageSwitcher, SEOHead, VerificationBadge, SourceCitation, IUCNBadge |
| **P1 (launch)** | DatePicker, FileUpload, Avatar, Tooltip, Popover, Accordion, Gallery, Lightbox, FilterPanel, Stat, Chart, CommentThread, ReactionBar, FavoriteButton, ShareButtons, Map, Stepper, Timeline, CommandPalette |
| **P2 (post-launch)** | PageBuilderCanvas, DiffViewer, MediaPicker, RoleMatrix, TranslationEditor, AuditLogTable, RichTextEditor |

Component rules:

- Every component ships with: default, hover, focus, active, disabled, loading, error, and empty states where relevant.
- Every interactive component is keyboard-operable and has an accessible name.
- Variants are defined with CVA, not ad-hoc class strings.
- Every component has a Storybook story and an axe check.

### 7.9 Public page specifications

| Route | Purpose | Key UI | SEO / notes |
|---|---|---|---|
| `/` | Discover and orient | Hero search, featured plants, categories, seasonal picks, latest articles, community stats | SSR, OG image, `WebSite` + `Organization` JSON-LD |
| `/plants` | Browse the encyclopedia | Filter sidebar (family, category, region, light, water), sort, grid/list toggle, pagination | SSR shell; canonical; noindex on deep filter combos |
| `/plants/{slug}` | Core plant profile | Gallery, names, scientific name + author, taxonomy, quick facts, tabs (overview, characteristics, distribution, care, uses, sources), related plants, comments | SSR; Bioschemas `Taxon`; OG image; breadcrumbs |
| `/search` | Find anything | Search bar, facets, result count, suggestions, recent/popular searches | No CDN cache; `noindex` |
| `/categories` | Browse by use | Category grid with counts and imagery | SSR |
| `/categories/{slug}` | Category listing | Filters + plant grid | SSR |
| `/taxonomy` | Browse the tree | Kingdom → family drill-down | SSR |
| `/families/{slug}` | Family page | Description, genera, species list | SSR |
| `/articles` | Knowledge hub | Featured, categories, search | SSR |
| `/articles/{slug}` | Article | Rich text, TOC, author, sources, related | SSR; `Article` JSON-LD |
| `/identify` | AI identification | Upload, crop, candidates, confidence, feedback | **Later**; noindex until launch |
| `/plant-map` | Distribution map | MapLibre, clustering, filters, sensitive-species masking | **Later** |
| `/calendar` | Seasonal guide | Month/region selector, planting/flowering/fruiting | **Later** |
| `/community` | Community hub | Posts, observations, contributors | Phase 6 |
| `/nurseries` | Nursery directory | Map + list, verified badges | **Later** |
| `/account/*` | Profile, favorites, collections, submissions, settings | Sidebar, forms, empty states | CSR, noindex |
| `/about`, `/contributors`, `/experts`, `/sources`, `/contact`, `/privacy`, `/terms` | Trust and legal | Editorial layouts, CMS-driven | SSR; CMS-managed |

### 7.10 Admin page specifications

| Screen | Key UI | Notes |
|---|---|---|
| Dashboard | KPI cards, content pipeline, review queue, recent activity, system health | Real data only |
| Plants list | Data table, saved views, bulk actions, filters, import/export | Virtualized rows |
| Plant editor | Tabbed form + right meta panel (status, translations, SEO, media, sources) | Autosave draft, version diff, optimistic lock |
| Taxonomy | Tree editor, drag/reparent, synonym manager | Guard against cycles |
| Categories & attributes | CRUD, ordering, translations | Many-to-many |
| Countries & regions | Country list, region tree, settings | Bangladesh only at launch |
| Languages & translations | Language list, completeness matrix, string editor, import/export | bn/en at launch |
| Media library | Grid/list, filters, upload, crop, variants, attribution, usage | EXIF stripped |
| Sources & references | Registry, license, ToS date, usage | Provenance |
| Submissions | Queue, diff, approve/reject/revise, reviewer notes | Status workflow |
| Moderation | Reports, comments, users, actions, appeals | SLA timer |
| Users & roles | User table, role matrix, impersonation (audited) | MFA status |
| CMS pages | List, status, schedule, preview, revisions | Draft preview tokens |
| Page builder | Section palette, canvas, settings panel, reorder, duplicate | 8 section types |
| Menus | Drag-and-drop tree, link picker | Per locale |
| SEO | Global defaults, per-entity overrides, redirects, sitemap status | Validation |
| AI research | Source runs, matches, field suggestions, review queue, cost | **Phase 7** |
| Settings & audit | Site settings, feature flags, audit log with diffs | Immutable log |

### 7.11 Admin UX patterns

1. **Command palette** (`Ctrl/Cmd + K`) for navigation and actions.
2. **Data tables** with column visibility, saved views, bulk selection, and sticky headers.
3. **Side-panel editing** for quick edits; full-page editor for complex entities.
4. **Unsaved-changes guard** and draft autosave every 20 seconds.
5. **Optimistic locking** with a clear "someone else edited this" conflict screen.
6. **Diff view** for revisions and AI field suggestions.
7. **Bulk actions** with a confirmation summary and undo window where safe.
8. **Empty, loading, error, and permission-denied states** designed for every screen.
9. **Keyboard-first** forms: tab order, Enter to save, Esc to cancel.
10. **Consistent status colors** and labels across all modules.

### 7.12 Responsive strategy

- **Public:** mobile-first. Plant detail becomes a single column with a sticky action bar (favorite, share, identify).
- **Admin:** desktop-first, usable on tablets (≥ 768 px). Tables switch to cards below 768 px; complex editors require ≥ 1024 px.
- **Touch targets:** minimum 44×44 px.
- **No horizontal scroll** except intentionally scrollable tables and galleries.

### 7.13 Accessibility (WCAG 2.2 AA)

- Semantic landmarks, one `h1` per page, logical heading order.
- Skip-to-content link; visible 2 px focus ring with offset.
- All form fields have labels, descriptions, and inline errors announced via `aria-live`.
- Error summary at the top of long forms, linking to fields.
- Contrast: 4.5:1 body, 3:1 large text and UI boundaries.
- No color-only meaning; icons + text for verification, toxicity, conservation.
- Keyboard-operable menus, modals (focus trap), tabs, and galleries.
- Captions/transcripts for video; alt text required for uploaded images.
- Reduced-motion support.
- Test with NVDA + Firefox, VoiceOver + Safari, and TalkBack + Chrome.
- Bengali screen-reader pass on the plant detail and search pages.

### 7.14 Content design and microcopy

- Plain language, active voice, short sentences.
- Bengali tone: respectful and simple, using **আপনি**; avoid overly formal register.
- Scientific names always italic, with author citation on the detail page.
- Verification labels: *Unverified*, *Community reviewed*, *Editor verified*, *Expert verified* — with a tooltip explaining each.
- Error messages: what happened + how to fix + a next action.
- Empty states: explain the value and offer the next step.
- Dates, numbers, and currency via `Intl` (`bn-BD`, `en-GB`), currency `BDT`.

### 7.15 Design process and deliverables

| Week | Activity | Deliverable |
|---|---|---|
| 1 | Discovery, brand workshop, IA, competitor review | Moodboard, sitemap, user flows |
| 2 | Wireframes: home, plant detail, search, admin shell | Low-fi wireframes |
| 3 | Visual direction, color/type tokens, key screens | Hi-fi home + plant detail |
| 4 | Component library, forms, tables, states | Figma component library v1 |
| 5 | Remaining public + admin screens, responsive variants | Full hi-fi set |
| 6 | Prototype, 5-user usability test, handoff | Clickable prototype, findings, specs |

**Handoff artifacts:**

- Figma variables exported to `contracts/design-tokens.json`.
- Storybook deployed to staging.
- Redlines and interaction notes in Figma.
- Accessibility annotations per component.
- Content matrix (bn/en) for every screen.

**Design QA:** every PR with UI changes includes screenshots at 375, 768, 1280, and 1440 px, plus a Storybook link.

---

## 8. Frontend implementation plan

### 8.1 Folder structure

```text
frontend/
├── app/
│   ├── root.tsx
│   ├── routes.ts
│   ├── entry.client.tsx
│   └── entry.server.tsx
├── routes/
│   ├── _public.tsx
│   ├── _public._index.tsx
│   ├── _public.plants._index.tsx
│   ├── _public.plants.$slug.tsx
│   ├── _public.search.tsx
│   ├── _public.articles.$slug.tsx
│   ├── _account.tsx
│   ├── _account.favorites.tsx
│   ├── _admin.tsx
│   ├── _admin.dashboard.tsx
│   └── _admin.plants.$id.tsx
├── components/
│   ├── ui/                  shadcn primitives
│   ├── domain/              PlantCard, SourceCitation, VerificationBadge
│   ├── layout/              Header, Footer, AdminShell
│   └── seo/                 Meta, JsonLd
├── features/
│   ├── plants/
│   ├── taxonomy/
│   ├── search/
│   ├── community/
│   ├── cms/
│   └── admin/
├── lib/
│   ├── api/                 client, endpoints, query keys
│   ├── auth/                token store, refresh, guards
│   ├── i18n/                config, formatters
│   └── utils/
├── hooks/
├── styles/
├── locales/
│   ├── bn/
│   └── en/
├── types/
└── tests/
```

### 8.2 Routing map

```text
/                          → Bengali home (canonical /bn)
/bn/*, /en/*               → localized public routes
/plants, /plants/{slug}
/categories, /categories/{slug}
/taxonomy, /families/{slug}
/search
/articles, /articles/{slug}
/community
/account/*                 → protected, CSR
/admin/*                   → protected, CSR, noindex
/sitemap.xml, /robots.txt
```

Locale rules:

- Default locale: **Bengali**.
- All content URLs are locale-prefixed: `/bn/plants/mango`, `/en/plants/mango`.
- `/` renders Bengali with canonical `/bn` (no redirect chain).
- `hreflang`: `bn-BD`, `en`, `x-default` → `bn`.
- Language switcher preserves the current path and stores preference in a cookie + user profile.
- Missing translation falls back to English and displays a subtle "English content" label.

### 8.3 Data fetching and caching

- TanStack Query for all client data.
- Query keys: `['plants', 'list', filters]`, `['plant', slug, locale]`.
- `staleTime`: 60 s for lists, 5 min for detail, 30 s for admin queues.
- Mutations invalidate by prefix and show optimistic updates for favorites/likes.
- SSR loaders fetch through the same API client for consistency.
- Personalized fragments load after hydration so public HTML stays cacheable.

### 8.4 Forms and validation

- React Hook Form + Zod.
- Zod schemas generated or shared from `contracts/` so frontend and backend agree.
- Server validation errors map to fields via error codes, not message strings.
- Bengali validation messages from `locales/bn/validation.json`.
- Unsaved-changes guard on all editors.

### 8.5 Error, loading, and empty states

- Route-level error boundaries with a friendly recovery action.
- Skeleton loaders that match final layout (no layout shift).
- Empty states with illustration + explanation + primary action.
- Offline/network banner for flaky connections.
- 404 and 410 pages with search and popular links.

### 8.6 SEO implementation

- Per-route `meta` and `links` exports in React Router.
- Canonical URL on every public page.
- `hreflang` alternates.
- Open Graph + Twitter card tags.
- JSON-LD: `Organization`, `WebSite`, `BreadcrumbList`, `Article`, `FAQPage`, Bioschemas `Taxon`.
- XML sitemap index (50k URL chunks) + image sitemap.
- `robots.txt` with admin/account disallow and AI-crawler policy.
- Slug history → 301 redirects.
- Filtered/paginated pages: canonical to the base page, `noindex` on deep combinations.
- CDN purge on publish.

### 8.7 i18n implementation

- i18next with namespaces (`common`, `plants`, `admin`, `validation`).
- Locale files lazy-loaded per route.
- `Intl` for dates, numbers, relative time, and currency.
- Scientific names and identifiers never translated.
- Pseudo-locale (`en-XA`) for layout testing.
- Missing-key reporting to Sentry in development; silent English fallback in production.

### 8.8 Theming and tokens

```text
Figma variables
   → contracts/design-tokens.json
   → Tailwind theme (CSS variables)
   → components
```

- Tokens are the only source of color, spacing, radius, and type values.
- No hard-coded hex values in components.
- Dark mode via `.dark` class and CSS variables.

### 8.9 Performance budgets

| Metric | Budget |
|---|---|
| LCP (mid-range Android, 3G) | ≤ 2.5 s |
| CLS | < 0.1 |
| INP | < 200 ms |
| Initial JS per public route | ≤ 180 KB gzip |
| CSS | ≤ 40 KB gzip |
| Hero image | ≤ 150 KB AVIF |
| Fonts (total) | ≤ 120 KB WOFF2 |
| SSR TTFB (cached) | ≤ 500 ms |
| API p95 (read) | ≤ 300 ms |
| Lighthouse | ≥ 90 performance, 100 a11y/SEO/best practices |

Enforcement: Lighthouse CI on every PR, bundle-size check, and a 3G throttled Playwright test for the plant detail page.

### 8.10 Bengali font strategy

- Self-host WOFF2, subset to Bengali + Latin + punctuation.
- Preload only the body font; display fonts load with `swap`.
- `size-adjust` and fallback metrics to reduce CLS.
- Test on low-end Android (2 GB RAM) and with system Bengali fonts disabled.

### 8.11 PWA (Phase 2)

- Installable shell, offline plant pages for saved plants, cached search suggestions.
- Background sync for favorites/observations.
- Not at launch; the API and asset strategy must not block it.

---

## 9. Backend implementation plan

### 9.1 Modules (modular monolith)

```text
Auth            Identity, tokens, MFA, devices, sessions
Localization    Languages, translations, locales, fallback
Geography       Countries, regions, distributions
Taxonomy        Taxa, names, synonyms, identifiers, change log
Plants          Profiles, attributes, care, seasonality, conservation
Media           Uploads, variants, licenses, attributions
Evidence        Sources, references, fact-level citations
CMS             Pages, sections, menus, revisions, scheduling
Search          Indexing, synonyms, query expansion
Community       Comments, reactions, favorites, collections, submissions
Moderation      Reports, actions, appeals, audit
Verification    Expert review, claims, decisions
AI              Providers, tasks, drafts, suggestions, cost
Admin           Dashboard, users, roles, settings, audit
Notifications   Email, SMS, in-app
```

Each module owns its models, actions, services, policies, requests, resources, jobs, and tests. Modules communicate through service interfaces, not direct cross-module queries where avoidable.

### 9.2 Folder structure

```text
backend/
├── app/
│   ├── Modules/
│   │   ├── Plants/
│   │   │   ├── Actions/
│   │   │   ├── Data/
│   │   │   ├── Models/
│   │   │   ├── Policies/
│   │   │   ├── Queries/
│   │   │   ├── Services/
│   │   │   └── Tests/
│   │   └── ...
│   ├── Http/
│   │   ├── Controllers/Api/V1/
│   │   ├── Controllers/Admin/V1/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Policies/
│   ├── Jobs/
│   ├── Services/
│   └── Support/
├── routes/
│   ├── api.php
│   ├── admin.php
│   └── console.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
└── tests/
```

### 9.3 Backend patterns

- **Controllers:** thin; validate → authorize → call action → return resource.
- **Actions:** one use case per class (`CreatePlant`, `ApproveSubmission`).
- **Services:** cross-cutting domain logic (`TaxonomyResolver`, `SearchIndexer`).
- **Queries:** reusable, testable query objects for complex filters.
- **Events/Listeners:** side effects (index, notify, audit) after commit.
- **Jobs:** queued, idempotent, retried with backoff.
- **Policies:** every write and sensitive read.
- **Form Requests:** all input validation.
- **API Resources:** all output serialization.

### 9.4 API standards

**Base paths:** `/api/v1/*` (public/client), `/api/admin/v1/*` (admin).

**Success envelope:**

```json
{
  "data": { "id": 42, "slug": "mangifera-indica" },
  "meta": { "request_id": "req_01H..." }
}
```

**Error envelope:**

```json
{
  "errors": [
    { "code": "PLANT_NOT_FOUND", "message": "Plant not found.", "field": null }
  ],
  "meta": { "request_id": "req_01H..." }
}
```

**Conventions:**

| Concern | Convention |
|---|---|
| Pagination | `?page=2&per_page=25`; cursor pagination for feeds |
| Filtering | `filter[family]=Anacardiaceae` |
| Sorting | `sort=-created_at` |
| Sparse fields | `fields=id,slug,name` |
| Idempotency | `Idempotency-Key` header on POST |
| Concurrency | `ETag` + `If-Match` on admin updates |
| Versioning | URL version; 6-month deprecation window |
| Docs | OpenAPI via Scramble; generated TS types in `contracts/` |
| Rate limits | Per-IP and per-user; headers `X-RateLimit-*` |
| Localization | `Accept-Language`; error codes are stable, messages localized |

**Core error codes:** `VALIDATION_FAILED`, `UNAUTHENTICATED`, `FORBIDDEN`, `NOT_FOUND`, `RATE_LIMITED`, `CONFLICT`, `UNPROCESSABLE`, `INTERNAL_ERROR`.

### 9.5 Authentication and token lifecycle

**Web:**

1. `POST /api/v1/auth/login` → access JWT in JSON (15 min) + refresh token in `HttpOnly; Secure; SameSite=Lax` cookie (30 days).
2. Access token held in memory only (never localStorage).
3. `POST /api/v1/auth/refresh` → rotates refresh token, returns a new access token. Requires CSRF double-submit token.
4. Refresh tokens stored **hashed** with a family ID; reuse revokes the whole family.
5. `POST /api/v1/auth/logout` revokes the current session; `logout-all` revokes every family.

**Mobile/external:** access + refresh in OS secure storage; no cookie, no CSRF.

**Admin:**

- Separate token audience `admin`.
- Mandatory TOTP MFA + recovery codes.
- Access 10 min, refresh 12 h, idle timeout 30 min.
- Optional IP allowlist.
- Every sensitive action audited.

**Password policy:** Argon2id, minimum 12 characters, breached-password check (HIBP k-anonymity), email verification, reset tokens single-use.

### 9.6 Permissions

- `spatie/laravel-permission` with configurable roles.
- Permissions checked in policies and middleware.
- Frontend hides unavailable actions, but the server is authoritative.
- Permission matrix is a Phase 0 deliverable.

### 9.7 Search indexing

- Indexes: `plants`, `articles`, `pages`, `nurseries` (later).
- Indexed fields: bn/en common names, scientific name + author, synonyms, local names, family/genus, categories, habitat, uses.
- Synonyms: scientific ↔ common, Banglish ↔ Bengali, misspellings.
- Transliteration: Banglish query expansion table (Avro-style rules) applied before search.
- Indexing is queued and idempotent; full reindex is zero-downtime (build alias → swap).
- Search analytics: zero-result queries, top queries, click-through.

### 9.8 Media pipeline

1. Client requests a presigned upload URL.
2. Upload goes directly to S3/R2.
3. A queued job validates MIME, size, and dimensions; strips EXIF; re-encodes; generates variants.
4. imgproxy serves on-the-fly sizes through the CDN.
5. Attribution, license, and source are mandatory fields.
6. Virus scan (ClamAV) for non-image uploads.
7. Orphaned media cleanup runs weekly.

### 9.9 Caching

| Data | Store | TTL | Invalidation |
|---|---|---|---|
| Plant detail API | Redis | 10 min | On update/publish |
| Taxonomy tree | Redis | 1 h | On taxonomy change |
| Home modules | Redis | 5 min | On content publish |
| Menus/settings | Redis | 1 h | On save |
| Search suggestions | Redis | 15 min | On index update |
| SSR HTML | Cloudflare | 5–10 min + SWR | Tag purge on publish |

### 9.10 Queues and scheduler

- Horizon queues: `default`, `media`, `search`, `ai`, `imports`, `notifications`.
- Priority: `ai` and `imports` are isolated so they never block user-facing jobs.
- Scheduler: source sync, sitemap generation, cleanup, analytics rollups, token pruning.
- Failed jobs: retry with exponential backoff; dead-letter review in admin.

### 9.11 Audit and activity

- Audit every admin write, publish, permission change, verification, and AI approval.
- Store actor, action, target, before/after diff, IP, user agent, timestamp.
- Audit log is append-only; no UI delete.

### 9.12 Feature flags

- Laravel Pennant for AI capabilities, PWA, maps, and risky rollouts.
- Flags exposed at `/api/v1/config/flags`.
- Every flag has an owner, description, and expiry date.

### 9.13 Rate limits

| Endpoint group | Limit |
|---|---|
| Login / OTP | 5/min per IP + per account |
| Register / password reset | 3/hour per IP |
| Search | 60/min per IP |
| Comments / reactions | 20/min per user |
| Uploads | 30/hour per user |
| AI requests | 10/hour per user; daily cost cap |
| Admin API | 300/min per admin |

### 9.14 AI integration

- `AIProvider` interface with OpenAI, Anthropic, and Gemini adapters.
- All calls queued with timeout, retry, and idempotency keys.
- Prompt templates versioned in the database.
- Every generation stores provider, model, prompt version, sources, tokens, cost, and latency.
- Output is a **draft**; publication requires human approval.
- Per-task and monthly cost caps; kill switch per capability.
- Source content is untrusted: sanitize, delimit, and never execute instructions from it.
- Evaluation set of 50 plant facts for regression testing before prompt changes.

---

## 10. Data & content plan

### 10.1 Core modeling decisions

| Decision | Choice |
|---|---|
| Taxon vs plant | `taxa` is the single source of truth; `plant_profiles` holds editorial content linked to a taxon |
| Names | Accepted name, basionym, synonyms, author citation, year, nomenclatural status |
| Ranks | Kingdom → species, plus subspecies, variety, form, hybrid, cultivar |
| Distribution | Per-country `establishment_means` (native/introduced/invasive), `occurrence_status` |
| Local names | Per language and region, with source |
| Evidence | Fact-level citations, not only profile-level |
| Media | License, attribution, creator, source URL required |
| Conservation | IUCN category + national status + source |
| Observations | Darwin Core-aligned fields for future GBIF publishing |

### 10.2 Seed content at launch

| Content | Target |
|---|---|
| Plant profiles | 300–500 Bangladesh species |
| Images | 3–5 per plant, licensed |
| Categories | 20–30 |
| Articles/guides | 30–50 |
| CMS pages | 10–15 |
| Glossary terms | 100 |
| Expert reviewers | 3–5 |

Content is produced in parallel with development, starting in Phase 0. A plant profile is not "done" until it has: scientific name + author, Bengali and English names, description, distribution, at least one image with license, and one source.

### 10.3 Translation workflow

```text
English draft → Bengali translation → bilingual review → expert check (facts)
→ editor approval → publish
```

- Translation completeness is tracked per field and shown in admin.
- Machine translation may assist but must be edited by a human.
- Bengali botanical terminology glossary is maintained and enforced.

### 10.4 Import pipeline

- CSV/Excel import with a dry-run validation report.
- Column mapping, duplicate detection, and a merge queue.
- Import is queued, resumable, and audited.
- Every imported row records source, license, and import date.

---

## 11. Security plan

| Area | Control |
|---|---|
| Standard | OWASP ASVS Level 2 |
| Transport | TLS 1.2+, HSTS, Cloudflare WAF |
| Headers | CSP with nonce, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, `frame-ancestors 'none'` |
| Auth | Short-lived JWT, rotating refresh, reuse detection, MFA for admins |
| Passwords | Argon2id, breach check, rate-limited reset |
| CSRF | Double-submit token on cookie-based endpoints |
| XSS | Server-side HTML sanitizing, React escaping, no `dangerouslySetInnerHTML` with untrusted input |
| SQL injection | Eloquent/query builder, no raw user input |
| SSRF | Allowlist outbound hosts; block private IP ranges |
| Uploads | MIME sniffing, extension allowlist, size limits, re-encode, EXIF strip, virus scan |
| Secrets | Server-side only; encrypted at rest; rotated quarterly |
| Dependencies | Composer audit, pnpm audit, Dependabot, Trivy |
| SAST/DAST | Semgrep in CI, OWASP ZAP on staging |
| Admin | MFA, IP allowlist (optional), `no-store`, audit log, separate audience |
| Backups | Encrypted, daily, 30-day retention, quarterly restore drill |
| Privacy | Consent, export, deletion, retention policy, PII scrubbing in logs |
| Legal | Terms, privacy, content license, medical/toxicity disclaimer, takedown process |

Pre-launch: external penetration test and remediation of all high/critical findings.

---

## 12. Testing & QA plan

| Layer | Tool | Scope | Target |
|---|---|---|---|
| Backend unit | Pest | Actions, services, value objects | 80% domain coverage |
| Backend feature | Pest | Every endpoint, auth, permissions | 100% of routes |
| Contract | OpenAPI schema validation | Request/response shape | Every endpoint |
| Frontend unit | Vitest + Testing Library | Components, hooks, utils | 70% |
| Frontend integration | MSW + Testing Library | Feature flows | Key features |
| E2E | Playwright | Register, login, search, plant view, favorite, comment, submit, admin publish, moderation | Critical paths |
| Accessibility | axe + manual | All pages and components | Zero critical issues |
| Visual | Playwright screenshots / Chromatic | Design system | No unintended diffs |
| Performance | Lighthouse CI | Public routes | Budgets in §8.9 |
| Localization | Pseudo-locale + bn snapshots | Layout and fallback | No overflow/truncation |
| Security | ZAP baseline + dependency audit | Staging | No high/critical |
| UAT | Scripted scenarios | Client acceptance | Sign-off per phase |

**Test data:** factories and seeders for a realistic mini-dataset (50 plants, 2 languages, 3 roles, 20 submissions).

**CI gates:** lint, static analysis, unit, feature, build, contract, E2E smoke, a11y, bundle size. A red gate blocks merge.

---

## 13. DevOps, environments & observability

### 13.1 Docker services (local)

```text
nginx        reverse proxy, TLS in production
web          React SSR (Node 22)
api          PHP-FPM 8.4
horizon      queue workers
scheduler    Laravel scheduler
mysql        8.4
redis        7.4
meilisearch  search
imgproxy     image variants
mailpit      local email capture
```

### 13.2 CI/CD pipeline

```text
push / PR
  → lint (Pint, ESLint, Prettier)
  → static analysis (Larastan, tsc)
  → unit + feature tests
  → build (frontend, Docker images)
  → contract + E2E smoke (ephemeral env)
  → security scan (Composer audit, pnpm audit, Trivy)
  → deploy staging
  → smoke tests
  → manual approval
  → deploy production (rolling)
  → post-deploy checks
```

- Migrations are backward-compatible (expand → migrate → contract).
- Rollback = redeploy previous image; migrations must not be destructive in the same release.
- Preview environments for frontend PRs where practical.

### 13.3 Monitoring and alerting

| Signal | Tool | Alert |
|---|---|---|
| Exceptions | Sentry | New issue, error-rate spike |
| Latency / traces | OpenTelemetry + Grafana | p95 above budget |
| Uptime | Better Stack | 2 consecutive failures |
| Queue depth | Horizon | > 1,000 jobs or > 10 min wait |
| Failed jobs | Horizon | Any spike |
| Disk / DB | Node + MySQL exporter | > 80% |
| Search health | Meilisearch/OpenSearch | Index unavailable or stale |
| AI cost | Custom metric | 80% of monthly cap |
| SSL / domain | Better Stack | 14 days before expiry |

### 13.4 Backups and disaster recovery

- MySQL: daily full + binlog; 30-day retention; encrypted.
- Media: versioned object storage; cross-region replication optional.
- Config/secrets: encrypted backup outside the app server.
- **RPO 24 h, RTO 4 h.**
- Restore drill every quarter; results recorded.

### 13.5 Runbooks (docs/)

- Deploy and rollback.
- Restore database and media.
- Reindex search.
- Rotate JWT keys and secrets.
- Disable an AI provider.
- Handle a security incident.
- Purge CDN after a bad publish.

---

## 14. Delivery plan

### 14.1 Phases

| Phase | Duration | Key outputs | Exit gate |
|---|---|---|---|
| **0 — Discovery & spikes** | 2 weeks | SRS, ERD v1, search spike, design brief, infra plan, permission matrix | Client signs SRS + ERD |
| **1 — Foundation** | 3 weeks | Repo, CI/CD, Docker, auth, RBAC, API envelope, error codes, audit base | Login + admin shell on staging |
| **2 — Design system & shell** | 3 weeks | Figma library, tokens, Storybook, layouts, navigation, i18n | Design system v1 approved |
| **3 — Botanical core** | 6 weeks | Taxonomy, plants, media, references, verification, import | 300 plants imported and browsable |
| **4 — CMS & page builder** | 4 weeks | Pages, 8 sections, menus, revisions, scheduling, preview | Editor publishes a page without code |
| **5 — Public site & SEO** | 5 weeks | Home, directory, detail, search, SSR, sitemap, OG images | Lighthouse + SEO checklist pass |
| **6 — Community** | 4 weeks | Profiles, comments, likes, favorites, submissions, moderation | Moderation E2E + UAT |
| **7 — AI assist** | 4 weeks | Source registry, draft assist, field suggestions, review queue, cost caps | Human approval flow + eval set |
| **8 — Hardening & launch** | 3 weeks | Performance, security, a11y, UAT, backups, runbooks, training | Launch checklist signed |

**Total:** 34 weeks of work; **≈30–34 calendar weeks** with parallel design and content work.

### 14.2 Critical path

```text
Phase 0 → Phase 1 → Phase 3 → Phase 5 → Phase 8
                 ↘ Phase 2 ↗        ↗
                  Phase 4 ─────────┘
Phase 6 and Phase 7 run in parallel after Phase 3.
```

### 14.3 Milestones

| Milestone | Week | Demo |
|---|---|---|
| M1 — Architecture signed | 2 | SRS, ERD, search decision |
| M2 — Admin login | 5 | Auth, roles, shell |
| M3 — Design system | 8 | Storybook + key screens |
| M4 — Botanical core | 14 | 300 plants, search, media |
| M5 — CMS | 18 | Page builder + publish |
| M6 — Public beta | 23 | SSR site, SEO, social previews |
| M7 — Community | 27 | Comments, submissions, moderation |
| M8 — AI assist | 31 | Draft + review queue |
| M9 — Launch | 34 | Production, runbooks, training |

### 14.4 Change control

- MVP scope is frozen at the end of Phase 0.
- New requests go to a backlog with impact estimate; approved changes adjust timeline or budget.
- Weekly demo + written status report (done, next, risks, decisions needed).

---

## 15. Team & responsibilities

### 15.1 Recommended team

| Role | Allocation | Main responsibility |
|---|---|---|
| Product manager / BA | 0.5 | Scope, priorities, client communication, UAT |
| Tech lead | 1.0 | Architecture, reviews, security, delivery |
| Backend engineer | 2.0 | API, domain modules, queues, search |
| Frontend engineer | 2.0 | SSR, admin, design system implementation |
| UI/UX designer | 1.0 (0.5 after week 12) | Research, wireframes, hi-fi, design system |
| QA engineer | 1.0 | Test plans, automation, regression, UAT |
| DevOps engineer | 0.3 | Infra, CI/CD, monitoring, backups |
| Content editor | 1.0 | Content plan, editorial QA, publishing |
| Writers / translators | 2.0 | bn/en content production |
| Botanical expert | 0.2 | Fact verification, taxonomy review |
| SEO specialist | 0.2 | Technical SEO, metadata, structured data |

### 15.2 RACI (summary)

| Area | Accountable | Responsible | Consulted |
|---|---|---|---|
| Scope & priorities | Client sponsor | PM | Tech lead |
| Architecture | Tech lead | Backend/Frontend leads | DevOps |
| UI/UX | Design lead | Designer | PM, Frontend |
| Content | Client content owner | Editor + writers | Botanical expert |
| Security | Tech lead | DevOps + Backend | External pen tester |
| Launch | Client sponsor | PM + Tech lead | All |

### 15.3 Lean team option

A team of 1 backend, 1 frontend, 1 designer (part-time), and 1 QA extends the timeline to **44–52 weeks** and increases key-person risk. It is viable only if the launch scope is reduced to Phases 0–5 (encyclopedia + CMS + SEO) and community/AI move to a second release.

---

## 16. Budget estimate

### 16.1 Infrastructure (indicative, USD/month)

| Service | Early stage | Growth |
|---|---|---|
| App servers (2 × 2 vCPU / 4 GB) | $40–80 | $150–300 |
| Managed MySQL (2 vCPU / 4 GB / 100 GB) | $50–100 | $150–300 |
| Managed Redis | $15–30 | $50–100 |
| Search (Meilisearch Cloud or 2-node OpenSearch) | $30–150 | $150–400 |
| Object storage + CDN (100 GB) | $10–40 | $50–150 |
| Email (SES) | $5–20 | $20–80 |
| SMS/OTP (10k/month) | $100–300 | $300–800 |
| AI providers | $100–500 | $500–2,000 |
| Monitoring/logging | $0–50 | $50–150 |
| **Total** | **≈ $350–1,270** | **≈ $1,400–4,600** |

### 16.2 One-time costs (excluding team)

| Item | Estimate |
|---|---|
| UI/UX design (6 weeks) | $6,000–15,000 |
| External penetration test | $3,000–8,000 |
| Legal (terms, privacy, content license) | $2,000–5,000 |
| Image licensing / content acquisition | $2,000–10,000 |
| Brand identity (if not provided) | $2,000–8,000 |

### 16.3 Team cost

Team cost depends on local rates and contract structure. As a planning unit, the recommended team is **≈ 70 person-months** over 34 weeks. The lean team is **≈ 40 person-months** over 44–52 weeks. Convert using the agreed rate card.

---

## 17. Risk register

| # | Risk | Likelihood | Impact | Mitigation | Owner |
|---|---|---|---|---|---|
| R-01 | Bengali search quality is poor | High | High | 1-week spike in Phase 0; OpenSearch fallback; transliteration table; relevance test set | Tech lead |
| R-02 | Content volume/licensing not ready at launch | High | High | Start content in Phase 0; 300-species target; license register; expert reviewers | Content owner |
| R-03 | Scope creep beyond MVP | High | High | Frozen MVP, change control, phased backlog | PM |
| R-04 | SSR adds operational complexity | Medium | Medium | React Router 7, CDN caching, runbooks, staging parity | DevOps |
| R-05 | AI accuracy/cost | Medium | Medium | Human review, eval set, caps, kill switch, provider fallback | Tech lead |
| R-06 | Poor performance on low-end Android | Medium | High | Budgets, image optimization, font subsetting, 3G tests | Frontend |
| R-07 | Legal liability for medicinal/toxicity content | Medium | High | Disclaimers, expert review, conservative claims, legal review | Client + editor |
| R-08 | SMS/OTP deliverability in Bangladesh | Medium | Medium | Local aggregator, fallback provider, email fallback | Backend |
| R-09 | Import data quality/duplicates | Medium | Medium | Dry-run validation, dedupe, merge tool, provenance | Backend |
| R-10 | Key-person dependency | Medium | Medium | Documentation, pairing, ADRs, code reviews | Tech lead |
| R-11 | Security incident | Low | High | ASVS L2, pen test, monitoring, incident runbook | Tech lead |
| R-12 | Taxonomy churn breaks URLs/SEO | Medium | Medium | Slug history + 301s, canonical strategy, change log | Backend + SEO |

---

## 18. Definition of done & acceptance criteria

### 18.1 Definition of done (every feature)

- [ ] Acceptance criteria met and demonstrated.
- [ ] API documented in OpenAPI; contract test passing.
- [ ] Backend feature tests + frontend tests added.
- [ ] Permission checks enforced server-side.
- [ ] Bengali and English strings complete; no hard-coded copy.
- [ ] Loading, empty, error, and permission-denied states implemented.
- [ ] Keyboard and screen-reader check passed.
- [ ] Responsive at 375 / 768 / 1280 / 1440 px.
- [ ] Performance budget respected.
- [ ] Audit log entry for writes.
- [ ] Storybook story for new components.
- [ ] No new high/critical security findings.
- [ ] Product owner accepts in staging.

### 18.2 Acceptance criteria template

```text
Feature: <name>
Given <context>
When <action>
Then <observable result>
And <non-functional requirement: performance, a11y, i18n, security>
```

### 18.3 Launch checklist

- [ ] 300+ verified plant profiles with sources and licensed images.
- [ ] bn/en complete for all launch surfaces.
- [ ] Search relevance meets the agreed thresholds.
- [ ] Lighthouse ≥ 90 performance, 100 a11y/SEO on key pages.
- [ ] OG previews verified on Facebook, X, LinkedIn, WhatsApp, Telegram.
- [ ] Sitemap submitted; robots correct; 404/410 handled.
- [ ] Pen test high/critical findings closed.
- [ ] Backups restored successfully in a drill.
- [ ] Monitoring, alerts, and runbooks live.
- [ ] Legal pages published.
- [ ] Admin team trained; handover documentation delivered.

---

## 19. Open questions for the client

1. Is **Bootstrap 5** contractually required, or may we use Tailwind + shadcn/ui? (D-02)
2. Do you have brand assets (logo, colors, typography) or should we create the identity?
3. What is the **launch content volume**, and who owns content production and translation?
4. What is the target **launch date** and budget envelope?
5. What is the **monthly AI budget**, and do you have provider preferences?
6. Do you have an **SMS gateway** account, or should we procure one?
7. Hosting region and data-residency preference (Bangladesh, Singapore, or global)?
8. Who owns the legal documents (terms, privacy, content license, medical disclaimer)?
9. Is **dark mode** required at launch?
10. Is **PWA/offline** required at launch or later?
11. Should the **public API** be available to third parties at launch?
12. Which **analytics** tool do you prefer (self-hosted vs GA4)?
13. Who are the **expert reviewers**, and what are their credentials?
14. Bengali numerals in the UI by default, or Western numerals?
15. Confirm **WCAG 2.2 AA** as the accessibility target.

---

## 20. Appendices

### Appendix A — Route map

**Public**

```text
/                         home (bn canonical /bn)
/bn, /en                  localized home
/plants                   directory
/plants/{slug}            plant profile
/categories               category index
/categories/{slug}        category listing
/taxonomy                 taxonomy browser
/families/{slug}          family page
/search                   search
/articles                 knowledge hub
/articles/{slug}          article
/community                community hub (Phase 6)
/identify                 identification (Later)
/plant-map                distribution map (Later)
/calendar                 seasonal calendar (Later)
/nurseries                nursery directory (Later)
/about, /contributors, /experts, /sources, /contact, /privacy, /terms
```

**Account**

```text
/account                  profile
/account/favorites        favorites
/account/collections      collections
/account/submissions      my submissions
/account/settings         preferences, language, security, devices
```

**Admin**

```text
/admin                    dashboard
/admin/plants             list
/admin/plants/{id}        editor
/admin/taxonomy
/admin/categories
/admin/countries
/admin/languages
/admin/media
/admin/sources
/admin/submissions
/admin/moderation
/admin/users
/admin/roles
/admin/cms/pages
/admin/cms/pages/{id}     page builder
/admin/cms/menus
/admin/seo
/admin/ai                 Phase 7
/admin/settings
/admin/audit
```

### Appendix B — API envelope and error codes

```json
{
  "data": [],
  "meta": {
    "request_id": "req_01HX...",
    "pagination": { "page": 1, "per_page": 25, "total": 412 }
  }
}
```

```json
{
  "errors": [
    { "code": "VALIDATION_FAILED", "message": "The slug has already been taken.", "field": "slug" }
  ],
  "meta": { "request_id": "req_01HX..." }
}
```

| Code | HTTP | Meaning |
|---|---|---|
| `VALIDATION_FAILED` | 422 | Field-level validation error |
| `UNAUTHENTICATED` | 401 | Missing or expired token |
| `FORBIDDEN` | 403 | Authenticated but not allowed |
| `NOT_FOUND` | 404 | Resource missing |
| `CONFLICT` | 409 | Duplicate or version conflict |
| `RATE_LIMITED` | 429 | Too many requests |
| `UNPROCESSABLE` | 422 | Business rule violation |
| `INTERNAL_ERROR` | 500 | Unexpected failure |

### Appendix C — Design tokens (sample)

```json
{
  "color": {
    "primary": { "900": "#0B3D2E", "700": "#14532D", "600": "#166534", "500": "#15803D" },
    "leaf": { "500": "#4D7C0F" },
    "sage": { "200": "#D9E5D6" },
    "cream": { "50": "#FBFAF6" },
    "stone": { "900": "#1C1917", "600": "#57534E", "300": "#D6D3D1" },
    "amber": { "500": "#D97706" },
    "danger": { "600": "#B91C1C" }
  },
  "font": {
    "display": { "en": "Fraunces", "bn": "Noto Serif Bengali" },
    "body": { "en": "Inter", "bn": "Hind Siliguri" }
  },
  "radius": { "sm": "4px", "md": "8px", "lg": "12px", "xl": "16px", "full": "999px" },
  "space": { "1": "4px", "2": "8px", "3": "12px", "4": "16px", "6": "24px", "8": "32px", "12": "48px", "16": "64px" },
  "breakpoint": { "sm": "640px", "md": "768px", "lg": "1024px", "xl": "1280px", "2xl": "1536px" }
}
```

### Appendix D — Docker services

```yaml
services:
  nginx:        # reverse proxy
  web:          # React SSR (Node 22)
  api:          # PHP-FPM 8.4
  horizon:      # queue workers
  scheduler:    # cron
  mysql:        # 8.4
  redis:        # 7.4
  meilisearch:  # search
  imgproxy:     # image variants
  mailpit:      # dev email
```

### Appendix E — CI pipeline stages

```text
lint → static analysis → unit → feature → build → contract
→ e2e smoke → security scan → deploy staging → smoke
→ manual approval → deploy production → post-deploy checks
```

### Appendix F — Phase 0 deliverables

1. Software Requirements Specification with requirement IDs.
2. ERD v1 and table specification.
3. Permission/role matrix.
4. Bengali search spike report and decision.
5. Design brief, sitemap, and user flows.
6. Infrastructure and cost plan.
7. Content production plan and license register.
8. Acceptance criteria and UAT plan.
9. Risk register and change-control process.
10. Signed MVP scope.

---

*End of document.*
