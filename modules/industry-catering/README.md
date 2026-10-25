# Catering Website Module

The Catering Website module is the first industry package for Cajun Web Pro
Website Core. It supplies a complete catering-focused public site and an
industry-specific administration category while leaving shared content,
navigation, security, users, backups, integrations, and operations in Core.
Its [Module Creation Rules](MODULE_RULES.md) record the required preflight and
adoption of [Cajun Web Pro Module Rules v1.1](../../MOD_RULES.md).

## Current release

- Module version: `0.3.0`
- Module ID: `cajunwebpro/catering`
- Type: `industry`
- Module API: `1`
- Required Core: `>=2.3.0,<3.0.0`
- Development branch: `module/catering`

## Menu planning in 0.2.0

Customers can select dishes and a package in Request a Quote, enter a guest
count, filter business-verified dietary labels, and calculate a preliminary
food estimate. The same server calculation handles preview and submission.
Every selected dish is assumed to serve the full headcount. Per-guest prices,
rounded-up tray quantities, fixed event prices, minimum billing quantities
and optional guest-count discounts are supported. Unknown/manual prices stay
unconfirmed instead of producing a misleading complete total.

Catering → Menus now edits existing dishes, serving sizes, pricing methods,
five optional discount tiers, dietary labels, known allergens and handling
notes. Catering → Packages edits existing packages, guest ranges and the
minimum/maximum number of dishes allowed from each included category.
Configured package dishes are included in the package price and never billed
again. A package with no configured allowances requires team confirmation for
any selected dishes. Existing packages are not assigned invented allowances.

Inquiries retain the selected menu, labels, quantities and calculated prices
as a submission snapshot, even if the catalog changes later. Core queues the
notification email. Estimates exclude taxes, staffing, rentals, delivery and
service charges; no order, payment, availability reservation or final quote is
created. Dietary labels do not establish allergy safety. Empty allergen lists
mean information has not been supplied. Review starter recipes before assigning
structured labels; the upgrade does not invent allergen or dietary claims.

## Catalog management in 0.2.1

Core 2.3's shared resource workspace now supplies searchable quick management,
keyboard-accessible visible-row ordering, pagination, bulk selection, CSRF,
permissions, confirmations and audit records for dishes and packages. Catering
owns the persistence callbacks and rejects IDs outside its tables. Quick edits
change only the record name, category and visibility; the detailed Catering
editors continue to own prices, serving sizes, discount tiers, dietary and
allergen data, images, guest ranges and package choice rules. Quick-created
records use manual pricing until those details are completed.

The Core 2.2 inquiry-detail schema defect is fixed and the 0.2.1 acceptance
suite treats the shared detail/notes contract as a required check.

## Quote configuration in 0.3.0

Catering settings now controls the public event-type, service-style, and
budget choices. The administrator may also show, hide, or require the
Catering-specific company, phone, event time, venue, service style, budget,
menu-interest, dietary, and additional-notes questions. The server uses the
same saved configuration for validation, rejects unsupported choices, and
does not retain values submitted for disabled questions. Existing inquiry
records and immutable menu snapshots are unaffected by later configuration
changes.

This is a module-owned event questionnaire layer. Generic form schemas,
notifications, exports, spam operations, attachments, and generated documents
remain Core services and are not duplicated here.

## Included features

- Catering home, about, services, menus, packages, gallery, FAQ, service-area,
  contact, and quote pages
- Detail pages for services, menus, and packages
- Quote and inquiry capture linked to Core's shared inquiry and quotation
  workspace while preserving detailed event questionnaires
- Core-owned business identity plus module management screens for settings,
  menus, and packages
- Responsive catering theme assets, fourteen bundled WebP images (including
  ten dish-specific photographs), and a transparent
  generic CATERING CO. starter logo without an opaque footer frame
- Database migration and seeded catering content
- A ten-dish Cajun starter menu with Gumbo, Crawfish Bisque, Crawfish Étouffée,
  Shrimp Fettuccine, Jambalaya, Blackened Redfish, appetizers, and dessert
- Complete starter home-page search metadata with an upgrade-safe repair for
  the original short Welcome placeholder
- Integration with Core navigation, administration, permissions, installation,
  licensing, and version reporting
- Module-owned navigation defaults imported through Core's Module API, followed
  by live use of Core's editable tree, nesting, and configured link targets
- One-time logo handoff through Core's brand asset API; Core owns later logo
  and favicon uploads, replacements, clearing, and shared admin rendering
- Separate one-time header/footer navigation defaults, followed by live Core
  tree rendering and an editable Core-owned copyright notice
- A live new-inquiry widget on Core's main Dashboard; the module category
  contains only actionable catering management screens
- Core-owned social profiles rendered after Request a Quote in the header,
  in the footer, or both according to Core's shared Appearance setting

## Installation

Install the single `industry-catering-0.3.0.zip` through Website Core’s
module/package manager. This delivery contains only the module; use an
already-installed compatible Core. It contains no nested ZIP and does not
include or update Core. Combined installers require an explicit owner request. The installable module ZIP keeps
`addon.json` at its archive root as required by Package Spec v1.

Production licensing remains controlled by Core. The historical 0.1.22 and
0.1.23 repository ZIPs were found truncated during this release's acceptance;
use the new validated package. Upgrade coverage includes the valid published
0.1.21 package and a clearly identified fixture rebuilt from historical
0.1.23 source. See the testing record for exact evidence and limitations.

## Development records

- [Project status](docs/PROJECT_STATUS.md)
- [Roadmap](docs/ROADMAP.md)
- [Testing](docs/TESTING.md)
- [Session handoff](docs/SESSION_HANDOFF.md)
- [Changelog](CHANGELOG.md)

