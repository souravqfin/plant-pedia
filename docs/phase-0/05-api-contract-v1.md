# API Contract v1

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026
**Status:** Draft
**Base paths:** `/api/v1` (public/client) · `/api/admin/v1` (admin)

---

## 1. Conventions

| Concern | Rule |
|---|---|
| Format | JSON only; `Content-Type: application/json` |
| Auth | `Authorization: Bearer <access_token>` |
| Localization | `Accept-Language: bn` or `en`; default `bn` |
| Request ID | Returned as `meta.request_id`; echo `X-Request-ID` if supplied |
| Pagination | `?page=2&per_page=25` (admin), cursor for feeds |
| Filtering | `filter[family]=Anacardiaceae` |
| Sorting | `sort=-created_at` |
| Sparse fields | `fields=id,slug,name` |
| Idempotency | `Idempotency-Key` on POST |
| Concurrency | `ETag` + `If-Match` on admin updates |
| Rate limits | `X-RateLimit-Limit`, `X-RateLimit-Remaining`, `X-RateLimit-Reset` |
| Versioning | URL version; 6-month deprecation window |

### Success envelope

```json
{
  "data": { "id": 42, "slug": "mangifera-indica" },
  "meta": { "request_id": "req_01HX..." }
}
```

### Error envelope

```json
{
  "errors": [
    { "code": "VALIDATION_FAILED", "message": "The slug has already been taken.", "field": "slug" }
  ],
  "meta": { "request_id": "req_01HX..." }
}
```

### Error codes

| Code | HTTP | Meaning |
|---|---|---|
| `VALIDATION_FAILED` | 422 | Field validation |
| `UNAUTHENTICATED` | 401 | Missing/expired token |
| `FORBIDDEN` | 403 | Not allowed |
| `NOT_FOUND` | 404 | Missing resource |
| `CONFLICT` | 409 | Duplicate or version conflict |
| `RATE_LIMITED` | 429 | Too many requests |
| `UNPROCESSABLE` | 422 | Business rule violation |
| `INTERNAL_ERROR` | 500 | Unexpected failure |

---

## 2. Authentication

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/auth/register` | Create account |
| POST | `/api/v1/auth/login` | Email/password login |
| POST | `/api/v1/auth/refresh` | Rotate refresh, issue access |
| POST | `/api/v1/auth/logout` | Revoke current session |
| POST | `/api/v1/auth/logout-all` | Revoke all sessions |
| POST | `/api/v1/auth/forgot-password` | Send reset link |
| POST | `/api/v1/auth/reset-password` | Reset with token |
| POST | `/api/v1/auth/verify-email` | Verify signed link |
| POST | `/api/v1/auth/otp/request` | Send phone OTP |
| POST | `/api/v1/auth/otp/verify` | Verify OTP / login |
| GET | `/api/v1/auth/me` | Current user |
| GET | `/api/v1/auth/sessions` | Device/session list |
| DELETE | `/api/v1/auth/sessions/{id}` | Revoke a session |

### Login

```http
POST /api/v1/auth/login
Content-Type: application/json

{ "email": "user@example.com", "password": "correct horse battery" }
```

```http
200 OK
Set-Cookie: refresh_token=...; HttpOnly; Secure; SameSite=Lax; Path=/api/v1/auth
```

```json
{
  "data": {
    "access_token": "eyJ...",
    "token_type": "Bearer",
    "expires_in": 900,
    "user": { "id": 7, "name": "Ayesha", "locale": "bn", "roles": ["user"] }
  },
  "meta": { "request_id": "req_01HX..." }
}
```

Refresh requires the cookie plus `X-CSRF-TOKEN` (double-submit).

---

## 3. Public content

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/config` | Site config, locales, feature flags |
| GET | `/api/v1/home` | Homepage modules |
| GET | `/api/v1/pages/{slug}` | CMS page |
| GET | `/api/v1/menus/{location}` | Menu by location + locale |
| GET | `/api/v1/plants` | Plant list |
| GET | `/api/v1/plants/{slug}` | Plant detail |
| GET | `/api/v1/plants/{slug}/related` | Related plants |
| GET | `/api/v1/categories` | Category list |
| GET | `/api/v1/categories/{slug}` | Category + plants |
| GET | `/api/v1/taxonomy` | Taxonomy tree |
| GET | `/api/v1/taxa/{id}` | Taxon detail |
| GET | `/api/v1/families/{slug}` | Family page |
| GET | `/api/v1/articles` | Article list |
| GET | `/api/v1/articles/{slug}` | Article detail |
| GET | `/api/v1/search` | Search |
| GET | `/api/v1/search/suggest` | Autocomplete |
| GET | `/api/v1/sources/{id}` | Source detail |

### Plant detail

```http
GET /api/v1/plants/mangifera-indica?locale=bn
```

