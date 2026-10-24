# Module Creation Rules

**Rules contract: Cajun Web Pro Module Rules 1.1**

The authoritative contract is repository [`MOD_RULES.md`](../../MOD_RULES.md).

## Creation preflight

- [x] Dedicated branch: `module/landscaping`.
- [x] Single folder: `modules/industry-landscaping/`.
- [x] Manifest identity, semantic version, Core range, API major, provider, PSR-4 map,
  migrations, assets, and capabilities are declared.
- [x] Industry behavior remains in the module and shared behavior uses `ModuleContext`.
- [x] Separate primary/footer navigation defaults render Core-owned navigation trees.
- [x] The shared default logo is declared with `brandAssets()` and the live logo and
  favicon resolve through Core.
- [x] Footer copyright and social links resolve through Core services.
- [x] Admin routes share one Landscaping category and check explicit capabilities.
- [x] The module contributes a compact Core dashboard widget and admin search provider.
- [x] Namespaced tables, routes, assets, settings, and additive migration preserve data.
- [x] Public intake uses CSRF, validation, rate limiting, a honeypot, validated private
  media, prepared database operations, and escaped output.
- [x] Required README, changelog, status, roadmap, testing, and handoff records exist.
- [x] Current module/theme ZIPs and SHA-256 manifests are built and checked.
- [ ] Record passing module-branch CI and synchronized Website Core bundle CI before
  marking the commercial release fully accepted.

## Module-specific decisions

The audience, visitor goals, page map, navigation, module data, admin workflows, Core
service boundaries, compatibility range, and acceptance tests are recorded in
`docs/PROJECT_STATUS.md`, `docs/ROADMAP.md`, and `docs/TESTING.md`.
