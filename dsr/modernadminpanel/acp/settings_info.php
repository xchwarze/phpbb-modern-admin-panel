<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dsr\modernadminpanel\acp;

/**
 * Modern Admin Panel: ACP settings module info (read by ACP » System » Module management)
 */
class settings_info
{
	/**
	 * The module definition, as the core module manager expects it.
	 *
	 * @return array
	 */
	public function module(): array
	{
		return [
			'filename'	=> '\dsr\modernadminpanel\acp\settings_module',
			'title'		=> 'ACP_ADMIN_DASHBOARD_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_ADMIN_DASHBOARD_SETTINGS',
					'auth'	=> 'ext_dsr/modernadminpanel && acl_a_board',
					'cat'	=> ['ACP_ADMIN_DASHBOARD_TITLE'],
				],
			],
		];
	}
}
