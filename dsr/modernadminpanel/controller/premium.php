<?php
/**
 * Modern Admin Panel premium module promotion.
 *
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace dsr\modernadminpanel\controller;

class premium
{
	/** Set false to remove the premium control and dialog from every ACP page. */
	public const SHOW_PROMOTION = true;

	public function assign_template(\phpbb\template\template $template, bool $enabled = self::SHOW_PROMOTION): void
	{
		$template->assign_vars([
			'S_STUDIO_PREMIUM_ENABLED' => $enabled,
			'STUDIO_PREMIUM_CONTACT_TOKEN' => $enabled ? base64_encode('xchwarze' . chr(64) . 'gmail.com') : '',
		]);
	}
}
