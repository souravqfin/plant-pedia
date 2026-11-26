# Scope Freeze & Change Control

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026
**Status:** Draft for client signature

---

## 1. Frozen v1 scope

Plant Pedia v1 delivers:

1. A bilingual (bn/en) botanical encyclopedia for Bangladesh.
2. 300+ verified plant profiles with media and sources.
3. Taxonomy, categories, and distribution.
4. A dynamic CMS with 8 page-builder sections.
5. Public SSR website with SEO and social previews.
6. Search with Bengali, English, scientific, and Banglish support.
7. Accounts, comments, reactions, favorites, submissions.
8. Moderation and expert verification.
9. AI-assisted drafts with mandatory human review.
10. Admin panel with roles, audit, import/export.

**Explicitly excluded from v1:** marketplace, payments, native apps, AI identification, plant map, seasonal calendar, nursery directory, multi-country admin UI, PWA, third-party API billing.

---

## 2. Change control process

Any request that changes scope, schedule, or cost follows this process:

```text
Request
  → log in backlog (date, requester, description)
  → impact assessment (design, backend, frontend, QA, content, ops)
  → estimate in days + risk
  → decision: accept / defer / reject
  → if accepted: adjust timeline or budget, or trigger the cut list
  → update SRS + traceability + task list
  → communicate in the weekly report
```

### Decision authority

| Change size | Approver |
|---|---|
| ≤ 1 day, no new dependency | Tech lead |
| 2–5 days | PM + tech lead |
| > 5 days or new module | Client sponsor |
| Changes launch date or budget | Client sponsor (written) |

### Rules

- No verbal scope changes. Every change is written.
- A change that adds work must remove work or move the date.
- The cut list (below) is the default source of trade-offs.
- Changes after Phase 5 require a regression plan.

---

## 3. Cut list (in drop order)

1. AI assist (Phase 8)
2. Community posts/feed
3. Plant map + seasonal calendar
4. Page builder → fixed templates
5. Dark mode
6. PWA
7. Third-party public API
8. Nursery directory
9. Advanced search facets
10. Multi-country admin UI

**Never cut:** authentication, RBAC, plant core, CMS pages, SEO/SSR, moderation, backups, tests, accessibility.

---

## 4. Sign-off

| Party | Name | Signature | Date |
|---|---|---|---|
| Client sponsor | | | |
| Product manager | | | |
| Tech lead | | | |
| Design lead | | | |

---

*End of scope document.*
