# Catering Module Session Handoff

## Authoritative locations

- Repository: `afterburn25/Core_Mods`
- Branch: `module/catering`
- Folder: `modules/industry-catering/`
- Current version: `0.1.23`
- Manifest ID: `cajunwebpro/catering`

Do not develop the catering module directly on `main`, and do not create a
second top-level folder for it. New catering work belongs on this branch and in
this folder. Website Core's `examples/industry-catering/` directory is a tested
release snapshot used to build combined installers; it is not the development
source of truth after this separation.

This branch follows repository `MOD_RULES.md` contract version 1.1. Future
sessions must apply those shared rules before module-specific roadmap choices.

## Owner direction — 2026-10-25

Catering work is module-only while the owner develops Website Core separately.
Do not change Website Core implementation, synchronize its catering snapshot,
merge the latest Core, or build a combined installer until the owner explicitly asks to combine
this module with the latest Core. This direction supersedes inherited automatic
Core synchronization and combined-bundle CI requirements for catering work.
Continue module ZIP packaging, honest compatibility declarations, module
validation, and documentation. Record deferred integration tests without
claiming that a newer Core has been tested. See `../MODULE_RULES.md`.

The owner also authorizes Core roadmap updates when catering work identifies
required Core changes. Add or update the dependency in
`afterburn25/Website_Core` → `main` → `docs/ROADMAP.md`, checking for an
existing entry first. Include the shared API/service needed, the catering use
case, and acceptance criteria. Cross-link it in the module roadmap/handoff and
mark dependent module work blocked or deferred until Core implements it.
This documentation exception does not authorize Core implementation changes
or a combined build. No new Core dependency was identified in this
instruction-only update.

## Current baseline

Version 0.1.23 carries the 0.1.22 Cajun menu upgrade in a substantially smaller
shared-host-safe archive. It replaces the generic demo menu with ten Cajun dishes and ten
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
`f5bf20d24b01213d7c913b666e444302505e7254166c1df7c75eac73262d645a`.
Core_Mods release commit `e931f7e06822e85a96263e326e3847cbeb9db9df`
is synchronized to Website Core commit
`bccb4d6e2be5a59a5780cafb453efc46aeaa568a`. Website Core CI run
`40063594623` (run 429) passed all six jobs. The Core_Mods repository did not
create a Module CI run for the branch update.

The shared-host-sized 0.1.23 release is 1,608,151 bytes with SHA-256
`99b90f88db88cfc8913cfe56b8289e358191681927e274d38dce9a893985b379`.
Its release commit is `fe37c950addd1aa9fb839e3bb6a627b86b0ea92b`,
the synchronized Core commit is
`307d32a8e7ee3aaa49ec37f16a3a4f348c175346`, and Website Core CI run
`40064437493` (run 431) passed all six jobs.

## Next safe task

Continue module-only work on the 0.2.0 content/media roadmap when requested.
The historical live-host 0.1.21 → 0.1.23 upgrade check and missing Core_Mods
Actions runs remain open verification follow-ups. Do not update a live site or
Website Core as part of module-only development.

