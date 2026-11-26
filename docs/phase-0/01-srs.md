# Software Requirements Specification (SRS)

**Project:** Plant Pedia
**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026
**Status:** Draft for client sign-off
**Related:** `plant_pedia_complete_project_plan.md`, `plant_pedia_ui_tech_plan.md`

---

## 1. Introduction

### 1.1 Purpose

This SRS defines the functional and non-functional requirements for the first public release of Plant Pedia, a Bangladesh-first, bilingual botanical encyclopedia and community platform.

### 1.2 Scope

In scope for v1:

- Public botanical encyclopedia (plants, taxonomy, categories, articles).
- Bengali and English.
- Dynamic CMS pages and a page builder.
- Search.
- User accounts, comments, reactions, favorites, submissions.
- Moderation and expert verification.
- AI-assisted research drafts with human approval.
- Admin panel.
- SEO, server-side rendering, social previews.

Out of scope for v1: marketplace, payments, native mobile apps, AI plant identification, multi-country admin UI, third-party public API billing.

### 1.3 Definitions

| Term | Meaning |
|---|---|
| Taxon | A taxonomic entity at any rank (family, genus, species, etc.) |
| Plant profile | Editorial content attached to a taxon |
| Accepted name | The currently correct scientific name |
| Synonym | A name no longer accepted for the taxon |
| Establishment means | Native, introduced, invasive, casual, etc. |
| Fact-level citation | A source attached to a specific claim, not the whole page |

### 1.4 Priority

| Code | Meaning |
|---|---|
| **M** | Must have for launch |
| **S** | Should have for launch if time allows |
| **C** | Could have; post-launch |

---

## 2. Product overview

Plant Pedia helps people discover, identify, understand, and care for plants. It launches with Bangladesh-focused content in Bengali and English, with a global-ready data model.

Primary users: curious visitors, home gardeners, students, contributors, botanists, editors, moderators, administrators.

---

## 3. Users and roles

| Role | Description |
|---|---|
| Guest | Unauthenticated visitor |
| Registered user | Can comment, react, favorite, report |
| Contributor | Can submit plants, names, photos, corrections |
| Author | Can write and manage own articles |
| Reviewer | Reviews submissions |
| Botanical expert | Verifies botanical claims |
| Moderator | Moderates community content |
| Editor | Edits and publishes content |
| Country manager | Manages assigned country content |
| Administrator | Manages platform modules |
| Super admin | Full control |

---

## 4. Functional requirements

### 4.1 Authentication and accounts

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-AUTH-001 | Register with email and password | M | Valid email + 12-char password creates an unverified account |
| FR-AUTH-002 | Verify email via signed link | M | Link expires in 24 h; verified state shown in admin |
| FR-AUTH-003 | Log in and receive access + refresh tokens | M | Access token works for 15 min; refresh rotates |
| FR-AUTH-004 | Refresh access token | M | Reuse of an old refresh token revokes the family |
| FR-AUTH-005 | Log out current session | M | Refresh token revoked; access token rejected |
| FR-AUTH-006 | Log out all sessions | M | All refresh families revoked |
| FR-AUTH-007 | Reset password | M | Single-use token; all sessions revoked on success |
| FR-AUTH-008 | Register/login with phone OTP | S | OTP expires in 5 min; rate-limited |
| FR-AUTH-009 | Google social login | S | Account linked by verified email |
| FR-AUTH-010 | MFA for administrators | M | TOTP + recovery codes; cannot access admin without MFA |
| FR-AUTH-011 | Session/device list and revoke | S | User sees device, IP, last active; can revoke |
| FR-AUTH-012 | Export and delete account | S | Export JSON; deletion anonymizes authored content |

### 4.2 Localization

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-LOC-001 | Bengali and English UI | M | Every string exists in both locales |
| FR-LOC-002 | Locale-prefixed URLs | M | `/bn/...` and `/en/...`; canonical set |
| FR-LOC-003 | Translate plant, article, page content | M | Each translatable field has per-locale values |
| FR-LOC-004 | Fallback to English | M | Missing bn value falls back and is labelled |
| FR-LOC-005 | User language preference | S | Saved to profile and cookie |
| FR-LOC-006 | Admin translation management | M | Completeness matrix; edit and import/export |
| FR-LOC-007 | Add/disable languages | C | Schema-ready; UI post-launch |
| FR-LOC-008 | Bengali dates, numbers, currency | M | `Intl` formatting with `bn-BD` |

### 4.3 Geography

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-GEO-001 | Manage countries | M | Add, enable/disable, set default |
| FR-GEO-002 | Manage regions (division/district/upazila) | M | Bangladesh tree seeded |
| FR-GEO-003 | Record plant distribution | M | Per country/region with source |
| FR-GEO-004 | Establishment means | M | Native/introduced/invasive/casual recorded |
| FR-GEO-005 | Mask sensitive locations | M | Coordinates fuzzed or hidden for protected species |

