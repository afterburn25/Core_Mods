# Project Status

**Version:** 1.0.0 synchronized release
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

Module CI and the synchronized latest-Core CI pass. Website Core 2.0.14 now carries
the verified 1.0.0 runtime snapshot and builds dedicated landscaping TEST-BUNDLE and
REPAIR-UPLOAD artifacts. Live-host browser, upgrade, and rollback acceptance remains
recorded separately in `TESTING.md`; no unperformed external check is claimed.
