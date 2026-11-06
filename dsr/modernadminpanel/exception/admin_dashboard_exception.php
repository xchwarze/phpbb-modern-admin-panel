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

namespace dsr\modernadminpanel\exception;

/**
 * Modern Admin Panel: thrown by listener::capture_search_settings() to stop acp_board once its settings have been captured
 */
class admin_dashboard_exception extends \phpbb\exception\runtime_exception
{
}
