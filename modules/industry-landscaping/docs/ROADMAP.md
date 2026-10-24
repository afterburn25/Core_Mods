# Roadmap

## 0.1 — Design and architecture foundation

- [x] Adopt all four approved concepts.
- [x] Set Bold Outdoors as default.
- [x] Preserve one Core-owned logo, menu set, footer, social configuration, and content set.
- [x] Establish responsive public routes and design tokens.

## 0.2 — Editable services and projects

- [x] Service categories and services with images, summaries, details, order,
  residential/commercial flags, featured state, and visibility.
- [x] Project gallery with before/after pairs, location labels, services used,
  completion dates, ordering, featured state, and visibility.
- [x] Admin screens under one `Landscaping` category.

## 0.3 — Maintenance plans and service areas

- [x] Recurring plan editor with cadence, included tasks, seasonal notes, optional
  starting-price display, ordering, and visibility.
- [x] Service area editor with cities, ZIP codes, travel notes, and area copy.
- [x] Seasonal service availability and callouts.

## 0.4 — Estimate and lead workflow

- [x] Estimate request with property type, address, lot size, requested service or plan,
  up to three private photos, schedule preference, budget, and contact consent.
- [x] CSRF, server validation, rate limiting, honeypot, media validation, notification
  mail, statuses, internal notes, filters, protected photo review, and dashboard count.

## 0.5 — Theme completion

- [x] Complete industry layouts and component styling for all four themes.
- [x] Package each theme separately for Core Appearance.
- [x] Keep data, menus, logo, social links, media, and footer configuration in Core so
  theme switching preserves them by design.
- [x] Add responsive breakpoints, keyboard focus, semantic labels, and reduced-motion CSS.
- [ ] Complete browser visual acceptance at desktop, tablet, and mobile sizes in Core.

## 1.0 — Commercial release

- [x] Add idempotent MySQL/MariaDB production and SQLite development migrations.
- [x] Replace temporary image fields with original landscaping photography.
- [x] Build module and four theme ZIPs with matching release digests.
- [x] Update README, changelog, status, roadmap, testing, handoff, and rules preflight.
- [ ] Pass live Core install, activation, upgrade, and rollback checks on MySQL/MariaDB.
- [ ] Synchronize the accepted package into Website Core and pass bundle CI.
