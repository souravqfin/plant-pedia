# Open Questions & Client Decisions

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

Each item blocks a phase. A default is proposed so work can continue if the client does not respond.

---

## Blocking decisions

| # | Question | Why it blocks | Proposed default | Owner | Status |
|---|---|---|---|---|---|
| Q-01 | Is Bootstrap 5 contractually required, or may we use Tailwind + shadcn/ui? | Changes the entire design system and component library | Tailwind + shadcn/ui | Client | ⛔ Blocked |
| Q-02 | Do you have brand assets (logo, colors, type), or should we create the identity? | Needed for hi-fi and tokens | Create a minimal identity in Phase 2 | Client | ⛔ Blocked |
| Q-03 | What is the launch content volume, and who owns content production? | Determines launch date and cost | 300 profiles, client-owned with our workflow | Client | ⛔ Blocked |
| Q-04 | What is the target launch date? | Drives schedule and cut list | 34 weeks from Phase 1 start | Client | ⛔ Blocked |
| Q-05 | What is the budget envelope? | Drives team size and scope | Recommended team, 34 weeks | Client | ⛔ Blocked |
| Q-06 | What is the monthly AI budget and provider preference? | Caps and provider adapter | $300/month, OpenAI primary | Client | ⛔ Blocked |
| Q-07 | Do you have an SMS gateway account? | OTP and notifications | Procure local gateway in Phase 1 | Client | ⛔ Blocked |
| Q-08 | Hosting region and data residency? | Infra and latency | Singapore, Cloudflare global | Client | ⛔ Blocked |
| Q-09 | Who owns legal documents (terms, privacy, licence, disclaimer)? | Launch blocker | Client legal + our template | Client | ⛔ Blocked |
| Q-10 | Is dark mode required at launch? | Design and QA scope | No; admin dark mode post-launch | Client | ⛔ Blocked |
| Q-11 | Is PWA/offline required at launch? | Scope and testing | No; v1.1 | Client | ⛔ Blocked |
| Q-12 | Should the public API be open to third parties at launch? | Auth, docs, rate limits | No; internal only | Client | ⛔ Blocked |
| Q-13 | Which analytics tool? | Privacy and implementation | Self-hosted Plausible | Client | ⛔ Blocked |
| Q-14 | Who are the expert reviewers and their credentials? | Verification workflow | 3 reviewers nominated by client | Client | ⛔ Blocked |
| Q-15 | Bengali numerals by default in the UI? | Formatting and QA | Bengali in editorial UI, Western in science | Client | ⛔ Blocked |

---

## Technical decisions already made

| # | Decision | Status |
|---|---|---|
| D-01 | React 19 + Vite + React Router 7 | Accepted |
| D-03 | SSR public + CSR admin | Accepted |
| D-04 | JWT + rotating refresh cookie | Accepted |
| D-05 | MySQL 8.4 | Accepted |
| D-06 | Search engine | Spike |
| D-07 | MapLibre + OSM | Accepted |
| D-08 | S3 + imgproxy + CDN | Accepted |
| D-09 | TipTap | Accepted |
| D-10 | Locale-prefixed URLs, bn default | Accepted |
| D-11 | Admin namespace + MFA | Accepted |
| D-12 | AI human-in-the-loop | Accepted |
| D-13 | Docker Compose deployment | Accepted |
| D-14 | Monorepo layout | Accepted |
| D-15 | Pest + Vitest + Playwright | Accepted |
| D-16 | Token-driven design system | Accepted |
| D-17 | SES + local SMS | Accepted |
| D-18 | Self-hosted analytics | Accepted |
| D-19 | Pennant flags | Accepted |
| D-20 | Sentry + OpenTelemetry | Accepted |

---

## Response template

```text
Q-01: Tailwind approved / Bootstrap required
Q-02: Brand assets at <link> / create new
Q-03: 300 profiles / 500 / other; owner: <name>
Q-04: Launch date: <date>
Q-05: Budget: <amount> / range
Q-06: AI budget: <amount>; provider: <name>
Q-07: SMS account: yes (<provider>) / procure
Q-08: Hosting region: <region>
Q-09: Legal owner: <name/team>
Q-10: Dark mode at launch: yes/no
Q-11: PWA at launch: yes/no
Q-12: Public API at launch: yes/no
Q-13: Analytics: <tool>
Q-14: Experts: <names>
Q-15: Bengali numerals: default / toggle
```

---

*End of open questions.*