```json
{
  "data": {
    "id": 42,
    "slug": "mangifera-indica",
    "scientific_name": "Mangifera indica",
    "author_citation": "L.",
    "taxon": { "id": 12, "family": "Anacardiaceae", "genus": "Mangifera" },
    "names": {
      "bn": "আম",
      "en": "Mango"
    },
    "local_names": [{ "name": "আম", "region": "Dhaka", "source_id": 3 }],
    "description": "…",
    "characteristics": { "habit": "tree", "height_max_cm": 2500 },
    "care": { "light": "full_sun", "water": "moderate" },
    "seasonality": [{ "event": "flowering", "start_month": 1, "end_month": 3 }],
    "distribution": [{ "country": "BD", "establishment_means": "native" }],
    "conservation": { "iucn_category": "LC" },
    "media": [{ "id": 9, "url": "https://cdn…/mango.avif", "type": "fruit", "license": "CC BY-SA 4.0" }],
    "sources": [{ "id": 3, "title": "…", "url": "…" }],
    "verification_status": "editor_verified",
    "seo": { "title": "আম — Plant Pedia", "description": "…" }
  },
  "meta": { "request_id": "req_01HX..." }
}
```

### Search

```http
GET /api/v1/search?q=am&locale=bn&filter[family]=Anacardiaceae&page=1&per_page=20
```

```json
{
  "data": [
    { "id": 42, "slug": "mangifera-indica", "name": "আম", "scientific_name": "Mangifera indica", "score": 0.98 }
  ],
  "meta": {
    "request_id": "req_01HX...",
    "pagination": { "page": 1, "per_page": 20, "total": 1 },
    "facets": { "family": [{ "value": "Anacardiaceae", "count": 1 }] }
  }
}
```

---

## 4. Account and community

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/account` | Profile |
| PATCH | `/api/v1/account` | Update profile |
| DELETE | `/api/v1/account` | Delete/anonymize account |
| GET | `/api/v1/account/favorites` | Favorites |
| POST | `/api/v1/plants/{id}/favorite` | Add favorite |
| DELETE | `/api/v1/plants/{id}/favorite` | Remove favorite |
| GET | `/api/v1/account/collections` | Collections |
| POST | `/api/v1/account/collections` | Create collection |
| GET | `/api/v1/account/submissions` | My submissions |
| POST | `/api/v1/submissions` | Submit content |
| GET | `/api/v1/plants/{id}/comments` | Comments |
| POST | `/api/v1/plants/{id}/comments` | Add comment |
| PATCH | `/api/v1/comments/{id}` | Edit own comment |
| DELETE | `/api/v1/comments/{id}` | Delete own comment |
| POST | `/api/v1/reactions` | Add/change reaction |
| DELETE | `/api/v1/reactions/{id}` | Remove reaction |
| POST | `/api/v1/reports` | Report content |
| GET | `/api/v1/notifications` | Notifications |
| PATCH | `/api/v1/notifications/{id}` | Mark read |

---

## 5. Admin API

All admin routes require an admin-audience token, MFA, and the listed permission.

| Group | Path | Key operations |
|---|---|---|
| Dashboard | `/api/admin/v1/dashboard` | Metrics, queues |
| Plants | `/api/admin/v1/plants` | CRUD, publish, archive, bulk |
| Taxonomy | `/api/admin/v1/taxa` | CRUD, move, synonyms |
| Categories | `/api/admin/v1/categories` | CRUD, order |
| Countries | `/api/admin/v1/countries` | CRUD, regions |
| Languages | `/api/admin/v1/languages` | CRUD, translations |
| Media | `/api/admin/v1/media` | Upload, update, delete |
| Sources | `/api/admin/v1/sources` | CRUD, licence |
| Submissions | `/api/admin/v1/submissions` | Queue, review |
| Moderation | `/api/admin/v1/moderation` | Reports, actions |
| Users | `/api/admin/v1/users` | CRUD, roles, impersonate |
| Roles | `/api/admin/v1/roles` | CRUD, permissions |
| CMS | `/api/admin/v1/pages` | CRUD, sections, revisions |
| Menus | `/api/admin/v1/menus` | CRUD |
| SEO | `/api/admin/v1/seo` | Metadata, redirects |
| AI | `/api/admin/v1/ai` | Tasks, drafts, suggestions |
| Settings | `/api/admin/v1/settings` | Site, flags |
| Audit | `/api/admin/v1/audit` | Search log |

### Admin update with concurrency

```http
PUT /api/admin/v1/plants/42
If-Match: "7"
```

```json
{ "slug": "mangifera-indica", "status": "published" }
```

A stale `If-Match` returns `409 CONFLICT` with the current version.

---

## 6. Rate limits

| Group | Limit |
|---|---|
| Login / OTP | 5/min per IP + per account |
| Register / reset | 3/hour per IP |
| Search | 60/min per IP |
| Comments / reactions | 20/min per user |
| Uploads | 30/hour per user |
| AI | 10/hour per user; daily cost cap |
| Admin | 300/min per admin |

---

## 7. Versioning and deprecation

- Breaking changes require a new URL version.
- Additive changes are allowed within a version.
- Deprecated fields are announced in the changelog and removed after 6 months.
- `Sunset` and `Deprecation` headers are sent on deprecated endpoints.

---

## 8. OpenAPI

- Generated with Scramble and committed to `contracts/openapi.json`.
- TypeScript types generated into `contracts/types.ts`.
- CI validates responses against the schema.
- Admin endpoints are excluded from the public documentation build.

---

*End of API contract.*
