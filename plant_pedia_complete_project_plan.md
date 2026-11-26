# Plant Pedia --- Complete Project Plan

**Project:** Plant Pedia\
**Initial market:** Bangladesh\
**Expansion strategy:** Global, country by country\
**Initial languages:** Bengali (বাংলা) and English\
**Frontend:** One React application for the public website and admin
panel\
**Backend:** Laravel API only\
**Authentication:** JWT-based\
**Architecture:** API-first modular monolith

------------------------------------------------------------------------

## 1. Product vision

Plant Pedia will be a global botanical knowledge, discovery, and
community platform. It will help people discover plants, explore
reliable botanical information, identify plants, learn how to grow and
care for them, contribute observations and knowledge, and connect with
botanical experts and nurseries.

The platform will launch with Bangladesh-focused content and
Bengali/English support. Its data model and application architecture
must support additional countries and languages without requiring a
fundamental redesign.

Plant Pedia is both:

-   An educational and informational botanical encyclopedia.
-   A community and commercial platform that can later support
    nurseries, sponsored listings, a marketplace, institutional
    services, and public data APIs.

### Core principles

1.  One global identity for each taxon/species; country-specific
    distribution and names are separate data.
2.  One common API for the website, future mobile apps, and authorized
    external client applications.
3.  A private, separately protected admin API for administration.
4.  One React frontend codebase containing both the public website and
    admin panel.
5.  Dynamic CMS pages, navigation, layouts, and reusable page sections.
6.  Bengali and English from launch, with dynamically manageable
    languages.
7.  AI assists research and content preparation; it does not
    autonomously publish unverified scientific claims.
8.  Every important botanical fact should be traceable to evidence and a
    source where possible.
9.  Community participation must be separated from scientific
    verification.
10. SEO, server-rendered social previews, accessibility, security, and
    performance are foundational requirements.

------------------------------------------------------------------------

## 2. Architecture and technology

### 2.1 High-level architecture

``` text
                         PLANT PEDIA
                              |
                 One React + TypeScript App
                              |
             +----------------+----------------+
             |                                 |
       Public Website                     Admin Panel
       Public routes                      Protected routes
       Public layout                      Admin layout
             |                                 |
             +----------------+----------------+
                              |
                       Laravel API
                              |
              +---------------+----------------+
              |                                |
       Common Client API                 Private Admin API
       Website / Mobile /                Admin frontend only
       External clients
              |                                |
              +---------------+----------------+
                              |
                 Shared Laravel domain services
                              |
       +----------------------+----------------------+
       |                      |                      |
     MySQL                  Redis                Search
   Main data            Cache/queues           Meilisearch
       |
  Object storage / CDN / AI providers / approved data sources
```

### 2.2 Technology stack

  -----------------------------------------------------------------------
  Layer                               Recommended technology
  ----------------------------------- -----------------------------------
  Backend                             Laravel 13.x (latest stable)

  Backend architecture                Modular monolith

  API style                           Versioned REST API

  Frontend                            React + TypeScript + Vite

  UI framework                        Bootstrap 5

  Routing                             React Router

  API client                          Axios

  Server state                        TanStack Query

  Forms                               React Hook Form + Zod

  Authentication                      JWT access and refresh tokens

  Database                            MySQL 8

  Cache and queues                    Redis

  Search                              Meilisearch, optionally Laravel
                                      Scout

  File storage                        S3-compatible object storage

  Web server                          Nginx

  Deployment                          Docker Compose initially

  Maps                                OpenStreetMap-based mapping

  Public SEO rendering                React SSR or prerendering within
                                      the same frontend codebase
  -----------------------------------------------------------------------

### 2.3 One frontend, two route areas

Use one React application, shared component system, shared API client,
and separate route/layout groups.

Example:

``` text
frontend/
└── src/
    ├── app/
    ├── routes/
    │   ├── public.routes.tsx
    │   └── admin.routes.tsx
    ├── layouts/
    │   ├── PublicLayout.tsx
    │   ├── AdminLayout.tsx
    │   └── AccountLayout.tsx
    ├── pages/
    │   ├── public/
    │   ├── admin/
    │   └── account/
    ├── components/
    ├── modules/
    ├── services/
    ├── hooks/
    ├── contexts/
    ├── types/
    ├── validations/
    ├── locales/
    ├── assets/
    └── styles/
```

Suggested route groups:

-   Public website: `/`, `/plants`, `/articles`, `/community`
-   User account: `/account/*`
-   Admin panel: `/admin/*`

Admin routes must be protected by server-side authorization. Hiding
admin navigation in React is not a security control.

### 2.4 SEO and rendering requirement

The public website must produce server-readable HTML metadata for search
engines and social crawlers. A client-only SPA often fails to provide
reliable Open Graph previews because many social crawlers do not execute
JavaScript.

Keep one frontend codebase, but use SSR or prerendering for public
routes. Admin routes can remain client-rendered. This does not require a
separate admin frontend.

------------------------------------------------------------------------

## 3. API architecture

The API is Laravel-only. The React application must never access the
database directly.

### 3.1 Common client API

Use one common API for:

-   Public website.
-   Future Android application.
-   Future iOS application.
-   Authorized third-party client applications.

Suggested base path:

``` text
/api/v1/
```

