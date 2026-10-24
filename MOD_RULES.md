# Cajun Web Pro Module Rules

**Contract version: 1.1**

These are the permanent cross-module rules for every industry module and
add-on in `afterburn25/Core_Mods`. A module roadmap may change its features;
it may not quietly bypass this contract. Changes to this file are made on
`main`, versioned, documented, and then merged into every active module branch.

## 1. One module, one branch, one folder

- Develop each module on `module/<slug>`.
- Keep it in `modules/<module-id>/` and nowhere else.
- Never develop two modules on the same module branch.
- Keep reusable standards, templates, validation, and CI on `main`.

## 2. Core stays universal

- Industry workflows, terminology, tables, pages, and presentation belong to
  the module.
- Shared security, users, roles, navigation editing, media, content services,
  installation, updates, backups, and operations belong to Website Core.
- Integrate only through the documented `ModuleContext` API. Never patch Core
  controllers or views from a module and never read another module's tables.

## 3. The module defines navigation; Core manages it

- Every industry module declares its suitable starting header and footer menus
  as separate locations with `ModuleContext::navigationMenu()`.
- Core imports those defaults once after installation and owns the editable
  saved menu from that point forward.
- A module must render `ModuleContext::navigationTree()` for each declared
  location instead of maintaining hard-coded header or footer links.
- Module boot and upgrades must not overwrite a customized menu or repopulate
  a menu an administrator intentionally cleared.

## 4. Modules may supply branding defaults; Core owns the live assets

- An industry module may declare a starting logo, favicon, and social image
  with `ModuleContext::brandAssets()` using package-relative image paths.
- Core validates and copies missing defaults into Core Media once. Existing
  selections always win; module updates never overwrite or repopulate an asset
  an administrator replaced or intentionally cleared.
- Public and module-admin layouts resolve the current Core selection through
  `ModuleContext::brandAssetUrl()` and never keep rendering the bundled default
  directly after installation.
- Shared brand upload, selection, storage, and administration stay in Core.
- Business copyright text comes from `ModuleContext::copyrightNotice()` and is
  escaped when rendered. Modules never hard-code it or interfere with Core's
  immutable Cajun Web Pro attribution.
- Optional social profiles and their shared header/footer placement belong to Core.
  Modules render only `socialLinks('header')` and `socialLinks('footer')`, use
  shared accessible marks, emit no empty icon links, and never add individual
  placement controls.

## 5. Administration remains predictable

- Core `/admin` is the only overview dashboard. Modules contribute compact,
  capability-filtered summaries through `dashboardWidget()` and link them to
  real management screens; they do not register duplicate Dashboard pages.
- Shared website controls stay in Core's existing categories.
- Industry-specific screens register under one module-owned admin category.
- Every admin route uses `adminRoute()`, declares a capability, and performs
  its own server-side capability check.
- Navigation visibility is never treated as authorization.

## 6. Installation and upgrades are repeatable

- `addon.json` is the package identity and version source of truth.
- Migrations are ordered, additive-first, safe on MySQL/MariaDB, and journaled
  by Core. SQLite support is for development tests only.
- Install and upgrade operations must be idempotent. Existing customer content
  and administrator customization are preserved.
- The manifest must state an honest Core version range and Module API major.

## 7. Data and security boundaries are mandatory

- Use prepared database operations through Core's shared connection.
- Prefix module-owned tables consistently and declare them in module
  migrations. Uninstall preserves data unless the administrator explicitly
  requests a purge.
- Escape output with Core helpers, protect forms with CSRF, validate on the
  server, rate-limit public submissions, and never commit secrets.
- Public assets and routes stay inside the module's assigned namespace.

## 8. Releases are complete and traceable

- Use stable semantic versions and increment whenever packaged behavior
  changes.
- Ship only the latest installable ZIP in `releases/`, with `addon.json` at its
  root and a matching SHA-256 in `releases/LATEST.json`.
- Keep README, changelog, project status, roadmap, testing record, and session
  handoff current in the same commit as the behavior they describe.
- Record only tests that actually ran. A release is not complete until its
  module-branch CI and the synchronized Website Core bundle CI pass.

## 9. Accessibility and responsive behavior are release requirements

- Public and admin interfaces must be keyboard usable, semantically labeled,
  responsive, and readable at supported breakpoints.
- Images require useful alternative text when informative; reduced-motion and
  focus behavior must remain usable.
- A module cannot ship dead controls, placeholder actions, or a separate UI
  that contradicts the Core admin system.

## Changing these rules

A contract change requires:

1. Update this file and increment its contract version.
2. Update `MODULE_STANDARD.md`, templates, validator, and affected Core API
   documentation together.
3. Merge the rule change into every active `module/*` branch.
4. Record adoption in each module's handoff and testing documentation.

