# Module Creation Rules

**Rules contract: Cajun Web Pro Module Rules 1.0**

Read this file before creating or changing this module. The authoritative
cross-module contract is repository [`MOD_RULES.md`](../../MOD_RULES.md). If
this file and the master contract ever differ, stop and update the module
branch from `main` before continuing.

## Creation preflight

- [x] This module has its own `module/catering` branch.
- [x] This module has one folder under `modules/industry-catering/`.
- [x] `addon.json` has a unique namespaced ID, semantic version, honest Core
      range, Module API major, provider, autoload mapping, and capabilities.
- [x] Industry-specific behavior remains here; universal behavior remains in
      Core and is used only through `ModuleContext`.
- [x] The module declares its starting header menu with `navigationMenu()` and
      renders Core's editable result with `navigationTree()`.
- [x] Industry admin screens use one module category, protected admin routes,
      and explicit server-side capabilities.
- [x] Tables, migrations, public routes, assets, and settings are namespaced;
      installs and upgrades preserve existing data and customization.
- [x] Public forms use CSRF, validation, abuse controls, prepared database
      access, and escaped output.
- [x] The README, changelog, status, roadmap, testing, and handoff records have
      been created before the first release.
- [x] Complete the current release ZIP, `LATEST.json`, module CI, and
      synchronized Website Core bundle CI for version 0.1.16.

## Catering decisions

1. Audience: people and organizations planning catered gatherings.
2. Public navigation: Our Story, Menus, Services, Packages, Gallery, Contact.
3. Module data: menu categories/items, packages, inquiries, and catering
   settings; administration appears in the Catering category.
4. Core services: routing, views, settings, navigation, permissions, database,
   CSRF, rate limiting, mail, admin search, dashboard, and health checks.
5. Compatibility: Catering 0.1.16 requires Core `>=2.0.1,<3.0.0` and Module
   API 1; acceptance runs in the module branch and synchronized Core bundle.

Do not remove this file. It preserves the creation rules and module decisions
for future sessions without relying on conversation history.

