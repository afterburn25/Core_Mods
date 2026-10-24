# Catering Module Session Handoff

## Authoritative locations

- Repository: `afterburn25/Core_Mods`
- Branch: `module/catering`
- Folder: `modules/industry-catering/`
- Current version: `0.1.22`
- Manifest ID: `cajunwebpro/catering`

Do not develop the catering module directly on `main`, and do not create a
second top-level folder for it. New catering work belongs on this branch and in
this folder. Website Core's `examples/industry-catering/` directory is a tested
release snapshot used to build combined installers; it is not the development
source of truth after this separation.

This branch follows repository `MOD_RULES.md` contract version 1.1. Future
sessions must apply those shared rules before module-specific roadmap choices.

## Current baseline

Version 0.1.22 replaces the generic demo menu with ten Cajun dishes and ten
matching original WebP photographs. Migration `catering_0002_cajun_menu_images`
adds the image field portably and upgrades only recognized starter records, so
unrelated administrator-created dishes remain intact. The public homepage,
menu overview, category detail pages, and menu administration all render the
new photography. Core 2.0.7 remains the compatible baseline.

The module contains its provider, catering controller, two database
migrations, public views, administration views, responsive CSS/JS, fourteen
WebP images, and the transparent generic CATERING CO. logo. The module declares its approved header links through Core's
`navigationMenu()` API. Core imports that declaration once, owns the editable
copy, and returns the current nested tree through `navigationTree()`, so an
administrator can customize or intentionally empty it without defaults
returning. Website Core 2.0.5 supplies shared navigation, users/roles, content,
media, integrations, security, operations, backups, marketplace, and agency
services. Version 0.1.17 declares the starter logo with `brandAssets()`; Core
copies it once and module layouts resolve the live Core logo/favicon through
`brandAssetUrl()`.

## Verification evidence

Core_Mods release commit `13190474e6d786854742b06e23e0691c131dda5f`
passed Module CI run 8 (`40044852033`). The synchronized Website Core commit
`49c124d0292efeb6b7e6965cd93259cc1234e580` passed all six jobs in Core CI run
397 (`40044526443`), including PHP 8.4/8.5, MySQL 8.4, MariaDB 11.4, installer,
Core-only boot, and release artifact contract coverage. The release ZIP digest
is `b182dbd11edbdff03cc52a2bc9ffd83fe5f7b1c125588e6149cf6f5d69eb11cc`.
That is the historical 0.1.16 baseline. The 0.1.21 release ZIP digest is
`4c300c583f658b6ee76c1545c875cfe31404b97894cfaaba27a359b93b2a4d1f`;
both CI runs remain pending because local PHP execution is unavailable in the
current workspace.

The 0.1.22 release ZIP digest is
`f8dde13511b9844089e78c5deb243ff28d500b94b508ec88f555add6e31dddb3`.
Record its module and synchronized Core CI run IDs after publication.

## Next safe task

Publish module 0.1.22 on `module/catering`, synchronize Website Core's tested
example snapshot, run the package acceptance suite, and record the resulting
CI evidence and release digest.

