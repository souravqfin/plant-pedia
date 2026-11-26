# Plant Pedia — Progress Tracker

**Updated:** 26 November 2026
**Current phase:** Phase 0 — Discovery & Plan Hardening

Legend: `[x]` done · `[~]` in progress · `[ ]` todo · `[!]` blocked · `[-]` cut

---

## Phase 0 — Discovery & Plan Hardening

### Product & requirements

- [x] `P0-PM-01` Stakeholder kickoff (planning baseline)
- [x] `P0-PM-02` SRS with requirement IDs → `docs/phase-0/01-srs.md`
- [x] `P0-PM-03` Traceability matrix → `docs/phase-0/06-traceability-matrix.md`
- [x] `P0-PM-04` MVP scope freeze → `docs/phase-0/02-scope-and-change-control.md`
- [x] `P0-PM-05` Change-control process → same file
- [ ] `P0-PM-06` Client answers to the 15 open questions — **blocked on client**
- [x] `P0-PM-07` Role/permission matrix → `docs/phase-0/03-permission-matrix.md`
- [x] `P0-PM-08` ERD v1 → `docs/phase-0/04-erd-v1.md`
- [x] `P0-PM-09` API contract v1 → `docs/phase-0/05-api-contract-v1.md`
- [x] `P0-PM-10` UAT plan → `docs/phase-0/10-uat-plan.md`

### Technical spikes

- [ ] `P0-TL-01` Bengali search corpus + queries — next
- [ ] `P0-TL-02` Search engine spike + decision — next
- [ ] `P0-TL-03` SSR/admin PoC — next
- [ ] `P0-TL-04` JWT PoC — next
- [ ] `P0-TL-05` Upload/imgproxy PoC — next
- [ ] `P0-TL-06` i18n + Bengali font PoC — next
- [ ] `P0-TL-07` Load-test baseline — next
- [x] `P0-TL-08` Version compatibility matrix → `docs/phase-0/14-stack-compatibility.md`
- [x] `P0-TL-09` Licence audit → `docs/phase-0/15-licence-audit.md`
- [x] `P0-TL-10` ADRs → `docs/phase-0/07-adrs.md`
- [x] `P0-TL-11` Threat model → `docs/phase-0/08-threat-model.md`
- [x] `P0-TL-12` Infrastructure + cost plan → `docs/phase-0/13-estimation-budget.md`

### Design

- [x] `P0-UX-01` Brand direction + moodboard (brief) → `plant_pedia_ui_tech_plan.md` §7
- [x] `P0-UX-02` Sitemap + user flows → `plant_pedia_ui_tech_plan.md` §7.9
- [x] `P0-UX-03` Design brief → `plant_pedia_ui_tech_plan.md` §7
- [ ] `P0-UX-04` Wireframes for 10 key screens — next
- [ ] `P0-UX-05` Hi-fi for 5 critical screens — next
- [ ] `P0-UX-06` Clickable prototype — next
- [ ] `P0-UX-07` Usability test with 5 users — next
- [ ] `P0-UX-08` Findings report — next

### Content & data

- [x] `P0-CT-01` Content production plan → `docs/phase-0/11-content-plan.md`
- [x] `P0-CT-02` Content cost model → same file
- [ ] `P0-CT-03` Source/licence register — needs client input
- [ ] `P0-CT-04` 50-profile pilot — needs content team
- [ ] `P0-CT-05` Bengali botanical glossary v1 — needs expert
- [x] `P0-CT-06` Translation workflow → content plan

### Delivery & risk

- [x] `P0-PM-11` WBS → `plant_pedia_task_phases.md`
- [~] `P0-PM-12` Three-point estimates — baseline done, needs team validation
- [~] `P0-PM-13` Resource-loaded schedule — baseline done, needs team calendar
- [x] `P0-PM-14` Budget workbook + TCO → `docs/phase-0/13-estimation-budget.md`
- [x] `P0-PM-15` Risk register → `docs/phase-0/12-risk-register.md`
- [x] `P0-PM-16` Cut list → `docs/phase-0/02-scope-and-change-control.md`
- [ ] `P0-PM-17` Phase 0 sign-off pack — needs client review

---

## Phase 1 — Foundation

- [x] `P1-DO-01` Monorepo structure (`backend/`, `frontend/`, `contracts/`, `infra/`, `docs/`)
- [x] `P1-DO-02` Docker Compose (nginx, api, horizon, scheduler, web, mysql, redis, meilisearch, imgproxy, mailpit)
- [x] `P1-DO-03` Repository README + `.gitignore`
- [ ] `P1-DO-04` CI pipeline
- [ ] `P1-DO-05` Staging
- [ ] `P1-DO-06` Production
- [ ] `P1-DO-07` Secrets
- [ ] `P1-DO-08` Backups
- [ ] `P1-DO-09` Monitoring
- [ ] `P1-DO-10` Runbooks

- [x] `P1-BE-01` Laravel **13.44.1** skeleton + Boost installed (`backend/`)

Remaining backend and frontend tasks are listed in `plant_pedia_task_phases.md`.

---

## Blocked / waiting on client

| Item | Why |
|---|---|
| Bootstrap vs Tailwind (D-02) | Changes the design system |
| Brand assets | Needed for hi-fi |
| Content ownership and budget | Needed for launch date |
| SMS gateway account | Needed for OTP |
| Hosting region | Needed for infra |
| Legal documents | Needed for launch |
| Launch date and budget | Needed for baseline |
| Expert reviewers | Needed for verification |

---

## Next actions (in order)

1. Run the Bengali search spike (`P0-TL-01`, `P0-TL-02`).
2. Run the SSR/admin PoC (`P0-TL-03`).
3. Run the JWT PoC (`P0-TL-04`).
4. Create the 10 wireframes (`P0-UX-04`).
5. Get client answers to the 8 blocking questions.
6. Re-baseline the schedule at M1.

---

*End of progress tracker.*
