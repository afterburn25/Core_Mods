# Catering Module Testing

## 2026-10-24 — Cajun menu and dish photography release candidate

- Module version advanced to `0.1.22` with Core `>=2.0.5,<3.0.0`.
- Ten original 1200×800 WebP dish photographs were visually reviewed for
  subject accuracy, consistent lighting, usable crop, and absence of text.
- The upgrade migration adds `catering_menu_items.image` on SQLite, MySQL, and
  MariaDB, replaces only recognized demo slugs, and inserts Blackened Redfish
  only when absent.
- Public views render semantic dish images with dimensions, alt text, and lazy
  loading; administration renders thumbnails and validates bundled selections.
- Local JSON, asset-dimension, repository, and whitespace checks pass. Local
  PHP execution is unavailable, so GitHub CI remains the PHP release gate.
- Deterministic release ZIP SHA-256:
  `f8dde13511b9844089e78c5deb243ff28d500b94b508ec88f555add6e31dddb3`.

## 2026-10-24 — Transparent footer brand release candidate

- Module version advanced to `0.1.21` with Core `>=2.0.5,<3.0.0`.
- The source CATERING CO. PNG retains its alpha channel and exact artwork.
- The footer stylesheet no longer applies an opaque frame behind the logo.
- Social rendering remains location-filtered after Core's move from
  per-profile placement to one shared placement control.
- Local PHP execution is unavailable; module and synchronized Core CI remain
  the authoritative release gates.

## 2026-10-24 — Social placement release candidate

- Module version advanced to `0.1.20` with Core `>=2.0.5,<3.0.0`.
- Core owns profile URLs and per-platform header/footer/both placement.
- Catering renders header icons after Request a Quote and footer icons before
  copyright, with HTTPS validation, accessible labels, and opener isolation.
- Package coverage asserts blank omission and dual-placement rendering.
- Deterministic release ZIP SHA-256:
  `1bf40847b7ef6f260546d4030a694f7bc655a3d410a1ac023b4eefe44d96a3a8`.

## 2026-10-24 — Unified dashboard release candidate

- Module version advanced to `0.1.19` with Core `>=2.0.5,<3.0.0`.
- The module no longer registers `/admin/catering` or a Dashboard navigation
  item, and the retired dashboard view is absent from the package.
- The Core overview still evaluates the capability-filtered `new-inquiries`
  widget and links it to `/admin/catering/inquiries`.
- Website Core package coverage asserts both sides of that ownership boundary.
- Deterministic release ZIP SHA-256:
  `0dfdc928f988202269896510554e5268961e7c69ed0624a11251e27882cb9ff0`.

## 2026-10-24 — Footer navigation and copyright release candidate

- Module version advanced to `0.1.18` with Core `>=2.0.5,<3.0.0`.
- Provider declares `footer` independently from `primary`; the public layout
  renders the current Core tree and honors nesting and configured targets.
- The public footer renders Core's token-expanded plain-text copyright and
  remains compatible with the separately injected immutable platform credit.
- Website Core coverage checks location isolation, module default import,
  installed rendering, token expansion, Appearance input, and portable export.
- Deterministic release ZIP SHA-256:
  `e311c02e416fc5776ba7b9faf4a8016bfe77173364e5d68039d64589a2f4d8d5`.
- Publication and CI evidence are recorded after both branch runs pass.

## 2026-10-24 — Branding integration release candidate

- Module version advanced to `0.1.17` with Core `>=2.0.5,<3.0.0`.
- The transparent CATERING CO. logo is present in the package manifest and
  declared with `brandAssets()`; public and module-admin layouts resolve Core's
  current logo/favicon through `brandAssetUrl()`.
- Website Core regression coverage checks one-time copying, source
  preservation, path confinement, the permanent clear marker, direct
  Appearance uploads, shared admin wiring, and installed-package rendering.
- Repository rules were advanced to contract 1.1 so every future module uses
  the same default-then-Core-ownership branding rule.
- Local PHP execution is unavailable in the current workspace. Do not mark the
  release verified until module and synchronized Core GitHub CI pass.

## 2026-10-24 — Navigation integration

- Module version advanced to `0.1.16`.
- Empty primary menus are initialized with the six existing catering links;
  existing customized menus are preserved and an intentionally cleared menu is
  not repopulated after the one-time marker is stored.
- The module declares defaults and reads the live tree exclusively through the
  Core 2.0.1 Module API navigation contract.
- Repository validation confirms Module Rules contract version 1.0 is present.
- Module branch validation covers manifest/release agreement, release digest,
  required records, and PHP syntax.
- Core_Mods release commit `13190474e6d786854742b06e23e0691c131dda5f`
  passed Module CI run `40044852033` (run 8): repository/release validation,
  SHA-256 agreement, and complete module PHP syntax lint.
- The synchronized Website Core commit
  `49c124d0292efeb6b7e6965cd93259cc1234e580` passed CI run `40044526443`
  (run 397): PHP 8.4/8.5 lint/unit/package suites, Core-only boot, release
  artifact contracts, and native MySQL 8.4/MariaDB 11.4 installer and bundle
  acceptance.
- Shared Module Rules main commit `020cfa03fc868bdd92ac7a914f1ad8817f35dba9`
  passed Module CI run `40044729463` (run 7).

## 2026-10-23 — Website Core 2.0 combined-bundle verification

- Core branch: `devin/website-core`
- Core commit: `c6b52d434b89bf997eb363367e0a78d78cc56a3d`
- GitHub Actions run: `40042097426` (run number 393)
- Result: passed

Successful jobs:

- Core-only boot check
- Release artifacts build and contract check
- PHP 8.4 lint, unit, and package tests
- PHP 8.5 lint, unit, and package tests
- MySQL 8.4 native schema, installer, licensed bundle, and keyless bundle tests
- MariaDB 11.4 native schema, installer, licensed bundle, and keyless bundle tests

The generated `Website-Core-catering-2.0.0-TEST-BUNDLE.zip` passed ZIP integrity
verification and contained the complete catering source and assets.

## Module-branch validation

The `module/catering` branch CI validates:

- Stable semantic version in `addon.json`
- Required development documentation
- Latest release version and SHA-256 agreement
- PHP syntax across module source, migrations, and views

## Remaining test work

- Add isolated module database/controller tests on SQLite and native MySQL.
- Add browser-level public-page and admin interaction tests.
- Add upgrade coverage from older catering schema versions.

