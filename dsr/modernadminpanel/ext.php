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

namespace dsr\modernadminpanel;

/**
 * Modern Admin Panel: Extension base
 */
class ext extends \phpbb\extension\base
{
	/**
	 * phpBB 3.3.x, and phpBB 4.0.x as declared in composer.json (soft-require).
	 * Only 3.3.x has been tested.
	 *
	 * {@inheritDoc}
	 */
	public function is_enableable()
	{
		if (!phpbb_version_compare(PHPBB_VERSION, '3.3.0', '>=') || !phpbb_version_compare(PHPBB_VERSION, '4.1.0@dev', '<'))
		{
			return false;
		}

		// Two restyles of the ACP at once clash (both draw the sidebar and the header). The old extension must be disabled
		// before enabling this one; delete its data separately to avoid retaining its database records.
		if ($this->container->get('ext.manager')->is_enabled('phpbbstudio/admindashboard'))
		{
			$language = $this->container->get('language');
			$language->add_lang('info_acp_dashboard', 'dsr/modernadminpanel');

			return $language->lang('STUDIO_DASHBOARD_OLD_INSTALLED');
		}

		return true;
	}
}
