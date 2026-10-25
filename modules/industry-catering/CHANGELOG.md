# Catering Module Changelog

## 0.3.0 — 2026-10-25

- Add administrator-managed event types, service styles, and budget ranges to
  the quote planner without introducing a parallel generic form engine.
- Allow each Catering-specific planning question to be shown, hidden, or
  required while keeping identity, event, menu, consent, and contact essentials
  mandatory.
- Enforce the saved configuration server-side, discard disabled-field input,
  reject unsupported select values, preserve existing inquiry snapshots, and
  audit settings changes.
- Correct service-page quote links so their preselected event type matches the
  validated quote option.
- Keep generic form notifications, CSV export, reminders, attachments, spam
  reporting, and proposal documents on the existing Core reuse backlog.

## 0.2.1 — 2026-10-25

- Require Website Core 2.3 and adopt its shared `adminResource()` workspace
  for searchable dish/package quick management, pagination, accessible
  visible-row ordering and declared bulk visibility/featured actions.
- Keep advanced pricing, serving, discount-tier, dietary, allergen, image,
  guest-range and package-choice fields in Catering's detailed editors; quick
  edits preserve those fields and quick-created records require manual price
  confirmation.
- Validate every resource callback against module-owned tables, preserve
  unsubmitted ordering positions and retain Core's shared permission, CSRF,
  confirmation and audit workflow.
- Pin module acceptance to the CI-verified Core 2.3.0 source, require the fixed
  inquiry detail/notes contract, and add direct upgrade coverage from 0.2.0.

## 0.2.0 — 2026-10-25

- Reconcile the menu planner with the 0.1.25 Core 2.2 adoption, preserving the
  shared business profile, admin renderer and sales workspace.
- Fix quote-submission ID typing; serialize inquiry linking and include the
  submitted menu snapshot in Core's readable inquiry summary.
- Record Core's inquiry-detail schema defect as CORE-REUSE-7A; this candidate
  is not production-ready until that dependency passes acceptance.

- Add a dish/package menu planner to the quote workflow with server-computed
  preliminary food estimates and an accessible no-JavaScript preview.
- Add per-guest, tray and fixed food pricing, tray serving sizes, minimum
  quantities and guest-count discount tiers using integer-cent calculations.
- Add editable package guest ranges and included category choice limits;
  validate selections server-side without charging included dishes twice.
- Add structured dietary filters, business-supplied known allergens and
  cross-contact notes while preserving existing dietary display text.
- Add existing dish/package editors, retained validation errors, server-side
  permissions/CSRF, confirmed removal and Core audit records.
- Save an authoritative menu/price snapshot with each inquiry and include
  selected dishes and food estimates in Core-queued notifications.
- Keep estimates incomplete for manual pricing or unconfigured package dish
  allowances, and distinguish requests from bookings, orders and payments.
- Preserve edited legacy demo dishes/categories when their older Cajun-menu
  migration is still pending. Fix portable menu/category proposal URLs and
  invalidate assets using the module manifest version.
- Add isolated pricing, controller, database and exact-ZIP lifecycle checks,
  plus module-specific PHP/MySQL/MariaDB CI. No Core implementation, bundled
  catering snapshot or combined installer is changed.


## 0.1.25 — 2026-10-25

- Requires Website Core 2.2 and opts Catering into the Core-owned business
  profile, inquiry workspace, and itemized quotations.
- Seeds only blank shared business fields from existing settings, then reads
  the live Core profile for the public name, email, phone, and service area.
- Preserves detailed Catering questionnaires while linking existing and new
  records into the Core sales pipeline through `InquiryService`.
- Retires the duplicate Catering inquiry screen, dashboard/search
  contributions, and identity settings; old inquiry bookmarks redirect to the
  shared workspace.
- Retires the obsolete `catering.inquiries` permission; Core activates and
  enforces its shared `inquiries.manage` and `quotes.manage` capabilities.
- Uses Core's shared module-admin renderer for consistent permissions,
  navigation, notices, branding, and page presentation.

## 0.1.24 — 2026-10-24

- Adds a safe upgrade migration that completes missing search metadata on the
  Core starter home page while preserving any existing custom title or text.
- Replaces the untouched short “Welcome” label with the installed business
  name and supplies a useful catering search title and description.
- Keeps the public module-rendered home page unchanged; this repair brings the
  Core page record and SEO audit into agreement with the live catering site.

## 0.1.23 — 2026-10-24

