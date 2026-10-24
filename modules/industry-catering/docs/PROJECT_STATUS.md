# Catering Module Project Status

- Current version: `0.1.23`
- State: Active development; release candidate being verified
- Development branch: `module/catering`
- Repository folder: `modules/industry-catering/`
- Module API: `1`
- Required Core: Website Core `>=2.0.5,<3.0.0`
- Last updated: 2026-10-24

## Completed

- Public multi-page catering website and detail pages
- Responsive catering layout, styles, scripts, and WebP imagery
- Catering data migration and seeded starter content
- Inquiry and quote workflows
- Catering admin dashboard
- Menu, package, inquiry, and settings administration
- Core module/package, permission, version, installer, and licensing integration
- Module-owned navigation declaration imported and managed by Core
- Core-owned editable navigation tree rendered by the catering header
- Generic transparent CATERING CO. starter logo declared through the Core brand
  asset handoff, with current Core logo/favicon rendered publicly and in admin
- Separate Core-owned header/footer navigation trees and Core-owned editable
  business copyright rendered by the public catering layout
- New-inquiry overview contributed to Core's main Dashboard without a
  duplicate Catering dashboard page
- Core-owned optional social icons rendered in the configured header/footer
  locations using one shared Core placement, with blank profiles omitted
- Transparent footer logo presentation without an opaque frame
- Ten-dish Cajun demo menu with matching original photography across public
  pages and administration
- Portable in-place upgrade migration from the original generic demo menu
- Versioned module ZIP and SHA-256 latest-release record
- Website Core 2.0.1 bundle, installer, MySQL, and MariaDB acceptance coverage

## Current focus

- Treat this repository branch as the authoritative module-development source.
- Keep the tested snapshot in Website Core synchronized only at release points.
- Continue content editing and media-management improvements in future module
  versions without moving industry-specific behavior into Core.

## Known issues

- No code-level release blocker is recorded for `0.1.23`. Synchronized Website
  Core CI run 429 passed all six jobs. The Core_Mods repository did not create
  a Module CI run for the published branch update, so its repository-level
  workflow remains an operational follow-up rather than a package-code blocker.
- The module still needs its own isolated controller/database test suite instead
  of relying primarily on Website Core bundle and acceptance coverage.

