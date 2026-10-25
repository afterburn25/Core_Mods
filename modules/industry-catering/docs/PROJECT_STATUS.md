# Catering Module Project Status

- Current version: `0.2.1`
- State: Active development; Core 2.3 shared catalog-management candidate
- Development branch: `module/catering`
- Repository folder: `modules/industry-catering/`
- Module API: `1`
- Required Core: Website Core `>=2.3.0,<3.0.0`
- Last updated: 2026-10-25

## 0.2.0 delivered scope

- Menu planner integrated into the four-step quote flow, with dietary filters
  and server-rendered preview when JavaScript is unavailable.
- Editable dishes/packages, included category choices, guest ranges, per-guest,
  tray and fixed food pricing, serving sizes, minima and discount tiers.
- Business-verified dietary labels, known allergens and handling notes.
- Server-authoritative selection/price snapshots retained with inquiries and
  surfaced in the lead workspace and Core-queued notification email.
- Additive migration and guards against overwriting edited older demo content.
- Local pricing, integration, packaged install/upgrade/lifecycle and browser
  acceptance; the current ZIP is complete and below the upload-size ceiling.

## 0.2.1 candidate scope

- Core-owned searchable resource workspaces for dish and package quick edits.
- Keyboard-reachable visible-row ordering with safe partial-order persistence.
- Bulk show, hide, feature and unfeature actions limited to module records.
- Detailed module editors retained for all industry-specific configuration.
- Required Core inquiry-detail and note-attribution regression acceptance.

## Completed

- Public multi-page catering website and detail pages
- Responsive catering layout, styles, scripts, and WebP imagery
- Catering data migration and seeded starter content
- Inquiry and quote workflows
- Catering administration category
- Menu, package, and settings administration; inquiries use Core
- Core module/package, permission, version, installer, and licensing integration
- Module-owned navigation declaration imported and managed by Core
- Core-owned editable navigation tree rendered by the catering header
- Generic transparent CATERING CO. starter logo declared through the Core brand
  asset handoff, with current Core logo/favicon rendered publicly and in admin
- Separate Core-owned header/footer navigation trees and Core-owned editable
  business copyright rendered by the public catering layout
- Shared inquiries/quotations capability enabled through Core without a
  duplicate Catering dashboard or inbox
- Core-owned optional social icons rendered in the configured header/footer
  locations using one shared Core placement, with blank profiles omitted
- Transparent footer logo presentation without an opaque frame
- Ten-dish Cajun demo menu with matching original photography across public
  pages and administration
- Portable in-place upgrade migration from the original generic demo menu
- Upgrade-safe home-page title and search metadata repair for untouched Core
  installer placeholders
- Versioned module ZIP and SHA-256 latest-release record
- Website Core 2.0.1 bundle, installer, MySQL, and MariaDB acceptance coverage
- Core-owned business profile, inquiry pipeline, and quotation capability
- Upgrade-safe linking of preserved Catering questionnaires to Core inquiries
- Shared Core module-admin rendering with duplicate lead surfaces removed

## Current focus

- Treat this repository branch as the authoritative module-development source.
- Develop and release catering independently while Core is developed separately.
- Synchronize the Website Core snapshot and build a combined installer only
  when the owner explicitly requests integration (direction: 2026-10-25).
- Defer combined Core bundle CI until that integration is requested; preserve
  honest compatibility requirements and continue module validation.
- Verify and publish 0.2.1 against Core 2.3, then continue content editing and
  media-management improvements without moving industry-specific behavior into
  Core.

## Known issues and release gates

- CORE-REUSE-7A was repaired in Core 2.2.2; 0.2.1 makes its detail/notes
  diagnostic blocking against the Core 2.3 fixture.
- PHP 8.4/8.5 and MySQL 8.4/MariaDB 11.4 module CI passed (Catering run `40216516597`; Module CI `40216516473`). Core detail/notes diagnostic still fails.
- No combined Core bundle or live-host installation was performed.
- Shared content/media/forms integration remains on CORE-REUSE-3/4.
- Historical 0.1.22/0.1.23 archives are truncated. Only the complete current
  ZIP remains in releases; legacy-source fixtures are explicitly test-only.
- Additional charges, availability, tastings, event operations, checkout and
  payments remain on the roadmap.
