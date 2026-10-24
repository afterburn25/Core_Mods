# Testing

## 2026-10-24 — 0.1.0 foundation

- Manifest JSON parsing: passed for the module and all four themes.
- Package integrity: passed; the module and four theme ZIPs open cleanly and
  each has the correct root manifest.
- Module ZIP structure: passed; `addon.json` is at archive root and theme
  source/release artifacts are excluded.
- PHP syntax checks: not run locally because the execution image has no PHP
  binary. Module CI is configured to run PHP 8.4 lint after the branch push.
- Repository module validator: not run locally for the same reason; Module CI
  is configured to run it after the branch push.
- Manual browser rendering: not run.
- Website Core installation/activation: not run.
- Theme switching and visual regression: not run.

No release acceptance is claimed for this foundation.