Example endpoint groups:

``` text
/api/v1/
├── auth/
├── config/
├── home/
├── pages/
├── menus/
├── plants/
├── categories/
├── taxonomy/
├── search/
├── articles/
├── calendar/
├── maps/
├── identification/
├── community/
├── comments/
├── reactions/
├── reviews/
├── favorites/
├── observations/
├── submissions/
└── notifications/
```

The API contract must be stable, documented, versioned, and independent
of the React website implementation.

### 3.2 Private admin API

Use a separate protected route namespace, for example:

``` text
/internal/admin/
├── dashboard/
├── plants/
├── taxonomy/
├── categories/
├── users/
├── roles/
├── permissions/
├── countries/
├── languages/
├── cms/
├── page-builder/
├── menus/
├── ai-research/
├── ai-content/
├── data-sources/
├── submissions/
├── moderation/
├── seo/
├── media/
├── analytics/
└── settings/
```

The admin API is intended for the admin area of the same React
application. It is not a second backend or a separate business-logic
implementation.

### 3.3 Shared services

Both API areas must call shared domain services. Do not duplicate
business logic in client and admin controllers.

Example:

``` text
PlantService
  ├── Client Plant Controller
  └── Admin Plant Controller

TaxonomyService
  ├── Client Taxonomy Controller
  └── Admin Taxonomy Controller

AIResearchService
  └── Admin AI Research Controller
```

### 3.4 API standards

-   Use `/api/v1` versioning for the common API.
-   Define consistent JSON response envelopes.
-   Use proper HTTP status codes.
-   Support pagination, filtering, sorting, and field selection where
    appropriate.
-   Validate all input with Laravel Form Requests.
-   Use API Resources for response serialization.
-   Apply rate limits to authentication, search, uploads, comments, and
    AI requests.
-   Publish OpenAPI/Swagger documentation for the common API.
-   Maintain API contract tests.
-   Do not expose internal admin endpoints in public API documentation.
-   Use explicit permissions for write operations and sensitive reads.

------------------------------------------------------------------------

## 4. Authentication and authorization

### 4.1 JWT authentication

Use JWT access tokens and refresh tokens.

Recommended design:

-   Short-lived access tokens.
-   Longer-lived refresh tokens.
-   Refresh-token rotation.
-   Refresh-token revocation and reuse detection.
-   Session/device management.
-   Logout current session and logout all sessions.
-   Login rate limiting.
-   Account verification and suspension checks.
-   MFA for administrators and privileged users.

Token storage:

-   Browser: prefer secure, HttpOnly, SameSite cookies with appropriate
    CSRF protection for the chosen architecture.
-   Mobile: use operating-system secure storage.
-   Never store privileged secrets in frontend JavaScript or public
    environment variables.

JWT identifies the authenticated principal. It does not replace
authorization checks.

### 4.2 Common identity and admin access

A user identity may be used across the platform, but admin privileges
must be checked independently.

For admin access:

-   Admin-specific token audience or scope.
-   Server-side role and permission checks on every protected action.
-   MFA for privileged accounts.
-   Optional IP allowlisting or VPN restrictions.
-   Audit logs for sensitive actions.
-   Stronger session and timeout policies.

A secret URL is not security. The admin API must remain secure even if
its route names are discovered. Do not put an admin API secret into
React code; browser code is inspectable.

### 4.3 Roles

Initial suggested roles:

  Role               Main capabilities
  ------------------ -------------------------------------------
  Guest              Browse public information
  Registered User    Comment, react, favorite, report
  Contributor        Submit plant information and observations
  Author             Create and manage own articles
  Reviewer           Review submissions
  Botanical Expert   Expert verification
  Moderator          Moderate comments and community content
  Editor             Edit and publish approved content
  Country Manager    Manage assigned country's content
  Administrator      Manage platform modules
  Super Admin        Full platform control

Roles must be configurable, not hardcoded.

### 4.4 Permission examples

``` text
plants.view
plants.create
plants.edit_own
plants.edit_any
plants.submit
plants.review
plants.approve
plants.publish
plants.archive

comments.create
comments.edit_own
comments.delete_own
comments.moderate

reviews.create
reviews.edit_own
reviews.moderate

users.manage
roles.manage
countries.manage
languages.manage
cms.manage
ai_research.manage
settings.manage
```

Use policies and permission middleware. Enforce permissions in Laravel
even when the frontend hides unavailable actions.

------------------------------------------------------------------------

## 5. Country and regional management

### 5.1 Bangladesh-first, global-ready

Bangladesh is the initial launch country, not a hardcoded global
constraint.

Administrators should be able to:

-   Add and configure countries.
-   Enable or disable a country.
-   Set a default country.
-   Assign supported languages.
-   Manage divisions, districts, upazilas, and other regional levels.
-   Assign country managers.
-   Configure country-specific content visibility.
-   Manage country-specific local names and observations.
-   Configure local business settings and currencies where relevant.

### 5.2 Botanical data must be global

A species may occur in multiple countries. Do not create a separate
species record for each country.

Separate:

-   Global taxonomic identity.
-   Accepted scientific name and synonyms.
-   Country-specific distribution.
-   Regional observations.
-   Local/common names.
-   Country-specific editorial content where appropriate.

Suggested tables:

