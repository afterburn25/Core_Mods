# Module Creation Rules

**Rules contract: Cajun Web Pro Module Rules 1.1**

Read this file before creating or changing this module. The authoritative
cross-module contract is repository [`MOD_RULES.md`](../../MOD_RULES.md). If
this file and the master contract ever differ, stop and update the module
branch from `main` before continuing.

## Creation preflight

- [ ] This module has its own `module/<slug>` branch.
- [ ] This module has one folder under `modules/<module-id>/`.
- [ ] `addon.json` has a unique namespaced ID, semantic version, honest Core
      range, Module API major, provider, autoload mapping, and capabilities.
- [ ] Industry-specific behavior remains here; universal behavior remains in
      Core and is used only through `ModuleContext`.
- [ ] The module declares its starting header and footer menus as separate
      `navigationMenu()` locations and renders each Core-owned
      `navigationTree()`.
- [ ] If the module ships initial brand images, it declares package-relative
      paths with `brandAssets()` and renders Core values with `brandAssetUrl()`;
      it never overwrites or bypasses Core-owned branding.
- [ ] The public footer uses `copyrightNotice()` for the business copyright and
      does not replace or hide Core's mandatory Cajun Web Pro attribution.
- [ ] Header/footer social icons use Core's location-filtered `socialLinks()`;
      the module owns no duplicate profile settings and emits no blank links.
- [ ] Industry admin screens use one module category, protected admin routes,
      and explicit server-side capabilities.
- [ ] The module contributes overview data with `dashboardWidget()` and does
      not register a duplicate module Dashboard page.
- [ ] Tables, migrations, public routes, assets, and settings are namespaced;
      installs and upgrades preserve existing data and customization.
- [ ] Public forms use CSRF, validation, abuse controls, prepared database
      access, and escaped output.
- [ ] The README, changelog, status, roadmap, testing, and handoff records have
      been created before the first release.
- [ ] The current release ZIP, `LATEST.json`, checksum, module CI, and
      synchronized Website Core bundle CI will be completed before release.

## Module-specific decisions to record

Before implementation, put these decisions in `docs/PROJECT_STATUS.md` and
`docs/ROADMAP.md`:

1. Industry audience and primary visitor goals.
2. Public page map and default navigation labels.
3. Module-owned data, workflows, and administrator screens.
4. Which Core services the module will reuse.
5. Initial version, Core compatibility range, and acceptance tests.

Do not remove this file. It is copied into every module at creation so future
sessions can recover the shared rules without relying on conversation history.

