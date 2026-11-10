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

namespace dsr\modernadminpanel\event;

use dsr\modernadminpanel\exception\admin_dashboard_exception;
use dsr\modernadminpanel\helper\options;
use dsr\modernadminpanel\helper\palette;
use dsr\modernadminpanel\helper\quick_access;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Modern Admin Panel: Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\event\dispatcher_interface */
	protected $dispatcher;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\log\log_interface */
	protected $log;

	/** @var \phpbb\notification\manager */
	protected $notifications;

	/** @var \phpbb\textformatter\s9e\parser */
	protected $parser;

	/** @var \phpbb\textformatter\s9e\renderer */
	protected $renderer;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \dsr\modernadminpanel\helper\search_capture */
	protected $search_capture;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\textformatter\s9e\utils */
	protected $utils;

	/** @var string Table prefix */
	protected $table_prefix;

	/** @var string Admin path (the ACP directory under the phpBB root) */
	protected $admin_path;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string php File extension */
	protected $php_ext;

	/** @var array Dashboard extension parameters */
	protected $params;

	/** @var \dsr\modernadminpanel\controller\premium */
	protected $premium;

	/** @var array|null The board-wide options, read once per request */
	protected $options;

	/** @var array The sections (l_block2) of the ACP menu of this request, to guess an icon for: module id => langname, title, module basenames */
	protected $sections = [];

	/**
	 * Constructor.
	 *
	 * @param \phpbb\auth\auth						$auth				Auth object
	 * @param \phpbb\config\config					$config				Config object
	 * @param \phpbb\config\db_text					$config_text		Config text object
	 * @param \phpbb\db\driver\driver_interface		$db					Database object
	 * @param \phpbb\event\dispatcher_interface		$dispatcher			Event dispatcher object
	 * @param \phpbb\language\language				$language			Language object
	 * @param \phpbb\log\log_interface				$log				Log object
	 * @param \phpbb\notification\manager			$notifications		Notifications manager object
	 * @param \phpbb\textformatter\s9e\parser		$parser				Textformatter parser object (a lazy proxy, see services.yml)
	 * @param \phpbb\textformatter\s9e\renderer		$renderer			Textformatter renderer object (a lazy proxy)
	 * @param \phpbb\request\request				$request			Request object
	 * @param \dsr\modernadminpanel\helper\search_capture	$search_capture		Settings capture shared with the search controller
	 * @param \phpbb\template\template				$template			Template object
	 * @param \phpbb\user							$user				User object
	 * @param \phpbb\textformatter\s9e\utils		$utils				Textformatter utilities object
	 * @param string								$table_prefix		Table prefix
	 * @param string								$admin_path			Admin relative path
	 * @param string								$root_path			phpBB root path
	 * @param string								$php_ext			php File extension
	 * @param array									$params				Dashboard extension parameters
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\config\db_text $config_text,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\event\dispatcher_interface $dispatcher,
		\phpbb\language\language $language,
		\phpbb\log\log_interface $log,
		\phpbb\notification\manager $notifications,
		\phpbb\textformatter\s9e\parser $parser,
		\phpbb\textformatter\s9e\renderer $renderer,
		\phpbb\request\request $request,
		\dsr\modernadminpanel\helper\search_capture $search_capture,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\textformatter\s9e\utils $utils,
		string $table_prefix,
		string $admin_path,
		string $root_path,
		string $php_ext,
		array $params,
		\dsr\modernadminpanel\controller\premium $premium
	)
	{
		$this->auth				= $auth;
		$this->config			= $config;
		$this->config_text		= $config_text;
		$this->db				= $db;
		$this->dispatcher		= $dispatcher;
		$this->language			= $language;
		$this->log				= $log;
		$this->notifications	= $notifications;
		$this->parser			= $parser;
		$this->renderer			= $renderer;
		$this->request			= $request;
		$this->search_capture	= $search_capture;
		$this->template			= $template;
		$this->user				= $user;
		$this->utils			= $utils;

		$this->table_prefix		= $table_prefix;
		$this->admin_path		= $root_path . $admin_path;
		$this->root_path		= $root_path;
		$this->php_ext			= $php_ext;
		$this->params			= $params;
		$this->premium			= $premium;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'core.acp_board_config_edit_add'	=> 'capture_search_settings',
			'core.adm_page_header_after'		=> 'assign_shell_vars',
			'core.acp_main_notice'				=> 'handle_dashboard',
			'core.build_config_template'		=> 'restyle_yes_no_settings',
			'core.modify_module_row'			=> 'collect_module_row',
		];
	}

	/**
	 * While the ACP settings search captures the settings of an acp_board page (controller\admin), keep them
	 * and stop acp_board before it saves or renders anything. At any other time do nothing.
	 *
	 * @param \phpbb\event\data		$event		The event object
	 * @return void
	 * @throws admin_dashboard_exception	While capturing
	 */
	public function capture_search_settings(\phpbb\event\data $event): void
	{
		if ($this->search_capture->is_capturing())
		{
			$this->search_capture->set_captured($event['display_vars']);

			throw new admin_dashboard_exception();
		}
	}

	/**
	 * Keep the sections of the ACP menu and what is in them, for the icon of a section that has none (guess_menu_icon()). Core runs
	 * this event for every module of the tree it builds, in menu order: a section (depth 1, a category) before its modules (depth 2).
	 * Only the ACP, the one menu this extension draws.
	 *
	 * @param \phpbb\event\data		$event		The event object
	 * @return void
	 */
	public function collect_module_row(\phpbb\event\data $event): void
	{
		$row = $event['module_row'];

		if (!defined('ADMIN_START') || !is_array($row))
		{
			return;
		}

		if ((int) $row['depth'] === 1 && (string) $row['name'] === '')
		{
			$this->sections[(int) $row['id']] = [
				'langname'	=> (string) $row['langname'],
				'title'		=> (string) $row['lang'],
				'bases'		=> [],
			];
		}
		else if ((int) $row['depth'] === 2 && (string) $row['name'] !== '' && isset($this->sections[(int) $row['parent']]))
		{
			$this->sections[(int) $row['parent']]['bases'][] = (string) $row['name'];
		}
	}

	/**
	 * The data of the shell (sidebar, header, palette) on every ACP page: notifications, the
	 * administrator, the theme, the palette's endpoint and actions, and the menu icons.
	 *
	 * @param \phpbb\event\data		$event		The event object
	 * @return void
	 */
	public function assign_shell_vars(\phpbb\event\data $event): void
	{
		$this->premium->assign_template($this->template);
		$options = $this->get_options();

		// Do not let another site frame the ACP (clickjacking). Core sets no X-Frame-Options of its own
		// (phpBB 3.3.19) and nothing in the ACP is framed from another origin. A header another listener
		// already set stays as it is.
		$http_headers = (array) ($event['http_headers'] ?? []);

		if (!isset($http_headers['X-Frame-Options']))
		{
			$http_headers['X-Frame-Options'] = 'SAMEORIGIN';
			$event['http_headers'] = $http_headers;
		}

		if ($this->config['load_notifications'] && $this->config['allow_board_notifications'])
		{
			$notifications = $this->notifications->load_notifications('notification.method.board', [
				'all_unread'	=> true,
				'limit'			=> 5,
			]);

			/** @var \phpbb\notification\type\base $notification */
			foreach ($notifications['notifications'] as $notification)
			{
				$this->template->assign_block_vars('notifications', $notification->prepare_for_display());
			}

			$this->template->assign_vars([
				'NOTIFICATIONS_COUNT'			=> $notifications['unread_count'],

				'S_NOTIFICATIONS_DISPLAY'		=> true,

				'U_VIEW_ALL_NOTIFICATIONS'		=> append_sid("{$this->root_path}ucp.{$this->php_ext}", 'i=ucp_notifications'),
				'U_MARK_ALL_NOTIFICATIONS'		=> append_sid("{$this->root_path}ucp.{$this->php_ext}", 'i=ucp_notifications&amp;mode=notification_list&amp;mark=all&amp;token=' . generate_link_hash('mark_all_notifications_read')),
				'U_NOTIFICATION_SETTINGS'		=> append_sid("{$this->root_path}ucp.{$this->php_ext}", 'i=ucp_notifications&amp;mode=notification_options'),
			]);
		}

		// Core fills these on the ACP index before the page header is built
		if ($this->template->retrieve_var('S_STUDIO_DASHBOARD') === true)
		{
			if (!$options['display_logs'])
			{
				$this->template->destroy_block_vars('log');
			}

			if (!$options['display_users'])
			{
				$this->template->assign_var('S_INACTIVE_USERS', false);
			}
		}

		$this->assign_palette($options);

		$this->template->assign_vars([
			'USER_AVATAR'				=> phpbb_get_user_avatar($this->user->data),
			'USERNAME_COLOUR'			=> get_username_string('full', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']),

			// The colours and the shape of the menu entries: known values, printed as class names (studio-bg-<colour>, studio-hue-<colour>, studio-menu-corner-<corner>)
			'T_STUDIO_HEADER_COLOUR'	=> $options['header_colour'],
			'T_STUDIO_SIDEBAR_COLOUR'	=> $options['sidebar_colour'],
			'T_STUDIO_SIDEBAR_CORNER'	=> $options['sidebar_corner'],

			'S_STUDIO_DETACH_QA'		=> $options['detach_qa'],
			'S_STUDIO_DISPLAY_QA'		=> $options['display_qa'],
			'S_STUDIO_DISPLAY_NOTES'	=> $options['display_notes'],
			'S_STUDIO_DISPLAY_PENDING'	=> $options['display_pending'],
			'S_STUDIO_DISPLAY_STATS'	=> $options['display_stats'],
			'S_STUDIO_REMODEL_STATS'	=> $options['remodel_stats'],

			'STUDIO_MENU_ICONS'			=> $this->get_menu_icons(),
			// The sidebar state is per browser (js/studio.js, set before the first paint by
			// event/acp_overall_header_head_append.html); the user id scopes recents and pins
			'STUDIO_USER_ID'			=> (int) $this->user->data['user_id'],
			'STUDIO_SITENAME'			=> $this->config['sitename'],

			'U_STUDIO_ADMIN_DASHBOARD'	=> append_sid("{$this->admin_path}index.{$this->php_ext}", [
				'i'		=> '-dsr-modernadminpanel-acp-main_module',
				'mode'	=> 'general',
			]),
		]);
	}

	/**
	 * The dashboard of the ACP index: saves the admin notes, prepares them for display or editing, the quick
	 * access link and the "needs attention" counts.
	 *
	 * @return void
	 */
	public function handle_dashboard(): void
	{
		$options = $this->get_options();
		$notes = (string) $this->config_text->get('admin_dashboard_notes');
		$can_edit = (bool) $this->auth->acl_get('a_board');
		$u_dashboard = append_sid("{$this->admin_path}index.{$this->php_ext}", 'i=acp_main&amp;mode=main');

		if ($this->request->is_set_post('submit_notes'))
		{
			if (!$can_edit)
			{
				trigger_error($this->language->lang('NO_AUTH_OPERATION') . adm_back_link($u_dashboard), E_USER_WARNING);
			}

			if (!check_form_key('studio_admin_notes'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($u_dashboard), E_USER_WARNING);
			}

			// The request HTML-escapes its strings. Undo that, so the parser gets the text as typed (like core's
			// message parser) and the stored XML renders without any decoding
			$notes = htmlspecialchars_decode($this->request->variable('admin_notes', '', true), ENT_COMPAT);

			$this->config_text->set('admin_dashboard_notes', $this->parse_notes($notes));

			// Every admin sees the notes, so keep track of who changed them
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_DASHBOARD_NOTES');

			// Post/Redirect/Get, so reloading the dashboard does not post the notes again
			redirect($u_dashboard);
		}

		$edit = $can_edit && $options['display_notes'] && $this->request->is_set('edit_notes');

		if ($edit)
		{
			add_form_key('studio_admin_notes', '_STUDIO_NOTES');
		}

		if ($options['display_qa'] && $this->auth->acl_get('a_modules'))
		{
			$this->template->assign_var('U_STUDIO_ADMIN_QA', quick_access::edit_url($this->auth, $this->db, $this->table_prefix, $this->admin_path, $this->php_ext));
		}

		if ($options['display_pending'])
		{
			$this->assign_pending();
		}

		$this->template->assign_vars([
			'STUDIO_ADMIN_NOTES'			=> $options['display_notes'] && !$edit && $notes !== '' ? $this->render_notes($notes) : '',
			'STUDIO_ADMIN_NOTES_EDIT'		=> $edit ? $this->notes_text($notes) : '',

			'S_STUDIO_DASHBOARD'			=> true,
			'S_STUDIO_ADMIN_NOTES_EDIT'		=> $edit,
			'S_STUDIO_ADMIN_NOTES_CAN_EDIT'	=> $can_edit,
		]);
	}

	/**
	 * Draw the Yes/No settings as a pair of labels that css/content.css can style.
	 *
	 * @param \phpbb\event\data		$event		The event object
	 * @return void
	 */
	public function restyle_yes_no_settings(\phpbb\event\data $event): void
	{
		if ($event['tpl_type'][0] === 'radio')
		{
			$key = $event['key'];
			$new = $event['new'];
			$name = $event['name'];
			$type = $event['tpl_type'];

			$config_key = str_replace('config[', '', $name);
			$config_key = trim($config_key, ']');

			$template = '';
			$id_added = false;
			$enabled = !empty($new[$config_key]);

			foreach (explode('_', $type[1]) as $label)
			{
				$negation	= in_array($label, ['no', 'disabled']);

				$checked	= $negation ? !$enabled : $enabled;
				$checked	= $checked ? ' checked="checked"' : '';
				$class		= $negation ? 'studio-bool-no' : 'studio-bool-yes';
				$value		= $negation ? ' value="0"' : ' value="1"';
				$id			= !$id_added ? ' id="' . $key . '"' : '';

				$id_added	= true;

				// The radio is only hidden visually (css/content.css), so it stays keyboard accessible
				$template .= '<label>';
				$template .= '<input class="studio-bool"' . $id . ' name="' . $name . '" type="radio" ' . $value . $checked . ' />';
				$template .= '<span class="' . $class . '">' . $this->language->lang(strtoupper($label)) . '</span>';
				$template .= '</label>';
			}

			$event['tpl'] = $template;
		}
	}

	/**
	 * The board-wide options (see \dsr\modernadminpanel\helper\options).
	 *
	 * @return array
	 */
	protected function get_options(): array
	{
		if ($this->options === null)
		{
			$this->options = options::read($this->config, $this->params);
		}

		return $this->options;
	}

	/**
	 * Give the command palette its endpoint and its actions.
	 *
	 * The endpoint (controller\admin) is a read-only JSON request checked by a link hash. Its URL is only given
	 * when at least one search is on for this administrator (palette::features()): otherwise the palette sends
	 * no request. The actions are plain links, listed only when the administrator may use them.
	 *
	 * @param array		$options	The board-wide options
	 * @return void
	 */
	protected function assign_palette(array $options): void
	{
		$features = palette::features($this->auth, $options);
		$admin_index = "{$this->admin_path}index.{$this->php_ext}";

		$this->template->assign_vars([
			'U_STUDIO_PALETTE_SEARCH'	=> in_array(true, $features, true) ? append_sid($admin_index, [
				'i'				=> '-dsr-modernadminpanel-acp-main_module',
				'mode'			=> 'general',
				'studio_palette'	=> 1,
				'hash'			=> generate_link_hash('studio_palette'),
			], false) : '',
			'S_STUDIO_PALETTE_USERS'	=> $features['users'],
			'S_STUDIO_PALETTE_PINS'		=> $options['palette_pins'],
		]);

		$actions = $options['palette_jumps'] ? palette::actions($this->auth, (int) $this->user->data['user_type']) : [];

		foreach ($actions as $action)
		{
			$this->template->assign_block_vars('studio_actions', [
				'TITLE'		=> $this->language->lang($action['lang']),
				'HINT'		=> $action['hint'] !== '' ? $this->language->lang($action['hint']) : '',
				'ICON'		=> $action['icon'],
				'U_ACTION'	=> append_sid($admin_index, $action['params']),
			]);
		}
	}

	/**
	 * The "needs attention" counts on the dashboard. Each one needs the permission
	 * of the page it links to, and all come from counters or indexed lookups:
	 * no scan of the posts or topics tables.
	 *
	 * @return void
	 */
	protected function assign_pending(): void
	{
		$items = [];
		$readable = array_keys($this->auth->acl_getf('f_read', true));

		// The per-forum counters phpBB keeps for the forum index and the MCP
		$approve = array_intersect(array_keys($this->auth->acl_getf('m_approve', true)), $readable);

		if (!empty($approve))
		{
			$sql = 'SELECT SUM(forum_topics_unapproved) AS topics, SUM(forum_posts_unapproved) AS posts
					FROM ' . $this->table_prefix . 'forums
					WHERE ' . $this->db->sql_in_set('forum_id', array_map('intval', $approve));
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$items[] = ['STUDIO_DASHBOARD_PENDING_TOPICS', 'topic', (int) $row['topics'], $this->mcp_url('i=queue&amp;mode=unapproved_topics')];
			$items[] = ['STUDIO_DASHBOARD_PENDING_POSTS', 'chat_bubble', (int) $row['posts'], $this->mcp_url('i=queue&amp;mode=unapproved_posts')];
		}

		// Counted like the MCP front page: the (small) reports table, joined on the post key
		$report = array_intersect(array_keys($this->auth->acl_getf('m_report', true)), $readable);

		if (!empty($report))
		{
			$sql = 'SELECT COUNT(r.report_id) AS total
					FROM ' . $this->table_prefix . 'reports r, ' . $this->table_prefix . 'posts p
					WHERE r.post_id = p.post_id
						AND r.pm_id = 0
						AND r.report_closed = 0
						AND ' . $this->db->sql_in_set('p.forum_id', array_map('intval', $report));
			$items[] = ['STUDIO_DASHBOARD_PENDING_REPORTS', 'flag', $this->count_rows($sql), $this->mcp_url('i=reports&amp;mode=reports')];
		}

		if ($this->config['allow_pm_report'] && $this->auth->acl_get('m_pm_report'))
		{
			$sql = 'SELECT COUNT(r.report_id) AS total
					FROM ' . $this->table_prefix . 'reports r, ' . $this->table_prefix . 'privmsgs p
					WHERE r.post_id = 0
						AND r.pm_id = p.msg_id
						AND r.report_closed = 0';
			$items[] = ['STUDIO_DASHBOARD_PENDING_PM_REPORTS', 'mail', $this->count_rows($sql), $this->mcp_url('i=pm_reports&amp;mode=pm_reports')];
		}

		// The users table has an index on user_type
		if ($this->auth->acl_get('a_user'))
		{
			$sql = 'SELECT COUNT(user_id) AS total
					FROM ' . $this->table_prefix . 'users
					WHERE user_type = ' . USER_INACTIVE;
			$items[] = ['STUDIO_DASHBOARD_PENDING_INACTIVE', 'person_off', $this->count_rows($sql), append_sid("{$this->admin_path}index.{$this->php_ext}", 'i=inactive&amp;mode=list')];
		}

		foreach ($items as $item)
		{
			$this->template->assign_block_vars('studio_pending', [
				'TITLE'		=> $this->language->lang($item[0]),
				'ICON'		=> $item[1],
				'COUNT'		=> $item[2],
				'COUNT_FORMATTED'	=> $this->format_count($item[2]),
				'U_VIEW'	=> $item[3],
			]);
		}

		$this->template->assign_vars([
			'S_STUDIO_BOARD_DISABLED'	=> $this->config['board_disable'] && $this->auth->acl_get('a_board'),
			'U_STUDIO_BOARD_SETTINGS'	=> append_sid("{$this->admin_path}index.{$this->php_ext}", 'i=acp_board&amp;mode=settings'),
		]);
	}

	/**
	 * A count with the thousands separator of the language (5.116 in Spanish, 5,116 in English): phpBB has no number helper, so
	 * the separator is a language string of the extension; a language without it gets the comma.
	 *
	 * @param int	$count	The count
	 * @return string
	 */
	protected function format_count(int $count): string
	{
		$separator = $this->language->is_set('STUDIO_DASHBOARD_THOUSANDS_SEPARATOR') ? $this->language->lang('STUDIO_DASHBOARD_THOUSANDS_SEPARATOR') : ',';

		return number_format($count, 0, '', $separator);
	}

	/**
	 * Run a "SELECT COUNT(...) AS total" query.
	 *
	 * @param string	$sql		The query
	 * @return int
	 */
	protected function count_rows(string $sql): int
	{
		$result = $this->db->sql_query($sql);
		$total = (int) $this->db->sql_fetchfield('total');
		$this->db->sql_freeresult($result);

		return $total;
	}

	/**
	 * A link into the MCP.
	 *
	 * @param string	$params		The URL parameters
	 * @return string
	 */
	protected function mcp_url(string $params): string
	{
		return append_sid("{$this->root_path}mcp.{$this->php_ext}", $params);
	}

	/**
	 * Parse the admin notes. Every admin sees them on the ACP index, so the BBCodes
	 * that make the browser load remote content (an image is a tracking pixel) stay text.
	 *
	 * @param string	$text		The notes as typed
	 * @return string				The parsed text (XML)
	 */
	protected function parse_notes(string $text): string
	{
		$this->parser->disable_bbcode('img');
		$this->parser->disable_bbcode('flash');

		try
		{
			return $this->parser->parse($text);
		}
		finally
		{
			// The core parser service is shared with the rest of the request
			$this->parser->enable_bbcode('img');
			$this->parser->enable_bbcode('flash');
		}
	}

	/**
	 * Render the admin notes. A note that cannot be rendered must not take
	 * the ACP index down, so any failure falls back to the escaped plain text.
	 *
	 * @param string	$notes		The parsed text (XML)
	 * @return string				HTML
	 */
	protected function render_notes(string $notes): string
	{
		// The renderer shows images and flash according to the viewing administrator's own preferences: not here,
		// whatever the XML holds
		$viewimg = $this->renderer->get_viewimg();
		$viewflash = $this->renderer->get_viewflash();
		$this->renderer->set_viewimg(false);
		$this->renderer->set_viewflash(false);

		try
		{
			return $this->renderer->render($notes);
		}
		catch (\Throwable $e)
		{
			return nl2br($this->notes_text($notes));
		}
		finally
		{
			$this->renderer->set_viewimg($viewimg);
			$this->renderer->set_viewflash($viewflash);
		}
	}

	/**
	 * The notes as text that is safe to print (text box, plain text fallback).
	 *
	 * @param string	$notes		The parsed text (XML)
	 * @return string				Unparsed and HTML-escaped
	 */
	protected function notes_text(string $notes): string
	{
		return utf8_htmlspecialchars($this->utils->unparse($notes), ENT_COMPAT);
	}

	/**
	 * Map the ACP menu entries to their Material icon (the name of a symbol of adm/style/images/icons.svg).
	 *
	 * Keyed by module langname, which does not depend on the board language. The sidebar shows the icon of a
	 * tab (l_block1) on its rail and the icon of every section (l_block2) in its rows: every section of the
	 * core tree (phpbb/install/module/install_data/task/add_modules.php) has one below. The icon of a section
	 * comes from, in this order: the map below, the dsr.modernadminpanel.menu_icons event, then a guess from its
	 * title and the name of its extension (guess_menu_icon()); a section that has none gets the generic
	 * extension icon in the menu (menu.html). The command palette shows the most specific icon it finds for a
	 * module: its own, its group's or its tab's, and a letter badge of the tab when there is none.
	 *
	 * @return array		Module langname => icon name
	 */
	protected function get_menu_icons(): array
	{
		$icons = [
			// Tabs
			'ACP_CAT_GENERAL'				=> 'settings',
			'ACP_CAT_FORUMS'				=> 'forum',
			'ACP_CAT_POSTING'				=> 'edit_note',
			'ACP_CAT_USERGROUP'				=> 'group',
			'ACP_CAT_PERMISSIONS'			=> 'key',
			'ACP_CAT_CUSTOMISE'				=> 'palette',
			'ACP_CAT_MAINTENANCE'			=> 'build',
			'ACP_CAT_SYSTEM'				=> 'dns',
			'ACP_CAT_DOT_MODS'				=> 'extension',

			// General
			'ACP_ADMIN_DASHBOARD_SEARCH'	=> 'search',
			'ACP_INDEX'						=> 'dashboard',
			'ACP_QUICK_ACCESS'				=> 'rocket_launch',
			'ACP_BOARD_CONFIGURATION'		=> 'tune',
			'ACP_CLIENT_COMMUNICATION'		=> 'contact_mail',
			'ACP_SERVER_CONFIGURATION'		=> 'memory',

			// Forums
			'ACP_MANAGE_FORUMS'				=> 'account_tree',
			'ACP_FORUM_BASED_PERMISSIONS'	=> 'shield_lock',

			// Posting
			'ACP_MESSAGES'					=> 'chat',
			'ACP_ATTACHMENTS'				=> 'attach_file',

			// Users and groups
			'ACP_CAT_USERS'					=> 'person',
			'ACP_GROUPS'					=> 'groups',
			'ACP_USER_SECURITY'				=> 'gavel',

			// Permissions
			'ACP_PERMISSIONS'				=> 'lock',
			'ACP_GLOBAL_PERMISSIONS'		=> 'public',
			'ACP_PERMISSION_ROLES'			=> 'badge',
			'ACP_PERMISSION_MASKS'			=> 'visibility',

			// Customise
			'ACP_EXTENSION_MANAGEMENT'		=> 'extension',
			'ACP_STYLE_MANAGEMENT'			=> 'brush',
			'ACP_LANGUAGE'					=> 'translate',

			// Maintenance
			'ACP_FORUM_LOGS'				=> 'history',
			'ACP_CAT_DATABASE'				=> 'database',
			'ACP_CRON_STATUS_TITLE'			=> 'schedule',

			// System
			'ACP_AUTOMATION'				=> 'automation',
			'ACP_GENERAL_TASKS'				=> 'checklist',
			'ACP_MODULE_MANAGEMENT'			=> 'widgets',

			// Extensions
			'ACP_ADMIN_DASHBOARD_TITLE'		=> 'dashboard',
			'ACP_BOARDRULES'					=> 'gavel',
			'ACP_APS_POINTS'				=> 'paid',
			'ACP_ASS_SYSTEM'				=> 'shopping_cart',
			'ACP_BOARD_ANNOUNCEMENTS'		=> 'campaign',
			'ACP_PHPBB_MEDIA_EMBED'			=> 'photo_library',
			'ACP_PHPBB_ADS_TITLE'				=> 'campaign',
			'ACP_TOPIC_PREFIXES'				=> 'topic',
			'ACP_CAT_MCHAT'					=> 'chat',
			'ACP_DSR_CKE_TITLE'				=> 'text_format',
			'ACP_FLAIR_TITLE'				=> 'workspace_premium',
			'ACP_MOBIQUO_TITLE'				=> 'smartphone',
			'ACP_DSR_OAUTH_REGISTER_TITLE'	=> 'login',
			'ACP_DSR_SECURE_TITLE'			=> 'shield',
			'ACP_DSR_SPAM_GUARD_TITLE'		=> 'shield',
			'ACP_DSR_SPAM_GUARD_IA_TITLE'	=> 'shield',
			'ACP_ULTIMATEBLOG'					=> 'article',
			'ACP_PAGES'						=> 'description',
			'ACP_POSTLOVE'					=> 'favorite',
			'ACP_POSTLOVE_GRP'				=> 'favorite',
			'ACP_POSTS_MERGING'				=> 'merge',
			'ACP_PRIMELINKS_TITLE'			=> 'link',
			'RECENT_TOPICS'						=> 'topic',
			'ACP_PORTAL'						=> 'dashboard',
			'ACP_CAT_CONTACTADMIN'			=> 'contact_mail',
			'ACP_PRIMENOTIFY_TITLE'			=> 'mail',
			'ACP_CAT_FLAGS'					=> 'flag',
			'ACP_PRIMEPOSTREVISIONS_TITLE'	=> 'history',
			'ACP_ABBC3_MODULE'				=> 'code',
			'TOPIC_PREVIEW'					=> 'topic',
			'ACP_SENKY_PUSHNOTIFICATIONS_CAT'	=> 'notifications',
			'ACP_SEO_METADATA'				=> 'travel_explore',
			'ACP_SFS_TITLE'					=> 'shield',
			'ACP_THANKS'					=> 'thumb_up',
			'ACP_VBLOG_TITLE'				=> 'movie',
			'PRIVACY_POLICY'				=> 'policy',
			'PST_TITLE_ACP'					=> 'topic',
			'SO_ACP'						=> 'share',
		];

		/**
		 * Add or change the icons of the Modern Admin Panel sidebar and command palette.
		 *
		 * @event dsr.modernadminpanel.menu_icons
		 * @var array	icons	Module langname => Material icon name, a symbol of adm/style/images/icons.svg,
		 * 						lower case with underscores (e.g. 'ACP_FOO' => 'rocket_launch')
		 * @since 2.0.0
		 */
		$vars = ['icons'];
		extract($this->dispatcher->trigger_event('dsr.modernadminpanel.menu_icons', compact($vars)));

		// The names end up in attributes, unescaped, and only the symbols of the sprite exist: any other
		// name is dropped, and its module gets a letter badge
		$sprite = $this->sprite_icons();

		$icons = array_filter((array) $icons, function($icon, $langname) use ($sprite)
		{
			return is_string($langname) && is_string($icon) && preg_match('/^[a-z0-9_-]+\z/', $icon) && isset($sprite[$icon]);
		}, ARRAY_FILTER_USE_BOTH);

		// Last, a guess for the sections that have none (the ones of the extensions that nobody mapped)
		foreach ($this->sections as $section)
		{
			if (!isset($icons[$section['langname']]))
			{
				$icon = $this->guess_menu_icon($section['title'], $section['langname'], $section['bases']);

				if ($icon !== '' && isset($sprite[$icon]))
				{
					$icons[$section['langname']] = $icon;
				}
			}
		}

		return $icons;
	}

	/**
	 * Keyword -> icon, in priority order: the first row with a word in the text decides. A word is a whole word, or with a "*" at the end
	 * a word that starts so. Lower case and without accents ("articul*" finds "art\u00edculo": the text is folded the same way). The icons
	 * are symbols of the sprite (a test checks that every one is there). Spanish and English; the specific rows come before the general ones
	 * ("spam" before "forum", so "Stop Forum Spam" is a shield).
	 */
	protected const ICON_WORDS = [
		['forum',				['discord']],
		['chat',				['chat*', 'shout*', 'irc']],
		['mail',				['mail*', 'email*', 'e mail', 'correo*', 'smtp', 'newsletter*', 'boletin*']],
		['backup',				['backup*', 'copia', 'copias', 'respaldo*']],
		['shield',				['spam*', 'antispam*', 'guard', 'seguridad', 'secur*', 'captcha*', 'firewall', 'protec*']],
		['login',				['oauth*', 'login*', 'sso', 'regist*', 'signup', 'autentic*', 'authentic*']],
		['notifications',		['push', 'notific*', 'aviso*', 'alert*']],
		['article',				['blog*', 'articul*', 'article*', 'news', 'noticia*']],
		['dashboard',			['dashboard*', 'panel*']],
		['bar_chart',			['stats', 'statistic*', 'estadist*', 'analytic*', 'analitic*', 'activity*', 'actividad*', 'metric*']],
		['travel_explore',		['seo', 'sitemap*', 'robots']],
		['schedule',			['cron*', 'tarea*', 'task*', 'schedul*', 'queue*', 'programad*']],
		['smartphone',			['android', 'app', 'apps', 'mobil*', 'movil*', 'tapatalk', 'phone*', 'telefon*', 'ios', 'pwa']],
		['report',				['error*', '404']],
		['gavel',				['rule*', 'regla*', 'norma*', 'moderat*', 'moderac*']],
		['photo_library',		['media', 'gallery', 'galleries', 'galeria*', 'photo*', 'foto*', 'image*', 'imagen*', 'album*']],
		['calendar_month',		['calendar*', 'calendario*', 'event*', 'evento*', 'agenda']],
		['how_to_vote',			['poll*', 'encuesta*', 'survey*', 'vote*', 'votac*', 'voting']],
		['attach_file',			['attach*', 'adjunt*', 'upload*', 'archivo*', 'file*']],
		['mood',				['smil*', 'emoji*', 'emoticon*', 'reaction*', 'reaccion*']],
		['code',				['bbcode*', 'code', 'codigo*', 'highlight*', 'syntax*', 'sintaxis']],
		['text_format',			['editor*', 'ckeditor*', 'wysiwyg', 'tinymce']],
		['palette',				['theme*', 'style*', 'estilo*', 'skin*', 'colour*', 'color*', 'apariencia']],
		['policy',				['privacy*', 'privacidad', 'gdpr', 'rgpd', 'terms', 'terminos', 'cookie*']],
		['share',				['share*', 'compart*', 'social*']],
		['merge',				['merg*', 'fusion*']],
		['workspace_premium',	['flair*', 'badge*', 'insignia*', 'rank*', 'rango*', 'medal*']],
		['link',				['link*', 'enlace*']],
		['description',			['page*', 'pagina*', 'wiki']],
		['group',				['user*', 'usuario*', 'member*', 'miembro*', 'group*', 'grupo*']],
		['forum',				['forum*', 'foro*']],
		['topic',				['similar*', 'parecid*', 'topic*', 'hilo*']],
	];

	/**
	 * Guess the icon of a section of an extension that is neither in the map nor given by the event, from words. What core gives the
	 * menu for a section (includes/functions_module.php, assign_tpl_vars()) is its translated title (L_TITLE) and its langname; its own
	 * module basename is empty (a category), so the extension is read from the basenames of its modules, "\vendor\name\acp\module"
	 * ("dsr discord link"). The texts are tried in this order, each against the whole table (ICON_WORDS): the title, the langname, the
	 * vendor and name of the extension. The title is the one a person wrote for people, so it is the most likely to say what the
	 * section is.
	 *
	 * @param string	$title		The translated title of the section
	 * @param string	$langname	Its module langname (ACP_FOO_TITLE)
	 * @param array		$bases		The module basenames of its modules
	 * @return string				The icon name, or '' when no word matches (the menu then draws the generic extension icon)
	 */
	protected function guess_menu_icon(string $title, string $langname, array $bases): string
	{
		$extension = '';

		foreach ($bases as $base)
		{
			$parts = explode('\\', trim($base, '\\'));

			if (count($parts) > 2)
			{
				$extension = $parts[0] . ' ' . $parts[1];
				break;
			}
		}

		foreach ([$title, $langname, $extension] as $text)
		{
			// Whole words between spaces: letters and digits, anything else is a separator ("discord_link", "a&amp;b")
			$text = html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8');
			$text = strtr(utf8_strtolower($text), [
				"\u{e1}" => 'a', "\u{e0}" => 'a', "\u{e2}" => 'a', "\u{e4}" => 'a', "\u{e3}" => 'a',
				"\u{e9}" => 'e', "\u{e8}" => 'e', "\u{ea}" => 'e', "\u{eb}" => 'e',
				"\u{ed}" => 'i', "\u{ec}" => 'i', "\u{ee}" => 'i', "\u{ef}" => 'i',
				"\u{f3}" => 'o', "\u{f2}" => 'o', "\u{f4}" => 'o', "\u{f6}" => 'o', "\u{f5}" => 'o',
				"\u{fa}" => 'u', "\u{f9}" => 'u', "\u{fb}" => 'u', "\u{fc}" => 'u',
				"\u{f1}" => 'n', "\u{e7}" => 'c',
			]);
			$text = ' ' . trim(preg_replace('/[^a-z0-9]+/', ' ', $text)) . ' ';

			foreach (self::ICON_WORDS as [$icon, $words])
			{
				foreach ($words as $word)
				{
					$prefix = substr($word, -1) === '*';

					if (strpos($text, ' ' . ($prefix ? substr($word, 0, -1) : $word . ' ')) !== false)
					{
						return $icon;
					}
				}
			}
		}

		return '';
	}

	/**
	 * The names of the icons in the sprite (adm/style/images/icons.svg, included once per page by
	 * adm/style/event/acp_overall_header_body_before.html), as keys. Read once per request.
	 *
	 * @return array		Icon name => true
	 */
	protected function sprite_icons(): array
	{
		static $names = null;

		if ($names === null)
		{
			$names = [];
			$sprite = @file_get_contents(dirname(__DIR__) . '/adm/style/images/icons.svg');

			if (is_string($sprite) && preg_match_all('/<symbol id="studio-i-([a-z0-9_-]+)"/', $sprite, $matches))
			{
				$names = array_fill_keys($matches[1], true);
			}
		}

		return $names;
	}
}
