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
 * Modern Admin Panel: the quick jumps of the command palette (users, forums and groups)
 *
 * Read-only lookups for controller\admin. The caller checks the permission of each kind (palette::features())
 * and the typed text; this class only reads names and builds links. What leaves it is a plain-text name and the
 * ACP page that edits that user, forum or group: no e-mail address, IP address or any other column.
 */
class jumps
{
	/** Most results of each kind */
	public const LIMIT = 10;

	/** Most rows of forums or groups read to find them, however many names match */
	protected const SCAN = 100;

	/** Most words of the typed text that must all be in a forum or group name */
	protected const WORDS = 5;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var string Table prefix */
	protected $table_prefix;

	/** @var string Admin path (the ACP directory under the phpBB root) */
	protected $admin_path;

	/** @var string php File extension */
	protected $php_ext;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\db\driver\driver_interface		$db				Database object
	 * @param \phpbb\language\language				$language		Language object
	 * @param \phpbb\user							$user			User object
	 * @param string								$table_prefix	Table prefix
	 * @param string								$root_path		phpBB root path
	 * @param string								$admin_path		Admin relative path
	 * @param string								$php_ext		php File extension
	 */
	public function __construct(
		\phpbb\db\driver\driver_interface $db,
		\phpbb\language\language $language,
		\phpbb\user $user,
		string $table_prefix,
		string $root_path,
		string $admin_path,
		string $php_ext
	)
	{
		$this->db			= $db;
		$this->language		= $language;
		$this->user			= $user;
		$this->table_prefix	= $table_prefix;
		$this->admin_path	= $root_path . $admin_path;
		$this->php_ext		= $php_ext;
	}

	/**
	 * Users whose clean name starts with the typed name.
	 *
	 * The match is a prefix of username_clean (which the users table indexes), never "contains". Bots and the
	 * guest user are left out, and so are founders when the administrator is not one: core refuses to open them.
	 *
	 * @param string	$name		The text typed after the "@", as typed
	 * @return array[]				Each: title (the user name) and url (the ACP user page), at most LIMIT
	 */
	public function users(string $name): array
	{
		// Core cleans the HTML-escaped name when it stores username_clean (the request class escapes what is typed)
		$prefix = utf8_clean_string(utf8_htmlspecialchars($name, ENT_COMPAT, 'UTF-8'));

		// Nothing to anchor the match on: without it "LIKE '%'" would list the first users of the board
		if ($prefix === '')
		{
			return [];
		}

		$excluded = [USER_IGNORE];

		if ((int) $this->user->data['user_type'] !== USER_FOUNDER)
		{
			$excluded[] = USER_FOUNDER;
		}

		$sql = 'SELECT user_id, username
				FROM ' . $this->table_prefix . 'users
				WHERE username_clean ' . $this->db->sql_like_expression($prefix . $this->db->get_any_char()) . '
					AND user_id <> ' . ANONYMOUS . '
					AND ' . $this->db->sql_in_set('user_type', $excluded, true) . '
				ORDER BY username_clean';
		$result = $this->db->sql_query_limit($sql, self::LIMIT);

		$found = [];

		while ($row = $this->db->sql_fetchrow($result))
		{
			$found[] = $this->item($row['username'], ['i' => 'acp_users', 'mode' => 'overview', 'u' => (int) $row['user_id']]);
		}

		$this->db->sql_freeresult($result);

		return $found;
	}

	/**
	 * Forums whose name contains every typed word (case-insensitive).
	 *
	 * The forums table is small, so the match is a contains match on the lower-cased name, as the stored name is
	 * (HTML-escaped by core): the words are escaped the same way and the rows are checked again as plain text.
	 *
	 * @param string[]	$words		The lower-case words (see controller\admin::parse_keywords())
	 * @return array[]				Each: title (the forum name) and url (the ACP page that edits the forum), at most LIMIT
	 */
	public function forums(array $words): array
	{
		$sql = 'SELECT forum_id, parent_id, forum_name
				FROM ' . $this->table_prefix . 'forums
				WHERE ' . $this->name_matches('forum_name', $words) . '
				ORDER BY left_id';
		$result = $this->db->sql_query_limit($sql, self::SCAN);

		$found = [];

		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($this->has_words($row['forum_name'], $words))
			{
				// The link core's own forum list gives its edit button
				$found[] = $this->item($row['forum_name'], ['i' => 'acp_forums', 'mode' => 'manage', 'parent_id' => (int) $row['parent_id'], 'f' => (int) $row['forum_id'], 'action' => 'edit']);
			}
		}

