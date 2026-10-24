# Catering Module Testing

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

