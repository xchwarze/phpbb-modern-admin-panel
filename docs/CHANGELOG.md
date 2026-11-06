# Modern Admin Panel: changelog

## 2.0.0 — Initial public free edition

### Interface

- Responsive ACP shell with a hierarchical sidebar, icon rail, mobile drawer,
  keyboard navigation, RTL support, and reduced-motion handling.
- Personal favourites selected with the page-bar star and shown under Home.
  Single-item extensions use their extension name; multi-item extensions use
  “Extension › Item” in favourites and module search.
- Command palette for modules, recent modules, favourites, board settings,
  users, forums, groups, and permission-appropriate maintenance links.
- Twenty colour presets, light/dark/system modes, configurable menu-row shapes,
  and consistent ACP forms, tables, popups, and confirmation controls.
- Configurable dashboard widgets for pending work, statistics, admin notes,
  Quick access, the administrator log, and inactive users.
- English and Spanish translations and a bundled Material Symbols icon sprite.
- Optional information about independent paid modules and custom development;
  no free-edition feature is locked by that dialog.

### Safety and integration

- Board-wide configuration and shared notes require permission checks and
  valid CSRF form tokens. Changes are recorded in phpBB's administrator log.
- Read-only palette searches validate their request token, enforce permissions,
  bound query lengths and results, and return plain text without private user data.
- Local browser preferences store module keys rather than session-bearing URLs.
- Notes use phpBB's formatter; remote-loading image/flash tags remain plain text.
- ACP pages receive `X-Frame-Options: SAMEORIGIN` unless another extension has
  already set that header.
- Menu-icon integration event with validated sprite identifiers and a curated
  ACP-module icon map.
- Separate `dsr/modernadminpanel` identity; it does not upgrade or import data
  from `phpbbstudio/admindashboard`.

### Distribution

- Install-ready extension under `dsr/modernadminpanel/`, with bundled CSS and
  JavaScript. No Node.js installation or authoring-source build is required.
- Shared installation, feature, privacy, compatibility, and integration docs.
- Existing GPL-2.0-only notices and Apache-2.0 icon attribution preserved.