``` text
countries
country_settings
country_languages
country_regions
country_content_settings
plant_distributions
plant_observations
plant_local_names
```

Occurrence records must be sourced and verified. Do not invent a plant's
presence in a region.

Protect precise locations of endangered or vulnerable species where
public disclosure could cause harm.

------------------------------------------------------------------------

## 6. Dynamic multilingual system

Initial languages:

1.  Bengali (`bn`)
2.  English (`en`)

The system must allow languages to be added, disabled, configured, and
archived without code changes for ordinary translation management.

### 6.1 Two localization layers

**Interface localization**

-   Menus and buttons.
-   Forms and validation.
-   Notifications.
-   Dashboard labels.
-   Error messages.
-   System emails.
-   Admin interface.

**Content localization**

-   Plant names and descriptions.
-   Articles and guides.
-   Categories.
-   CMS pages.
-   SEO titles and descriptions.
-   Social sharing metadata.

### 6.2 Language management features

-   Add/edit language.
-   Enable/disable language.
-   Set default language.
-   Set fallback language.
-   Configure locale code and text direction.
-   Manage missing translations.
-   Import/export translation resources.
-   Configure language-specific URLs and SEO.
-   Preserve existing translations when a language is disabled.

Disabling a language must not delete its content. Language deletion
should be restricted and use archival or retention rules.

### 6.3 Suggested tables

``` text
languages
language_translations
country_languages
user_language_preferences

plant_translations
article_translations
category_translations
care_guide_translations
page_translations
```

Keep scientific names, taxonomy identifiers, and other canonical
identifiers language-independent.

------------------------------------------------------------------------

## 7. Dynamic CMS and website page builder

The public website must be manageable through the admin panel.
Administrators should be able to add, edit, reorder, publish, unpublish,
schedule, and remove CMS pages without changing React source code.

### 7.1 Page management

-   Create/edit/delete pages.
-   Custom slug and URL.
-   Draft, review, published, scheduled, archived statuses.
-   Page templates.
-   Language-specific content.
-   Country visibility rules.
-   Preview before publishing.
-   Revision history and rollback.
-   SEO and social metadata.
-   Canonical URL.
-   Publication scheduling.

### 7.2 Page builder

Use reusable, typed sections rather than arbitrary executable HTML.

Suggested section types:

-   Hero/banner.
-   Rich text.
-   Image gallery.
-   Video embed.
-   Plant grid.
-   Featured plants.
-   Category grid.
-   Article grid.
-   Search section.
-   Plant map.
-   Seasonal calendar.
-   Community feed.
-   Statistics.
-   Call to action.
-   FAQ.
-   Newsletter signup.

Administrators should be able to add, remove, duplicate, configure, and
reorder sections.

### 7.3 Controlled application pages

Keep special functional routes as controlled application templates:

-   Plant detail pages.
-   Search results.
-   Login and registration.
-   User profiles.
-   Identification workflows.
-   Account pages.

The CMS may configure content and selected presentation options, but
must not overwrite application logic or execute arbitrary code.

### 7.4 Suggested tables

``` text
pages
page_translations
page_sections
page_section_translations
page_revisions
page_templates
menus
menu_items
website_layouts
website_settings
```

A section should store validated structured settings, for example:

``` json
{
  "type": "hero",
  "settings": {
    "title": "Discover the World of Plants",
    "subtitle": "Explore botanical knowledge",
    "background_image": "media/hero.jpg",
    "button_text": "Explore Plants",
    "button_url": "/plants"
  }
}
```

------------------------------------------------------------------------

## 8. Botanical encyclopedia

Plant Pedia covers all plant groups, not only trees.

Examples:

-   Trees.
-   Shrubs.
-   Herbs.
-   Climbers and creepers.
-   Aquatic plants.
-   Ferns.
-   Grasses.
-   Orchids.
-   Mosses and related groups where included in the chosen scope.
-   Succulents and cacti.
-   Flowering and non-flowering plants.

### 8.1 Taxonomy

Support a structured hierarchy such as:

-   Kingdom.
-   Division.
-   Class.
-   Order.
-   Family.
-   Genus.
-   Species.
-   Subspecies.
-   Variety.
-   Form, where applicable.

Store synonyms, accepted names, taxonomic identifiers, relationships,
references, and taxonomy change history.

Taxonomy is different from user-facing categories. A plant may belong to
multiple categories without changing its scientific classification.

### 8.2 Plant profile

Each plant profile can include:

-   Scientific name.
-   Accepted name and synonyms.
-   Bengali and English common names.
-   Other local names.
-   Taxonomic hierarchy.
-   Description and overview.
-   Botanical characteristics.
-   Growth form and size.
-   Leaf characteristics.
-   Flower characteristics.
-   Fruit and seed characteristics.
-   Bark, stem, root, and other relevant features.
-   Habitat and ecology.
-   Geographic distribution.
-   Growing requirements.
-   Soil, water, light, and temperature preferences.
-   Propagation.
-   Flowering and fruiting season.
-   Uses and ecological importance.
-   Toxicity and safety notes where evidence supports them.
-   Conservation status and threats.
-   References and sources.
-   Image gallery and licenses.
-   Verification status and revision history.

### 8.3 Categories and tags

Examples:

