# Catering Module Changelog

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

