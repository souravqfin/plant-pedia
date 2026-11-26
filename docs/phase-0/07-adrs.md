# Architecture Decision Records (ADRs)

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

Format: status, context, decision, consequences, alternatives.

---

## ADR-001 — SSR public site + CSR admin in one React app

**Status:** Accepted
**Context:** Public pages need crawlable metadata and social previews. Admin is authenticated, data-dense, and does not need SEO. The client requires one React codebase.

**Decision:** Use React Router 7 framework mode with SSR enabled. Public routes render on the server; admin and account routes use client-side data loading and are served with `no-store` and `noindex`.

**Consequences:**
- A Node SSR runtime joins the production topology.
- Public HTML is cacheable at the CDN; admin is never cached.
- Route-level code splitting keeps admin code out of public bundles.
- If admin bundle isolation becomes a problem, split a second build target later (documented escape hatch).

**Alternatives:** Next.js (adds RSC and a second routing model); two separate apps (duplicates the design system); client-only SPA (fails social previews).

---

## ADR-002 — Tailwind CSS + shadcn/ui instead of Bootstrap 5

**Status:** Proposed — client sign-off required
**Context:** The master plan names Bootstrap 5. The product needs a distinctive botanical brand, accessible primitives, and predictable React behaviour. Bootstrap's JS plugins conflict with React state.

**Decision:** Use Tailwind CSS 4, shadcn/ui, and Radix primitives, with tokens exported from Figma.

**Consequences:**
- Stronger design control and smaller CSS.
- Team must be fluent in Tailwind.
- If the client requires Bootstrap, fall back to React-Bootstrap + SCSS tokens; the rest of the plan is unchanged.

**Alternatives:** Bootstrap 5 + React-Bootstrap (faster CRUD, weaker brand control); MUI (heavy, opinionated); CSS Modules (slower to build a system).

---

## ADR-003 — JWT access token + rotating refresh cookie

**Status:** Accepted
**Context:** The client requires JWT. The web app is first-party; mobile and external clients are future.

**Decision:** Short-lived access JWT (15 min) held in memory. Refresh token (30 days) in an `HttpOnly; Secure; SameSite=Lax` cookie, rotated on every use, stored hashed with a family ID. CSRF double-submit on cookie endpoints. Admin uses a separate audience, MFA, and shorter TTLs.

**Consequences:**
- No token in `localStorage`.
- Refresh reuse revokes the family.
- Mobile uses secure storage and no CSRF.

**Alternatives:** Sanctum cookie sessions (no clean mobile token story); access token in localStorage (XSS risk); long-lived JWT (revocation is hard).

---

## ADR-004 — MySQL 8.4 LTS

**Status:** Accepted
**Context:** The master plan specifies MySQL. The data is mostly relational with modest geospatial needs.

**Decision:** MySQL 8.4 LTS, InnoDB, `utf8mb4`, spatial indexes where needed.

**Consequences:**
- Geospatial queries use `ST_*` functions; less rich than PostGIS.
- If maps become a core product, evaluate PostgreSQL + PostGIS in a later ADR.

**Alternatives:** PostgreSQL + PostGIS (better geospatial, stack change); MariaDB (no clear benefit).

---

## ADR-005 — Search engine selected by spike

**Status:** Open — decision gate in Phase 0
**Context:** Bengali tokenization and transliteration are critical. Meilisearch is in the master plan but has limited Bengali stemming. Lucene/OpenSearch has a native Bengali analyzer.

**Decision:** Run a 1-week spike on a 2,000-name corpus with 200 queries. Choose Meilisearch if it meets top-1 ≥ 90% and top-3 ≥ 95%; otherwise OpenSearch.

**Consequences:**
- Architecture cannot be finalized until the spike closes.
- The search adapter is isolated behind an interface so the engine can change.

**Alternatives:** Meilisearch (simple ops), OpenSearch (better Bengali, heavier ops), Typesense (fast, weaker language tooling), Algolia (cost, data residency).

---

## ADR-006 — Modular monolith

**Status:** Accepted
**Context:** The domain is broad but the team is small. Microservices would add operational cost without a proven scaling need.

**Decision:** One Laravel application with domain modules and shared services.