### 4.4 Taxonomy

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-TAX-001 | Configurable taxonomic ranks | M | Kingdom → form, plus hybrid/cultivar |
| FR-TAX-002 | Taxon tree with parent/child | M | No cycles; move is audited |
| FR-TAX-003 | Accepted names, basionyms, synonyms | M | One accepted name per taxon |
| FR-TAX-004 | Author citation, year, status | M | Displayed on the plant page |
| FR-TAX-005 | External identifiers | M | IPNI, POWO, GBIF, WFO |
| FR-TAX-006 | Taxonomy change history | M | Every rename/reparent is logged |
| FR-TAX-007 | Hybrid and cultivar support | S | Flags and rank support |

### 4.5 Plants

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-PLT-001 | Plant profile with botanical fields | M | All fields in master plan §8.2 available |
| FR-PLT-002 | Bengali/English profile translations | M | Both locales editable |
| FR-PLT-003 | Common and local names | M | Per language and region, with source |
| FR-PLT-004 | Categories (many-to-many) | M | A plant can have multiple categories |
| FR-PLT-005 | Botanical attributes | M | Leaves, flowers, fruit, bark, habit |
| FR-PLT-006 | Care and growing requirements | M | Light, water, soil, temperature |
| FR-PLT-007 | Flowering/fruiting season | M | Month ranges per region |
| FR-PLT-008 | Conservation status | M | IUCN + national status + source |
| FR-PLT-009 | Slug history and redirects | M | Old URL 301s to new URL |
| FR-PLT-010 | Related plants | S | Manual and taxon-based suggestions |

### 4.6 Media and evidence

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-MED-001 | Upload images with validation | M | Type/size checked; EXIF stripped |
| FR-MED-002 | Responsive variants | M | AVIF/WebP + fallback |
| FR-MED-003 | License and attribution | M | Required before publish |
| FR-MED-004 | Fact-level citations | M | Each key fact can reference a source |
| FR-MED-005 | Verification states | M | Unverified → community → editor → expert |

### 4.7 CMS and page builder

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-CMS-001 | Create/edit/delete pages | M | Slug, template, locale, status |
| FR-CMS-002 | Reusable typed sections | M | 8 section types at launch |
| FR-CMS-003 | Revisions and rollback | M | Every publish creates a revision |
| FR-CMS-004 | Scheduled publishing | M | Publishes at the set time in Asia/Dhaka |
| FR-CMS-005 | Preview before publish | M | Signed preview URL |
| FR-CMS-006 | Menus and navigation | M | Per locale, drag-and-drop |
| FR-CMS-007 | Templates | S | Landing, article, legal, contact |

### 4.8 SEO and social sharing

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-SEO-001 | Per-page title/description | M | Editable with defaults |
| FR-SEO-002 | Open Graph + Twitter cards | M | Server-rendered in HTML |
| FR-SEO-003 | Sitemap index + image sitemap | M | Generated and submitted |
| FR-SEO-004 | hreflang alternates | M | bn-BD, en, x-default |
| FR-SEO-005 | Structured data | M | Organization, WebSite, Breadcrumb, Article, Taxon |
| FR-SEO-006 | Redirect manager | M | 301/302 with hit counts |
| FR-SEO-007 | Canonical URLs | M | Every public page |
| FR-SEO-008 | OG image generation | S | 1200×630 with plant name + logo |

### 4.9 Search

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-SRC-001 | Search names and taxonomy | M | Bengali, English, scientific, synonyms |
| FR-SRC-002 | Filters and facets | M | Family, category, region, light, water |
| FR-SRC-003 | Autocomplete | M | Suggestions within 200 ms |
| FR-SRC-004 | Typo tolerance | M | Common misspellings resolve |
| FR-SRC-005 | Banglish transliteration | S | "am" finds "আম" |
| FR-SRC-006 | Synonym-aware matching | M | Old names resolve to accepted taxon |
| FR-SRC-007 | Search analytics | S | Zero-result and top-query reports |

### 4.10 Community

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-COM-001 | Comments and replies | M | Nested one level; edit/delete own |
| FR-COM-002 | Reactions | M | One per user per target; like/helpful |
| FR-COM-003 | Favorites | M | Toggle; visible in account |
| FR-COM-004 | Collections | S | Named folders of plants |
| FR-COM-005 | Submissions | M | New plant, name, photo, correction |
| FR-COM-006 | Notifications | S | Email for replies, submissions, moderation |

