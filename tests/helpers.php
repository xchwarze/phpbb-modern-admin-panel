<?php
/** Focused helper tests, not a phpBB integration suite. */

namespace phpbb\config
{
	class config extends \ArrayObject {}
}

namespace phpbb\auth
{
	/** Permission input double; all decisions under test live in the extension. */
	class auth
	{
		private $permissions;

		public function __construct(array $permissions)
		{
			$this->permissions = $permissions;
		}

		public function acl_get($permission)
		{
			return in_array($permission, $this->permissions, true);
		}
	}
}

namespace
{
	use dsr\modernadminpanel\helper\options;
	use dsr\modernadminpanel\helper\palette;

	set_error_handler(function ($severity, $message, $file, $line) {
		throw new \ErrorException($message, 0, $severity, $file, $line);
	});
	define('IN_PHPBB', true);
	define('USER_FOUNDER', 3);
	require __DIR__ . '/../dsr/modernadminpanel/helper/options.php';
	require __DIR__ . '/../dsr/modernadminpanel/helper/palette.php';

	$checks = 0;
	function same($expected, $actual, $label)
	{
		global $checks;
		if ($expected !== $actual)
		{
			throw new \RuntimeException($label . ': ' . var_export($actual, true));
		}
		$checks++;
	}

	$params = ['colours' => ['white', 'purple-indigo', 'black'], 'corners' => ['full', 'rounded']];
	$config = new \phpbb\config\config([
		'admin_dashboard_header_colour' => 'black',
		'admin_dashboard_sidebar_colour' => '" onclick="alert(1)',
		'admin_dashboard_sidebar_corner' => 'invalid',
		'admin_dashboard_palette_jumps' => '0',
		'admin_dashboard_display_qa' => '1',
	]);
	$read = options::read($config, $params);
	same('black', $read['header_colour'], 'Valid colour');
	same('purple-indigo', $read['sidebar_colour'], 'Unsafe colour falls back');
	same('full', $read['sidebar_corner'], 'Unknown corner falls back');
	same(false, $read['palette_jumps'], 'Stored zero becomes false');
	same(true, $read['display_qa'], 'Stored one becomes true');
	same(true, $read['display_notes'], 'Missing boolean gets default');
	same('white', options::read(new \phpbb\config\config(), [])['header_colour'], 'Missing parameters use default');
	same([], options::allowed('unknown', $params), 'Unknown option has no choices');

	$none = ['settings' => false, 'users' => false, 'forums' => false, 'groups' => false];
	same($none, palette::features(new \phpbb\auth\auth([]), options::DEFAULTS), 'Permissions required');
	same($none, palette::features(new \phpbb\auth\auth(['a_board', 'a_user', 'a_forum', 'a_group']), []), 'Options required');
	foreach (['a_board' => 'settings', 'a_server' => 'settings', 'a_user' => 'users', 'a_forum' => 'forums', 'a_group' => 'groups'] as $permission => $feature)
	{
		$expected = $none;
		$expected[$feature] = true;
		same($expected, palette::features(new \phpbb\auth\auth([$permission]), options::DEFAULTS), $permission . ' only enables its search');
	}
	same([], palette::actions(new \phpbb\auth\auth([]), USER_FOUNDER), 'Founder still needs permissions');
	same(['view_log'], array_column(palette::actions(new \phpbb\auth\auth(['a_viewlogs']), 0), 'id'), 'Log permission only');
	$regular = array_column(palette::actions(new \phpbb\auth\auth(['a_board']), 0), 'id');
	same(['purge_cache', 'date', 'stats', 'online', 'user', 'db_track', 'disable_board'], $regular, 'Regular admin actions');
	$founder = array_column(palette::actions(new \phpbb\auth\auth(['a_board']), USER_FOUNDER), 'id');
	same(true, in_array('purge_sessions', $founder, true), 'Founder can purge sessions');

	foreach ([
		['<script>alert(1)</script><style>body{}</style><p>Hello<br>world</p>', 'Hello world'],
		["<b>Caf&eacute;</b> &amp; té\x00\n next", 'Café & té next'],
		["broken\xFF", ''],
		['', ''],
	] as list($input, $expected))
	{
		same($expected, palette::plain_text($input), 'Plain-text sanitation');
	}

	$lang = [];
	require __DIR__ . '/../dsr/modernadminpanel/language/en/info_acp_dashboard.php';
	$english = array_keys($lang);
	$lang = [];
	require __DIR__ . '/../dsr/modernadminpanel/language/es/info_acp_dashboard.php';
	$spanish = array_keys($lang);
	sort($english);
	sort($spanish);
	same($english, $spanish, 'English and Spanish keys match');
	echo "PHP helper and language checks: $checks OK\n";
}
