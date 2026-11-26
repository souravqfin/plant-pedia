# Estimation & Budget

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026
**Status:** Planning estimate — re-baseline at M1

---

## 1. Basis of estimate

- Task-level WBS in `plant_pedia_task_phases.md`.
- Durations are effort windows with a 25% schedule buffer.
- Rates are placeholders; replace with the agreed rate card.
- Infra is indicative USD.

---

## 2. Effort by phase (person-days)

| Phase | PM | UX | BE | FE | QA | DO | Content | Total |
|---|---|---|---|---|---|---|---|---|
| 0 — Discovery | 18 | 12 | 4 | 4 | 2 | 4 | 8 | 52 |
| 1 — Foundation | 4 | 0 | 24 | 20 | 6 | 14 | 0 | 68 |
| 2 — Design system | 2 | 14 | 0 | 18 | 3 | 0 | 0 | 37 |
| 3 — All page design | 2 | 60 | 0 | 0 | 0 | 0 | 8 | 70 |
| 4 — Botanical core | 3 | 0 | 55 | 0 | 8 | 2 | 0 | 68 |
| 5 — CMS | 2 | 4 | 22 | 18 | 5 | 1 | 0 | 52 |
| 6 — Public site | 2 | 6 | 4 | 40 | 12 | 3 | 0 | 67 |
| 7 — Community | 2 | 4 | 20 | 16 | 6 | 1 | 0 | 49 |
| 8 — AI assist | 2 | 4 | 20 | 8 | 4 | 1 | 0 | 39 |
| 9 — Hardening | 6 | 2 | 8 | 8 | 18 | 8 | 6 | 56 |
| **Total** | **43** | **106** | **157** | **132** | **64** | **34** | **22** | **558** |

**≈ 558 person-days ≈ 70 person-months** for the recommended team over 34 weeks (including buffer and parallel work).

Lean team (1 BE, 1 FE, 0.5 UX, 0.5 QA): **≈ 320 person-days ≈ 40 person-months**, 44–52 weeks.

---

## 3. Team cost

Formula:

```text
team cost = Σ (person-months × monthly rate for role)
```

Placeholder monthly rates (replace with local rates):

| Role | Low | High |
|---|---|---|
| PM/BA | $1,200 | $2,500 |
| Tech lead | $2,000 | $4,000 |
| Backend | $1,200 | $2,500 |
| Frontend | $1,200 | $2,500 |
| UI/UX | $1,000 | $2,500 |
| QA | $800 | $1,800 |
| DevOps | $1,500 | $3,000 |
| Content editor | $600 | $1,200 |
| Writer/translator | $500 | $1,000 |
| Botanical expert | $500 | $1,500 |

At the midpoint, the recommended team is approximately **$95,000–$160,000** for the 34-week build. The lean team is **$55,000–$95,000** over 44–52 weeks.

> These are planning placeholders, not a quote. The final figure depends on the agreed rate card and contract structure.

---

## 4. Infrastructure (monthly, USD)

| Service | Early | Growth |
|---|---|---|
| App servers (2 × 2 vCPU / 4 GB) | 40–80 | 150–300 |
| Managed MySQL | 50–100 | 150–300 |
| Managed Redis | 15–30 | 50–100 |
| Search | 30–150 | 150–400 |
| Storage + CDN (100 GB) | 10–40 | 50–150 |
| Email | 5–20 | 20–80 |
| SMS/OTP (10k/month) | 100–300 | 300–800 |
| AI providers | 100–500 | 500–2,000 |
| Monitoring/logging | 0–50 | 50–150 |
| **Total** | **350–1,270** | **1,400–4,600** |

---

## 5. One-time costs (excluding team)

| Item | Estimate |
|---|---|
| UI/UX design (6 weeks) | $6,000–15,000 |
| External penetration test | $3,000–8,000 |
| Legal (terms, privacy, licence) | $2,000–5,000 |
| Image licensing / content acquisition | $2,000–10,000 |
| Brand identity (if needed) | $2,000–8,000 |
| **Total** | **$15,000–46,000** |

---

## 6. Three-year TCO

| Year | Build | Infra | Maintenance (18% of build) | Content | Total |
|---|---|---|---|---|---|
| 1 | $95k–160k | $4k–15k | — | $17k–56k | $116k–231k |
| 2 | — | $5k–20k | $17k–29k | $10k–25k | $32k–74k |
| 3 | — | $6k–25k | $17k–29k | $10k–25k | $33k–79k |
| **3-year** | | | | | **$181k–384k** |

Maintenance covers security updates, dependency upgrades, bug fixes, monitoring, and a small enhancement pool. It does not cover new modules.

---

## 7. Payment schedule (suggested)

| Milestone | % |
|---|---|
| Contract / Phase 0 | 15% |
| Foundation + design system | 20% |
| Botanical core + CMS | 25% |
| Public site beta | 20% |
| Community + AI | 10% |
| Launch | 10% |

---

## 8. Assumptions

1. Client provides brand assets or approves a design phase.
2. Content is funded separately or included as above.
3. AI usage is capped monthly.
4. No marketplace or native apps in v1.
5. Team is available full-time for the duration.
6. Third-party licences are approved before ingestion.
7. Rate card is confirmed before M1.

---

*End of estimation and budget.*
