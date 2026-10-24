# Catering Website Module

The Catering Website module is the first industry package for Cajun Web Pro
Website Core. It supplies a complete catering-focused public site and an
industry-specific administration category while leaving shared content,
navigation, security, users, backups, integrations, and operations in Core.
Its [Module Creation Rules](MODULE_RULES.md) record the required preflight and
adoption of [Cajun Web Pro Module Rules v1.0](../../MOD_RULES.md).

## Current release

- Module version: `0.1.18`
- Module ID: `cajunwebpro/catering`
- Type: `industry`
- Module API: `1`
- Required Core: `>=2.0.5,<3.0.0`
- Development branch: `module/catering`

## Included features

- Catering home, about, services, menus, packages, gallery, FAQ, service-area,
  contact, and quote pages
- Detail pages for services, menus, and packages
- Quote and inquiry capture
- Catering dashboard and management screens for settings, menus, packages,
  and inquiries
- Responsive catering theme assets, four bundled WebP images, and a transparent
  generic CATERING CO. starter logo
- Database migration and seeded catering content
- Integration with Core navigation, administration, permissions, installation,
  licensing, and version reporting
- Module-owned navigation defaults imported through Core's Module API, followed
  by live use of Core's editable tree, nesting, and configured link targets
- One-time logo handoff through Core's brand asset API; Core owns later logo
  and favicon uploads, replacements, clearing, and shared admin rendering
- Separate one-time header/footer navigation defaults, followed by live Core
  tree rendering and an editable Core-owned copyright notice

## Installation

Install this module through Website Core's module/package manager, or use a
Website Core bundle that already contains it. The installable module ZIP keeps
`addon.json` at its archive root as required by Package Spec v1.

The keyless Website Core + Catering TEST-BUNDLE is for testing. Production
licensing is controlled by Website Core's package and licensing services.

## Development records

- [Project status](docs/PROJECT_STATUS.md)
- [Roadmap](docs/ROADMAP.md)
- [Testing](docs/TESTING.md)
- [Session handoff](docs/SESSION_HANDOFF.md)
- [Changelog](CHANGELOG.md)

