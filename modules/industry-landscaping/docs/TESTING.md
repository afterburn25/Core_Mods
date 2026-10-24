# Testing

## 2026-10-24 — 1.0.0 source and package verification

The following local checks were run against the release candidate:

- Module and four theme manifest JSON parsing: passed.
- Git whitespace/error check (`git diff --check`): passed.
- Module ZIP root structure and exclusion rules: passed.
- Four theme ZIP root structures: passed.
- Release manifest filenames, versions, and SHA-256 digests: passed.
- Archive integrity (`ZipFile.testzip`) and 2 MiB module package budget: passed.

The execution image does not include a PHP binary. GitHub Module CI supplied PHP 8.4
syntax lint and the repository PHP validator; both passed in
[run 40124327464](https://github.com/afterburn25/Core_Mods/actions/runs/40124327464).

## 2026-10-24 — latest-Core synchronization

- Website Core branch: `devin/website-core`
- Website Core integration commit:
  `7b04c7ae760a928f2863ceccc43d02a6e7f54cdf`
- Full Core CI, including PHP 8.4/8.5, native MySQL 8.4, native MariaDB 11.4,
  package installation, public rendering, estimate storage, four theme installs,
  and landscaping TEST-BUNDLE/REPAIR-UPLOAD contracts: passed in
  [run 40125415702](https://github.com/afterburn25/Website_Core/actions/runs/40125415702).
- The Core snapshot was verified byte-for-byte against the 24 deployable runtime
  files in the module release archive.

## Deployment acceptance still required

- Live-host clean install and seed verification outside CI.
- Live-host upgrade preservation and module rollback/uninstall behavior.
- Estimate submission, mail queue, private photo access, capability boundaries, and
  lead-management browser testing.
- All four themes at desktop, tablet, and mobile sizes, including keyboard and contrast review.

No unperformed deployment or browser check is claimed as passed.
