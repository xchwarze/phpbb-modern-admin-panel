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

namespace dsr\modernadminpanel\acp;

/**
 * Modern Admin Panel: ACP search module (the header search box in controller\admin)
 */
class main_module
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
	 * Show the ACP search page.
	 *
	 * @return void
	 */
	public function main(): void
	{
		global $phpbb_container;

		/** @var \dsr\modernadminpanel\controller\admin $controller */
		$controller = $phpbb_container->get('dsr.modernadminpanel.controller');

		$this->tpl_name = 'search';
		$this->page_title = 'SEARCH';

		$controller->handle();
	}
}
