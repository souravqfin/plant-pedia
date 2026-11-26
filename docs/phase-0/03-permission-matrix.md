# Permission & Role Matrix

**Version:** 1.0 (Phase 0)
**Date:** 26 November 2026

Legend: ✓ allowed · — not allowed · ○ own records only

---

## 1. Roles

| Code | Role |
|---|---|
| `guest` | Unauthenticated |
| `user` | Registered user |
| `contributor` | Can submit content |
| `author` | Can write articles |
| `reviewer` | Reviews submissions |
| `expert` | Verifies botanical claims |
| `moderator` | Moderates community |
| `editor` | Publishes content |
| `country_manager` | Manages assigned country |
| `admin` | Manages modules |
| `super_admin` | Full control |

Roles are configurable in the database; this matrix is the launch baseline.

---

## 2. Permissions

### Plants and taxonomy

| Permission | guest | user | contributor | author | reviewer | expert | moderator | editor | country_manager | admin | super_admin |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `plants.view` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `plants.submit` | — | — | ✓ | ✓ | ✓ | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| `plants.create` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `plants.edit_own` | — | — | ○ | ○ | ○ | ○ | — | ✓ | ✓ | ✓ | ✓ |
| `plants.edit_any` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `plants.review` | — | — | — | — | ✓ | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| `plants.verify` | — | — | — | — | — | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| `plants.publish` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `plants.archive` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `taxonomy.view` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `taxonomy.manage` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `taxonomy.verify` | — | — | — | — | — | ✓ | — | ✓ | ✓ | ✓ | ✓ |

### Media, sources, evidence

| Permission | guest | user | contributor | author | reviewer | expert | moderator | editor | country_manager | admin | super_admin |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `media.upload` | — | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `media.edit_own` | — | ○ | ○ | ○ | ○ | ○ | ○ | ✓ | ✓ | ✓ | ✓ |
| `media.manage` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `sources.manage` | — | — | — | — | — | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| `evidence.attach` | — | — | ✓ | ✓ | ✓ | ✓ | — | ✓ | ✓ | ✓ | ✓ |

### CMS and SEO

| Permission | guest | user | contributor | author | reviewer | expert | moderator | editor | country_manager | admin | super_admin |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `cms.view` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `cms.create` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `cms.edit` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `cms.publish` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `cms.delete` | — | — | — | — | — | — | — | — | — | ✓ | ✓ |
| `menus.manage` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `seo.manage` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |

### Community and moderation

| Permission | guest | user | contributor | author | reviewer | expert | moderator | editor | country_manager | admin | super_admin |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `comments.create` | — | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `comments.edit_own` | — | ○ | ○ | ○ | ○ | ○ | ○ | ○ | ○ | ○ | ○ |
| `comments.delete_own` | — | ○ | ○ | ○ | ○ | ○ | ○ | ○ | ○ | ○ | ○ |
| `comments.moderate` | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ | ✓ |
| `reactions.create` | — | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `favorites.manage` | — | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `submissions.create` | — | — | ✓ | ✓ | ✓ | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| `submissions.review` | — | — | — | — | ✓ | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| `moderation.queue` | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ | ✓ |
| `moderation.act` | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ | ✓ |
| `moderation.appeal` | — | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |

### Users, roles, AI, settings

| Permission | guest | user | contributor | author | reviewer | expert | moderator | editor | country_manager | admin | super_admin |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `users.view` | — | — | — | — | — | — | — | — | — | ✓ | ✓ |
| `users.manage` | — | — | — | — | — | — | — | — | — | ✓ | ✓ |
| `roles.manage` | — | — | — | — | — | — | — | — | — | — | ✓ |
| `ai.use` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `ai.configure` | — | — | — | — | — | — | — | — | — | ✓ | ✓ |
| `ai.approve` | — | — | — | — | — | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| `settings.manage` | — | — | — | — | — | — | — | — | — | ✓ | ✓ |
| `audit.view` | — | — | — | — | — | — | — | — | — | ✓ | ✓ |
| `import.run` | — | — | — | — | — | — | — | ✓ | ✓ | ✓ | ✓ |
| `api.manage` | — | — | — | — | — | — | — | — | — | ✓ | ✓ |

---

## 3. Enforcement rules

1. Every write endpoint calls a policy or permission middleware.
2. The frontend may hide actions, but the server is authoritative.
3. Admin routes require an admin-audience token + MFA.
4. Role changes are audited with before/after values.
5. A user cannot escalate their own role.
6. Country managers are scoped to their assigned country.

---

## 4. Seeded roles at launch

`super_admin`, `admin`, `editor`, `moderator`, `expert`, `reviewer`, `author`, `contributor`, `user`.

---

*End of permission matrix.*
