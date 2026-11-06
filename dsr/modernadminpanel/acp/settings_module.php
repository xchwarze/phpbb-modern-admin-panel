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
 * Modern Admin Panel: ACP settings module (Extensions » Modern Admin Panel » Settings)
 */
class settings_module
{
	/** @var string Page title */
	public $page_title;

	/** @var string Template name */
	public $tpl_name;

	/** @var string Set by the core module manager (p_master), which also sets $module_path: declared so PHP 8.2 sees no dynamic property */
	public $u_action;

	/** @var string */
	public $module_path;

	/**
	 * Show and save the settings. The module manager passes $id and $mode; one mode exists, so they are not used.
	 *
	 * @param string	$id		Module id
	 * @param string	$mode	Module mode
	 * @return void
	 */
	public function main($id = '', $mode = ''): void
	{
		global $phpbb_container;

		/** @var \dsr\modernadminpanel\controller\settings $controller */
		$controller = $phpbb_container->get('dsr.modernadminpanel.settings');

		$this->tpl_name = 'acp_admindashboard_settings';
		$this->page_title = 'STUDIO_DASHBOARD_SETTINGS_TITLE';

		$controller->set_page_url($this->u_action);
		$controller->handle();
	}
}
