# Modern Admin Panel for phpBB

**A free, full-width Administration Control Panel for phpBB 3.3.** Modern Admin Panel provides a responsive navigation system, command palette, configurable colour themes, and a configurable dashboard. Everything in this repository belongs to the install-ready free edition. No asset build or Node.js installation is required on your forum.

## Highlights

- **A more navigable ACP:** hierarchical sidebar with Home, expandable tabs and sections, a pinned 264px menu or 64px icon rail, rail flyouts, and a touch-friendly drawer on screens up to 800px. The current page and its path stay visible, including a dot when a containing tab or section is closed.
- **Fast, contextual search:** sticky header search opens a command palette with `Ctrl/Cmd+K` or `/`. Find ACP modules, recent modules, up to 12 personal pins, board settings, forums, groups, users by `@name`, and permission-appropriate maintenance links. Settings control which search groups appear.
- **Personal navigation, private to each browser:** sidebar state, colour mode, recent modules (up to 8), and pins are stored in browser local storage. Recent and pinned module keys are not URLs and contain no session IDs.
- **Recognizable favourites:** extensions with one menu item use the extension name; extensions with several items use “Extension › Item”. Mark or unmark a module with the page-bar star to update its shortcut under Home.
- **A dashboard you can tailor:** optional “Needs attention” counts, statistics as cards or standard tables, shared administrator notes, Quick access, admin log, and inactive users. Settings control which widgets appear; phpBB permissions govern access and counts.
- **A coherent visual refresh:** full-width layout by default; 20 header and sidebar colour presets; light, dark, and system modes; and rounded, squared, or full-width menu rows. The ACP’s stock forms, tables, panels, messages, pagination, popups, and permission pages share the theme.
- **Keyboard and language support:** keyboard-operable menu and palette, visible focus, reduced-motion handling, right-to-left layout, and English and Spanish translations.
- **Useful extension icons:** Material Symbols sprite for the ACP menu and palette, a core section icon map, safe icon hooks for other extensions, and contextual icons on form buttons. The curated section map covers **30 source-verified public extensions with ACP menus**, plus local DSR extensions; this is not a popularity ranking or a compatibility guarantee. See the [coverage list](docs/menu-extension-icons.md).
- **No external assets:** compiled styles and scripts, with a selected 86-icon SVG sprite. No CDN or external font request is needed.

## Requirements

- phpBB 3.3.x; the supplied version was tested against phpBB 3.3.19. Metadata also permits phpBB 4.0.x, but that compatibility is not verified.
- PHP 7.1.3 or later as declared by the extension. phpBB 4.x itself requires PHP 8.2 or later.
- JavaScript is needed for the interactive sidebar, drawer, colour-mode button, command palette, and optional premium modules dialog. Without it, the promotion control stays hidden. The dialog's email action opens the administrator's mail client; it does not send a message or contact a server.
- The contact address is lightly encoded in page data and reconstructed after the click; this only discourages simple scrapers, not scripts that execute page JavaScript.

## Install

1. Download the installable ZIP from [Releases](https://github.com/xchwarze/phpbb-modern-admin-panel/releases), or copy the repository's `dsr/modernadminpanel/` directory into your board's `ext/dsr/modernadminpanel/`.
2. In the ACP, open **Customise → Extension management → Manage extensions** and enable **Modern Admin Panel**.
3. Optionally configure the board-wide settings at **Extensions → Modern Admin Panel → Settings**.

Modern Admin Panel is a separate extension from `phpbbstudio/admindashboard`; do not enable both together. See the [replacement instructions](docs/README.md#replace-phpbbstudioadmindashboard) if that extension is installed.

## Documentation

- [Installation, updates, privacy, and credits](docs/README.md)
- [Complete feature reference and settings](docs/FEATURES.md)
- [Curated extension icon coverage](docs/menu-extension-icons.md)
- [Version history](docs/CHANGELOG.md)
- [Extension events and palette endpoint](docs/EVENTS.txt)

## Repository layout

```text
README.md                 Product overview and installation
docs/                     User guide, features, changelog and integration notes
dsr/modernadminpanel/      Complete installable extension, including CSS and JavaScript
```

The extension directory is the canonical runtime. This repository does not contain a separate SCSS or JavaScript authoring project. Work with the supplied files; do not look for a `src/` directory or run an npm asset build.

## Support

Maintainers: see [checks and release packaging](docs/RELEASING.md).

Report reproducible bugs and feature requests in [GitHub Issues](https://github.com/xchwarze/phpbb-modern-admin-panel/issues). Include the phpBB version, PHP version, browser, installed ACP-related extensions, and steps to reproduce. Do not include passwords, API keys, session URLs, or private board data.

## Credits and licence

This is an independent rewrite derived from phpBB Studio’s Admin Dashboard 1.1.0. It is not a release of phpBB Studio and does not upgrade that extension. Original authors and contributors are credited in the [detailed documentation](docs/README.md#credits-and-licence).

Modern Admin Panel is licensed under [GPL-2.0-only](LICENSE). Material Symbols retain their [Apache-2.0 licence and attribution](dsr/modernadminpanel/adm/style/images/ICONS-LICENSE.txt).
