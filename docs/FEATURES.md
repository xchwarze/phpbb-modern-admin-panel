# Modern Admin Panel: features

What version 2.0.0 does, as it works now. Where a setting is meant, it is one of the board-wide settings in `Extensions » Modern Admin Panel » Settings` (see [Settings page](#settings-page)).

## Contents
- [Sidebar](#sidebar)
- [Header and search box](#header-and-search-box)
- [Page bar](#page-bar)
- [Command palette](#command-palette)
- [Colour mode](#colour-mode)
- [Colours](#colours)
- [Content area](#content-area)
- [Dashboard](#dashboard)
- [Settings page](#settings-page)
- [Permissions](#permissions)
- [Keyboard shortcuts](#keyboard-shortcuts)
- [Other](#other)

## Sidebar
The ACP menu is a sidebar of one list, painted with the sidebar colour: **Home**, then one collapsible row per tab of the ACP (General, Forums, and so on), each with its sections and their modules. Only the modules the administrator may open are in it. It is 264px wide. Nothing is painted when a level opens (no block, no container): the indentation shows the hierarchy.

- **Home.** A row like the tabs: the home icon, a bold label and a chevron. The label goes to the ACP front page; the chevron opens the **pinned modules**, the shortcuts the administrator chose, which are the children of Home: plain rows, one per module. Home opens by itself only on the front page, where it is the current entry: on any other page only the path of the current page is open on load, pins or not (pinning a page does not open it either). With nothing pinned its panel only holds a hint about the star. The front page module of core is not listed a second time in the General tab: Home is it, and the command palette finds it as "Home".
- **Pinning a module.** On a module page the page bar has a star (see [Page bar](#page-bar)). One click pins the module under Home and the list updates at once, another unpins it. Up to 12 modules can be pinned. An extension with one visible menu item uses the extension name; with several items it uses “Extension › Item”. The same labels identify extension modules in the command palette. Core module titles stay unchanged. Pins are kept in the browser (see the [privacy notes](README.md#privacy-and-security)).
- **Three levels.** **A tab** is a row of 40px with its icon (20px), a bold label and a chevron that turns when it opens; one tab is open at a time, and the one that holds the current page is open when the page loads. **A section** is an accordion row indented under its tab: the same icon, its name as a small uppercase label (up to two lines in its row, so that names that start alike can be told apart) and a smaller chevron; it opens and closes its modules, one section is open at a time in each tab, and the section that holds the current page is open when the page loads. A section with a single module is a row of the section, not of its module: the icon and the **name of the section**, no chevron. The title of a module of an extension is usually as generic as "Settings", so several rows would read the same; the name of the section is, for an extension, usually the extension's own name. The row links to the module, and its tooltip reads "Section › Module" (for example "Spam Guard › Settings"). This is so for every section with one module, those of core included. Favourites and the command palette use the extension-aware labels described above; breadcrumbs retain phpBB's module hierarchy. **A module** is plain text, with no icon, on one line, indented under the name of its section, along a thin guide line. A name that appears three times (the section "Board configuration", the module "Board configuration" under it, and its copy in Quick access) is a row with a chevron, a row and a row, and can be told apart. A module that core lists under more than one category opens with the category it was chosen in (the sidebar writes the right `icat` in the link), so the right category and breadcrumbs light up. The palette and the pins use the same links.
- **The current page.** The current module has the highlight of the menu: a tint of the sidebar colour, a bold label and a bar at the start of its row. The path to it shows from the top: the icons of its tab and of its section are in the accent of the sidebar colour (on a light or a dark sidebar colour the accent is close to the text colour, as its contrast has to stay at 4.5:1). The **dot of the active path**: a tab or a section that holds the current module, while it is **closed** (you opened another tab or another section), has a small round dot, 6px, in the same accent, right after its name; it hides as soon as the row is open, because the current module is in sight then. The dot is decorative (`aria-hidden`), and a text only for screen readers, "(contains the current page)" ("(contiene la página actual)" in Spanish), comes with it and hides together with it. A row of a single module that is the current page has the highlight and no dot. On the rail (the menu folded into icons) the icons have no dot; the flyout of a tab has the same sections and the same dot as the pinned menu: open another section there and the section of the current module shows its dot. Nothing else is highlighted. The menu scrolls to the current module when it is out of sight.
- **Long names.** A module, a row of one module and a pinned module are one line; one that is too long for its row is cut with an ellipsis, and its title (the tooltip) has the whole name. Rows keep their height.
- **Section icons.** Every section of the core ACP tree has an icon in the icon map. Icon precedence is: **(1)** the built-in map, including core sections and curated known extensions; **(2)** the `dsr.modernadminpanel.menu_icons` event, whose valid icon overrides a built-in mapping (see [EVENTS.txt](EVENTS.txt)); **(3)** a title/vendor word guess for still-unmapped sections; **(4)** the generic extension icon. The curated set covers 30 source-verified public extensions that register ACP menus, plus local DSR mappings. It is not a popularity ranking or compatibility guarantee; see [curated extension icon coverage](menu-extension-icons.md). Guesses use ordered English and Spanish keywords from the translated section title, module name, then extension vendor/name. Matching ignores case and accents, but requires whole words or word beginnings. Invalid icons outside the sprite are rejected.
- **Pin toggle.** A button at the bottom of the sidebar (and Ctrl/Cmd+B) switches between two states. *Pinned* keeps the sidebar open, 264px wide, with labels. *Unpinned* folds it into a rail 64px wide with the category icons only. The state is kept in the browser, applied before the first paint, and followed live by the other tabs of the same browser. With no stored choice, a window 1280px wide or more starts pinned and a narrower one unpinned. The tack is tilted while the sidebar is unpinned. The sidebar is painted with the **sidebar colour** (see [Colours](#colours)); the shape of the rows is the setting "Menu entry shape" (rounded, squared or full width).
- **Rail and flyouts.** On the rail each category opens its content in a flyout next to its icon. The flyout has the same levels as the pinned menu (its title is a link to the tab, the sections are accordion rows with their icon and chevron, the modules are indented under their name, the current one has the highlight) on the surface of a popup, with the accent of the page, and nothing is painted when a section opens. It opens with one section open, the one of the current page or else the first, and the sections open and close in place. With a mouse the flyout opens after 120ms of hovering and closes 300ms after the pointer leaves. With the keyboard, the right arrow (the left arrow in right-to-left languages), Enter or Space opens it and moves to its first link, Up and Down move between its rows, and Esc or the back arrow closes it and returns to the tab. Home has a flyout with its pinned modules. On a touch screen the first tap on Home opens the flyout and the title inside it goes home. The flyout also closes when you click elsewhere, scroll the menu or resize the window.
- **Keyboard in the menu.** The tabs and the sections are buttons: Tab reaches them in order, Enter or Space opens or closes one. The modules of a closed section and the rows of a closed tab are not in the Tab order. Up and Down move between the rows that are in sight (Home, the tabs, and in the open tabs the sections and the modules of the open sections), Home and End go to the first and the last. The current module has `aria-current="page"`, a tab and a section have `aria-expanded` and `aria-controls`, and the list of the modules of a section is named by it.
- **Motion.** Only the chevrons turn (120ms). With reduced motion nothing moves.
- **Drawer.** On screens up to 800px wide the sidebar is a drawer, hidden until the menu button of the header opens it. It closes with its close button, the backdrop, Esc, Ctrl/Cmd+B or by choosing a module. While it is open the page behind it cannot be reached with the keyboard, the mouse or a screen reader, and focus goes to the current module. The drawer is the pinned menu: the same Home, tabs, sections and modules.
- The board name is the title of the sidebar and links to the ACP front page.

## Header and search box
The header is 56px high and always sticks to the top. It gets a stronger shadow once the page is scrolled. It is painted with the **header colour** (see [Colours](#colours)); the search box stays white on every colour. From the start to the end it holds:

- the **menu button**, only on screens up to 800px wide (it opens the drawer);
- the **search box**, centred in the whole bar, 40% of the window wide (at least 240px, at most 720px). Clicking it, or typing in it, opens the [command palette](#command-palette) with what was typed. It shows the shortcut (`Ctrl K`, `⌘ K` on Apple devices). Without JavaScript it is a form that searches the board settings and shows the ACP search page;
- the **colour mode button** (see [Colour mode](#colour-mode));
- the optional **premium modules** icon, which opens a bilingual overview of independently available paid modules and custom extensions; it does not imply any Dashboard feature is locked;
- the **notifications** menu, when the board has board notifications on: the number of unread notifications, the 5 latest unread ones, a button to mark each one as read and a row of buttons for the notification settings, for all notifications and for marking all as read;
- the **account menu**: the name of the administrator, and links to log out, to log out of the ACP and to the forum index.

## Page bar
A slim row at the top of the page content, under the header. It has the **breadcrumbs** of the current page at the start, and at the end the **star** that pins or unpins the current module. The star is shown on module pages only, outlined while the module is not pinned and filled while it is.

## Command palette
Open it with Ctrl/Cmd+K, with `/` when you are not typing in a field, or by clicking or typing in the search box of the header. It is a dialog with one text field.

- **Nothing typed:** the pinned modules, then the recent ones (the last 8 visited, not counting the current page). With the search setting "Show the pinned modules" off, the pinned modules are not listed here (the pins go on working in the sidebar menu, under Home, the recent ones stay, and typing still finds a pinned module like any other).
- **Modules:** typing filters the modules of the sidebar as you type. Case and accents are ignored. Every word must be in the title or in the path (tab › group). Modules whose title starts with the first word come first, then those whose title contains all words, then the rest, each in menu order. Up to 50 are listed. Each row shows the icon, the title and the path.
- **Settings:** with the search setting "Include settings" on, from 3 typed characters on, and after 250ms without typing, the palette asks the board for the settings that match (titles and explanations of the settings pages the administrator may open) and lists up to 20 of them under **Settings**. A loading line shows while it waits and an error line when the answer is not valid (for example when the ACP session has expired). Choosing a setting opens its page with the setting highlighted. See [EVENTS.txt](EVENTS.txt) for the request.
- **Forums and groups:** with the search setting "Include users, forums, groups and actions" on, the same request (3 characters or more, after 250ms) also lists up to 10 forums whose name contains every typed word, under **Forums** (for administrators with `a_forum`), and up to 10 groups, under **Groups** (for administrators with `a_group`). At most 100 SQL candidates per kind are checked, so results are approximate: earlier unrelated Unicode names can displace later matches. SQLite widens candidates for Unicode names before PHP checks them; this does not guarantee complete recall. phpBB's SQLite GLOB translation has existing limitations for some wildcard punctuation; Unicode widening does not change those semantics. A forum or a group opens its edit page. Groups are matched on the name the board shows ("Administradores", not the stored key), and a group that only founders manage is not listed for other administrators.
- **Users:** typing `@` and a name (the name needs 2 characters or more, after 250ms) lists up to 10 users whose name starts with it, under **Users**, for administrators with `a_user`. The match is on the start of the name, case and accents as phpBB cleans user names; bots and the guest user are never listed, and founders are only listed for founders. Choosing a user opens their page in user management. Only the name and a link come back: never an e-mail address, an IP address or any other data. With `@` the palette lists users only: no modules, settings, forums, groups or actions.
- **Actions:** the actions below are listed under **Actions** while their words are typed (as modules are matched, nothing is listed before something is typed), only for administrators who have the permission core asks for. Each one is a plain link. The maintenance actions are the ones of the Overview page, so core's own confirmation box comes first and nothing changes until it is confirmed: the palette never sends a request that changes anything.
  - Purge the cache, reset the board's start date, resynchronise statistics, reset most users ever online, resynchronise post counts, resynchronise dotted topics: `a_board`.
  - Purge all sessions: `a_board` and a founder.
  - View the admin log: `a_viewlogs`.
  - Disable the board: `a_board`. It is not an action: it opens the board settings at the setting (highlighted), and nothing changes until you save.
- **Last row:** from 3 characters on (not with `@`), the last row runs the board settings search page with the typed text.
- **Keys:** Up and Down choose a row (and wrap around), Enter opens it, Esc closes the palette. Tab stays in the field. A click on a row opens it, a click outside the dialog closes it. Choosing a module adds it to the recent list.

## Colour mode
The header button cycles through **System**, **Light** and **Dark**, and its label and tooltip name the mode in use. In System mode the ACP follows the colour scheme of the operating system, live. The choice is kept in the browser, applied before the first paint, and followed by other tabs. It works together with the colours. In light mode the header and the sidebar have the colours that were chosen. In dark mode they take neutral dark surfaces, whatever colours were chosen: the sidebar a little darker than the content area, the header a little lighter. The chosen colours then give only the accent (the current module, the icons of its path, the dot, links and focus rings).

Dark mode covers the stock ACP content (tables, forms, buttons, message boxes, pagination, permission pages, popups). The surfaces and text come from a neutral dark scheme, and the accent from the sidebar colour (a lighter variant of it for the dark surfaces).

## Colours
Two board settings, each offering 20 presets shown as round swatches on the settings page:

- **Header colour** (default: white) paints the header.
- **Sidebar colour** (default: purple to blue) paints the sidebar. It is also the **accent** of the rest of the ACP: the links, the main button of a form, the focus rings, the current page of the pagination, the tabs of the permission pages, the tiles of "Needs attention", the cards of the statistics and the command palette (a strip painted with the preset itself, a surface that is a tint of its hue, and the selected row) take their colour from it. Ordinary table headers and dividers use neutral theme surfaces and outline roles. The accent of each preset is generated once and stored as CSS, in a light and a dark variant.
- **Menu entry shape** (default: full width): how the rows of the menu are drawn. Rounded has stadium rows, a 16px container and a 16px Home button; squared has 8px corners on all three; full width has square rows that run from edge to edge of the sidebar.

Preset text and accent roles are designed for at least 4.5:1 text contrast and 3:1 edge/focus contrast on their intended surfaces. The message boxes (a container colour with the colour of that container and a 4px stripe at the start) and the buttons (the accent for the main action, outlined for the others, error for cancel and delete, tertiary for preview) use the same roles in light and dark mode.

## Content area
The stock markup of every ACP page, restyled with modern rules of its own, over the stock stylesheet and without changing a template:

- **Page spacing** uses 32px inline padding on wide screens, 24px from 801px to 1199px, and 16px up to 800px; block spacing is 24px above and 32px below, or 16px/24px on small screens. The page bar shares the content's inline spacing, including in right-to-left layouts.

- **Titles** of 1.5rem and 1.2rem, bold, with the line under them in a muted colour.
- **Panels** (the fieldsets) are cards: a thin edge, 12px corners, and the legend as the heading line of the card with a rule under it. Fieldsets that are not panels (quick forms, display options, the buttons of a form, `nobg`, `tabulated`) stay as they are.
- **Form rows** are a bold label with its small muted explanation and the field beside it, divided by hairlines instead of the vertical rules of the stock sheet. On small screens the label goes over the field.
- **Fields** are 36px high (44px on touch screens) with 6px corners and a quiet edge that darkens on hover and takes the accent, with a ring, on focus.
- **Buttons:** one filled button per form (the main action), the others outlined; cancel and delete in the error colour.
- **Main content text** uses the existing sans-serif stack at 15px with 22–23px line height. Descriptions remain at least 14px/20px.
- **Tables** use 15px/22px ordinary list cells, 14px/20px neutral headers with authored casing, clear card edges and row separators. Stronger header, section-band and column surfaces distinguish dense tables, including extension groups within one table. Ordinary spacers remain hairline separators. Permission yes/never states keep distinct themed fills. Genuine adjacent top-level lists have a 16px gap; list toolbars have an 8px gap. Permission grids stay compact and preserve their radio controls.
- **Pagination**, the **tabs of the permission pages** and the **message boxes** follow the same roles. The links are the accent, underlined on hover.

Nothing moves for decoration. Motion is limited to the drawer sliding in (200ms), chevrons turning, and controls changing colour (120ms). `prefers-reduced-motion` disables those transitions.

## Dashboard
The ACP front page. The widgets are shown or hidden with the [settings](#settings-page), and some depend on the permissions of the administrator (see [Permissions](#permissions)).

- **Needs attention.** A grid of equal tiles (at least 170px wide, all the same height), each one linking to the page that handles it: topics to approve, posts to approve, post reports, private message reports and inactive users. A tile has a small icon, the **number big on top** (with the thousands separator of the language: 5.116 in Spanish, 5,116 in English) and a short label under it, two lines at most. **Only what is waiting has a tile**: an item whose count is zero is not shown, and every tile carries the accent (the container colour of the sidebar colour). When nothing is waiting at all, one compact line, "All clear", with a check icon, takes the place of the grid. Each administrator only sees the items they have the permissions for, and only the forums they can read count. While the board is disabled, a notice with a link to the board settings comes first.
- **Statistics.** The board statistics (posts, topics, users and attachments, with the number per day, orphan attachments and the size of the upload directory), the board (start date, phpBB and PHP versions, and, when the version check is on, the link to it and a button to force it), the database (size and server), GZip compression and the avatar directory. They are shown as coloured cards, or, with "Statistics as cards" off, as the stock tables. They can be hidden altogether.
- **Admin notes.** A card with notes that every administrator who opens the front page can read. They are written in BBCode, except `[img]` and `[flash]`, which stay plain text so that a note cannot load content from another site. Administrators with the permission to change the board settings get a pencil button that opens an editor with a text box; saving needs a valid form key, writes an entry in the admin log and returns to the front page. A note that cannot be displayed is shown as plain text instead of breaking the page.
- **Quick access.** A card on the ACP index page (the dashboard) that lists the modules of the "Quick access" category. Administrators who may manage modules get a pencil button that opens that category in module management. A setting can hide the category from the sidebar menu. The category is global, not personal: it is phpBB's own (`ACP_QUICK_ACCESS`), the same for every administrator, and it is edited in ACP » System » Module management. What each administrator pins with the star (the favourites, shown under Home) is personal and kept in that browser (see [Sidebar](#sidebar)); the [settings page](#settings-page) says both next to the two quick access settings.
- **Admin log and inactive users.** The two lists of the stock front page. Each can be switched off; the permissions of the stock page still decide who sees them.

## Settings page
`Extensions » Modern Admin Panel » Settings`. The settings are the same for every administrator. Saving needs the permission to change board settings and a valid form key, only known values are accepted, and every save is written to the admin log.

| Setting | Default | What it does |
| --- | --- | --- |
| Header: Colour | White | The bar at the top of every ACP page (20 presets, shown as swatches). In dark mode the header is dark grey and this colour is not used. |
| Sidebar menu: Colour | Purple to blue | The sidebar menu, and the accent of the rest of the ACP: links, buttons, focus rings and the Ctrl+K search (20 presets, shown as swatches). In dark mode the menu is dark grey and this colour is only the accent. |
| Sidebar menu: Shape of the rows | Full width | How the rows of the sidebar menu are drawn: full width (square rows, edge to edge), squared or rounded (stadium rows). |
| Sidebar menu: Show quick access | On | The "Quick access" category in the sidebar menu. Turn it off to take it out of the menu. (Stored as `admin_dashboard_detach_qa`, which is 1 when it is hidden: the page asks it the other way round, and the stored key did not change.) |
| Search (Ctrl+K): Include settings | On | The search also lists the board settings that match, from 3 characters on. |
| Search (Ctrl+K): Include users, forums, groups and actions | On | The search also finds users (`@name`), forums and groups, and lists the maintenance actions. Each administrator only sees what their permissions allow. |
| Search (Ctrl+K): Show the pinned modules | On | When the search is opened with nothing typed, it lists the pinned modules first. Off: that group is left out. The pins stay in the sidebar menu, under Home, and the recent modules are not affected. |
| ACP index page: Show "Needs attention" | On | The "Needs attention" tiles. |
| ACP index page: Show the statistics | On | The statistics and the server information. |
| ACP index page: Show the statistics as cards | On | Cards instead of phpBB's standard tables (no effect while the statistics are not shown). |
| ACP index page: Show the admin notes | On | The admin notes card. |
| ACP index page: Show quick access | Off | A card with the modules of "Quick access". |
| ACP index page: Show the admin log | On | The admin log list. |
| ACP index page: Show the inactive users | On | The inactive users list.

Under the quick access setting of the sidebar menu block a short note (it covers the card of the ACP index page too) says that quick access is global (phpBB's own category, the same for every administrator, edited in ACP » System » Module management) and that the pins (the star) are personal (each administrator pins modules with the star; they are kept in that browser and shown under Home). Administrators with `a_modules` also get an "Edit quick access" button there, which opens that category in module management.

What is kept per browser instead (sidebar state, colour mode, pins and recents) has no setting.

## Permissions
| What | Permission |
| --- | --- |
| Open the settings page and save the settings | `a_board` |
| Edit the admin notes | `a_board` |
| Read the admin notes | Any administrator who opens the front page |
| "Board is disabled" notice | `a_board` |
| Tiles: topics and posts awaiting approval | `m_approve` in a forum the administrator can read |
| Tile: open post reports | `m_report` in a forum the administrator can read |
| Tile: open private message reports | `m_pm_report`, and private message reports must be on in the board settings |
| Tile: inactive users | `a_user` |
| Pencil of the quick access card, and the "Edit quick access" button of the settings page | `a_modules` |
| Settings in the palette and in the ACP search: board settings pages (board, features, avatars, messages, posts, signatures, feeds, registration) | `a_board` |
| Settings in the palette and in the ACP search: server settings pages (authentication, email, cookies, load, server, security) | `a_server` |
| Palette: users (`@name`), opens user management | `a_user`; founders are only listed for founders |
| Palette: forums, opens the forum edit page | `a_forum` |
| Palette: groups, opens the group edit page | `a_group`; groups that only founders manage are only listed for founders |
| Palette actions: purge the cache, reset the start date, resynchronise statistics, post counts and dotted topics, reset the online record | `a_board` |
| Palette action: purge all sessions | `a_board` and a founder |
| Palette action: view the admin log | `a_viewlogs` |
| Palette action: disable the board (opens the board settings) | `a_board` |
| Admin log list, inactive users list | The permissions of the stock front page (`a_viewlogs`, `a_user`) |

An administrator with none of `a_board`, `a_server`, `a_user`, `a_forum` and `a_group` (with the matching option on) gets no settings, forums, groups or users in the palette and the palette sends no request.

## Keyboard shortcuts
| Keys | Action |
| --- | --- |
| Ctrl/Cmd+K | Open the command palette (not in a text area). |
| `/` | Open the command palette when you are not typing in a field. |
| Ctrl/Cmd+B | Pin or unpin the sidebar; on screens up to 800px wide, open or close the drawer. Not while typing. |
| Esc | Close the palette, the open header menu, the flyout or the drawer. |
| Up, Down, Enter | In the palette: choose a row, open it. |
| Tab, Enter, Space | In the menu: Tab goes through the rows, Enter or Space opens or closes the focused section. |
| Up, Down, Home, End | In the menu and the drawer: move between the rows in sight, or to the first and the last. |
| Right arrow, Enter, Space | On a tab of the rail: open its flyout and go to its first link. |
| Right arrow | On Home on the rail: open its flyout. |
| Up, Down | In a flyout: move between its rows (the title, the sections, the modules of the open section). |
| Left arrow, Esc | In a flyout: close it and go back to the tab. |
| Space | On a yes/no switch of the settings page: flip it. |

In right-to-left languages the left and right arrows swap.

## Other
- **ACP search page.** The search of the header (without JavaScript) and the last row of the palette show a page with the matching settings, which open the settings page with the setting highlighted, and the matching modules.
- **Yes/No settings** of the core board settings pages are drawn as two toggle buttons, with a check on Yes and a cross on No; the radio buttons behind them stay reachable with the keyboard.
- **Form controls.** The checkboxes, radio buttons and selects of the stock pages are drawn the Material 3 way in CSS only (the inputs stay the same inputs). The form buttons have an icon chosen by their name or type: submit, reset, cancel, delete, preview, search, add, and the test buttons of extensions. Cancel, delete and preview are outlined.
- **Rounded corners.** Tables, the permission panels, role tooltips, the progress bar and the log box have the card radius.
- **Popup windows.** The popups of the ACP (whois, permission trace, progress bar and the like) follow the colour mode too, so they are not white on a dark ACP. They have no sidebar or header, and take the accent of the board's sidebar colour; purple to blue is only the fallback for a page that never runs the ACP page header.
- **Small screens.** The quick forms, `p.quick` rows and the display options of the stock pages wrap with a gap: the fields grow and the buttons take the whole row. The transparent fieldsets of the stock pages (`nobg`, `tabulated`) stay transparent instead of becoming panels.
- **If the script does not start.** The page falls back to the layout for a page without JavaScript (a plain menu above the page, the header search as a form) instead of staying in a layout that needs the script.
- **Menu icons.** Icons are Material Symbols in one inline sprite of 86 icons, drawn at 20px. Other extensions can add or change the icon of their modules and sections (see [EVENTS.txt](EVENTS.txt)). The curated, source-verified 30-extension ACP mapping and local DSR cases are listed in [menu-extension-icons.md](menu-extension-icons.md); this list is not a popularity ranking or compatibility guarantee.
- **Languages.** English and Spanish.
- **Accessibility.** A skip link, labelled landmarks, `aria-expanded`, `aria-current` and `aria-pressed` on the menu controls, visible focus rings, targets of 48px on touch screens, reduced motion respected, and right-to-left layouts.
- **Security headers.** ACP pages are sent with `X-Frame-Options: SAMEORIGIN` unless another extension has already set it.
