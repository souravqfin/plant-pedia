# Stack Compatibility Matrix

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

Purpose: pin versions, record constraints, and flag compatibility risks before scaffolding.

---

## 1. Runtime

| Component | Target | Constraint | Status |
|---|---|---|---|
| PHP | 8.3+ (8.5 tested) | Laravel 13.44 requires `^8.3` | ✓ |
| Composer | 2.7+ | — | ✓ |
| Node.js | 22 LTS | SSR server and build | ✓ |
| npm | 10+ | Package manager | ✓ |
| MySQL | 8.4 LTS | `utf8mb4`, InnoDB, spatial | ✓ |
| Redis | 7.4 | Cache, queues, sessions | ✓ |
| Nginx | 1.27 | Reverse proxy | ✓ |
| Docker | 27+ / Compose v2 | Local and prod parity | ✓ |

---

## 2. Backend

| Package | Target | Notes |
|---|---|---|
| `laravel/framework` | 13.x — latest stable **13.44.1** (24 Nov 2026) | PHP `^8.3`; pin via `composer.lock` |
| `lcobucci/jwt` | 5.x | JWT signing and validation |
| `spatie/laravel-permission` | 6.x | Roles and permissions |
| `spatie/laravel-medialibrary` | 11.x | Media management |
| `spatie/laravel-activitylog` | 4.x | Audit log |
| `laravel/pennant` | 1.x | Feature flags |
| `laravel/horizon` | 6.x | Queue dashboard |
| `dedoc/scramble` | 0.12+ | OpenAPI generation |
| `mews/purifier` | 3.x | HTML sanitizing |
| `intervention/image` | 3.x | Image processing |
| `pestphp/pest` | 3.x | Tests |
| `larastan/larastan` | 3.x | Static analysis |
| `laravel/pint` | 1.x | Code style |

**Policy:** track the latest stable 13.x line. `composer.lock` pins the exact build for reproducibility. Update monthly after a regression run; a major version (14.x) requires an ADR. Laravel 13.44.1 declares `php: ^8.3`, so PHP 8.5 is supported.

---

## 3. Frontend

| Package | Target | Notes |
|---|---|---|
| `react` / `react-dom` | 19.x | Concurrent features |
| `typescript` | 5.7+ | Strict mode |
| `vite` | 7.x | Build and dev server |
| `react-router` | 7.x | Framework mode, SSR |
| `@tanstack/react-query` | 5.x | Server state |
| `@tanstack/react-table` | 8.x | Admin tables |
| `react-hook-form` | 7.x | Forms |
| `zod` | 3.x/4.x | Validation |
| `tailwindcss` | 4.x | Styling |
| `shadcn/ui` | latest | Components (Tailwind 4 compatible) |
| `@radix-ui/*` | latest | Primitives |
| `class-variance-authority` | 0.7+ | Variants |
| `lucide-react` | latest | Icons |
| `@tiptap/react` | 3.x | Rich text |
| `recharts` | 2.x/3.x | Charts |
| `maplibre-gl` | 4.x/5.x | Maps |
| `i18next` / `react-i18next` | 23.x/15.x | i18n |
| `zustand` | 5.x | UI state |
| `sonner` | 2.x | Toasts |
| `storybook` | 9.x | Component docs |
| `vitest` | 3.x | Unit tests |
| `@testing-library/react` | 16.x | Component tests |
| `msw` | 2.x | API mocking |
| `playwright` | 1.5x | E2E |

**Risks:**
- Storybook 9 + React 19: verify addon compatibility.
- Tailwind 4 + shadcn: use the Tailwind 4 preset; verify theming.
- React Router 7 framework mode: verify SSR behavior for admin routes.
- TipTap 3: verify Bengali IME input.

---

## 4. Services

| Service | Target | Notes |
|---|---|---|
| Meilisearch | 1.x | Bengali spike required |
| OpenSearch | 2.x | Fallback with `bengali` analyzer |
| imgproxy | 3.x | Image variants |
| Cloudflare | — | CDN, WAF, DNS |
| Amazon SES | — | Transactional email |
| Local SMS gateway | — | OTP delivery |
| Sentry | — | Error tracking |
| OpenTelemetry | — | Traces/metrics |

---

## 5. Verification method

Each risky combination gets a 1-day proof in Phase 0:

1. `php -v` + Laravel installer + `artisan about`.
2. React Router 7 SSR build + admin route behavior.
3. Tailwind 4 + shadcn button/input render.
4. TipTap Bengali input + save.
5. Storybook 9 build with React 19.
6. Meilisearch vs OpenSearch Bengali query set.
7. imgproxy variant generation from R2/S3.

A combination is "verified" only when the proof is committed to `docs/phase-0/poc/`.

---

## 6. Pinning rules

- `composer.lock` and `package-lock.json` are committed.
- Exact versions in Docker images.
- Renovate/Dependabot opens grouped monthly PRs.
- Major upgrades require an ADR and a regression run.

---

*End of compatibility matrix.*
