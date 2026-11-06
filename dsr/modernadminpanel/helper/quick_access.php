<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dsr\modernadminpanel\helper;

/**
 * Modern Admin Panel: the "Quick access" category of the ACP
 *
 * It is phpBB's own category (the module with the langname ACP_QUICK_ACCESS), the same for every administrator, and it is edited in
 * ACP » System » Module management. event\listener (the pencil of the quick access card on the ACP index) and controller\settings
 * (the link next to its two settings) both link to that page, so the lookup is here.
 */
class quick_access
{
	/**
	 * The URL of the page of the module management that edits the quick access category.
	 *
	 * Needs a_modules, like the page itself. The category is looked up by its langname among the modules of the ACP: another class (the
	 * MCP and the UCP have trees of their own) could have a module of that name.
	 *
	 * @param \phpbb\auth\auth					$auth			Auth object
	 * @param \phpbb\db\driver\driver_interface	$db				Database object
	 * @param string							$table_prefix	Table prefix
	 * @param string							$admin_path		The ACP directory under the phpBB root, with the root path in front
	 * @param string							$php_ext		php File extension
	 * @return string									The URL, or '' without a_modules or when the board has no such category
	 */
	public static function edit_url(\phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, string $table_prefix, string $admin_path, string $php_ext): string
	{
		if (!$auth->acl_get('a_modules'))
		{
			return '';
		}

		$sql = 'SELECT module_id
				FROM ' . $table_prefix . "modules
				WHERE module_class = 'acp'
					AND module_langname = 'ACP_QUICK_ACCESS'";
		$result = $db->sql_query_limit($sql, 1);
		$module_id = (int) $db->sql_fetchfield('module_id');
		$db->sql_freeresult($result);

		if ($module_id <= 0)
		{
			return '';
		}

		return append_sid("{$admin_path}index.{$php_ext}", [
			'i'			=> 'acp_modules',
			'mode'		=> 'acp',
			'parent_id'	=> $module_id,
		]);
	}
}
