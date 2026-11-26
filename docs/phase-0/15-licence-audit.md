# Licence Audit

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

Purpose: confirm that every dependency, font, image source, and dataset can be used commercially.

---

## 1. Software dependencies

| Dependency | Licence | Commercial use | Attribution | Status |
|---|---|---|---|---|
| Laravel | MIT | ✓ | No | ✓ |
| Symfony components | MIT | ✓ | No | ✓ |
| React / React DOM | MIT | ✓ | No | ✓ |
| React Router | MIT | ✓ | No | ✓ |
| Vite | MIT | ✓ | No | ✓ |
| TypeScript | Apache-2.0 | ✓ | No | ✓ |
| Tailwind CSS | MIT | ✓ | No | ✓ |
| shadcn/ui | MIT | ✓ | No | ✓ |
| Radix UI | MIT | ✓ | No | ✓ |
| TanStack Query/Table | MIT | ✓ | No | ✓ |
| React Hook Form | MIT | ✓ | No | ✓ |
| Zod | MIT | ✓ | No | ✓ |
| TipTap (core) | MIT | ✓ | No | ✓ |
| Recharts | MIT | ✓ | No | ✓ |
| MapLibre GL JS | BSD-3-Clause | ✓ | Yes (licence text) | ✓ |
| i18next | MIT | ✓ | No | ✓ |
| Zustand | MIT | ✓ | No | ✓ |
| Sonner | MIT | ✓ | No | ✓ |
| Lucide | ISC | ✓ | No | ✓ |
| Storybook | MIT | ✓ | No | ✓ |
| Vitest | MIT | ✓ | No | ✓ |
| Playwright | Apache-2.0 | ✓ | No | ✓ |
| Pest | MIT | ✓ | No | ✓ |
| Larastan / PHPStan | MIT | ✓ | No | ✓ |
| Scramble | MIT | ✓ | No | ✓ |
| Meilisearch | MIT | ✓ | No | ✓ |
| OpenSearch | Apache-2.0 | ✓ | No | ✓ |
| imgproxy | MIT | ✓ | No | ✓ |

**Rule:** no GPL/AGPL dependency in the application without a legal review and an ADR. None are planned.

---

## 2. Fonts

| Font | Licence | Embedding | Status |
|---|---|---|---|
| Fraunces | SIL OFL 1.1 | ✓ | ✓ |
| Inter | SIL OFL 1.1 | ✓ | ✓ |
| Noto Serif Bengali | SIL OFL 1.1 | ✓ | ✓ |
| Hind Siliguri | SIL OFL 1.1 | ✓ | ✓ |
| JetBrains Mono | SIL OFL 1.1 | ✓ | ✓ |

OFL allows embedding and commercial use; retain the licence file.

---

## 3. Data sources

| Source | Content | Licence / terms | Commercial use | Action |
|---|---|---|---|---|
| POWO (Kew) | Taxonomy, names | Kew terms; check bulk use | Verify | Written permission or API terms |
| GBIF | Occurrence, taxonomy | CC0 / CC BY per dataset | Usually ✓ | Record dataset licence |
| BNH e-Flora | Bangladesh flora | Copyright; check terms | Verify | Written permission |
| BFIS | Forest/tree data | Government terms | Verify | Written permission |
| Pl@ntNet | Identification | Commercial API terms | Paid | Contract |
| Wikimedia Commons | Images | CC0/CC BY/CC BY-SA | ✓ with attribution | Record per image |
| iNaturalist | Images/observations | CC licences per record | ✓ with attribution | Record per record |

**Rules:**

1. No bulk copying without written permission.
2. Record licence, attribution, and access date per item.
3. Do not use NC (non-commercial) or ND (no-derivatives) images in a commercial product.
4. AI must not invent citations or sources.

---

## 4. Content and media policy

- User-submitted content grants Plant Pedia a non-exclusive licence to display, translate, and distribute.
- Contributors confirm they own the rights.
- Takedown process for copyright complaints.
- AI-generated text is labelled and reviewed; AI-generated images are not used as botanical evidence.

---

## 5. Open items

| Item | Owner | Due |
|---|---|---|
| POWO bulk-use permission | Content lead | Phase 0 |
| BNH e-Flora permission | Content lead | Phase 0 |
| BFIS permission | Content lead | Phase 0 |
| Pl@ntNet commercial terms | PM | Phase 8 |
| Legal review of contributor terms | Client | Phase 3 |
| Font licence files added to repo | FE lead | Phase 1 |

---

*End of licence audit.*
