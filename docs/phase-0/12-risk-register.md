# Risk Register v1

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

Scale: probability 1–5, impact 1–5, score = P × I. Exposure is indicative.

---

## 1. Register

| ID | Risk | P | I | Score | Exposure | Mitigation | Owner | Trigger | Review |
|---|---|---|---|---|---|---|---|---|---|
| R-01 | Bengali search quality is unacceptable | 4 | 5 | 20 | 4 weeks + rework | Phase 0 spike; OpenSearch fallback; transliteration table; 200-query test set | TL | Top-1 < 90% in spike | Weekly |
| R-02 | Content not ready at launch | 4 | 5 | 20 | Launch delay 8–12 weeks | Start in Phase 0; 50-profile pilot; 300-profile threshold; hire writers early | Content lead | < 30 profiles/week by week 12 | Weekly |
| R-03 | Scope creep | 4 | 4 | 16 | 15–25% schedule | Frozen MVP; change control; cut list; weekly variance report | PM | Any unplanned feature > 1 day | Weekly |
| R-04 | Source licensing blocks ingestion | 3 | 4 | 12 | Legal + rework | Licence register; written permission; API-first; no scraping | Content lead | Source terms unclear | Phase 0 |
| R-05 | AI cost or accuracy out of control | 3 | 4 | 12 | $500–2,000/month | Caps, eval set, human review, kill switch, provider fallback | TL | Cost > 80% cap or eval < 80% | Monthly |
| R-06 | Performance poor on low-end Android | 3 | 4 | 12 | 20% rework | Budgets, image optimization, font subsetting, 3G tests from Phase 1 | FE lead | LCP > 3 s on test device | Every PR |
| R-07 | SSR operational complexity | 3 | 3 | 9 | 1–2 weeks | React Router 7 PoC; CDN caching; runbooks; staging parity | DO | Cache/hydration incidents | Monthly |
| R-08 | Admin account takeover | 2 | 5 | 10 | Reputation + legal | MFA, short TTL, audience, IP allowlist, audit, pen test | TL | Any auth anomaly | Quarterly |
| R-09 | Sensitive species location leak | 2 | 5 | 10 | Ecological + legal | Sensitivity flag, coordinate fuzzing, expert review, tests | TL + expert | Any precise public coordinate | Phase 4 |
| R-10 | SMS/OTP deliverability in BD | 3 | 3 | 9 | 1 week | Local aggregator, fallback provider, email fallback | BE lead | OTP success < 95% | Phase 1 |
| R-11 | Import data quality/duplicates | 3 | 3 | 9 | 2–3 weeks | Dry-run, dedupe, merge tool, provenance, pilot import | BE lead | Duplicate rate > 5% | Phase 4 |
| R-12 | Key-person dependency | 3 | 3 | 9 | 2–4 weeks | Docs, pairing, ADRs, code review, bus factor review | TL | One owner on > 2 modules | Monthly |
| R-13 | Legal liability for medicinal/toxicity claims | 2 | 4 | 8 | Legal + trust | Disclaimers, expert review, conservative claims, legal review | Client + editor | Any unsourced medical claim | Phase 3 |
| R-14 | Dependency supply-chain attack | 2 | 4 | 8 | Incident | Lockfiles, audit, Dependabot, Trivy, minimal dependencies | DO | New critical CVE | Weekly |
| R-15 | Budget overrun | 3 | 3 | 9 | 15–25% | 25% buffer, monthly burn review, cut list | PM | Burn > 10% over plan | Monthly |

---

## 2. Top five

1. **R-01 Bengali search** — decides the architecture.
2. **R-02 Content readiness** — decides the launch date.
3. **R-03 Scope creep** — decides the budget.
4. **R-04 Licensing** — decides what data can be used.
5. **R-05 AI cost/accuracy** — decides whether AI ships at all.

---

## 3. Reserve

| Reserve | Amount | Purpose |
|---|---|---|
| Schedule | 25% (≈ 8 weeks) | Absorb estimation error |
| Budget | 15% | Absorb scope and rate variance |
| Content | 20% extra profiles | Replace unusable profiles |
| AI | 30% monthly cap headroom | Provider price changes |

---

## 4. Review cadence

- Weekly: top five risks, triggers, mitigation progress.
- Monthly: full register, exposure update.
- Phase gate: re-score all risks.
- Post-incident: add a risk and a regression test.

---

*End of risk register.*
