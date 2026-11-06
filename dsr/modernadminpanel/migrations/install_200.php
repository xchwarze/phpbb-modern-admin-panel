<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, phpBB Studio, https://www.phpbbstudio.com
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dsr\modernadminpanel\migrations;

/**
 * Modern Admin Panel: everything the extension keeps in the database
 *
 * - the board-wide options (config), seeded with \dsr\modernadminpanel\helper\options::DEFAULTS;
 * - the admin notes (config_text), parsed from the text as typed: the format the listener saves;
 * - the ACP modules: the (hidden) search module that the header search box and the palette use, and the
 *   Extensions » Modern Admin Panel » Settings page.
 *
 * It follows phpBB's v330 migration.
 * Reverting ("Delete data") removes all of it: phpBB runs the reverse of every step below, last first.
 */
class install_200 extends \phpbb\db\migration\container_aware_migration
{
	/**
	 * {@inheritDoc}
	 */
	public static function depends_on(): array
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update_data(): array
	{
		/** @var \phpbb\textformatter\s9e\parser $parser */
		$parser = $this->container->get('text_formatter.parser');

		$data = [];
		foreach (\dsr\modernadminpanel\helper\options::DEFAULTS as $name => $default)
		{
			$data[] = ['config.add', ['admin_dashboard_' . $name, is_bool($default) ? (int) $default : $default]];
		}

		return array_merge($data, [

			['config_text.add', ['admin_dashboard_notes', $parser->parse('[b]Welcome to the Modern Admin Panel[/b]!')]],

			// Keep the hidden search module unavailable while this extension is disabled.
			['module.add', [
				'acp',
				'ACP_CAT_GENERAL',
				[
					'module_basename'	=> '\dsr\modernadminpanel\acp\main_module',
					'module_langname'	=> 'ACP_ADMIN_DASHBOARD_SEARCH',
					'module_enabled'	=> true,
					'module_display'	=> false,
					'module_mode'		=> 'general',
					'module_auth'		=> 'ext_dsr/modernadminpanel',
					'before'			=> 'ACP_INDEX',
				],
			]],

			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_ADMIN_DASHBOARD_TITLE']],
			['module.add', ['acp', 'ACP_ADMIN_DASHBOARD_TITLE', [
				'module_basename'	=> '\dsr\modernadminpanel\acp\settings_module',
				'module_langname'	=> 'ACP_ADMIN_DASHBOARD_SETTINGS',
				'module_mode'		=> 'settings',
				'module_auth'		=> 'ext_dsr/modernadminpanel && acl_a_board',
			]]],
		]);
	}
}
