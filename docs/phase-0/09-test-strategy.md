# Test Strategy

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

---

## 1. Objectives

1. Catch regressions before they reach staging.
2. Prove every requirement with an automated or documented test.
3. Protect the highest-risk areas: auth, permissions, content integrity, uploads, AI review.
4. Keep the suite fast enough to run on every pull request.

---

## 2. Test levels

| Level | Tool | Scope | Runs on |
|---|---|---|---|
| Backend unit | Pest | Actions, services, value objects, enums | Every PR |
| Backend feature | Pest | Endpoints: happy path, validation, auth, forbidden | Every PR |
| Contract | OpenAPI schema validation | Request/response shape | Every PR |
| Frontend unit | Vitest + Testing Library | Components, hooks, utils | Every PR |
| Frontend integration | Vitest + MSW | Feature flows with mocked API | Every PR |
| E2E | Playwright | Critical journeys | Every PR (smoke), nightly (full) |
| Accessibility | axe + manual | Pages and components | Every PR (storybook), Phase 9 (audit) |
| Visual | Playwright screenshots | Design system | Every PR |
| Performance | Lighthouse CI | Public routes | Every PR |
| Security | ZAP baseline, audits | Staging | Nightly + release |
| UAT | Manual scripts | Business acceptance | Phase 9 |

---

## 3. Coverage targets

| Area | Target |
|---|---|
| Domain actions/services | ≥ 80% line coverage |
| API routes | 100% have a feature test |
| Shared components | 100% have a Storybook story |
| Critical E2E journeys | 100% automated |
| Accessibility | Zero critical/serious axe issues |
| Performance | Budgets in §8.9 of the UI/tech plan |

Coverage is a floor, not a goal. A high percentage with weak assertions is treated as a failure.

---

## 4. Test data

- Factories for every model.
- A `MiniDataset` seeder: 50 plants, 2 languages, 3 roles, 20 submissions, 10 comments.
- No production data in staging; anonymize if a copy is needed.
- Tests never depend on execution order.
- Database is refreshed per test transaction.

---

## 5. Environments

| Environment | Purpose | Data |
|---|---|---|
| Local | Development | Fixtures |
| CI | Automated tests | Ephemeral |
| Staging | UAT, demos, DAST | Anonymized |
| Production | Live | Real |

---

## 6. CI gates

A pull request cannot merge unless:

1. Lint passes (Pint, ESLint, Prettier).
2. Static analysis passes (Larastan, `tsc --noEmit`).
3. Backend unit + feature tests pass.
4. Frontend unit tests pass.
5. Build succeeds.
6. Contract tests pass.
7. E2E smoke passes.
8. Accessibility checks pass.
9. Bundle-size budget passes.
10. No new high/critical dependency or container vulnerability.

Nightly: full E2E, visual regression, ZAP baseline, Lighthouse on all key routes.

---

## 7. Critical E2E journeys

1. Register → verify email → login → logout.
2. Login with phone OTP.
3. Search Bengali name → open plant → read care → favorite.
4. Switch language → URL and content update.
5. Comment → edit → delete.
6. Submit a correction → moderator approves → plant updates.
7. Admin logs in with MFA → creates a plant → publishes.
8. Editor creates a CMS page → previews → schedules → publishes.
9. AI draft → reviewer accepts one field → audit log records it.
10. Sensitive species: precise location is hidden.

---

## 8. Accessibility testing

- axe in Storybook for every component.
- axe in Playwright for every public page.
- Manual keyboard pass on auth, search, plant detail, admin editor.
- Screen reader pass: NVDA + Firefox, VoiceOver + Safari, TalkBack + Chrome.
- Bengali screen reader pass on plant detail and search.
- Contrast checked against tokens.

---

## 9. Performance testing

- Lighthouse CI on home, plant directory, plant detail, search, article.
- Budgets: LCP ≤ 2.5 s, CLS < 0.1, INP < 200 ms, JS ≤ 180 KB gzip.
- k6 or Artillery load test: 500 concurrent users on plant detail, p95 ≤ 300 ms.
- Database query count asserted on list endpoints (no N+1).
- Cache hit ratio monitored in staging.

---

## 10. Security testing

- Semgrep SAST in CI.
- Composer/npm audit and Trivy in CI.
- ZAP baseline nightly on staging.
- Authorization matrix tests for every role.
- Upload fuzzing: wrong MIME, oversized, polyglot, EXIF.
- Prompt-injection suite for AI ingestion.
- External pen test before launch; high/critical findings block release.

---

## 11. Internationalization testing

- Pseudo-locale `en-XA` for layout expansion.
- Missing-key check fails CI.
- Bengali font rendering snapshot.
- `Intl` formatting tests for dates, numbers, currency.
- Fallback labelling test.
- URL and hreflang tests.

---

## 12. UAT

- Scripts written in Given/When/Then.
- Executed by the client on staging with the QA engineer.
- Each scenario signed off or logged as a defect.
- Blocking defects fixed before launch.

See `09-uat-plan.md`.

---

## 13. Defect severity

| Severity | Definition | Fix SLA |
|---|---|---|
| S1 — Critical | Data loss, security breach, site down | Immediate |
| S2 — High | Core flow broken, no workaround | 1 business day |
| S3 — Medium | Feature degraded, workaround exists | 3 business days |
| S4 — Low | Cosmetic or minor | Next sprint |

---

## 14. Flaky tests

- A flaky test is quarantined within 48 hours with a linked issue.
- Quarantined tests do not count toward coverage.
- Root cause is fixed; retries are not a permanent solution.

---

## 15. Release criteria

- [ ] All CI gates green.
- [ ] No open S1/S2 defects.
- [ ] UAT signed.
- [ ] Accessibility audit passed.
- [ ] Performance budgets met.
- [ ] Pen test high/critical findings closed.
- [ ] Backup restore verified.
- [ ] Smoke test passed in production.

---

*End of test strategy.*
