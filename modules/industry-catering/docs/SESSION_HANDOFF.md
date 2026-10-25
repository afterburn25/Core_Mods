# Catering Module Session Handoff

## Publication completed — 2026-10-25

The recovered 0.2.0 candidate is now on GitHub at
`f3d20fe26a794513956cb8e6b4a7bb67388a7906`. Module CI run `40216516473`
and Catering acceptance run `40216516597` passed, including PHP 8.4/8.5 and
native MySQL 8.4/MariaDB 11.4. See [Testing](TESTING.md#github-publication-and-ci--2026-10-25)
for exact check counts and archive verification. The original candidate ZIP
and checksum are preserved; these publication notes supplement its pre-CI
documentation. Temporary publication files are absent from the branch.

CORE-REUSE-7A still reproduces in the pinned Core fixture. Its diagnostic is
non-blocking in module CI and must not be described as passed. Production
acceptance and real shared-host upload/upgrade remain outstanding. Continue
only module work; do not combine with Core without an explicit owner request.

## Authoritative locations

- Repository: `afterburn25/Core_Mods`
- Branch: `module/catering`
- Folder: `modules/industry-catering/`
- Current version: `0.2.0`
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
`afterburn25/Website_Core` → active implementation branch
(`devin/website-core` as verified 2026-10-25) → `docs/ROADMAP.md`, checking for an
existing entry first. Include the shared API/service needed, the catering use
case, and acceptance criteria. Cross-link it in the module roadmap/handoff and
mark dependent module work blocked or deferred until Core implements it.
This documentation exception does not authorize Core implementation changes
or a combined build. The shared-service planning review is now recorded under CORE-REUSE-1
through CORE-REUSE-8 in that roadmap; these are planning entries, not
implemented dependencies. Use existing Core services first. Shared API,
record editor, module content/media and forms integration gaps must be
resolved before catering work relies on them. Core implementation remains
separate and no combined installer has been built.

## Current baseline — reconciled 0.2.0 candidate

This continuation recovered two independent local work sets missing from the
remote branch: the 0.2.0 menu planner and 0.1.24/0.1.25 metadata/shared-service
adoption. Both are preserved in this candidate. The preceding local checkouts
were left intact. The module remains separate from Core implementation and
no combined installer or live deployment was produced.

The menu planner provides configurable package choices, per-guest/tray/fixed
food pricing, minimum quantities, guest-count discounts, dietary/allergen
labels, server-side validation and an immutable inquiry menu snapshot.
Estimates exclude taxes, staffing, rentals, delivery and service charges;
manual/unconfigured pricing requires confirmation. Existing recipe text is
preserved and structured dietary labels are never guessed.

Core 2.2 business profile, inquiry/quotation capability and shared admin
renderer are retained. The duplicate module inbox remains retired. Detailed
questionnaires and menu snapshots stay module-owned; the Core inquiry receives
a readable snapshot summary. Linking uses one transaction with a MySQL row
lock to prevent parallel boot requests creating duplicate sales records.
A recovered quote-submission TypeError was corrected by converting the
inserted questionnaire ID to the integer required by the linkage method.

Migration 0003 remains the existing home metadata repair, 0004 remains Core
inquiry linkage, and the previously unpublished planner migration is 0005.
The older 0002 migration preserves edited demo dishes/categories. Never rename
an already published migration ID. Core >=2.2.0,<3.0.0 / Module API 1 is required.
The acceptance workflow pins published Core 2.2.1 commit
`12f082936b0b2c9dae4f54f345e4d059c625f2e9` as a read-only fixture.

### Open Core dependency

CORE-REUSE-7A is recorded in the active Core roadmap (documentation commit
`411fae6b73f055ac592dcd4c90ba0ed30509d2ee`). Core InquiryService::find()
references a nonexistent admins.name field instead of the canonical username,
so the shared inquiry detail screen fails. It reproduces on SQLite in Core
2.2.0 and 2.2.1. Module persistence/linking checks pass, but this does not prove
a working Core inbox detail UI. Do not release this as production-ready until
Core fixes that dependency and the end-to-end detail/notes flow is retested.
Do not patch Core implementation or create a replacement inbox here.

### Package and verification rules

Build with modules/industry-catering/tools/build-release.py, retain only the
current ZIP, and recheck the exact archive after changing packaged files.
addon.json stays at the ZIP root, with no nested ZIP. The historical 0.1.22
and 0.1.23 ZIP blobs are truncated; use the valid 0.1.21 ZIP or the explicitly
rebuilt pinned 0.1.23 source fixture, never claim the corrupt archives installed.
Module-only lifecycle testing is permitted; combined packaging is owner-deferred.

## Historical baseline — 0.1.25

Version 0.1.25 carries the 0.1.22 Cajun menu upgrade in a substantially smaller
shared-host-safe archive. It replaces the generic demo menu with ten Cajun dishes and ten
matching original WebP photographs. Migration `catering_0002_cajun_menu_images`
adds the image field portably and upgrades only recognized starter records, so
unrelated administrator-created dishes remain intact. The public homepage,
menu overview, category detail pages, and menu administration all render the
new photography. Migration `catering_0003_home_search_metadata` also completes
blank search metadata on the Core starter home record and replaces only the
untouched short Welcome title. Core 2.2.0 is now the compatible baseline.

The module contains its provider, catering controller, five database
migrations, public views, administration views, responsive CSS/JS, fourteen
WebP images, and the transparent generic CATERING CO. logo. The module declares its approved header links through Core's
`navigationMenu()` API. Core imports that declaration once, owns the editable
copy, and returns the current nested tree through `navigationTree()`, so an
administrator can customize or intentionally empty it without defaults
returning. Website Core 2.2 supplies shared navigation, users/roles, business
identity, inquiries, quotations, content, media, integrations, security,
operations, backups, marketplace, and agency services. Version 0.1.17 declares
the starter logo with `brandAssets()`; Core
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

The 0.1.24 candidate is 1,609,810 bytes with SHA-256
`f3e04792e5e495b0513dd9a91d2eb18cf753d93ff1ea029d2a7feae5904379f0`.
Its module and synchronized Core CI evidence is pending.

Historical candidate 0.1.25 was the shared-service adoption baseline. It requires Core 2.2,
declares `businessProfile()` and `leadManagement()`, retires the duplicate
Catering lead workspace, and links preserved detailed questionnaires to Core
through `InquiryService`. Migration `catering_0004_core_sales_workspace` adds
only the nullable linkage column. The Core 2.2 database matrix and live-host
migration evidence are pending. The module-only ZIP is 1,610,452 bytes with
SHA-256 `8c0b6fe3474e459fe32ff6f9fec8adb93a71c019b527d9366da414bf9427b729`;
its `verified_core` field remains intentionally null until acceptance passes.

## Next safe task

Module-specific GitHub CI for this reconciled candidate has passed. Continue
module content/media work when the documented Core adapters are available
(CORE-REUSE-3/4). Keep CORE-REUSE-7A visibly blocked until Core fixes its detail
query. Run the core-dependencies.php diagnostic after the Core fix, then
verify detail rendering and notes through the shared admin UI. Shared-host
upload/upgrade remains unverified. No combined Core installer is authorized.
