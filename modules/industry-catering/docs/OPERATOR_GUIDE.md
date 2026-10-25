# Catering 1.0 Operator Guide

## Supported platform

Catering 1.0.0 supports Website Core `>=2.3.0,<3.0.0` and Module API 1. Install
the single `industry-catering-1.0.0.zip` through Core's package manager. The
archive is a module-only package: it does not contain Core or another ZIP.

Before installation or upgrade, create a complete Website Core backup using
the site's normal Core operations workflow. Module data uses additive,
journaled migrations and is retained when the module is disabled or
uninstalled without an explicit purge.

## First-run checklist

1. Open Core's Business profile and confirm the public name, email, phone,
   service area, address, and hours.
2. Review Catering settings, public event choices, minimum guests, lead time,
   capacity, blackout dates, fulfillment windows, delivery zones, and optional
   services. The bundled values are examples, not business commitments.
3. Review every dish's price, serving yield, dietary labels, known allergens,
   handling notes, image, and availability dates.
4. Configure package guest ranges, category allowances, optional curated menu
   collections, and availability dates.
5. Submit a test quote for pickup and, when enabled, each delivery zone. Check
   the immutable menu and operations summaries in Core's inquiry workspace.
6. Replace demo copy and imagery before launch when the corresponding shared
   Core content/media workflows are available.

## Operations configuration formats

The settings screen validates each structured line before saving:

- Service rules: `Style|min guests|max guests|lead days`
- Optional services: `key|public name|price|fixed` or `key|public name|price|per_guest`
- Delivery zones: `Name|postal codes separated by commas|fee|minimum food order`
- Blackout dates: one `YYYY-MM-DD` date per line
- Pickup and delivery windows: one public label per line

Use `*` as a postal code only when the team intentionally reviews every
delivery location manually. Removing every delivery zone removes Delivery
from the public planner. Quote estimates include configured extras and zone
fees but remain inquiries, not orders, reservations, invoices, or payments.

## Daily workflow and ownership

- Core owns business identity, users, permissions, navigation, media, mail,
  inquiries, quotations, audits, backups, and operations.
- Catering owns the detailed event questionnaire, catalog, package rules,
  menu/operations snapshots, seasonal availability, and request screening.
- Update inquiry status, assignment, internal notes, and final quote amounts
  in Core. Do not treat a planner estimate as a confirmed price or booking.
- Changing catalog or operations settings never rewrites a historical inquiry
  snapshot.

## Upgrade and rollback policy

Upgrade only through Core's package manager and do not skip the pre-upgrade
backup. Catering migrations are forward-only. A code downgrade is not a data
rollback: restore the complete Core backup if an upgrade must be reversed.
Never install an older module ZIP over newer migrated data.

The 1.0 acceptance matrix covers clean install; upgrades from the valid 0.1.21
release, a rebuilt 0.1.23 source fixture, the exact 0.2.0 release, and the exact
0.4.0 release; disable/enable; uninstall without purge; and reinstall with
retained customer catalog and inquiry records. Whole-site backup restoration
remains Core-owned and is not reimplemented by this module.

## Troubleshooting

- A rejected date can be inside the global or service-specific lead time, on a
  blackout date, or beyond the configured event capacity.
- A rejected delivery can have the wrong window, an unmatched postal code, or
  a food estimate below that zone's minimum.
- Manual-price dishes make the complete estimate require team confirmation.
- If detailed inquiries are not visible, run Core health checks and confirm the
  module's questionnaire-to-Core link health check is green.
- If JavaScript is unavailable, the Calculate food estimate button uses the
  same server rules and preserves form input.