- Re-optimized all ten Cajun dish photographs to 1000×667 WebP assets while
  preserving the approved visual quality and crop.
- Reduced the install archive well below common shared-hosting 2 MB request
  limits after the live site showed no stage or upgrade journal entry for the
  larger 0.1.22 upload.
- Kept the same portable Cajun data migration so sites still on 0.1.21 upgrade
  directly to the complete menu and photography set.

## 0.1.22 — 2026-10-24

- Replaced all generic starter dishes with a cohesive Louisiana Cajun menu,
  including Gumbo, Crawfish Bisque, Crawfish Étouffée, Shrimp Fettuccine,
  Jambalaya, Blackened Redfish, starters, and dessert.
- Added ten original dish-specific WebP photographs and renders each image on
  the homepage, menu overview, category pages, and menu administration.
- Added a portable upgrade migration that preserves unrelated custom records,
  adds the menu-image field on SQLite/MySQL/MariaDB, and upgrades recognized
  demo rows in place.
- Improved the public menu into polished photographic cards and upgraded the
  admin item list with dish thumbnails and category names.
- Added a validated bundled-image selector for new menu items and corrected
  the category-page proposal URL.

## 0.1.21 — 2026-10-24

- Removed the opaque footer frame from the existing transparent CATERING CO.
  logo so the artwork blends directly with the footer surface.
- Increased the footer logo presentation slightly while retaining the Core-
  owned live brand asset and the original transparent PNG.
- Documented Core 2.0.7's shared social placement control; Catering continues
  to render only Core-filtered header and footer records.

## 0.1.20 — 2026-10-24

- Added Core-owned social profile rendering in the header, footer, or both.
- Header icons appear immediately after Request a Quote; footer icons appear
  above the copyright row.
- Blank profiles render no link or placeholder, and configured links open with
  accessible labels and opener isolation.
- Updated shared module rules so future industries use the same Core-owned
  profile and placement contract.

## 0.1.19 — 2026-10-24

- Removed the duplicate catering dashboard route, view, and navigation item.
- Kept the live new-inquiry summary on Core's main Dashboard through the
  existing capability-filtered `dashboardWidget()` contract.
- Updated permanent module rules so future industries use one Core-owned
  overview and keep their categories focused on actionable workspaces.

## 0.1.18 — 2026-10-24

- Added a module-appropriate starting footer menu as a separate Core navigation
  location with the same one-time import and administrator-ownership rules.
- Replaced hard-coded footer links with the current Core-managed footer tree,
  including nested items and configured link targets.
- Renders Core's editable token-aware business copyright while leaving the
  mandatory Cajun Web Pro attribution unchanged.
- Updated the persistent module rules so future industries must declare and
  render both header and footer locations through Module API v1.

## 0.1.17 — 2026-10-24

- Added the approved transparent generic **CATERING CO.** starter logo.
- Declares the logo through Core 2.0.5 `brandAssets()` so Core copies it into
  Media once and owns all later administrator changes.
- Renders the current Core-owned logo and favicon in the public catering layout
  and uses the current logo/favicon in module administration.
- Preserves text branding when no logo is selected and adds a contrast-safe
  footer frame for transparent wordmarks.
- Requires Website Core `>=2.0.5,<3.0.0` with Module API 1.

## 0.1.16 — 2026-10-24

- Replaced the hard-coded catering header links with Website Core's editable
  primary navigation tree.
- Declares its existing header links with Core's reusable `navigationMenu()`
  API; Core imports them when no primary menu exists and takes over editing.
- Records one-time initialization so an administrator may intentionally remove
  every menu item without the defaults returning.
- Added nested submenu rendering and honored same-tab, new-tab, and dedicated
  new-window targets selected in Core's Navigation Menu editor.
- Preserved the original catering links as a safe fallback until an
  administrator saves a custom primary menu.
- Requires Website Core `>=2.0.1,<3.0.0` for the navigation API.

## 0.1.15 — 2026-10-23

- Added compatibility with Website Core's grouped, persistent administration
  navigation and protected built-in Core module behavior.
- Completed browser-deployment integration and release-bundle verification.
- Verified the module in the Website Core 2.0.0 keyless catering TEST-BUNDLE.

## 0.1.0 through 0.1.14

- Established the catering schema, provider, controllers, public routes,
  administration pages, responsive theme, seeded content, inquiries, menus,
  packages, services, gallery, service-area, contact, and quote workflows.
- Added package manifest, licensing integration, module permissions, bundled
  imagery, and Website Core installer integration.

