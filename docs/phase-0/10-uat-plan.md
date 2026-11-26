# UAT Plan

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026
**Environment:** Staging
**Participants:** Client product owner, QA engineer, PM, tech lead

---

## 1. Purpose

Confirm that Plant Pedia meets the agreed requirements before launch, using realistic content and role-based scenarios.

---

## 2. Entry criteria

- [ ] Staging deployed from `main`.
- [ ] 50-plant pilot dataset loaded.
- [ ] Bengali and English content present.
- [ ] Test accounts for each role.
- [ ] Email and SMS captured in a test inbox/gateway.
- [ ] Known defects documented.

## 3. Exit criteria

- [ ] All P1 scenarios pass.
- [ ] No open S1/S2 defects.
- [ ] S3 defects have an agreed workaround or deferral.
- [ ] Client signs the acceptance table.

---

## 4. Scenarios

### UAT-01 — Guest discovers a plant (Bengali)

**Given** an unauthenticated visitor on the home page
**When** they search for "আম"
**Then** the mango plant appears in the top 3 results
**And** the result shows the Bengali name, scientific name, and thumbnail
**And** the page loads in under 3 seconds on a throttled connection

### UAT-02 — Language switch

**Given** a visitor on `/bn/plants/mangifera-indica`
**When** they switch to English
**Then** they land on `/en/plants/mangifera-indica`
**And** all UI and content are in English
**And** the canonical and `hreflang` tags are correct

### UAT-03 — Search modes

**Given** a visitor on the search page
**When** they search by Bengali name, English name, scientific name, synonym, and Banglish
**Then** each query returns the expected plant in the top 3
**And** a zero-result query shows suggestions and a clear message

### UAT-04 — Plant profile trust signals

**Given** a published plant profile
**When** a visitor reads it
**Then** they see the scientific name with author citation, taxonomy, distribution, care, seasonality, conservation, sources, and verification status
**And** each key fact shows its source where available
**And** images show license and attribution

### UAT-05 — Registration and verification

**Given** a new visitor
**When** they register with a valid email and password
**Then** they receive a verification email
**And** they cannot comment until verified
**And** after verification they can log in

### UAT-06 — Phone OTP login

**Given** a user with a verified phone number
**When** they request an OTP and enter it
**Then** they are logged in
**And** an expired or reused OTP is rejected

### UAT-07 — User engagement

**Given** a logged-in user on a plant page
**When** they favorite the plant, react, and comment
**Then** each action persists after reload
**And** the user can edit and delete their own comment
**And** another user cannot edit it

### UAT-08 — Submission and moderation

**Given** a contributor
**When** they submit a correction with a source
**Then** the submission appears in the admin queue with status `submitted`
**When** a moderator approves it
**Then** the plant is updated
**And** the audit log records the decision

### UAT-09 — Editor creates a plant

**Given** an editor in the admin panel
**When** they create a plant with taxonomy, names, description, image, and source
**Then** they can save a draft, preview it, and publish it
**And** the public page appears with correct metadata

### UAT-10 — CMS page and page builder

**Given** an editor
**When** they create a page with hero, rich text, plant grid, and FAQ sections
**Then** they can reorder and preview sections
**And** schedule publication
**And** publish without developer help
**And** the page appears at the chosen URL in both locales

### UAT-11 — Roles and permissions

**Given** a moderator
**When** they open the users module
**Then** access is denied
**And** an admin can grant the permission
**And** the change is audited

### UAT-12 — Import

**Given** an admin with a CSV of plants
**When** they run a dry-run import
**Then** they see valid rows, errors, and duplicate candidates
**When** they confirm
**Then** valid rows are imported and the report is downloadable

### UAT-13 — AI review

**Given** an AI-generated draft with field suggestions
**When** a reviewer opens the diff
**Then** they see current value, proposed value, sources, and confidence
**And** they can accept one field, reject another, and edit a third
**And** nothing is published automatically

### UAT-14 — Sensitive species

**Given** a plant marked as sensitive
**When** a visitor views its distribution
**Then** precise coordinates are hidden or fuzzed
**And** the page explains why

### UAT-15 — Accessibility and responsive

**Given** a keyboard-only user on a 375 px screen
**When** they navigate home, search, plant detail, and login
**Then** all interactive elements are reachable and visible on focus
**And** no content is clipped
**And** form errors are announced

### UAT-16 — SEO and social preview

**Given** a plant page URL
**When** it is shared on Facebook, X, LinkedIn, WhatsApp, and Telegram
**Then** the preview shows the correct title, description, and image
**And** the page is indexable with a canonical URL

---

## 5. Acceptance table

| Scenario | Result | Defect ID | Notes |
|---|---|---|---|
| UAT-01 | ☐ Pass ☐ Fail | | |
| UAT-02 | ☐ Pass ☐ Fail | | |
| UAT-03 | ☐ Pass ☐ Fail | | |
| UAT-04 | ☐ Pass ☐ Fail | | |
| UAT-05 | ☐ Pass ☐ Fail | | |
| UAT-06 | ☐ Pass ☐ Fail | | |
| UAT-07 | ☐ Pass ☐ Fail | | |
| UAT-08 | ☐ Pass ☐ Fail | | |
| UAT-09 | ☐ Pass ☐ Fail | | |
| UAT-10 | ☐ Pass ☐ Fail | | |
| UAT-11 | ☐ Pass ☐ Fail | | |
| UAT-12 | ☐ Pass ☐ Fail | | |
| UAT-13 | ☐ Pass ☐ Fail | | |
| UAT-14 | ☐ Pass ☐ Fail | | |
| UAT-15 | ☐ Pass ☐ Fail | | |
| UAT-16 | ☐ Pass ☐ Fail | | |

---

## 6. Sign-off

| Party | Name | Signature | Date |
|---|---|---|---|
| Client product owner | | | |
| QA lead | | | |
| Tech lead | | | |

---

*End of UAT plan.*
