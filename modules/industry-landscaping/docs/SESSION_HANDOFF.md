# Session Handoff

The user approved all four landscaping concepts. **Bold Outdoors** is the default;
**Estate Green**, **Neighborhood Lawn**, and **Earth & Stone** are selectable Core
themes. Every theme renders the same Core-owned logo, menus, social profiles,
copyright, media, business settings, and landscaping records.

Authoritative development is `afterburn25/Core_Mods`, branch `module/landscaping`,
folder `modules/industry-landscaping/`. Version 1.0.0 implements roadmap milestones
0.2 through 1.0 and packages the module plus all four themes.

Before calling the commercial release fully accepted, install the module ZIP and each
theme ZIP in a supported Website Core 2.x/MySQL environment. Verify migration and seed
behavior on a clean install, preservation during upgrade, public/admin workflows,
private photo authorization, mail queueing, all four responsive themes, and uninstall
with both retained-data and explicit-purge choices. Then copy the accepted snapshot
into Website Core's release input, run bundle CI, update the registry on `main`, and
record exact run links in `docs/TESTING.md`.

Do not move industry behavior into Core or replace Core-owned branding, navigation,
media, security, admin, and settings services.
