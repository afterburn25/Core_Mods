# Catering Module Session Handoff

## Authoritative locations

- Repository: `afterburn25/Core_Mods`
- Branch: `module/catering`
- Folder: `modules/industry-catering/`
- Current version: `0.1.16`
- Manifest ID: `cajunwebpro/catering`

Do not develop the catering module directly on `main`, and do not create a
second top-level folder for it. New catering work belongs on this branch and in
this folder. Website Core's `examples/industry-catering/` directory is a tested
release snapshot used to build combined installers; it is not the development
source of truth after this separation.

This branch follows repository `MOD_RULES.md` contract version 1.0. Future
sessions must apply those shared rules before module-specific roadmap choices.

## Current baseline

The module contains its provider, catering controller, initial database
migration, public views, administration views, responsive CSS/JS, and four WebP
images. The module declares its approved header links through Core's
`navigationMenu()` API. Core imports that declaration once, owns the editable
copy, and returns the current nested tree through `navigationTree()`, so an
administrator can customize or intentionally empty it without defaults
returning. Website Core 2.0.1 supplies shared navigation, users/roles, content,
media, integrations, security, operations, backups, marketplace, and agency
services.

## Verification evidence

Website Core CI run 393 on 2026-10-23 passed all six jobs for the commit used to
produce the current combined bundle. Coverage included PHP 8.4/8.5 unit and
package suites, MySQL 8.4 and MariaDB 11.4 native acceptance, installer flows,
core-only boot, and release artifact contract checks.

## Next safe task

Start roadmap version `0.2.0` by adding isolated module tests and defining the
content/media editing boundary. Increment `addon.json` when behavior changes,
update all records in the same commit, rebuild the module ZIP, refresh
`releases/LATEST.json`, and wait for branch CI before recording success.

