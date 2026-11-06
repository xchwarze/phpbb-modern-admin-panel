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
 * Modern Admin Panel: what the command palette offers an administrator
 *
 * Decisions and text helpers without a database or any output. event\listener (which builds the page) and
 * controller\admin (which answers the palette request) both ask here, so they agree on who may do what.
 */
class palette
{
	/** Longest title in a palette row, in characters */
	public const TITLE_MAX = 120;

	/**
	 * Which server-side searches of the palette are on for an administrator.
	 *
	 * The settings search needs the option "Settings in the palette" and a_board or a_server (the permissions of
	 * the acp_board pages it reads). Each quick jump needs the option "Quick jumps and actions" and the permission
	 * of the ACP page it opens: a_user, a_forum and a_group.
	 *
	 * @param \phpbb\auth\auth	$auth		Auth object
	 * @param array				$options	The board-wide options (options::read())
	 * @return array						Feature => bool: settings, users, forums, groups
	 */
	public static function features(\phpbb\auth\auth $auth, array $options): array
	{
		$jumps = !empty($options['palette_jumps']);

		return [
			'settings'	=> !empty($options['palette_settings']) && ($auth->acl_get('a_board') || $auth->acl_get('a_server')),
			'users'		=> $jumps && $auth->acl_get('a_user'),
			'forums'	=> $jumps && $auth->acl_get('a_forum'),
			'groups'	=> $jumps && $auth->acl_get('a_group'),
		];
	}

	/**
	 * The actions the palette lists for an administrator. Each is a plain link to an ACP page.
	 *
	 * The maintenance actions are the ones of the Overview page (includes/acp/acp_main.php, action=<id>). That page
	 * answers a link with core's confirmation box and changes nothing before it is confirmed, so the palette sends
	 * no request that changes state. An action needs what the Overview page needs: a_board for the maintenance
	 * block, and a founder for "purge sessions". "Disable the board" only opens the board settings at the setting.
	 *
	 * @param \phpbb\auth\auth	$auth		Auth object
	 * @param int				$user_type	user_type of the administrator (USER_FOUNDER for a founder)
	 * @return array[]						Each: id, lang (key of the title), hint (key of the second line, or ''), icon (a symbol of images/icons.svg) and params (ACP URL parameters, '#' is the anchor)
	 */
	public static function actions(\phpbb\auth\auth $auth, int $user_type): array
	{
		$actions = [];

		if ($auth->acl_get('a_board'))
		{
			$maintenance = [
				['purge_cache',		'STUDIO_DASHBOARD_ACTION_PURGE_CACHE',		'refresh'],
				['date',			'STUDIO_DASHBOARD_ACTION_RESET_DATE',		'schedule'],
				['stats',			'STUDIO_DASHBOARD_ACTION_RESYNC_STATS',		'database'],
				['online',			'STUDIO_DASHBOARD_ACTION_RESET_ONLINE',		'visibility'],
				['user',			'STUDIO_DASHBOARD_ACTION_RESYNC_POSTCOUNTS',	'chat_bubble'],
				['db_track',		'STUDIO_DASHBOARD_ACTION_RESYNC_DOTS',		'topic'],
			];

			if ($user_type === USER_FOUNDER)
			{
				$maintenance[] = ['purge_sessions', 'STUDIO_DASHBOARD_ACTION_PURGE_SESSIONS', 'key'];
			}

			foreach ($maintenance as list($id, $lang, $icon))
			{
				$actions[] = [
					'id'		=> $id,
					'lang'		=> $lang,
					'hint'		=> 'STUDIO_DASHBOARD_ACTION_CONFIRMS',
					'icon'		=> $icon,
					'params'	=> ['i' => 'acp_main', 'mode' => 'main', 'action' => $id],
				];
			}
		}

		if ($auth->acl_get('a_viewlogs'))
		{
			$actions[] = [
				'id'		=> 'view_log',
				'lang'		=> 'STUDIO_DASHBOARD_ACTION_VIEW_LOG',
				'hint'		=> '',
				'icon'		=> 'history',
				'params'	=> ['i' => 'acp_logs', 'mode' => 'admin'],
			];
		}

		if ($auth->acl_get('a_board'))
		{
			$actions[] = [
				'id'		=> 'disable_board',
				'lang'		=> 'STUDIO_DASHBOARD_ACTION_DISABLE_BOARD',
				'hint'		=> 'STUDIO_DASHBOARD_ACTION_DISABLE_BOARD_HINT',
				'icon'		=> 'settings',
				'params'	=> ['i' => 'acp_board', 'mode' => 'settings', 'studio_search' => 'board_disable', '#' => 'board_disable'],
			];
		}

		return $actions;
	}

	/**
	 * A language string or a stored name as plain text: no <script> or <style> at all, no other tags (block
	 * tags like <br> leave a space), entities decoded, control characters gone, and white space collapsed.
	 * Text that is not valid UTF-8 comes out empty.
	 *
	 * @param string	$text		The language string or name
	 * @return string
	 */
	public static function plain_text(string $text): string
	{
		$text = (string) preg_replace('~<(script|style)\b[^>]*>.*?</\1\s*>~is', ' ', $text);
		$text = (string) preg_replace('~<\s*/?\s*(?:br|p|ul|ol|li|dl|dt|dd|div|hr|tr|td|th|h[1-6])\b[^>]*>~i', ' ', $text);
		$text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$text = (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/', '', $text);

		return trim((string) preg_replace('/\s+/u', ' ', $text));
	}

	/**
	 * Cut a text to at most $max characters, ending in an ellipsis when it was longer.
	 *
	 * @param string	$text		The plain text
	 * @param int		$max		The most characters
	 * @return string
	 */
	public static function limit_text(string $text, int $max): string
	{
		if (utf8_strlen($text) <= $max)
		{
			return $text;
		}

		return rtrim(utf8_substr($text, 0, $max - 1)) . "\u{2026}";
	}
}
