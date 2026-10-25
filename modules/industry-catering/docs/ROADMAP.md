# Catering Module Roadmap

## Resolved Core dependency — CORE-REUSE-7A

Core 2.2.2 repaired the administrator-field query. Catering 0.2.1 pins Core
2.3 and promotes the detail/notes diagnostic to a required CI check. The
module continues to preserve its questionnaire snapshot and use Core's shared
inquiry workspace.

## 0.2.1 — Shared catalog administration

- [x] Register module-owned dishes and packages with Core's shared resource workspace
- [x] Add searchable quick edits while preserving advanced Catering fields
- [x] Add accessible partial ordering and bulk visibility/featured actions
- [x] Reject unknown, duplicate and cross-resource record IDs in callbacks
- [x] Pass PHP 8.4/8.5, SQLite, MySQL 8.4 and MariaDB 11.4 acceptance
- [x] Verify direct packaged upgrade from 0.2.0
- [x] Verify the fixed Core inquiry detail and notes contract

## 0.2.0 — Catering menu planning

- [x] Select dishes and packages and retain choices through validation errors
- [x] Enforce included category choices and package guest ranges on the server
- [x] Calculate per-guest, rounded tray and fixed-event food pricing
- [x] Support minimum billing quantities and optional guest-count discounts
- [x] Edit dishes/packages and structured dietary/allergen information
- [x] Show business-verified dietary filters and cross-contact guidance
- [x] Retain authoritative inquiry snapshots and notify through Core mail
- [x] Finish module acceptance and record GitHub CI evidence (run `40216516597`; Core dependency remains blocked)
- [x] Pass module-specific PHP 8.4/8.5 and native MySQL 8.4/MariaDB 11.4 CI
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
(`CORE-REUSE-1` through `CORE-REUSE-10`). Existing Core content, forms,
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
- [x] Improve menu-item and package editing and ordering controls (0.2.1)
- [ ] Add draft/preview behavior for catering content changes
- [x] Add isolated module tests for controllers, permissions, and migrations (0.2.0)

## 0.3.0 — Leads and operations

- [x] Move inquiry status, priority, assignment, and internal notes to Core
- [ ] Add follow-up dates and reminders when Core exposes the shared service
- [x] Add configurable quote-request fields and event types
- [ ] Add CSV inquiry export with permission and audit controls
- [ ] Add notification templates using Core mail services
- [ ] Add spam and abuse reporting to the admin dashboard
- [ ] Add attachments and generated proposal documents through Core services

## 0.4.0 — Catalog depth

- [x] Add dietary, allergen, serving-size, and food-pricing options (0.2.0)
- [x] Add seasonal menus and limited-availability packages
- [x] Add package/menu relationships and reusable item groups
- [ ] Add structured data for services, menus, FAQs, and local service areas
      after Core exposes the module SEO/schema contribution contract in
      CORE-REUSE-3 (roadmap commit
      `30349cff3bfa971cbed359ead041850bc58d14e7`)

## 1.0.0 — Catering operations and production baseline

- [x] Add service-style guest limits, service-specific lead times, blackout
      dates, and a per-request capacity ceiling
- [x] Add pickup/delivery windows, delivery zones, fees, food minimums, and
      optional fixed/per-guest service lines
- [x] Preserve an immutable operations snapshot with the menu snapshot and
      summarize both through Core's inquiry/mail services
- [x] Keep estimates explicitly quote-only with no parallel booking, cart,
      account, invoice, or payment engine

## 1.0.0 — Production-stable module

- [x] Complete accessibility, performance, security, and responsive review
      with automated release-readiness checks and retained desktop/mobile
      browser evidence
- [x] Complete the module-owned clean install, upgrade, disable/enable,
      uninstall-without-purge, and data-preserving reinstall matrix; document
      Core-owned whole-site backup/restore and the no-downgrade policy
