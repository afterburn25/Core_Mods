# Module Repository Standard

All modules are governed by [MOD_RULES.md](MOD_RULES.md), currently contract
version **1.1**. This file describes repository mechanics; `MOD_RULES.md`
defines the cross-module product and integration invariants.

## Branch and folder rules

Each industry module gets one long-lived branch named `module/<slug>` and one
folder named `modules/<module-id>/`. For example, catering uses:

```text
branch: module/catering
folder: modules/industry-catering/
```

Do not develop two modules on the same module branch. Shared repository rules,
templates, and validation belong on `main`.

## Required module contents

```text
modules/<module-id>/
├── addon.json
├── assets/
├── migrations/
├── src/
├── views/
├── README.md
├── MODULE_RULES.md
├── CHANGELOG.md
├── docs/
│   ├── PROJECT_STATUS.md
│   ├── ROADMAP.md
│   ├── SESSION_HANDOFF.md
│   └── TESTING.md
└── releases/
    ├── LATEST.json
    └── <module-id>-<version>.zip
```

## Version policy

- `addon.json` is the source of truth for the module version.
- Use semantic versions: `MAJOR.MINOR.PATCH`.
- Increment the version whenever module code, migrations, views, styles,
  scripts, or packaged behavior changes.
- Documentation-only updates may keep the current version.
- `releases/LATEST.json` must match `addon.json`, name the latest ZIP, and
  contain its SHA-256 digest.
- Keep only the current installable ZIP in `releases/`; Git history preserves
  older versions.

## Documentation policy

- `README.md`: purpose, features, compatibility, and installation.
- `CHANGELOG.md`: released changes grouped by version.
- `docs/PROJECT_STATUS.md`: current completion state and known issues.
- `docs/ROADMAP.md`: planned milestones and acceptance criteria.
- `docs/SESSION_HANDOFF.md`: enough detail for a future session to resume.
- `docs/TESTING.md`: tests performed, results, and remaining test needs.

Update the status, changelog, testing record, and handoff in the same commit as
the code they describe. Never claim a test passed unless it was actually run.

## Core integration

Module manifests use Website Core Module API 1 until a documented API-major
upgrade is required. A tested module snapshot may be copied into Website
Core's `examples/` directory for bundle construction. That copy is a release
input, not the module's development authority.

Each industry module declares its default header and footer navigation as
separate locations with `ModuleContext::navigationMenu()` and renders each
`navigationTree()`. Core imports the defaults once and owns all later
administrator edits. Module footers resolve the editable business copyright
with `copyrightNotice()`; Core's mandatory platform attribution stays separate.

Modules may also declare package-relative starting brand images with
`ModuleContext::brandAssets()` and render the current Core-owned selection with
`brandAssetUrl()`. Core copies a missing default once; existing, replaced, and
intentionally cleared administrator selections remain authoritative.

## New-module checklist

1. Create `module/<slug>` from `main`.
2. Copy `templates/module/` to `modules/<module-id>/`.
3. Read and complete the copied `MODULE_RULES.md` creation preflight.
4. Add a valid `addon.json` and module source.
5. Complete all required documentation.
6. Build the latest module ZIP with `addon.json` at the archive root.
7. Generate `releases/LATEST.json` with the ZIP SHA-256.
8. Run the branch CI and record the result in `docs/TESTING.md`.
9. Add or update the module row in the registry on `main`.