		$this->db->sql_freeresult($result);

		return array_slice($found, 0, self::LIMIT);
	}

	/**
	 * Groups whose name contains every typed word (case-insensitive).
	 *
	 * The seven special groups are always read, because their stored name is a language key: a group is matched
	 * on the name the board shows ("Administradores"), which is what the administrator types. Groups that only
	 * founders may manage (group_founder_manage) are left out for other administrators: core refuses to open them.
	 *
	 * @param string[]	$words		The lower-case words (see controller\admin::parse_keywords())
	 * @return array[]				Each: title (the group name) and url (the ACP page that edits the group), at most LIMIT
	 */
	public function groups(array $words): array
	{
		$sql = 'SELECT group_id, group_name, group_type
				FROM ' . $this->table_prefix . 'groups
				WHERE (group_type = ' . GROUP_SPECIAL . ' OR ' . $this->name_matches('group_name', $words) . ')
					' . ((int) $this->user->data['user_type'] === USER_FOUNDER ? '' : 'AND group_founder_manage = 0') . '
				ORDER BY group_type DESC, group_name';
		$result = $this->db->sql_query_limit($sql, self::SCAN);

		$found = [];

		while ($row = $this->db->sql_fetchrow($result))
		{
			$name = $this->group_name($row['group_name']);

			if ($this->has_words($name, $words))
			{
				$found[] = $this->item($name, ['i' => 'acp_groups', 'mode' => 'manage', 'action' => 'edit', 'g' => (int) $row['group_id']]);
			}
		}

		$this->db->sql_freeresult($result);

		return array_slice($found, 0, self::LIMIT);
	}

	/**
	 * One result: the name as plain text, and a link built here with append_sid().
	 *
	 * @param string	$title		The stored name
	 * @param array		$params		The ACP URL parameters
	 * @return array				title and url
	 */
	protected function item(string $title, array $params): array
	{
		return [
			'title'	=> palette::limit_text(palette::plain_text($title), palette::TITLE_MAX),
			'url'	=> append_sid("{$this->admin_path}index.{$this->php_ext}", $params, false),
		];
	}

	/**
	 * The name of a group as the board shows it: the special groups are translated (as \phpbb\group\helper::get_name() does).
	 *
	 * @param string	$stored		The stored group name
	 * @return string
	 */
	protected function group_name(string $stored): string
	{
		$key = 'G_' . utf8_strtoupper($stored);

		return $this->language->is_set($key) ? $this->language->lang($key) : $stored;
	}

	/**
	 * The SQL condition that a name column contains every word: each word is escaped for LIKE, and for HTML
	 * as core stores names.
	 *
	 * @param string	$column		The column
	 * @param string[]	$words		The lower-case words
	 * @return string
	 */
	protected function name_matches(string $column, array $words): string
	{
		$columns = ['forum_name' => 'forum_name', 'group_name' => 'group_name'];

		if (!isset($columns[$column]))
		{
			return '1 = 0';
		}

		$column = $columns[$column];
		$conditions = [];

		foreach (array_slice($words, 0, self::WORDS) as $word)
		{
			$word = utf8_htmlspecialchars($word, ENT_COMPAT, 'UTF-8');
			$conditions[] = $this->db->sql_lower_text($column) . ' ' . $this->db->sql_like_expression($this->db->get_any_char() . $word . $this->db->get_any_char());
		}

		$matches = $conditions ? implode(' AND ', $conditions) : '1 = 0';

		if ($this->db->get_sql_layer() === 'sqlite3')
		{
			// SQLite LOWER/GLOB does not fold Unicode; let PHP's Unicode matcher check these bounded candidates.
			return '(' . $matches . " OR {$column} GLOB '*[^ -~]*')";
		}

		return $matches;
	}

	/**
	 * Whether a name, as plain text, contains every word (the first WORDS, as the query did).
	 * The SQL condition only narrows the rows: HTML entities and the translated group names are settled here.
	 *
	 * @param string	$name		The stored or translated name
	 * @param string[]	$words		The lower-case words
	 * @return bool
	 */
	protected function has_words(string $name, array $words): bool
	{
		$title = utf8_strtolower(palette::plain_text($name));

		foreach (array_slice($words, 0, self::WORDS) as $word)
		{
			if (strpos($title, $word) === false)
			{
				return false;
			}
		}

		return true;
	}
}