-   Fruit plants.
-   Medicinal plants.
-   Ornamental plants.
-   Timber plants.
-   Native plants.
-   Endemic plants.
-   Exotic plants.
-   Invasive plants.
-   Threatened plants.
-   Indoor and outdoor plants.

Categories must support many-to-many relationships.

### 8.4 Plant media

Image types:

-   Whole plant.
-   Leaf.
-   Flower.
-   Fruit.
-   Bark.
-   Stem.
-   Seed.
-   Root.
-   Habitat.

Store photographer, creator, source, license, attribution, upload date,
and verification status. Do not assume images found online are free to
reuse.

------------------------------------------------------------------------

## 9. Search and discovery

Provide search across:

-   Bengali names.
-   English names.
-   Scientific names.
-   Synonyms.
-   Local names.
-   Family, genus, and species.
-   Categories and tags.
-   Region and habitat.
-   Flower and fruit characteristics.
-   Uses and growing conditions.

Features:

-   Autocomplete.
-   Typo tolerance.
-   Filters and facets.
-   Related plant suggestions.
-   Recently viewed plants.
-   Popular searches.
-   Search analytics.
-   Search result ranking.
-   Synonym-aware matching.

Use Meilisearch or another suitable search engine, with MySQL remaining
the authoritative data store.

------------------------------------------------------------------------

## 10. AI Research Engine

### 10.1 Purpose

The AI Research Engine will help Plant Pedia discover, extract, compare,
organize, and prepare botanical information from approved sources.

It should:

1.  Discover or synchronize data from configured sources.
2.  Extract structured information.
3.  identify scientific names and synonyms.
4.  Match discoveries against existing Plant Pedia records.
5.  Suggest an existing plant when a likely match is found.
6.  Prepare a new plant draft when no suitable match is found.
7.  Suggest field-level updates to existing plants.
8.  Generate original Bengali and English draft content from permitted
    source evidence.
9.  Show source provenance and differences.
10. Send proposed changes into a human review workflow.

### 10.2 Research sources

Potential sources to assess:

-   Plants of the World Online (Kew): https://powo.science.kew.org/
-   GBIF: https://www.gbif.org/
-   Bangladesh National Herbarium e-Flora:
    https://plantsp-eflora.bnh.gov.bd/
-   Bangladesh Forest Information System:
    https://bfis.bforest.gov.bd/TreeInfo/public/
-   Pl@ntNet: https://my.plantnet.org/
-   Licensed datasets, scientific publications, and institutional
    sources.

Before ingesting any source, record its terms, API conditions, license,
attribution requirements, rate limits, and permitted reuse. Public
accessibility does not automatically grant permission to copy text or
images.

### 10.3 Data ingestion workflow

``` text
Configured source
      |
Scheduled fetch / API sync / authorized crawler
      |
Raw document and metadata storage
      |
Extraction and normalization
      |
Scientific name and taxonomy resolution
      |
Existing plant matching
      |
+----------------------------+
|                            |
Likely existing plant     Potential new plant
|                            |
Field-level update        New plant draft
proposal                  proposal
|                            |
+-------------+--------------+
              |
       Evidence comparison
              |
      Human/editor review
              |
   Expert verification if needed
              |
       Approved publication
```

### 10.4 Existing plant matching

Matching must use multiple signals:

-   Accepted scientific name.
-   Synonyms.
-   Taxonomic identifiers.
-   Genus and family.
-   Local and common names.
-   External source identifiers.
-   Existing references.
-   Similarity of structured botanical attributes.

Do not rely on an AI language model's confidence statement alone. Use
deterministic matching rules, normalized identifiers, fuzzy matching,
and evidence. Show uncertainty and possible alternatives.

Possible outcomes:

-   High-confidence existing match.
-   Possible existing match requiring review.
-   No likely match; propose new record.
-   Conflicting taxonomy; send to expert review.

### 10.5 AI content generation

AI may prepare drafts for:

-   Bengali and English descriptions.
-   Botanical characteristics.
-   Habitat and distribution summaries.
-   Growing and care guidance.
-   Flowering and fruiting information.
-   Uses and ecological importance.
-   SEO title and description.
-   FAQs and article outlines.

Requirements:

-   Synthesize facts rather than closely paraphrase copyrighted
    passages.
-   Retain source references and attribution.
-   Track model/provider, generation time, prompt/version, and input
    sources.
-   Distinguish extracted facts from AI-generated prose.
-   Identify unsupported or conflicting claims.
-   Never invent references, occurrence records, conservation status, or
    medicinal claims.
-   Mark AI-generated drafts clearly.
-   Require human review before publication.

### 10.6 Field-level comparison

When AI proposes an update, show:

-   Existing value.
-   Proposed value.
-   Supporting sources.
-   Source date and license.
-   Reason for the proposal.
-   Confidence/evidence indicators.
-   Accept, reject, edit, or request-more-evidence actions.

Reviewers should approve individual fields rather than being forced to
accept or reject a whole plant update.

### 10.7 AI administration

Admin features:

-   Source registry.
-   API credentials stored securely.
-   Crawl/sync schedules.
-   Per-source rate limits.
-   Country and data-type coverage.
-   Import queue.
-   Failed-job retry.
-   Extraction logs.
-   Duplicate candidates.
-   Match review queue.
-   Draft content queue.
-   Cost and token usage tracking.
-   Provider configuration.
-   Prompt/template versioning.
-   Audit history.

