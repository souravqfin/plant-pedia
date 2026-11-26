# Content Production Plan

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026
**Owner:** Content editor + botanical expert

---

## 1. Launch targets

| Content | Target | Minimum for launch |
|---|---|---|
| Plant profiles | 500 | 300 |
| Images | 3–5 per plant | 1 licensed image per plant |
| Categories | 30 | 20 |
| Articles / guides | 50 | 30 |
| CMS pages | 15 | 10 |
| Glossary terms | 150 | 100 |
| Expert reviewers | 5 | 3 |
| Sources in registry | 20 | 10 |

A profile is **launch-ready** only when it has:

1. Accepted scientific name + author citation.
2. Bengali and English common names.
3. Description in both languages.
4. Taxonomy (family, genus, species).
5. Distribution in Bangladesh with source.
6. At least one licensed image with attribution.
7. At least one source.
8. Editor verification.
9. SEO title and description in both languages.
10. No unresolved factual warning.

---

## 2. Workflow

```text
Topic selection
  → source collection (POWO, GBIF, BNH e-Flora, BFIS, publications)
  → licence check
  → English draft
  → botanical fact check
  → Bengali translation
  → bilingual copy edit
  → image sourcing + licence
  → SEO metadata
  → editor approval
  → publish
  → expert verification (post-publish allowed, labelled)
```

Statuses: `planned → research → draft → fact_check → translation → copy_edit → media → review → published → verified`.

---

## 3. Roles

| Role | Responsibility |
|---|---|
| Content editor | Plan, assign, edit, publish, quality gate |
| Writer (en) | Research and draft English |
| Translator (bn) | Translate and adapt |
| Botanical expert | Verify names, taxonomy, claims |
| Image researcher | Source and clear licences |
| SEO specialist | Titles, descriptions, internal links |

---

## 4. Effort model

Per profile:

| Step | Hours |
|---|---|
| Research and source collection | 1.5 |
| English draft | 1.5 |
| Botanical fact check | 0.5 |
| Bengali translation | 1.0 |
| Bilingual copy edit | 0.5 |
| Image sourcing and licence | 1.0 |
| SEO metadata | 0.25 |
| Editor QA | 0.25 |
| **Total** | **6.5 h** |

At 300 profiles: **1,950 hours**.
At 500 profiles: **3,250 hours**.

With 2 writers + 1 translator + 1 editor, 300 profiles takes approximately **14–18 weeks** with parallel review.

---

## 5. Cost model (indicative)

| Item | Low | High |
|---|---|---|
| Writing (300 × 3 h × $10–25) | $9,000 | $22,500 |
| Translation (300 × 1.5 h × $8–20) | $3,600 | $9,000 |
| Expert review (300 × 0.5 h × $20–60) | $3,000 | $9,000 |
| Editor/QA (300 × 0.5 h × $10–25) | $1,500 | $3,750 |
| Image licensing (300 × 2 × $0–20) | $0 | $12,000 |
| **Total** | **$17,100** | **$56,250** |

These are planning ranges; local rates and image sources will change them.

---

## 6. Source and licence register

Every source records:

- Title, publisher, URL.
- Licence (CC0, CC BY, CC BY-SA, all rights reserved, permission granted).
- Terms of use and API conditions.
- Attribution requirement.
- Rate limits.
- Access date.
- Permitted uses (text, images, data).

**Rules:**

- No bulk copying from a source without written permission.
- No image without a recorded licence.
- No AI-generated citation.
- Public accessibility does not equal reuse permission.

---

## 7. Image policy

- Prefer Wikimedia Commons, iNaturalist (licensed), GBIF (licensed), and original photography.
- Record creator, source URL, licence, and attribution text.
- Minimum 1200 px on the long edge.
- No watermarks, no visible logos, no AI-generated plant images presented as real.
- Sensitive species: strip GPS and avoid location-revealing backgrounds.

---

## 8. Bengali quality standards

- Use a maintained botanical glossary; enforce consistent terms.
- Bengali names verified against BNH e-Flora and local expert review.
- Avoid machine translation without human editing.
- Use respectful, simple register (`আপনি`).
- Scientific names remain Latin and italic.
- Dates and numerals follow `bn-BD` conventions.

---

## 9. SEO content standards

- One primary keyword per profile; no stuffing.
- Title ≤ 60 characters; description ≤ 155.
- Internal links: family, category, related plants, care guide.
- Unique descriptions; no template duplication.
- Alt text describes the image, not the keyword.

---

## 10. QA checklist per profile

- [ ] Scientific name and author correct.
- [ ] Synonym/accepted status correct.
- [ ] Bengali and English names present.
- [ ] Taxonomy complete.
- [ ] Distribution sourced.
- [ ] Description has no unsupported claim.
- [ ] Toxicity/medicinal claims have a source and disclaimer.
- [ ] Images licensed and attributed.
- [ ] SEO fields complete.
- [ ] No broken links.
- [ ] Both locales render correctly.

---

## 11. Schedule

| Phase | Content work |
|---|---|
| 0 | Plan, register, 50-profile pilot |
| 1–3 | 100 profiles |
| 4–5 | 200 profiles |
| 6–8 | 300 profiles (launch threshold) |
| 9 | QA and fix |
| 10 | Expand to 500 |

Content runs in parallel with engineering from day one. It is the longest lead time in the project.

---

*End of content plan.*