### 4.11 Moderation

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-MOD-001 | Report content | M | Reason required; reporter tracked |
| FR-MOD-002 | Moderation queue | M | Filter by type, age, severity |
| FR-MOD-003 | Actions | M | Approve, reject, hide, warn, suspend, ban |
| FR-MOD-004 | Audit trail | M | Every action logged with reason |
| FR-MOD-005 | Appeals | S | User can appeal a suspension |

### 4.12 Submissions and verification

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-SUB-001 | Submission workflow states | M | draft → submitted → review → approved/rejected |
| FR-SUB-002 | Revision history | M | Every change retained |
| FR-SUB-003 | Reviewer notes | M | Internal notes not shown publicly |
| FR-SUB-004 | Expert verification | M | Scoped to claims, not whole pages |

### 4.13 AI assist

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-AI-001 | Source registry | M | License, ToS date, rate limit |
| FR-AI-002 | Ingestion jobs | S | Queued, retried, logged |
| FR-AI-003 | Existing-plant matching | S | Deterministic signals + review |
| FR-AI-004 | Draft generation | S | bn/en, clearly marked as draft |
| FR-AI-005 | Field-level suggestions | S | Before/after + sources |
| FR-AI-006 | Human review queue | M | No auto-publish |
| FR-AI-007 | Cost and token tracking | S | Per task and per month |
| FR-AI-008 | Kill switch | M | Disable per capability instantly |

### 4.14 Admin

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-ADM-001 | Dashboard with real metrics | M | No hard-coded numbers |
| FR-ADM-002 | User and role management | M | Assign roles; audit changes |
| FR-ADM-003 | Audit log with diffs | M | Filter by actor, action, date |
| FR-ADM-004 | Settings | M | Site, SEO, email, feature flags |
| FR-ADM-005 | Bulk actions | M | Multi-select approve/reject/delete |
| FR-ADM-006 | Import/export | M | CSV with dry-run and report |

### 4.15 API

| ID | Requirement | Priority | Acceptance criteria |
|---|---|---|---|
| FR-API-001 | Versioned REST API | M | `/api/v1`, `/api/admin/v1` |
| FR-API-002 | OpenAPI documentation | M | Generated and current |
| FR-API-003 | Rate limiting | M | Per user/IP; headers returned |
| FR-API-004 | Idempotency | S | `Idempotency-Key` on POST |
| FR-API-005 | Contract tests | M | CI fails on schema drift |

---

## 5. Non-functional requirements

| ID | Requirement | Target |
|---|---|---|
| NFR-PERF-001 | LCP on mid-range Android / 3G | ≤ 2.5 s |
| NFR-PERF-002 | CLS | < 0.1 |
| NFR-PERF-003 | INP | < 200 ms |
| NFR-PERF-004 | API p95 read / write | ≤ 300 ms / ≤ 800 ms |
| NFR-PERF-005 | Initial public JS | ≤ 180 KB gzip |
| NFR-SEC-001 | Security standard | OWASP ASVS Level 2 |
| NFR-SEC-002 | Admin MFA | Mandatory |
| NFR-SEC-003 | Audit coverage | All writes and sensitive reads |
| NFR-A11Y-001 | Accessibility | WCAG 2.2 AA |
| NFR-I18N-001 | Languages | Bengali + English, no missing keys |
| NFR-SEO-001 | Crawlable metadata | Server-rendered HTML |
| NFR-AVAIL-001 | Uptime | 99.9% monthly |
| NFR-BACKUP-001 | RPO / RTO | 24 h / 4 h |
| NFR-MAINT-001 | Domain test coverage | ≥ 80% |
| NFR-COMP-001 | Privacy | Export/delete, consent, retention |
| NFR-SCALE-001 | Concurrent users at launch | 500 concurrent, 50k daily |

---

## 6. Out of scope for v1

- Marketplace, payments, commissions, subscriptions, advertising.
- Native iOS/Android apps.
- AI plant identification.
- Multi-country admin UI.
- Third-party public API plans and billing.
- Plant map and seasonal calendar (v1.1).
- Nursery directory and reviews (v1.1).
- PWA/offline (v1.1).

---

## 7. Assumptions and dependencies

1. Client provides or approves brand assets.
2. Client provides a Bangladesh SMS gateway account or approves procurement.
3. Content team produces 300 verified profiles before launch.
4. Data-source licences are confirmed before ingestion.
5. Bengali search quality is validated in Phase 0.
6. Hosting region and budget are confirmed in Phase 0.

---

## 8. Acceptance criteria format

Every feature is accepted with:

```text
Given <context>
When <action>
Then <observable result>
And <non-functional: performance, accessibility, i18n, security>
```

---

*End of SRS.*
