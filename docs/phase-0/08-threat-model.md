# Threat Model (STRIDE)

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026
**Method:** STRIDE per trust boundary
**Standard:** OWASP ASVS Level 2

---

## 1. Scope

Surfaces covered:

1. Public web (SSR) and public API.
2. Authentication and token lifecycle.
3. Admin panel and admin API.
4. File uploads and media delivery.
5. CMS rich text and page sections.
6. AI ingestion and generation.
7. Search.
8. Database and infrastructure.
9. Third-party integrations (email, SMS, AI, storage).

Out of scope for v1: payments, marketplace, native apps.

---

## 2. Assets

| Asset | Impact if compromised |
|---|---|
| User credentials and sessions | Account takeover, privacy breach |
| Admin accounts | Full content and user control |
| Refresh tokens | Persistent impersonation |
| Plant data and sources | Loss of trust, legal exposure |
| Unpublished content | Premature disclosure |
| Uploaded images | Malware, copyright liability |
| Observation locations of sensitive species | Ecological harm |
| AI provider keys | Financial loss, abuse |
| Audit logs | Loss of accountability |
| Backups | Total data loss |

---

## 3. Actors

| Actor | Capability |
|---|---|
| Anonymous visitor | Browse, search, register |
| Registered user | Comment, react, favorite, submit |
| Contributor | Submit content and media |
| Moderator | Hide, warn, suspend |
| Editor | Publish content |
| Admin | Manage modules and users |
| Malicious user | Abuse, spam, scrape, inject |
| Compromised dependency | Supply-chain attack |
| Automated bot | Credential stuffing, scraping, spam |

---

## 4. Trust boundaries

```text
Internet
  │
  ├── Cloudflare (WAF, TLS)
  │
  ├── Nginx
  │     ├── React SSR (Node)
  │     └── Laravel API (PHP)
  │              ├── MySQL
  │              ├── Redis
  │              ├── Search
  │              └── Object storage
  │
  └── Admin browser ──► /api/admin/v1 (MFA, audience, RBAC)
```

Every arrow crossing a boundary is authenticated, authorized, validated, and logged where sensitive.

---

## 5. STRIDE analysis

### 5.1 Authentication

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Spoofing | Credential stuffing | Rate limit, breach-password check, MFA for admins, device alerts | Low |
| Tampering | Forged JWT | Signature verification, algorithm pinning, `kid` rotation | Low |
| Repudiation | Denied login | Auth audit log with IP and user agent | Low |
| Information disclosure | Token in URL/logs | Tokens in headers/cookies only; redact logs | Low |
| Denial of service | Login flood | Per-IP and per-account limits, WAF | Medium |
| Elevation | Refresh reuse | Rotation, family revocation, reuse detection | Low |

### 5.2 Public API and web

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Spoofing | Forged request ID | Server-generated IDs; ignore client IDs for trust | Low |
| Tampering | Mass assignment | Form Requests, explicit fillable | Low |
| Repudiation | Abuse without trace | Rate-limit logs, audit for writes | Low |
| Information disclosure | Enumeration of drafts | Authorization on every resource; 404 not 403 for hidden | Low |
| DoS | Scraping, expensive queries | Rate limits, query caps, CDN caching, WAF | Medium |
| Elevation | IDOR on account resources | Policy checks on ownership | Low |

### 5.3 Admin

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Spoofing | Stolen admin cookie | MFA, short TTL, IP allowlist optional, re-auth for sensitive actions | Medium |
| Tampering | Unauthorized publish | Separate audience, RBAC, audit, optimistic locking | Low |
| Repudiation | Denied edit | Immutable audit with before/after | Low |
| Information disclosure | Admin API discoverable | `no-store`, `noindex`, auth, separate docs | Low |
| DoS | Bulk delete | Confirmation, rate limit, soft delete/undo | Medium |
| Elevation | Role self-escalation | Role changes require higher permission; audited | Low |

