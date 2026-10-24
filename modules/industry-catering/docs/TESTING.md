# Catering Module Testing

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

