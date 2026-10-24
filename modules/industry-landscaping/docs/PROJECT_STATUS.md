# Project Status

**Version:** 1.0.0 implementation candidate
**Branch:** `module/landscaping`
**Default theme:** Landscaping — Bold Outdoors
**Rules contract:** Cajun Web Pro Module Rules 1.1

## Product scope

The module serves residential and commercial lawn care and landscaping companies.
Visitors can explore services, projects, recurring plans, seasonal availability, and
service areas, then submit a detailed estimate request. Administrators manage every
industry record and process estimate leads from one Landscaping category in Core.

## Implemented

- Database-backed services, projects, plans, service areas, seasonal offers, and leads.
- Public pages and Core-owned nested primary/footer navigation.
- Secured estimate intake with private photo attachments, notification mail, statuses,
  notes, filtering, dashboard count, search integration, and diagnostics.
- Shared Core branding, logo, favicon, social profiles, copyright, admin shell, media,
  permissions, CSRF, rate limiting, and settings.
- Responsive Bold Outdoors presentation plus three separately packaged alternate themes.
- Original module photography, release archives, and SHA-256 manifests.

## Acceptance state

Repository validation, PHP lint, and package checks are delegated to module-branch CI.
A release becomes fully accepted under the repository contract only after the module
and theme archives are installed, activated, upgraded, and rolled back in a supported
Website Core/MySQL environment and the synchronized Website Core bundle CI passes.
Those deployment checks are recorded as pending in `TESTING.md`; the source and
installable 1.0.0 packages are complete for that acceptance pass.
