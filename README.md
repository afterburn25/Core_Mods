# Cajun Web Pro Industry Modules

This repository is the authoritative development home for industry modules
that run on [Website Core](https://github.com/afterburn25/Website_Core).

## Repository model

- `main` contains the shared module standard, registry, templates, and CI.
- Every module is developed on its own `module/<slug>` branch.
- Every module lives in its own `modules/<module-id>/` folder.
- A module branch must keep its source, roadmap, project status, changelog,
  session handoff, test record, and latest installable ZIP together.
- Website Core may bundle a tested snapshot, but development continues here.

## Current modules

| Module | Branch | Folder | Current version | Status |
| --- | --- | --- | --- | --- |
| Catering Website | `module/catering` | `modules/industry-catering/` | `0.1.18` | Release candidate; requires Website Core 2.0.5+ |

See [MODULE_STANDARD.md](MODULE_STANDARD.md) before adding or changing a module.
Every module must also follow the versioned [Module Rules](MOD_RULES.md), which
hold the integration, security, navigation, branding, release, and documentation
invariants shared by all module branches.

