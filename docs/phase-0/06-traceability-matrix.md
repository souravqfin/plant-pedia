# Requirements Traceability Matrix

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

Purpose: prove that every requirement has a screen, an API endpoint, and a test. A requirement without all three is not planned — it is a wish.

---

## 1. Test ID scheme

```text
T-<AREA>-<number>

T-AUTH-001   authentication test 001
T-PLT-014    plant test 014
T-CMS-006    CMS test 006
T-SEO-003    SEO test 003
```

Areas: `AUTH`, `LOC`, `GEO`, `TAX`, `PLT`, `MED`, `CMS`, `SEO`, `SRC`, `COM`, `MOD`, `SUB`, `AI`, `ADM`, `API`, `NFR`.

---

## 2. Matrix

| Requirement group | Screens | API | Tests | Phase |
|---|---|---|---|---|
| FR-AUTH-001…007 (register, verify, login, refresh, logout, reset) | Login, Register, Verify, Reset, Account settings | `/auth/*` | `T-AUTH-001…020` | 1 |
| FR-AUTH-008…012 (OTP, Google, MFA, sessions, export/delete) | OTP, Account security, Admin MFA | `/auth/otp/*`, `/auth/sessions`, `/account` | `T-AUTH-021…040` | 1, 7 |
| FR-LOC-001…008 (bn/en, URLs, translations, fallback, preference) | All public + admin | `Accept-Language`, `/config` | `T-LOC-001…020` | 1, 2, 6 |
| FR-GEO-001…005 (countries, regions, distribution, sensitivity) | Admin countries, Plant detail map/list | `/admin/countries`, `/plants/{slug}` | `T-GEO-001…015` | 4 |
| FR-TAX-001…007 (ranks, tree, names, author, identifiers, history) | Admin taxonomy, Plant detail taxonomy | `/admin/taxa`, `/taxa/{id}` | `T-TAX-001…025` | 4 |
| FR-PLT-001…010 (profiles, translations, names, categories, care, seasonality, conservation, slugs) | Plant directory, Plant detail, Admin plant editor | `/plants`, `/admin/plants` | `T-PLT-001…040` | 3, 4, 6 |
| FR-MED-001…005 (upload, variants, license, evidence, verification) | Admin media, Plant gallery, Source panel | `/admin/media`, `/plants/{slug}` | `T-MED-001…020` | 4 |
| FR-CMS-001…007 (pages, sections, revisions, schedule, preview, menus, templates) | Admin CMS, Page builder, Public pages | `/admin/pages`, `/pages/{slug}` | `T-CMS-001…025` | 5 |
| FR-SEO-001…008 (metadata, OG, sitemap, hreflang, JSON-LD, redirects, canonical, OG image) | Public pages, Admin SEO | `/pages/{slug}`, `/sitemap.xml` | `T-SEO-001…020` | 5, 6 |
| FR-SRC-001…007 (search, facets, autocomplete, typo, transliteration, synonyms, analytics) | Search, Plant directory | `/search`, `/search/suggest` | `T-SRC-001…025` | 4, 6 |
| FR-COM-001…006 (comments, reactions, favorites, collections, submissions, notifications) | Plant detail, Account, Community | `/comments`, `/reactions`, `/account/*` | `T-COM-001…025` | 7 |
| FR-MOD-001…005 (reports, queue, actions, audit, appeals) | Admin moderation | `/admin/moderation` | `T-MOD-001…015` | 7 |
| FR-SUB-001…004 (workflow, revisions, notes, expert) | Admin submissions, Account submissions | `/admin/submissions` | `T-SUB-001…015` | 7 |
| FR-AI-001…008 (sources, ingestion, matching, drafts, suggestions, review, cost, kill switch) | Admin AI research, Review queue | `/admin/ai/*` | `T-AI-001…020` | 8 |
| FR-ADM-001…006 (dashboard, users, audit, settings, bulk, import/export) | Admin shell + modules | `/admin/*` | `T-ADM-001…020` | 1, 4, 5 |
| FR-API-001…005 (versioning, docs, rate limits, idempotency, contracts) | — | All | `T-API-001…010` | 1, 4 |
| NFR-PERF-001…005 | Public pages | — | `T-NFR-PERF-001…010` | 6, 9 |
| NFR-SEC-001…003 | All | — | `T-NFR-SEC-001…010` | 1, 9 |
| NFR-A11Y-001 | All | — | `T-NFR-A11Y-001…010` | 2, 6, 9 |
| NFR-I18N-001 | All | — | `T-NFR-I18N-001…010` | 1, 6 |
| NFR-SEO-001 | Public | — | `T-NFR-SEO-001…005` | 6 |
| NFR-AVAIL/BACKUP/SCALE | Infra | — | `T-NFR-OPS-001…010` | 1, 9 |

---

## 3. Coverage rules

1. Every FR has at least one feature test.
2. Every public screen has an E2E happy path.
3. Every write endpoint has validation, auth, and forbidden tests.
4. Every NFR has a measurable check in CI or a documented manual test.
5. A requirement is marked **covered** only when its test exists and passes in staging.

---

## 4. Known gaps to close in Phase 0

| Gap | Action | Owner |
|---|---|---|
| Search quality thresholds not yet measured | Complete Bengali spike | TL |
| Content volume not committed | Sign content plan | PM + client |
| AI accuracy not measured | Build 50-fact eval set | TL |
| Mobile app contract not validated | Out of scope for v1 | PM |
| Legal/compliance tests not defined | Legal review | Client |

---

*End of traceability matrix.*