### 10.8 AI provider abstraction

Use provider adapters so the platform can change models or vendors
without rewriting the research workflow. Keep provider secrets on the
server. Use queued jobs, retries, timeouts, idempotency, usage limits,
and cost controls.

------------------------------------------------------------------------

## 11. Plant identification

A future AI identification feature should support:

-   Upload from gallery.
-   Camera capture in mobile apps.
-   Whole plant, leaf, flower, fruit, bark, and seed images.
-   Three to five candidate species where available.
-   Candidate evidence and confidence indicators.
-   Mapping results to Plant Pedia's existing taxonomy.
-   User feedback and correction.
-   Identification history.
-   Usage quotas and rate limits.

Start with a provider adapter such as Pl@ntNet where terms and access
permit. Do not claim that identification is always accurate. Show
uncertainty and recommend expert review for consequential decisions.

------------------------------------------------------------------------

## 12. Community and user contributions

### 12.1 Comments

Support comments on:

-   Plant profiles.
-   Articles.
-   Guides.
-   Community posts.
-   Identification results.

Features:

-   Replies and nesting.
-   Edit/delete own comments.
-   Reporting.
-   Moderation.
-   Spam controls.
-   Optional comment reactions.
-   Audit trail.

### 12.2 Likes and dislikes

Users can like, dislike, or remove their reaction on supported content.

Rules:

-   One reaction per user per target.
-   A user can change their reaction.
-   A user cannot simultaneously like and dislike the same target.
-   Use database uniqueness constraints.
-   Rate-limit reaction endpoints.
-   Do not treat reactions as scientific verification.

### 12.3 Reviews

Use reviews for suitable entities such as nurseries, services, or
educational resources.

Features:

-   One-to-five-star ratings.
-   Written review.
-   Optional images.
-   Edit own review.
-   Report review.
-   Moderation.
-   Business response.
-   Verified purchase/customer label only when supported by actual
    transaction data.

Do not use commercial reviews as expert assessment of botanical facts.

### 12.4 User submissions

Users with appropriate permissions may submit:

-   New plant suggestions.
-   Additional local names.
-   New photographs.
-   Distribution observations.
-   Corrections.
-   References.
-   Articles and educational content.

Suggested statuses:

``` text
draft
submitted
under_review
needs_revision
approved
published
rejected
archived
```

Keep submission revisions and review decisions.

### 12.5 Field observations

Allow users and researchers to submit:

-   Plant identification or proposed taxon.
-   Photos.
-   Observation date.
-   Location and habitat.
-   Notes.
-   Observer information.
-   Evidence and verification status.

Support sensitive-location privacy controls.

------------------------------------------------------------------------

## 13. Expert verification and data quality

Use explicit verification states, for example:

-   Unverified.
-   Community reviewed.
-   Editor verified.
-   Expert verified.

Verification should be scoped to the relevant claim or record where
possible, rather than implying that every field on a plant page has been
verified equally.

Maintain:

-   Source references.
-   Reviewer identity.
-   Verification date.
-   Decision and reason.
-   Revision history.
-   Conflicting evidence.
-   Taxonomy update history.

Provide a correction and dispute process.

------------------------------------------------------------------------

## 14. SEO and social sharing

This is a core launch requirement.

Every public, shareable page must have its own metadata and attractive
social preview.

### 14.1 Open Graph and social metadata

Support:

-   `og:title`
-   `og:description`
-   `og:image`
-   `og:url`
-   `og:type`
-   Twitter/X card metadata
-   Canonical URL
-   Language-specific metadata
-   Structured data where appropriate

Page types:

-   Plant profiles.
-   Categories.
-   Articles.
-   CMS pages.
-   Identification results where public and appropriate.
-   Nursery profiles.
-   Educational guides.
-   Seasonal pages.

### 14.2 Social preview image generation

Provide:

-   Plant-specific cover image.
-   Default fallback image.
-   Language-aware title and description.
-   Consistent Plant Pedia branding.
-   Image validation and appropriate dimensions.
-   Optional generated branded cards.
-   Preview testing tools.

Social crawlers should receive metadata in server-rendered HTML. Do not
depend on JavaScript execution. Social platforms may cache previews;
provide a process for refreshing/debugging cached metadata.

### 14.3 SEO features

-   SEO title and description.
-   Canonical URL.
-   Language-specific URLs and hreflang.
-   XML sitemap and image sitemap.
-   Robots directives.
-   Breadcrumbs.
-   Internal links.
-   Structured data where valid.
-   Optimized images and alt text.
-   Redirect management.
-   404 and 410 handling.
-   Search indexing controls.
-   Duplicate-content protection.

Never publish structured data that misrepresents reviews, ratings,
authorship, or scientific status.

------------------------------------------------------------------------

## 15. Public website structure

Suggested pages:

``` text
/
 /plants
 /plants/{slug}
 /categories
 /categories/{slug}
 /taxonomy
 /families/{slug}
 /identify
 /compare
 /plant-map
 /calendar
 /plant-care
 /articles
 /articles/{slug}
 /community
 /community/{slug}
 /observations
 /nurseries
 /nurseries/{slug}
 /marketplace
 /account
 /favorites
 /collections
 /about
 /contributors
 /experts
 /sources
 /contact
 /privacy
 /terms
```

