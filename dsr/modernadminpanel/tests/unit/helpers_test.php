<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dsr\modernadminpanel\tests\unit;

use dsr\modernadminpanel\helper\options;
use dsr\modernadminpanel\helper\palette;

require_once __DIR__ . '/../../helper/options.php';
require_once __DIR__ . '/../../helper/palette.php';

class helpers_test extends \phpbb_test_case
{
	public function test_options_read_validates_values_and_uses_defaults(): void
	{
		$params = ['colours' => ['white', 'purple-indigo', 'black'], 'corners' => ['full', 'rounded']];
		$config = new \phpbb\config\config([
			'admin_dashboard_header_colour' => 'black',
			'admin_dashboard_sidebar_colour' => '" onclick="alert(1)',
			'admin_dashboard_sidebar_corner' => 'invalid',
			'admin_dashboard_palette_jumps' => '0',
			'admin_dashboard_display_qa' => '1',
		]);

		$actual = options::read($config, $params);

		$this->assertSame('black', $actual['header_colour']);
		$this->assertSame('purple-indigo', $actual['sidebar_colour']);
		$this->assertSame('full', $actual['sidebar_corner']);
		$this->assertFalse($actual['palette_jumps']);
		$this->assertTrue($actual['display_qa']);
		$this->assertTrue($actual['display_notes']);
		$this->assertSame('white', options::read(new \phpbb\config\config([]), [])['header_colour']);
		$this->assertSame([], options::allowed('unknown', $params));
	}

	public function test_palette_features_require_each_feature_permission_and_option(): void
	{
		$none = ['settings' => false, 'users' => false, 'forums' => false, 'groups' => false];
		$this->assertSame($none, palette::features($this->auth_with_permissions([]), options::DEFAULTS));
		$this->assertSame($none, palette::features($this->auth_with_permissions(['a_board', 'a_user', 'a_forum', 'a_group']), []));

		foreach ([
			'a_board' => 'settings',
			'a_server' => 'settings',
			'a_user' => 'users',
			'a_forum' => 'forums',
			'a_group' => 'groups',
		] as $permission => $feature)
		{
			$expected = $none;
			$expected[$feature] = true;
			$this->assertSame($expected, palette::features($this->auth_with_permissions([$permission]), options::DEFAULTS));
		}
	}

	public function test_palette_actions_respect_permissions_and_founder_status(): void
	{
		$this->assertSame([], palette::actions($this->auth_with_permissions([]), USER_FOUNDER));
		$this->assertSame(['view_log'], array_column(palette::actions($this->auth_with_permissions(['a_viewlogs']), 0), 'id'));
		$this->assertSame(
			['purge_cache', 'date', 'stats', 'online', 'user', 'db_track', 'disable_board'],
			array_column(palette::actions($this->auth_with_permissions(['a_board']), 0), 'id')
		);
		$this->assertContains(
			'purge_sessions',
			array_column(palette::actions($this->auth_with_permissions(['a_board']), USER_FOUNDER), 'id')
		);
	}

	/**
	 * @dataProvider plain_text_cases
	 */
	public function test_plain_text_removes_markup_and_invalid_bytes(string $input, string $expected): void
	{
		$this->assertSame($expected, palette::plain_text($input));
	}

	public static function plain_text_cases(): array
	{
		return [
			['<script>alert(1)</script><style>body{}</style><p>Hello<br>world</p>', 'Hello world'],
			["<b>Caf&eacute;</b> &amp; té\x00\n next", 'Café & té next'],
			["broken\xFF", ''],
			['', ''],
		];
	}

	public function test_english_and_spanish_language_keys_match(): void
	{
		$lang = [];
		require __DIR__ . '/../../language/en/info_acp_dashboard.php';
		$english = array_keys($lang);

		$lang = [];
		require __DIR__ . '/../../language/es/info_acp_dashboard.php';
		$spanish = array_keys($lang);

		sort($english);
		sort($spanish);
		$this->assertSame($english, $spanish);
	}

	private function auth_with_permissions(array $permissions): \phpbb\auth\auth
	{
		$auth = $this->getMockBuilder(\phpbb\auth\auth::class)
			->disableOriginalConstructor()
			->getMock();
		$auth->method('acl_get')->willReturnCallback(function ($permission) use ($permissions)
		{
			return in_array($permission, $permissions, true);
		});

		return $auth;
	}
}
