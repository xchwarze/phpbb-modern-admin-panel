# Modern Admin Panel: user guide

Modern Admin Panel (`dsr/modernadminpanel`, version 2.0.0) is a free extension
for the phpBB Administration Control Panel. It adds responsive navigation,
personal favourites, a command palette, light/dark colour modes, configurable
colours, and dashboard widgets. All these features belong to the free edition.

See the [product overview](../README.md), [feature reference](FEATURES.md),
[integration notes](EVENTS.txt), [icon coverage](menu-extension-icons.md), and
[changelog](CHANGELOG.md). Runtime paths in this guide are relative to
`dsr/modernadminpanel/` in the repository, or `ext/dsr/modernadminpanel/` on a board.

## Requirements

- phpBB 3.3.x. The supplied version was tested against phpBB 3.3.19. Metadata
  permits phpBB 4.0.x, but runtime compatibility with that series is unverified.
- PHP 7.1.3 or later, subject to the requirements of the installed phpBB version.
  phpBB 4.x itself requires PHP 8.2 or later.
- JavaScript for the interactive sidebar, favourites, colour-mode control,
  command palette, and optional promotion dialog. Without it, the interface
  falls back to a plain menu and a regular ACP search form.

## Install

1. Back up the board before installing extensions.
2. Extract the installable ZIP into the board's `ext/` directory, or copy the
   repository's `dsr/modernadminpanel/` directory there. The resulting path must
   be `ext/dsr/modernadminpanel/composer.json`, not an extra nested repository folder.
3. Open **Customise → Extension management → Manage extensions** in the ACP.
4. Enable **Modern Admin Panel**.
5. Open **Extensions → Modern Admin Panel → Settings** to configure colours,
   menu shape, search options, and dashboard widgets.

The CSS and JavaScript are ready to use. There is no npm installation, asset
compilation step, or separate authoring project to install. Enabling the
extension adds `admin_dashboard_*` settings, a welcome admin note, and its two
ACP modules. It creates no additional database table.

## Update

1. Back up the board and read the release notes.
2. Disable **Modern Admin Panel**. **Do not select Delete data when updating.**
3. Replace the files in `ext/dsr/modernadminpanel/` with the new release.
4. Enable the extension so phpBB applies any pending migrations.
5. Purge phpBB's cache and reload the ACP. Clear a stale browser cache if needed.

The original installation migration is not rerun just because files change.
Future data changes must be delivered through new migrations.

## Replace phpbbstudio/admindashboard

Modern Admin Panel is derived from phpBB Studio's Admin Dashboard, but it is a
separate extension. Its enable check prevents both ACP shells running together.

1. Back up the board and copy any admin notes you need to preserve.
2. Disable **phpbbstudio/admindashboard**. Its data is not imported by Modern
   Admin Panel. If removing that distinct extension, use its own **Delete data**
   operation separately; this removes its settings and notes.
3. Install and enable **Modern Admin Panel**, then purge phpBB's cache.
4. Remove the unused `ext/phpbbstudio/admindashboard/` directory once its data
   has been dealt with. Do not remove other extensions in `ext/phpbbstudio/`.

Existing browser preferences using the `phpbbstudio.ad.` prefix are transferred
once to the `dsr.ad.` prefix. Database settings and admin notes are not imported.

## Uninstall

Disable **Modern Admin Panel** to keep its settings. To remove it permanently,
select **Delete data** for this extension and then remove its directory.

Deleting data removes the extension's ACP modules, `admin_dashboard_*` settings,
and admin notes. Browser preferences remain in that browser until its local
storage is cleared. Do not use Delete data as part of a normal update.

## Privacy and security

Board-wide settings and shared admin notes are stored in phpBB's database.
Changing settings or notes requires `a_board` permission, a valid CSRF form
token, and records an entry in phpBB's administrator log.

Personal navigation choices stay in the browser:

| localStorage key | Value |
| --- | --- |
| `dsr.ad.sidebar` | `expanded` or `collapsed` |
| `dsr.ad.theme` | `system`, `light`, or `dark` |
| `dsr.ad.u<ID>.recent` | Up to 8 recent module keys |
| `dsr.ad.u<ID>.pins` | Up to 12 favourite module keys |

`<ID>` is the administrator's user ID. Favourites and recent modules store
module keys rather than URLs, so session IDs are not retained in those lists.
Favourites are selected with the page-bar star and appear under **Home**.
phpBB's **Quick access** category is separate and shared by administrators.

Palette searches go to the board's own ACP endpoint and follow the requesting
administrator's permissions. Search results include readable titles, names,
and links, not passwords, email addresses, IP addresses, or setting values.
The extension loads its own styles, scripts, and icon sprite without a CDN.

The optional promotion dialog describes separately available modules and
custom work. It does not lock free-edition features. Its contact button opens
the administrator's mail client only after a click; the extension sends no
message automatically. The contact address is lightly encoded, not encrypted.

## Translations

English (`en`) and Spanish (`es`) are included in `language/`. To add a language,
copy `language/en/info_acp_dashboard.php` into `language/<iso>/` and translate
its values while preserving placeholders.

## Repository contents

- `dsr/modernadminpanel/`: installable PHP, templates, CSS, JavaScript, languages,
  migrations, and runtime licences.
- `docs/`: shared documentation for this free release.
- `README.md`: entry point for installation and support.
- `LICENSE`: GNU GPL version 2 text; the extension directory includes its own copy.

All files needed to run the extension are in its installable directory. The
distributed CSS and JavaScript need no compilation step.

## Support

Use [GitHub Issues](https://github.com/xchwarze/phpbb-modern-admin-panel/issues)
for reproducible bugs and feature requests. Include environment details and
steps, but redact session URLs and all credentials. For security-sensitive
reports, contact the maintainer privately rather than posting exploit details
or private board data in a public issue.

## Credits and licence

- Main author and maintainer: DSR! ([xchwarze](https://github.com/xchwarze)).
- Based on phpBB Studio's Admin Dashboard 1.1.0 by phpBB Studio, 3Di, and mrgoldy.
  Original contributions include RTL support by meis2m and settings-search
  improvements by kasimi. Existing copyright notices remain in the code.
- Material Symbols by Google retain their Apache-2.0 licence. See
  [icon notices](../dsr/modernadminpanel/adm/style/images/ICONS-LICENSE.txt).
- Modern Admin Panel is a derivative work licensed under
  [GNU GPL version 2 only](../LICENSE).