The CMS should allow additional editorial pages and landing pages
without code changes.

### Plant detail page layout

1.  Main image gallery.
2.  Bengali and English common names.
3.  Scientific name.
4.  Taxonomy.
5.  Overview.
6.  Botanical characteristics.
7.  Distribution and habitat.
8.  Growing requirements.
9.  Uses and safety notes.
10. Flowering and fruiting season.
11. Conservation information.
12. References and source citations.
13. Related plants.
14. Expert verification information.
15. Community observations.
16. Comments and reactions.
17. Favorite and share actions.

------------------------------------------------------------------------

## 16. Admin panel structure

Suggested navigation:

``` text
Dashboard

Plant Management
Taxonomy Management
Categories & Attributes
Countries & Regions
Languages & Translations

AI Research Engine
AI Content Assistant
Data Sources & Import
AI Provider Settings

Website Page Builder
Pages & Templates
Menus & Navigation
SEO & Social Sharing
Media Library

Articles & Knowledge Hub
Users & Contributors
Roles & Permissions
Expert Profiles

Comments & Community
Reviews & Moderation
Submissions & Verification
Observations & Distribution

Plant Identification
Plant Calendar
Maps

Nurseries
Marketplace
Subscriptions

Notifications
Analytics & Reports
API Management
System Settings
Audit Logs
```

Dashboard metrics must come from actual data, not hardcoded sample
values.

------------------------------------------------------------------------

## 17. Database domains and table candidates

The following is a planning inventory. Final normalization, keys,
indexes, and constraints should be established in the ERD stage.

### Taxonomy

``` text
taxonomic_ranks
taxa
taxon_synonyms
taxonomic_identifiers
taxon_relationships
taxon_references
taxon_change_logs
```

### Plants

``` text
plants
plant_translations
plant_names
plant_local_names
plant_categories
plant_category_map
plant_attributes
plant_characteristics
plant_uses
plant_care_guides
plant_propagation
plant_seasonality
plant_conservation_records
```

### Media and evidence

``` text
media
plant_media
media_licenses
media_attributions
plant_references
plant_fact_references
content_attributions
```

### Geography and observations

``` text
countries
country_settings
country_languages
country_regions
plant_distributions
plant_observations
observation_media
```

### CMS and SEO

``` text
pages
page_translations
page_sections
page_section_translations
page_templates
page_revisions
menus
menu_items
website_layouts
website_settings
seo_metadata
social_share_templates
social_share_images
```

### AI and data ingestion

``` text
data_sources
data_source_licenses
data_source_sync_logs
data_source_documents
ai_research_tasks
ai_research_documents
ai_research_extractions
ai_research_candidates
ai_plant_matches
ai_content_drafts
ai_content_revisions
ai_field_suggestions
ai_generation_logs
ai_provider_settings
duplicate_detection_logs
data_quality_reports
```

### Community and moderation

``` text
comments
reactions
reviews
review_media
review_reports
content_reports
community_posts
community_post_media
content_submissions
submission_revisions
submission_reviews
moderation_logs
expert_profiles
expert_verifications
```

### Users and security

``` text
users
user_profiles
user_devices
user_sessions
refresh_tokens
roles
permissions
role_permissions
user_roles
user_language_preferences
favorites
user_collections
audit_logs
```

### Identification

``` text
identification_requests
identification_images
identification_results
identification_providers
identification_feedback
identification_usage
```

### Commercial and platform

``` text
nurseries
nursery_locations
nursery_products
nursery_plant_inventory
business_reviews
business_subscriptions
products
sellers
orders
order_items
payments
commissions
plans
subscriptions
advertisements
notifications
api_clients
api_usage_logs
settings
```

Use relational columns and foreign keys for core domain data. Use JSON
only for genuinely flexible configuration, such as validated
page-section settings or provider-specific metadata. Do not place all
botanical information into one large JSON field.

------------------------------------------------------------------------

## 18. Additional features recommended

These features are not all MVP requirements, but they provide
significant long-term value.

### High-priority additions

1.  **Fact-level evidence management:** Attach sources to individual
    botanical facts, not only to the whole plant profile.
2.  **Plant comparison:** Compare species by taxonomy, leaves, flowers,
    fruits, habitat, and care requirements.
3.  **Field observations:** Build a verified community biodiversity
    observation dataset.
4.  **Taxonomy change tracking:** Preserve history when names and
    classifications change.
5.  **Regional growing calendar:** Show planting, flowering, fruiting,
    and propagation seasons by region.
6.  **Conservation protection:** Hide precise locations for sensitive
    species.
7.  **Expert profiles:** Display credentials, expertise areas, and
    reviewed contributions without overstating qualifications.
8.  **Plant glossary:** Explain botanical terminology in Bengali and
    English.
9.  **Collections:** Allow users to organize favorite plants into
    folders.
10. **QR codes:** Provide QR pages for gardens, campuses, botanical
    collections, and educational displays.

### Future opportunities

