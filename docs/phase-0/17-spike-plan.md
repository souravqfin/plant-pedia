# Phase 0 Technical Spike Plan

**Version:** 1.0
**Date:** 26 November 2026
**Goal:** prove or disprove the riskiest technical assumptions before Phase 1.

Each spike has a fixed timebox, a dataset, a metric, and a written decision. A spike that does not produce a decision is not complete.

---

## S-01 — Bengali search engine

**Timebox:** 3 days
**Owner:** Tech lead

### Setup

1. Build a corpus of 2,000 Bangladeshi plant names:
   - Bengali common names.
   - English common names.
   - Scientific names.
   - Synonyms.
   - Banglish transliterations.
2. Build 200 queries across five categories:
   - Exact Bengali (40).
   - Prefix Bengali (40).
   - Typo Bengali (30).
   - Banglish (50).
   - Scientific/synonym (40).
3. Load the corpus into Meilisearch and OpenSearch.
4. Add a synonym file and a Banglish→Bengali expansion table.

### Metrics

| Metric | Target |
|---|---|
| Top-1 accuracy (exact) | ≥ 90% |
| Top-3 accuracy (exact) | ≥ 95% |
| Top-3 accuracy (typo) | ≥ 85% |
| Top-3 accuracy (Banglish) | ≥ 85% |
| p95 latency | ≤ 100 ms |
| Index size | ≤ 200 MB |

### Decision

- Meilisearch if all targets met.
- OpenSearch if Meilisearch misses any target.
- If both miss, add a transliteration service and re-test.

**Output:** `docs/phase-0/poc/search-report.md` + raw results CSV.

---

## S-02 — SSR public + admin behavior

**Timebox:** 2 days
**Owner:** Frontend lead

### Setup

1. Scaffold React Router 7 in framework mode with SSR.
2. Create a public route with a loader and `meta`.
3. Create an admin route with `clientLoader` and `no-store`.
4. Verify:
   - Public HTML contains title, OG tags, and content before JS.
   - Admin HTML is not cached and does not leak data.
   - Hydration has no mismatch warnings.
   - Route-level code splitting keeps admin code out of the public bundle.

### Decision

- One app with SSR everywhere, admin `no-store`; or
- Split build targets (public SSR, admin SPA).

**Output:** `docs/phase-0/poc/ssr-report.md` + bundle analysis.

---

## S-03 — JWT lifecycle

**Timebox:** 2 days
**Owner:** Backend lead

### Setup

1. Implement login → access (15 min) + refresh cookie (30 days).
2. Rotate refresh on use; store hashed with family ID.
3. Detect reuse and revoke the family.
4. Add CSRF double-submit on refresh/logout.
5. Add an admin audience with a shorter TTL.
6. Test: expiry, reuse, revocation, logout-all, concurrent tabs.

### Decision

- Confirm the flow and libraries; document edge cases.

**Output:** `docs/phase-0/poc/auth-report.md` + tests.

---

## S-04 — Upload and image pipeline

**Timebox:** 1 day
**Owner:** Backend lead

### Setup

1. Request a presigned upload URL.
2. Upload to S3/R2.
3. Validate MIME, size, and dimensions.
4. Strip EXIF and re-encode.
5. Generate variants via imgproxy.
6. Serve through the CDN with cache headers.

### Decision

- Confirm storage provider, key format, and variant sizes.

**Output:** `docs/phase-0/poc/media-report.md`.

---

## S-05 — i18n and Bengali fonts

**Timebox:** 1 day
**Owner:** Frontend lead

### Setup

1. Locale-prefixed routes for bn/en.
2. `hreflang` and canonical tags.
3. Font subsetting and preload.
4. Bengali line-height and numeral formatting.
5. Missing-key fallback.

### Decision

- Confirm font files, subset size, and CLS impact.

**Output:** `docs/phase-0/poc/i18n-report.md`.

---

## S-06 — Baseline load test

**Timebox:** 1 day
**Owner:** DevOps + backend

### Setup

1. Seed 300 plants.
2. Load-test plant list and detail endpoints.
3. Measure p50/p95/p99 at 100 and 500 concurrent users.
4. Check DB query count and cache hit ratio.

### Decision

- Confirm launch capacity assumptions and the first bottleneck.

**Output:** `docs/phase-0/poc/load-report.md`.

---

## Spike board

| Spike | Days | Owner | Status | Decision |
|---|---|---|---|---|
| S-01 Bengali search | 3 | TL | ⬜ | — |
| S-02 SSR/admin | 2 | FE | ⬜ | — |
| S-03 JWT | 2 | BE | ⬜ | — |
| S-04 Media | 1 | BE | ⬜ | — |
| S-05 i18n/fonts | 1 | FE | ⬜ | — |
| S-06 Load | 1 | DO | ⬜ | — |
| **Total** | **10** | | | |

Spikes run in parallel where possible; all must close before Phase 1 exit.

---

*End of spike plan.*
