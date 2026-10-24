# Testing

## 2026-10-24 — 1.0.0 source and package verification

The following local checks were run against the release candidate:

- Module and four theme manifest JSON parsing: passed.
- Git whitespace/error check (`git diff --check`): passed.
- Module ZIP root structure and exclusion rules: passed.
- Four theme ZIP root structures: passed.
- Release manifest filenames, versions, and SHA-256 digests: passed.
- Archive integrity (`ZipFile.testzip`) and 2 MiB module package budget: passed.

The execution image does not include a PHP binary, so PHP 8.4 syntax lint and the
repository PHP validator run in GitHub Module CI after the branch update. Record the
final workflow URL and result below once available.

## Deployment acceptance still required

- Clean install and seed verification on supported MySQL/MariaDB.
- Upgrade preservation and module rollback/uninstall behavior in Website Core.
- Estimate submission, mail queue, private photo access, capability boundaries, and
  lead-management browser testing.
- All four themes at desktop, tablet, and mobile sizes, including keyboard and contrast review.
- Synchronized Website Core bundle build and CI.

No unperformed deployment or browser check is claimed as passed.