-   Public developer API plans.
-   Research data exports in CSV/JSON.
-   Institutional accounts for universities and botanical gardens.
-   Educational lesson plans and quizzes.
-   Nursery directory and verified business profiles.
-   Plant marketplace.
-   Sponsored educational content with clear labeling.
-   Newsletter and plant update subscriptions.
-   Offline-friendly mobile experience.
-   Accessibility features and screen-reader support.

------------------------------------------------------------------------

## 19. Security, privacy, and reliability

-   Validate and authorize all API requests.
-   Protect admin routes independently.
-   Use secure JWT and refresh-token practices.
-   Apply rate limits and abuse prevention.
-   Validate file type, size, and content for uploads.
-   Store secrets server-side.
-   Use CSRF protection where cookie-based authentication requires it.
-   Configure CORS narrowly.
-   Protect against SQL injection, XSS, SSRF, and unsafe HTML.
-   Sanitize CMS content and prohibit arbitrary executable scripts.
-   Use audit logs for administrative and scientific changes.
-   Back up database and media.
-   Test restore procedures.
-   Encrypt sensitive data where appropriate.
-   Apply retention and deletion policies.
-   Protect exact locations of vulnerable species and private user
    observations.
-   Keep AI source ingestion isolated and treat source content as
    untrusted data.
-   Do not allow crawled documents to instruct or override AI system
    behavior.
-   Monitor failed jobs, queue backlogs, provider costs, and API errors.

------------------------------------------------------------------------

## 20. Performance and operations

-   Redis caching for frequently accessed content.
-   Queue long-running AI and ingestion tasks.
-   Use idempotent jobs and retry policies.
-   Optimize image sizes and formats.
-   Use object storage and CDN.
-   Use database indexes and query profiling.
-   Cache public pages where appropriate.
-   Use search indexing for discovery.
-   Add monitoring, structured logs, error reporting, and uptime checks.
-   Separate development, staging, and production environments.
-   Automate migrations and deployment checks.
-   Maintain database and media backups.

------------------------------------------------------------------------

## 21. Testing and quality assurance

Required test areas:

-   Authentication and token refresh/revocation.
-   Permission and role enforcement.
-   Client/admin API separation.
-   Country and language configuration.
-   Translation fallback behavior.
-   Plant taxonomy integrity.
-   Duplicate detection and synonym matching.
-   AI ingestion and provenance.
-   Human approval workflow.
-   Page builder validation and rendering.
-   Social metadata output.
-   Comments, reactions, and reviews.
-   Upload validation and media permissions.
-   Search filtering and indexing.
-   Rate limits and abuse controls.
-   API contract and mobile-client compatibility.
-   Accessibility and responsive layouts.
-   Backup restoration.

Use unit tests, feature/API tests, frontend component tests, and
end-to-end tests for critical workflows.

------------------------------------------------------------------------

## 22. Development roadmap

### Phase 1 --- Foundation

-   Repository and Docker setup.
-   Laravel API.
-   Single React application.
-   Public and admin route groups.
-   JWT authentication.
-   Roles and permissions.
-   API response standards.
-   Logging, audit, and testing foundation.

### Phase 2 --- Global structure and localization

-   Country management.
-   Bangladesh regions.
-   Language management.
-   Bengali and English resources.
-   Translation-aware content structure.
-   User language preference.

### Phase 3 --- Botanical core

-   Taxonomy.
-   Scientific names and synonyms.
-   Plant management.
-   Categories and attributes.
-   Media and licensing.
-   References and evidence.
-   Verification and revision history.

### Phase 4 --- Dynamic CMS

-   Pages and templates.
-   Page builder.
-   Reusable sections.
-   Menus and navigation.
-   Page revisions and scheduling.
-   Language/country visibility.
-   SEO fields.

### Phase 5 --- Public website

-   Homepage.
-   Plant directory and profiles.
-   Categories and taxonomy browsing.
-   Search.
-   Articles.
-   Social sharing previews.
-   SSR/prerendering.
-   Sitemap and structured metadata.

### Phase 6 --- Community

-   User profiles.
-   Comments and replies.
-   Likes and dislikes.
-   Reviews.
-   Favorites and collections.
-   User submissions.
-   Moderation.
-   Expert review workflow.

### Phase 7 --- AI Research Engine

-   Source registry.
-   Approved API/crawler integrations.
-   Data ingestion jobs.
-   Extraction and normalization.
-   Taxonomy matching.
-   Existing-plant suggestions.
-   New-plant draft proposals.
-   Field-level comparison.
-   AI content drafting.
-   Human approval and provenance.

### Phase 8 --- Advanced botanical features

-   Plant identification.
-   Plant comparison.
-   Observations and maps.
-   Seasonal calendar.
-   Conservation data.
-   Notifications.

### Phase 9 --- Commercial platform

-   Nursery directory.
-   Business profiles.
-   Subscriptions.
-   Sponsored listings.
-   Marketplace.
-   Payments and commissions.
-   Public API access plans.

### Phase 10 --- International expansion

-   Add countries.
-   Add languages.
-   Add country-specific regions and data sources.
-   Recruit local experts and contributors.
-   Configure local content and commercial rules.

------------------------------------------------------------------------

## 23. MVP scope