- [x] Document supported Core versions and module upgrade policy
- [x] Publish production release notes and operator guide
- [x] Prove shared Module API navigation, branding, admin, health, identity,
      namespace, and table-isolation contracts against Landscaping 1.0.0 at
      commit `be009de567286b16ea60e4188b6e7eb34f482b83` without combining packages


## 1.1.0 — Confirmed-event workspace and service-area map

- [x] Convert an accepted Core quotation into one module-owned confirmed event
      without re-entering the questionnaire, menu or operations snapshot
- [x] Add tentative, confirmed, completed and cancelled event states with a
      calendar/list workspace and an audit-safe change history
- [x] Add event timelines, final-headcount deadlines and task ownership
- [ ] Send deadline reminders through Core's shared notification services
- [x] Add tasting request review, eligibility and scheduling states
- [ ] Add administrator-configurable tasting capacity and lead-time rules
- [ ] Let an administrator set the shared service-area center by city/state and
      select a mileage radius in Core's business profile; render the generated,
      accessible radius map on Catering's Service Area page through
      `CORE-REUSE-9`
- [ ] Keep postal delivery zones authoritative for quote validation; the radius
      map is customer guidance and must not silently promise service at every
      address inside the circle
- [ ] Collect a structured event/delivery address when the selected fulfillment
      method requires one and validate it through Core on both preview and final
      submission; never trust a browser-only distance result
- [ ] Show an accessible field error when Core confirms that the address is
      outside the configured radius: “This address is currently outside our
      service area.” Preserve the form values and offer the business contact
      path for a manually approved exception
- [ ] Treat ambiguous addresses and unavailable geocoding as unverified/manual
      review instead of incorrectly telling a customer that they are out of area

The shared geocoder, provider configuration, coordinate cache, privacy rules
and map renderer belong to Website Core because multiple industries need them.
The Core roadmap dependency was recorded as `CORE-REUSE-9` and its address
validation contract was completed in roadmap commit
`da86fc19551d191d4dc55572d42fef4cb92aa7a8`. Catering must not ship a private
map-provider integration while that contract is unavailable.

## 1.2.0 — Production and fulfillment

- [x] Scale recipes, yields and ingredient quantities from the final headcount
- [x] Produce kitchen preparation sheets grouped by station and preparation date
- [x] Produce portion, packing, container, dietary and allergen labels/checklists
- [x] Track equipment/rental pull, load, return, damage and replacement tasks
- [x] Assign catering-specific positions and shifts without duplicating Core
      administrator accounts or permissions
- [x] Produce delivery manifests, driver assignments, route order, venue
      contacts, setup instructions and status/proof records
- [x] Highlight post-production event changes so affected teams acknowledge them

## 1.3.0 — Proposals, contracts and customer completion

- [ ] Generate branded proposals and Banquet Event Orders from the same accepted
      event record through Core's shared document service
- [ ] Add contract templates, versioned terms, acceptance and electronic
      signatures through Core rather than storing signatures in the module
- [ ] Let customer accounts review the event, approve revisions, upload files,
      confirm headcount, sign documents and view status
- [ ] Use Core invoices, deposits, payment schedules, taxes, gratuity, service
      charges, refunds and payment audit records
- [ ] Send confirmations, due-date reminders and event updates through Core's
      notification templates and delivery tracking

This milestone is blocked on the remaining document, attachment, reminder and
customer-portal portions of `CORE-REUSE-4` and `CORE-REUSE-7`. Core 2.1 commerce
and accounts must be extended and adopted; Catering must not build parallel
account, invoice, signature or payment engines.

## 1.4.0 — Costing and operational reporting

- [x] Add module-owned ingredients, recipe yields and food cost
- [ ] Add per-dish margin reporting after accepted quote lines expose stable dish
      attribution through Core
- [x] Compare estimated and actual food, labor, rental, travel and event costs
- [x] Generate ingredient requirements, vendor purchasing lists and preparation
      totals across multiple events