**Consequences:**
- Simpler deploys, transactions, and debugging.
- Module boundaries must be enforced by review and tests.
- A module can be extracted later if a scaling need appears.

**Alternatives:** Microservices (premature); single-layer MVC (becomes unmaintainable).

---

## ADR-007 — Media pipeline: presigned upload → object storage → imgproxy

**Status:** Accepted
**Context:** Botanical content is image-heavy; uploads must not pass through PHP memory; variants are needed for cards, detail, and OG images.

**Decision:** Presigned uploads to S3-compatible storage, queued validation and EXIF stripping, imgproxy for on-the-fly variants behind the CDN.

**Consequences:**
- Lower app-server load.
- Requires storage credentials and imgproxy operations.
- Virus scanning applies to non-image files.

**Alternatives:** Upload through Laravel (memory pressure); store on local disk (no CDN, poor scaling).

---

## ADR-008 — Locale-prefixed URLs

**Status:** Accepted
**Context:** Bengali and English content must be indexable and shareable without duplicate-content penalties.

**Decision:** `/bn/...` and `/en/...` for all content. Default is Bengali; `/` renders Bengali with canonical `/bn`. `hreflang` includes `bn-BD`, `en`, and `x-default` → `bn`.

**Consequences:**
- Clear canonical URLs.
- Language switcher must preserve the path.
- Cookies store preference; no geo-redirect chains.

**Alternatives:** Subdomains (more DNS/TLS work); query parameter (weak SEO); cookie-only (bad for sharing and indexing).

---

## ADR-009 — Typed, validated page-builder sections

**Status:** Accepted
**Context:** Editors need layout flexibility without arbitrary HTML or executable code.

**Decision:** A fixed set of section types, each with a JSON schema validated on save and render. Eight types at launch.

**Consequences:**
- Safe rendering and predictable design.
- New section types require code + schema + tests.
- Content is portable and diffable.

**Alternatives:** Free HTML (XSS risk); block editor with arbitrary React (security and consistency risk).

---

## ADR-010 — AI is assistive, never auto-publishing

**Status:** Accepted
**Context:** Botanical claims carry scientific and legal risk. AI can hallucinate references and occurrence data.

**Decision:** AI output is always a draft. Human review is mandatory. Every generation stores provider, model, prompt version, sources, cost, and latency. Per-capability kill switch.

**Consequences:**
- Slower content pipeline, higher trust.
- Provider abstraction prevents lock-in.
- Cost caps and evaluation sets are required.

**Alternatives:** Auto-publish with post-review (unacceptable risk); no AI (slower but simpler).

---

## ADR-011 — Docker Compose deployment for v1

**Status:** Accepted
**Context:** The team is small and the launch load is modest. Kubernetes would add cost and complexity.

**Decision:** Docker Compose on two VPS instances with managed MySQL and Redis, Cloudflare in front.

**Consequences:**
- Simple, reproducible deploys.
- Manual scaling until traffic justifies orchestration.
- Clear path to Kubernetes later.

**Alternatives:** Kubernetes (premature); serverless (cold starts, PHP fit); PaaS (less control, higher cost at scale).

---

## ADR-012 — Testing strategy: Pest + Vitest + Playwright

**Status:** Accepted
**Context:** The product spans API, SSR, admin, and bilingual content.

**Decision:** Pest for backend, Vitest + Testing Library for frontend, Playwright for critical journeys, axe for accessibility, Lighthouse CI for performance.

**Consequences:**
- Fast unit feedback plus realistic E2E.
- CI time must be managed with sharding.
- Contract tests prevent API drift.

**Alternatives:** Cypress (heavier); Jest (slower with Vite); manual QA only (unacceptable).

---

## ADR-013 — Admin API namespace `/api/admin/v1`

**Status:** Accepted
**Context:** The master plan used `/internal/admin/`, which implies network isolation the browser cannot provide.

**Decision:** Use `/api/admin/v1`, protected by audience, MFA, permissions, and `no-store`.

**Consequences:**
- Honest naming; versioned.
- Separate OpenAPI documentation build.

**Alternatives:** `/internal/admin` (misleading); same namespace with permission checks (harder to secure and document).

---

*End of ADRs.*