### 5.4 Uploads and media

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Spoofing | Fake image MIME | Sniff content, re-encode, extension allowlist | Low |
| Tampering | Polyglot file | Re-encode images; scan non-images | Low |
| Information disclosure | EXIF GPS leak | Strip EXIF on ingest | Low |
| DoS | Huge upload | Size limits, presigned constraints, quotas | Low |
| Elevation | Path traversal | Storage keys generated server-side | Low |
| Malware | Infected PDF | ClamAV scan, quarantine | Medium |

### 5.5 CMS and rich text

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Tampering | Stored XSS via editor | Server-side HTMLPurifier, CSP nonce, allowlist tags | Low |
| Spoofing | Fake page author | Author from session, not payload | Low |
| Information disclosure | Draft preview leak | Signed, expiring preview tokens | Low |
| DoS | Huge page sections | Section count/size limits | Low |
| Elevation | Arbitrary component injection | Fixed section types, JSON schema validation | Low |

### 5.6 AI ingestion and generation

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Spoofing | Fake source | Source registry with allowlist and licence | Medium |
| Tampering | Prompt injection from crawled text | Delimit untrusted content, ignore instructions, separate system prompt | Medium |
| Information disclosure | PII sent to provider | Scrub PII, provider DPA, no user secrets in prompts | Medium |
| DoS / cost | Runaway generation | Per-task timeout, retries, daily caps, kill switch | Medium |
| Elevation | AI auto-publishes | Human approval mandatory; no publish permission for AI | Low |
| Repudiation | Unknown provenance | Store model, prompt version, sources, cost | Low |

### 5.7 Search

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Information disclosure | Draft content indexed | Index only published; filter by locale/visibility | Low |
| DoS | Expensive wildcard queries | Query limits, rate limits, timeout | Low |
| Tampering | Synonym poisoning | Synonym management restricted to editors | Low |

### 5.8 Data and infrastructure

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Information disclosure | DB dump | Private network, least-privilege user, encryption at rest | Medium |
| Tampering | Unauthorized migration | Deploy pipeline only; migration review | Low |
| Repudiation | Log deletion | Append-only audit; external log retention | Low |
| DoS | Disk full | Alerts, retention, log rotation | Medium |
| Elevation | SSH compromise | Key-only auth, firewall, no password login | Medium |

### 5.9 Third parties

| Threat | Example | Mitigation | Residual |
|---|---|---|---|
| Spoofing | Forged webhook | Signature verification, IP allowlist | Low |
| Information disclosure | Email/SMS content | Minimize PII, DPA with vendors | Medium |
| DoS | Provider outage | Fallback provider, queue retry | Medium |
| Supply chain | Malicious package | Lockfiles, audit, Dependabot, Trivy | Medium |

---

## 6. Top risks and owners

| # | Risk | Severity | Owner | Mitigation deadline |
|---|---|---|---|---|
| 1 | Admin account takeover | High | TL | Phase 1 |
| 2 | Stored XSS via CMS | High | TL | Phase 5 |
| 3 | Prompt injection in AI ingestion | High | TL | Phase 8 |
| 4 | Sensitive species location leak | High | TL + expert | Phase 4 |
| 5 | Uploaded malware | Medium | TL | Phase 4 |
| 6 | Credential stuffing | Medium | TL | Phase 1 |
| 7 | Dependency supply chain | Medium | DO | Phase 1 |
| 8 | Backup loss | Medium | DO | Phase 1 |
| 9 | AI cost runaway | Medium | TL | Phase 8 |
| 10 | Scraping of plant data | Medium | TL | Phase 6 |

---

## 7. Verification plan

- [ ] ASVS Level 2 checklist mapped to controls (Phase 0).
- [ ] SAST (Semgrep) in CI from Phase 1.
- [ ] Dependency and container scanning from Phase 1.
- [ ] DAST (OWASP ZAP baseline) on staging from Phase 6.
- [ ] External penetration test before launch.
- [ ] Prompt-injection test suite for AI (Phase 8).
- [ ] Restore drill (Phase 9).
- [ ] Incident runbook (Phase 9).

---

*End of threat model.*