The first release should be useful without attempting every future
feature.

  Feature                                    MVP
  ------------------------------------------ ----------------------------
  One React frontend for website and admin   Yes
  Laravel API only                           Yes
  Common client API                          Yes
  Private admin API                          Yes
  JWT authentication                         Yes
  Roles and permissions                      Yes
  Bangladesh configuration                   Yes
  Bengali and English                        Yes
  Dynamic language management                Yes
  Botanical taxonomy                         Yes
  Plant profiles and categories              Yes
  Media, references, and verification        Yes
  Dynamic CMS page builder                   Yes
  Public SSR/prerendering                    Yes
  SEO and social previews                    Yes
  Search                                     Yes
  Comments and reactions                     Yes
  User submissions and moderation            Yes
  Reviews                                    Yes, for suitable entities
  Basic AI research and plant matching       Yes
  Full AI crawler network                    Later
  AI plant identification                    Later
  Advanced maps and calendar                 Later
  Nursery marketplace                        Later
  Native mobile apps                         Later, using common API

------------------------------------------------------------------------

## 24. Data sources and content governance

Potential initial reference sources:

-   Bangladesh National Herbarium: https://bnh.gov.bd/
-   Bangladesh National Herbarium e-Flora:
    https://plantsp-eflora.bnh.gov.bd/
-   Bangladesh Forest Information System:
    https://bfis.bforest.gov.bd/TreeInfo/public/
-   Plants of the World Online: https://powo.science.kew.org/
-   GBIF: https://www.gbif.org/
-   Pl@ntNet: https://my.plantnet.org/

Before importing information, confirm each source's licensing and reuse
rules.

For every imported or contributed item, record where applicable:

-   Source and source URL.
-   Creator or publisher.
-   License and attribution.
-   Access/import date.
-   External identifier.
-   Original source language.
-   AI processing history.
-   Reviewer and verification state.

Never fabricate scientific references, distribution records,
conservation classifications, medicinal effects, or expert endorsements.
Clearly label uncertain, approximate, or unverified information.

------------------------------------------------------------------------

## 25. Recommended implementation rules for the development team or AI coding agent

1.  Analyze the requirements and produce an ERD before implementing
    modules.
2.  Build a modular monolith; do not introduce microservices
    prematurely.
3.  Keep Laravel as the only backend and API layer.
4.  Use one React frontend codebase for public and admin experiences.
5.  Use one common client API for website, mobile, and authorized
    external clients.
6.  Keep the admin API in a separate protected route namespace with
    stricter authorization.
7.  Share domain services between client and admin controllers.
8.  Do not duplicate plant, taxonomy, user, or permission logic.
9.  Keep country and language configuration data-driven.
10. Separate global taxonomy from country-specific distribution.
11. Separate interface translations from content translations.
12. Keep scientific facts, source evidence, AI drafts, and published
    prose distinguishable.
13. Do not automatically publish AI-generated botanical content.
14. Build the CMS using validated reusable section types.
15. Render public metadata server-side for SEO and social previews.
16. Use database constraints to prevent duplicate reactions and other
    invalid states.
17. Keep controllers thin and business logic in services/actions.
18. Use Form Requests, Policies, API Resources, queues, events, and
    tests appropriately.
19. Document every common API endpoint and maintain contract tests.
20. Never hardcode sample statistics or pretend demo data is verified.
21. Keep secrets and AI provider credentials on the server.
22. Build responsive, accessible interfaces with Bengali font support.
23. Preserve audit and revision history for important data changes.
24. Implement features phase by phase and keep each phase testable.

------------------------------------------------------------------------

## 26. First implementation deliverables

Before writing the full application, produce these technical documents:

1.  Software Requirements Specification (SRS).
2.  System architecture diagram.
3.  Complete database ERD.
4.  Database table and relationship specification.
5.  Laravel module/folder structure.
6.  Common client API specification.
7.  Private admin API specification.
8.  JWT authentication and token lifecycle design.
9.  Role/permission matrix.
10. Country and language management design.
11. CMS and page-builder schema.
12. AI research and source-ingestion workflow.
13. AI matching and field-level review design.
14. Botanical data provenance and verification policy.
15. Public website sitemap and page specifications.
16. Admin panel navigation and page specifications.
17. UI design system and responsive component inventory.
18. SEO and social-sharing implementation specification.
19. Security and privacy threat model.
20. Testing and deployment plan.

Only after these are reviewed should implementation proceed module by
module.

------------------------------------------------------------------------

## 27. Final product definition

**Plant Pedia** is a global botanical knowledge, discovery, and
community platform, launching with Bangladesh and Bengali/English
support.

Its defining capabilities are:

-   A global botanical encyclopedia.
-   A multilingual and multi-country content system.
-   A dynamic website CMS and page builder.
-   One React frontend for the public website and admin panel.
-   One Laravel API backend.
-   One common API for website, mobile, and authorized client
    applications.
-   A private, separately protected admin API.
-   JWT authentication and configurable permissions.
-   Community participation and expert verification.
-   AI-assisted botanical research, matching, and content preparation.
-   Strong source provenance, SEO, and social sharing previews.
-   A future path to mobile applications, institutional services,
    nurseries, and commerce.

**The foundational priority is trustworthy botanical data.** The
taxonomy, evidence/provenance, multilingual model, permissions, CMS, and
AI review workflow should be designed correctly before scaling the
volume of content.