- [ ] Report revenue, margin, covers and utilization by event type, menu,
      package, service style, venue and lead source using Core reporting hooks
- [ ] Preserve tax-exempt status and financial documents in the appropriate
      Core account/commerce records

## 1.5.0 — Sales growth and public proof

- [x] Add venue partnerships, preferred-venue pages and venue-specific guidance
- [x] Add administrator-approved event case studies and verified
      event-specific reviews through Core content/media services
- [ ] Add Core Media galleries to event stories after adopting the delivered
      `CORE-REUSE-3` media-field contract in a future tested Core baseline
- [x] Add focused wedding, corporate, school, nonprofit, holiday and memorial
      landing-page patterns without duplicating Core's page composer
- [ ] Add referral/promotion attribution, repeat-customer insights and abandoned
      inquiry follow-up through Core analytics, privacy and notification services
- [ ] Publish service, menu, FAQ, review and service-area structured data through
      the future `CORE-REUSE-3` schema contribution contract

## 1.6.0 — Ten-layout Catering theme collection

Deliver ten complete Catering themes. These are distinct page compositions,
not one template with ten CSS skins. Working theme identities are:

1. Heritage Editorial — story-led split editorial composition
2. Modern Culinary — asymmetric modular showcase
3. Garden Celebration — layered organic event presentation
4. Grand Wedding — cinematic, full-bleed celebration layout
5. Corporate Table — precise information-first grid
6. Rustic Gathering — warm collage and card composition
7. Bayou Night — dark immersive hospitality layout
8. Market Kitchen — menu-first storefront composition
9. Social Feast — image-led masonry and event discovery
10. Minimal Luxe — restrained typography and open-space composition

- [ ] Give every theme a distinct header/navigation treatment, home-page
      composition, inner-page layout, menu/package presentation, quote entry,
      service-area treatment and footer hierarchy
- [ ] Give every theme a distinct default hero and featured-image assignment;
      expose all theme image slots through Core Media so administrators can
      replace them without modifying the package
- [ ] Provide at least four curated, contrast-checked color palettes per theme
      and save palette choice independently for each theme
- [ ] Use Core's visual theme chooser for desktop/mobile preview, draft,
      publish and rollback without changing public content or navigation
- [ ] Preserve per-theme palette/media selections while another theme is active;
      upgrades must not overwrite administrator selections
- [ ] Test all ten themes at supported responsive breakpoints with keyboard,
      reduced-motion, contrast, image-performance and Core-attribution checks
- [ ] Keep the installable module archive free of nested ZIPs and optimize the
      distinct image assignments for shared-host upload and page performance

Theme registration, preview/publish state, palette controls, contrast checks
and media-slot persistence are shared Core responsibilities. Catering will
adopt `CORE-REUSE-10`, recorded in Website Core roadmap commit
`1ef7c5545e40bbd993be298fd1368519a2d0a216`; it must not add a second theme
manager inside the module.

## 2.0.0 — Optional direct ordering

- [ ] Support administrator-selectable quote-only, direct online-order and mixed
      modes through Core commerce/accounts
- [ ] Limit direct ordering to eligible menus, service styles, dates, lead times,
      capacity, fulfillment windows, delivery zones and payment rules
- [ ] Support saved contacts/locations, previous-order duplication and recurring
      corporate meal programs through Core customer accounts
- [ ] Keep complex or manually priced events in the quotation workflow and
      never create a parallel cart, checkout, invoice or payment engine

Core 2.3 supplies the dormant commerce/account surfaces, but its documented
Module API does not yet let Catering enforce event date, lead time, capacity,
fulfillment, postal/radius, minimum-order and manual-price rules atomically at
checkout. The reusable validation contract is recorded as `CORE-REUSE-11` in
Website Core roadmap commit `0215410d4a6676350cf4f297497dccf9dba7c92a`.
Catering must remain quote-only until that hook exists and passes acceptance;
enabling the generic store without those checks would create unsafe orders.
