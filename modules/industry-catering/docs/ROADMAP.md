# Catering Module Roadmap

## Current release blocker — CORE-REUSE-7A

The shared Core inquiry-detail query references the nonexistent admins.name
column. Dependency recorded in the [active Core roadmap](https://github.com/afterburn25/Website_Core/blob/devin/website-core/docs/ROADMAP.md#core-reuse-7a--inquiry-detail-schema-regression-2026-10-25).
Module-owned records and linking remain intact. Production acceptance waits
for Core to fix detail/notes rendering; do not duplicate that workspace here.

## 0.2.0 — Catering menu planning

- [x] Select dishes and packages and retain choices through validation errors
- [x] Enforce included category choices and package guest ranges on the server
- [x] Calculate per-guest, rounded tray and fixed-event food pricing
- [x] Support minimum billing quantities and optional guest-count discounts
- [x] Edit dishes/packages and structured dietary/allergen information
- [x] Show business-verified dietary filters and cross-contact guidance
- [x] Retain authoritative inquiry snapshots and notify through Core mail
- [ ] Finish reconciled-candidate acceptance and record GitHub CI evidence
- [ ] Pass module-specific PHP 8.4/8.5 and native MySQL/MariaDB CI
- Core synchronization and combined-bundle testing remain owner-deferred.

## 0.1.25 — Core 2.2 shared-service adoption

- [x] Declare the Core-owned business profile and seed only blank values from
  existing Catering settings
- [x] Render the shared business name, email, phone, and service area publicly
- [x] Remove duplicate business identity fields from Catering settings
- [x] Declare Core inquiries and itemized quotations as Catering capabilities
- [x] Link preserved legacy questionnaires to Core inquiries without deleting
  the detailed event records
- [x] Send new contact and quote forms into both the detailed Catering record
  and the Core sales workspace without duplicate notification emails
- [x] Redirect historical Catering inquiry bookmarks to Core's inquiry screen
- [x] Remove the duplicate Catering lead navigation, dashboard widget, search
  provider, and retired inquiry view
- [x] Render module administration through Core's shared admin renderer
- [ ] Verify the migration from 0.1.24 against packaged Core 2.2 on SQLite,
  MySQL, MariaDB, and the shared host
- [ ] Pass module-branch CI before publishing the 0.1.25 ZIP

## 0.1.23 — Shared-host upload compatibility

- [x] Verify the live site never recorded the attempted 0.1.22 stage operation
- [x] Reduce the complete module archive below common 2 MB request limits
- [x] Preserve visual quality and responsive crops for all ten menu photographs
- [ ] Verify direct 0.1.21 → 0.1.23 upgrade on the live shared host

## 0.1.22 — Cajun menu and photography

- [x] Replace generic dishes with a cohesive Louisiana Cajun starter menu
- [x] Create and optimize a unique, relevant photograph for every dish
- [x] Render the photographs across homepage, menu, detail, and admin surfaces
- [x] Upgrade existing demo data without overwriting unrelated custom items
- [x] Add a validated dish-image selector for newly created menu items
- [ ] Pass module-branch and synchronized Website Core bundle CI

## 0.1.21 — Transparent footer brand

- [x] Remove the opaque footer frame from the transparent Core-owned logo
- [x] Preserve the original logo artwork and branding ownership contract
- [x] Follow Core's shared social-placement setting without module-owned UI
- [ ] Pass module-branch and synchronized Website Core bundle CI

## 0.1.20 — Core-owned social placement

- [x] Render configured social icons after the header quote action
- [x] Render configured social icons above the footer copyright
- [x] Honor header, footer, and both placement per platform
- [x] Omit blank profiles entirely
- [ ] Pass module-branch and synchronized Website Core bundle CI

## 0.1.19 — Unified Core overview

- [x] Keep the live inquiry summary on Core's main Dashboard
- [x] Remove the duplicate catering dashboard route, view, and navigation item
- [x] Preserve direct links from the Core widget to the inquiry workspace
- [ ] Pass module-branch and synchronized Website Core bundle CI

## 0.1.18 — Editable footer identity

- [x] Declare a one-time default footer navigation location
- [x] Render Core's current footer tree with nested items and link targets
- [x] Render the Core-owned token-aware copyright notice
- [x] Preserve the immutable Cajun Web Pro attribution as a separate Core layer
- [ ] Pass module-branch and synchronized Website Core bundle CI

## 0.1.17 — Core-owned branding

- [x] Bundle the approved transparent generic CATERING CO. logo
- [x] Declare the logo through Core's one-time `brandAssets()` handoff
- [x] Render the live Core logo and favicon in public and module-admin layouts
- [x] Preserve accessible text branding when no logo is selected
- [ ] Pass module-branch and synchronized Website Core bundle CI

## 0.1.16 — Editable site navigation

- [x] Render the Core-managed primary menu in the catering header
- [x] Populate an empty primary menu with the current catering navigation once
- [x] Render nested submenu items responsively
- [x] Honor same-tab, new-tab, and new-window targets
- [x] Retain the approved default header links as an empty-menu fallback

## 0.1.15 — Repository separation and verified baseline

- [x] Complete public catering page set
- [x] Complete catering administration category
- [x] Integrate with Website Core installer and package system
- [x] Verify Website Core 2.0.0 keyless testing bundle
- [x] Establish dedicated branch, module folder, documentation, and release ZIP

## Shared Core dependencies — 2026-10-25

See the [Core shared-service planning backlog](https://github.com/afterburn25/Website_Core/blob/devin/website-core/docs/ROADMAP.md)
(`CORE-REUSE-1` through `CORE-REUSE-8`). Existing Core content, forms,
Media, branding, permissions and notification services must be reused first.
Core 2.2 now supplies the business-profile, shared admin-rendering, inquiry,
and quotation foundations adopted in 0.1.25. Remaining content/media, form,
and module-SDK entries still require their documented Core APIs. Defer only
the work that actually requires a missing supported API. Do not create
module copies of the shared engines or implement Core changes here. Verify
supported Core service APIs before replacing existing module behavior, preserve
administrator content and inquiry history, and record compatibility evidence.
Core synchronization and combined packaging still require an explicit request.

## 0.2.x — Content and media management follow-up

- [ ] Add first-class editing for public catering page copy and calls to action
- [ ] Select or replace catering imagery through Core media management
- [ ] Replace the bundled dish selector with first-class Core Media selection
- [ ] Improve menu-item and package editing and ordering controls
- [ ] Add draft/preview behavior for catering content changes
- [x] Add isolated module tests for controllers, permissions, and migrations (0.2.0)

## 0.3.0 — Leads and operations

- [x] Move inquiry status, priority, assignment, and internal notes to Core
- [ ] Add follow-up dates and reminders when Core exposes the shared service
- [ ] Add configurable quote-request fields and event types
- [ ] Add CSV inquiry export with permission and audit controls
- [ ] Add notification templates using Core mail services
- [ ] Add spam and abuse reporting to the admin dashboard
- [ ] Add attachments and generated proposal documents through Core services

## 0.4.0 — Catalog depth

- [x] Add dietary, allergen, serving-size, and food-pricing options (0.2.0)
- [ ] Add seasonal menus and limited-availability packages
- [ ] Add package/menu relationships and reusable item groups
- [ ] Add structured data for services, menus, FAQs, and local service areas

## 1.0.0 — Production-stable module

- [ ] Complete accessibility, performance, security, and responsive review
- [ ] Complete clean install, upgrade, reinstall, backup, and rollback matrix
- [ ] Document supported Core versions and module upgrade policy
- [ ] Publish production release notes and operator guide
- [ ] Prove the same shared-service contracts with the Landscaping module


## Later catering operations backlog

- [ ] Service-style rules and optional staffing/rental/setup/cleanup extras
- [ ] Event lead times, blackout dates, capacity and pickup/delivery windows
- [ ] Delivery zones, travel fees, minimum delivery orders and setup requirements
- [ ] Tasting requests and industry-specific eligibility rules
- [ ] Event planning workspace, timeline and final-headcount deadlines
- [ ] Kitchen preparation, portion, packing, delivery and staff worksheets
- [ ] Venue partnerships, event case studies and event-specific review content
- [ ] Quote-only, online-order and mixed modes using Core commerce/accounts
      when explicitly integrated; never create a parallel cart or payment engine
