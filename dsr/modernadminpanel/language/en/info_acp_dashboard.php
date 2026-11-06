<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// Some characters you may want to copy&paste: ’ » “ ” …
$lang = array_merge($lang, [
	// ACP modules and log entries
	'STUDIO_PREMIUM_TOOLTIP' => 'Premium modules',
	'STUDIO_PREMIUM_EYEBROW' => 'Optional paid modules',
	'STUDIO_PREMIUM_TITLE' => 'Strengthen your community',
	'STUDIO_PREMIUM_INTRO' => 'These independent modules are available as paid options. They do not limit any feature in Modern Admin Panel.',
	'STUDIO_PREMIUM_OAUTH_TITLE' => 'OAuth Register',
	'STUDIO_PREMIUM_OAUTH_TEXT' => 'Let members sign in or register through external identity providers.',
	'STUDIO_PREMIUM_SPAM_TITLE' => 'Spam Guard',
	'STUDIO_PREMIUM_SPAM_TEXT' => 'Combine manual filters with AI-assisted spam detection.',
	'STUDIO_PREMIUM_ASYNC_TITLE' => 'Async Notifications',
	'STUDIO_PREMIUM_ASYNC_TEXT' => 'Process notifications in the background for large forums.',
	'STUDIO_PREMIUM_PUSH_TITLE' => 'Push Notifications',
	'STUDIO_PREMIUM_PUSH_TEXT' => 'Send push alerts to forum users.',
	'STUDIO_PREMIUM_APPS_TITLE' => 'Android and iOS apps',
	'STUDIO_PREMIUM_APPS_TEXT' => 'Integrated mobile apps for your forum.',
	'STUDIO_PREMIUM_CUSTOM_TITLE' => 'Custom extensions',
	'STUDIO_PREMIUM_CUSTOM_TEXT' => 'Turn your idea into a feature for your community. We design extensions integrated with your forum and tailored to your needs.',
	'STUDIO_PREMIUM_CLOSE' => 'Close',
	'STUDIO_PREMIUM_CONTACT' => 'Ask by email',
	'STUDIO_PREMIUM_SUBJECT' => 'Question about premium modules',
	'STUDIO_PREMIUM_BODY' => 'I am interested in one of the paid modules.',
	'ACP_INDEX'								=> 'Overview',
	'ACP_ADMIN_DASHBOARD_TITLE'				=> 'Modern Admin Panel',
	'ACP_ADMIN_DASHBOARD_SEARCH'			=> 'ACP search',
	'ACP_ADMIN_DASHBOARD_SETTINGS'			=> 'Settings',
	'LOG_ADMIN_DASHBOARD_NOTES'				=> '<strong>Edited the admin notes</strong>',
	'LOG_ADMIN_DASHBOARD_SETTINGS'			=> '<strong>Changed the Modern Admin Panel settings</strong>',

	// Sidebar, header and page bar
	'STUDIO_DASHBOARD_ACCOUNT'				=> 'Account',
	'STUDIO_DASHBOARD_BREADCRUMBS'			=> 'You are here',
	'STUDIO_DASHBOARD_HOME'					=> 'Home',
	'STUDIO_DASHBOARD_HOME_TOGGLE'			=> 'Show or hide the pinned modules',
	'STUDIO_DASHBOARD_MENU'					=> 'Administration menu',
	'STUDIO_DASHBOARD_MENU_CLOSE'			=> 'Close the menu',
	'STUDIO_DASHBOARD_MENU_HERE'			=> '(contains the current page)',
	'STUDIO_DASHBOARD_MENU_OPEN'			=> 'Open the menu',
	'STUDIO_DASHBOARD_MENU_PIN'				=> 'Pin the menu',
	'STUDIO_DASHBOARD_MENU_PIN_KEEP'		=> 'Keep the menu open (Ctrl+B)',
	'STUDIO_DASHBOARD_MENU_PIN_RELEASE'		=> 'Unpin the menu and show icons only (Ctrl+B)',
	'STUDIO_DASHBOARD_MODE'					=> 'Colour mode: %s',
	'STUDIO_DASHBOARD_MODE_DARK'			=> 'Dark',
	'STUDIO_DASHBOARD_MODE_LIGHT'			=> 'Light',
	'STUDIO_DASHBOARD_MODE_SYSTEM'			=> 'System',
	'STUDIO_DASHBOARD_PIN'					=> 'Pin this module to the menu',
	'STUDIO_DASHBOARD_PINNED'				=> 'Pinned',
	'STUDIO_DASHBOARD_PINS_EMPTY'			=> 'Pin modules with %s',
	'STUDIO_DASHBOARD_RECENT'				=> 'Recent',
	'STUDIO_DASHBOARD_SEARCH_ACP'			=> 'Search the admin panel',
	'STUDIO_DASHBOARD_SKIP'					=> 'Skip to content',

	// Command palette
	'STUDIO_DASHBOARD_MODULES'					=> 'Modules in the menu',
	'STUDIO_DASHBOARD_PALETTE'					=> 'Go to a module',
	'STUDIO_DASHBOARD_PALETTE_EMPTY'			=> 'Nothing matches.',
	'STUDIO_DASHBOARD_PALETTE_ERROR'			=> 'The search failed.',
	'STUDIO_DASHBOARD_PALETTE_HINT'				=> '↑ ↓ choose · Enter open · Esc close',
	'STUDIO_DASHBOARD_PALETTE_LOADING'			=> 'Searching…',
	'STUDIO_DASHBOARD_PALETTE_PLACEHOLDER'		=> 'Type a module name…',
	'STUDIO_DASHBOARD_PALETTE_PLACEHOLDER_USERS'	=> 'Type a module name, or @ and a user name…',
	'STUDIO_DASHBOARD_PALETTE_SEARCH_PAGE'		=> 'Search the board settings for “%s”',
	'STUDIO_DASHBOARD_PALETTE_SETTINGS_GROUP'	=> 'Settings',
	'STUDIO_DASHBOARD_PALETTE_START'			=> 'Start typing to search the menu. Your recent and pinned modules are listed here.',
	'STUDIO_DASHBOARD_PALETTE_START_USERS'		=> 'Start typing to search the menu, or type @ and a user name. Your recent and pinned modules are listed here.',
	'STUDIO_DASHBOARD_PALETTE_START_NO_PINS'		=> 'Start typing to search the menu. Your recent modules are listed here.',
	'STUDIO_DASHBOARD_PALETTE_START_USERS_NO_PINS'		=> 'Start typing to search the menu, or type @ and a user name. Your recent modules are listed here.',
	'STUDIO_DASHBOARD_PALETTE_ACTIONS_GROUP'	=> 'Actions',
	'STUDIO_DASHBOARD_PALETTE_FORUMS_GROUP'		=> 'Forums',
	'STUDIO_DASHBOARD_PALETTE_GROUPS_GROUP'		=> 'Groups',
	'STUDIO_DASHBOARD_PALETTE_USERS_GROUP'		=> 'Users',
	'STUDIO_DASHBOARD_PALETTE_USERS_HINT'		=> 'Type at least 2 letters of a user name after the @.',

	// Actions of the command palette: plain links, listed for administrators who may use them
	'STUDIO_DASHBOARD_ACTION_CONFIRMS'			=> 'Asks for confirmation first',
	'STUDIO_DASHBOARD_ACTION_PURGE_CACHE'		=> 'Purge the cache',
	'STUDIO_DASHBOARD_ACTION_RESET_DATE'		=> 'Reset the board’s start date',
	'STUDIO_DASHBOARD_ACTION_RESYNC_STATS'		=> 'Resynchronise statistics',
	'STUDIO_DASHBOARD_ACTION_RESET_ONLINE'		=> 'Reset most users ever online',
	'STUDIO_DASHBOARD_ACTION_RESYNC_POSTCOUNTS'	=> 'Resynchronise post counts',
	'STUDIO_DASHBOARD_ACTION_RESYNC_DOTS'		=> 'Resynchronise dotted topics',
	'STUDIO_DASHBOARD_ACTION_PURGE_SESSIONS'	=> 'Purge all sessions',
	'STUDIO_DASHBOARD_ACTION_VIEW_LOG'			=> 'View the admin log',
	'STUDIO_DASHBOARD_ACTION_DISABLE_BOARD'		=> 'Disable the board',
	'STUDIO_DASHBOARD_ACTION_DISABLE_BOARD_HINT'	=> 'Opens the board settings. Nothing changes until you save.',

	// Dashboard
	'STUDIO_DASHBOARD_ADMIN_NOTES'			=> 'Administrator notes',
	'STUDIO_DASHBOARD_AVATARS'				=> 'Avatar',
	'STUDIO_DASHBOARD_BOARD'				=> 'Forum',
	'STUDIO_DASHBOARD_BOARD_SETTINGS'		=> 'Board settings',
	'STUDIO_DASHBOARD_GZIP'					=> 'Gzip',
	'STUDIO_DASHBOARD_NOTES_EXPLAIN'		=> 'Every administrator can read these notes. BBCode works, except for images and Flash.',
	'STUDIO_DASHBOARD_ORPHANS'				=> [
		1 => '%d orphaned file',
		2 => '%d orphaned files',
	],
	'STUDIO_DASHBOARD_PER_DAY'				=> '%s per day',
	'STUDIO_DASHBOARD_PENDING'				=> 'Needs attention',
	'STUDIO_DASHBOARD_PENDING_CLEAR'		=> 'All clear',
	'STUDIO_DASHBOARD_PENDING_INACTIVE'		=> 'Inactive users',
	'STUDIO_DASHBOARD_PENDING_PM_REPORTS'	=> 'PM reports',
	'STUDIO_DASHBOARD_PENDING_POSTS'		=> 'Posts to approve',
	'STUDIO_DASHBOARD_PENDING_REPORTS'		=> 'Post reports',
	'STUDIO_DASHBOARD_PENDING_TOPICS'		=> 'Topics to approve',
	// The thousands separator of the language, for the counts of that list (the board has no helper for it)
	'STUDIO_DASHBOARD_THOUSANDS_SEPARATOR'	=> ',',

	// Search page
	'STUDIO_DASHBOARD_MODULES_MATCHING'		=> 'Modules found',
	'STUDIO_DASHBOARD_MODULES_NONE'			=> 'No module matches what you typed.',
	'STUDIO_DASHBOARD_RESULTS_NO'			=> 'Nothing found',
	'STUDIO_DASHBOARD_RESULTS_NONE'			=> 'No setting matches what you typed.',
	'STUDIO_DASHBOARD_RESULTS_NOTE'			=> 'A missing result does not prove that the setting is not there.<br>The settings search covers only part of the ACP, mostly the settings of the “General” tab.',

	// Settings page (Extensions » Modern Admin Panel » Settings)
	'STUDIO_DASHBOARD_SETTINGS_TITLE'		=> 'Modern Admin Panel settings',
	'STUDIO_DASHBOARD_SETTINGS_EXPLAIN'		=> 'These settings are shared by every administrator. The menu state (pinned or not), the pinned and recent modules and the colour mode are kept by each browser.',
	'STUDIO_DASHBOARD_SETTINGS_INVALID'		=> 'One of the submitted values is not valid.',
	'STUDIO_DASHBOARD_APPEARANCE'			=> 'Header',
	'STUDIO_DASHBOARD_MENU_LEGEND'		=> 'Sidebar menu',
	'STUDIO_DASHBOARD_HEADER_COLOUR'		=> 'Colour',
	'STUDIO_DASHBOARD_HEADER_COLOUR_EXPLAIN'	=> 'The bar at the top. In dark mode it is dark grey and this colour is not used.',
	'STUDIO_DASHBOARD_SIDEBAR_COLOUR'		=> 'Colour',
	'STUDIO_DASHBOARD_SIDEBAR_COLOUR_EXPLAIN'	=> 'Also the accent colour: links, buttons, focus rings and the search. In dark mode the menu is dark grey and this colour is only the accent.',
	'STUDIO_DASHBOARD_SIDEBAR_CORNER'		=> 'Shape of the rows',
	'STUDIO_DASHBOARD_SIDEBAR_CORNER_EXPLAIN'	=> 'Rounded, squared or full width.',
	'STUDIO_DASHBOARD_PALETTE_LEGEND'		=> 'Search (Ctrl+K)',
	'STUDIO_DASHBOARD_PALETTE_SETTINGS'		=> 'Include settings',
	'STUDIO_DASHBOARD_PALETTE_SETTINGS_EXPLAIN'	=> 'Besides modules, finds the settings that match what is typed (from 3 characters). Each administrator only sees those of the pages they may open.',
	'STUDIO_DASHBOARD_PALETTE_JUMPS'		=> 'Include users, forums, groups and actions',
	'STUDIO_DASHBOARD_PALETTE_JUMPS_EXPLAIN'	=> 'Finds users (type @ and a name), forums and groups (from 3 characters), and lists maintenance actions such as purging the cache, which ask for confirmation. Each administrator only sees what their permissions allow.',
	'STUDIO_DASHBOARD_PALETTE_PINS'			=> 'Show the pinned modules',
	'STUDIO_DASHBOARD_PALETTE_PINS_EXPLAIN'		=> 'When opened with nothing typed, it lists first the modules pinned with the star (☆). They still show in the sidebar menu, under Home.',
	'STUDIO_DASHBOARD_INDEX_LEGEND'			=> 'ACP index page',

	// The yes/no options: STUDIO_DASHBOARD_<NAME> and STUDIO_DASHBOARD_<NAME>_EXPLAIN (adm/style/acp_admindashboard_settings.html)
	'STUDIO_DASHBOARD_DISPLAY_PENDING'			=> 'Show “Needs attention”',
	'STUDIO_DASHBOARD_DISPLAY_PENDING_EXPLAIN'	=> 'Topics and posts waiting for approval, open reports and inactive users, plus a warning while the board is disabled. Each administrator only sees the counts of what they may handle.',
	'STUDIO_DASHBOARD_DISPLAY_STATS'			=> 'Show the statistics',
	'STUDIO_DASHBOARD_DISPLAY_STATS_EXPLAIN'	=> 'Board statistics and server information.',
	'STUDIO_DASHBOARD_REMODEL_STATS'			=> 'Show the statistics as cards',
	'STUDIO_DASHBOARD_REMODEL_STATS_EXPLAIN'	=> 'Instead of phpBB’s standard tables. No effect while the statistics are hidden.',
	'STUDIO_DASHBOARD_DISPLAY_NOTES'			=> 'Show the admin notes',
	'STUDIO_DASHBOARD_DISPLAY_NOTES_EXPLAIN'	=> 'Notes shared by the administrators. Any administrator who may change the board settings can edit them.',
	'STUDIO_DASHBOARD_DISPLAY_QA'				=> 'Show quick access',
	'STUDIO_DASHBOARD_DISPLAY_QA_EXPLAIN'		=> 'A card with the modules of the “Quick access” category.',
	'STUDIO_DASHBOARD_SHOW_QA_MENU'				=> 'Show quick access',
	'STUDIO_DASHBOARD_SHOW_QA_MENU_EXPLAIN'		=> 'Turn it off for a shorter menu if quick access already shows on the index page.',
	'STUDIO_DASHBOARD_QA_EDIT'					=> 'Edit quick access',
	'STUDIO_DASHBOARD_QA_HELP_GLOBAL_TITLE'	=> 'Quick access is global',
	'STUDIO_DASHBOARD_QA_HELP_GLOBAL'			=> 'It is phpBB’s own category (ACP_QUICK_ACCESS), the same for every administrator. It is edited in ACP » System » Module management.',
	'STUDIO_DASHBOARD_QA_HELP_PINS_TITLE'		=> 'Pins (☆) are personal',
	'STUDIO_DASHBOARD_QA_HELP_PINS'			=> 'Each administrator pins modules with the star. Pins are kept in their browser and shown under Home.',
	'STUDIO_DASHBOARD_DISPLAY_LOGS'				=> 'Show the admin log',
	'STUDIO_DASHBOARD_DISPLAY_LOGS_EXPLAIN'		=> 'The latest entries, only for those who may view the log.',
	'STUDIO_DASHBOARD_DISPLAY_USERS'			=> 'Show the inactive users',
	'STUDIO_DASHBOARD_DISPLAY_USERS_EXPLAIN'	=> 'The most recent ones, only for those who may manage users.',

	// Colours and shapes: STUDIO_DASHBOARD_CHOICE_<VALUE> (controller/settings.php)
	'STUDIO_DASHBOARD_CHOICE_FULL'	=> 'Full width',
	'STUDIO_DASHBOARD_CHOICE_SQUARED'	=> 'Squared',
	'STUDIO_DASHBOARD_CHOICE_ROUNDED'	=> 'Rounded',
	'STUDIO_DASHBOARD_CHOICE_PHPBB'	=> 'phpBB blue',
	'STUDIO_DASHBOARD_CHOICE_BLUE_LIGHT'	=> 'Light blue',
	'STUDIO_DASHBOARD_CHOICE_DARK_BLUE'	=> 'Dark blue',
	'STUDIO_DASHBOARD_CHOICE_PURPLE_INDIGO'	=> 'Purple to blue',
	'STUDIO_DASHBOARD_CHOICE_PURPLE_DARK'	=> 'Dark purple',
	'STUDIO_DASHBOARD_CHOICE_PURPLE_ORANGE'	=> 'Purple to orange',
	'STUDIO_DASHBOARD_CHOICE_PINK_ORANGE'	=> 'Pink to orange',
	'STUDIO_DASHBOARD_CHOICE_ORANGE_LIGHT'	=> 'Light orange',
	'STUDIO_DASHBOARD_CHOICE_PINK_LIGHT'	=> 'Light pink',
	'STUDIO_DASHBOARD_CHOICE_GREEN_CYAN'	=> 'Green to cyan',
	'STUDIO_DASHBOARD_CHOICE_RED'	=> 'Red',
	'STUDIO_DASHBOARD_CHOICE_PURPLE'	=> 'Purple',
	'STUDIO_DASHBOARD_CHOICE_VIOLET'	=> 'Pink',
	'STUDIO_DASHBOARD_CHOICE_INDIGO'	=> 'Indigo',
	'STUDIO_DASHBOARD_CHOICE_CYAN'	=> 'Cyan',
	'STUDIO_DASHBOARD_CHOICE_TEAL'	=> 'Teal',
	'STUDIO_DASHBOARD_CHOICE_BLUE'	=> 'Blue',
	'STUDIO_DASHBOARD_CHOICE_ORANGE'	=> 'Orange',
	'STUDIO_DASHBOARD_CHOICE_WHITE'	=> 'White',
	'STUDIO_DASHBOARD_CHOICE_BLACK'	=> 'Black',

	// ext.php: the old extension is still enabled
	'STUDIO_DASHBOARD_OLD_INSTALLED'		=> 'The old phpbbstudio/admindashboard extension is enabled. Back up the board and copy any admin notes you want to keep. Disable the old extension, delete its data separately in Extension management, then enable this extension. This extension does not import or remove the old extension’s database records.',
]);
