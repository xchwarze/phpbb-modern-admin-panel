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

namespace dsr\modernadminpanel\controller;

use dsr\modernadminpanel\helper\options;
use dsr\modernadminpanel\helper\palette;

/**
 * Modern Admin Panel: the ACP search page (the header search box without JavaScript) and the command palette endpoint
 */
class admin
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\context */
	protected $context;

	/** @var \dsr\modernadminpanel\helper\jumps */
	protected $jumps;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \dsr\modernadminpanel\helper\search_capture */
	protected $search_capture;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var string Admin path (the ACP directory under the phpBB root) */
	protected $admin_path;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string php File extension */
	protected $php_ext;

	/** @var array Dashboard extension parameters */
	protected $params;

	/** Most settings the command palette gets in one response */
	const PALETTE_LIMIT = 20;

	/** Shortest and longest palette query, in characters */
	const PALETTE_MIN = 3;
	const PALETTE_MAX = 100;

	/** Most distinct words accepted by either search entry point */
	const SEARCH_WORDS_MAX = 25;

	/** Shortest user name after the "@" of a palette query, in characters (once cleaned the way core cleans user names) */
	const PALETTE_MIN_USER = 2;

	/** Longest explanation in a palette result, in characters (titles use palette::TITLE_MAX) */
	const PALETTE_EXPLAIN = 200;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\auth\auth						$auth				Auth object
	 * @param \phpbb\config\config					$config				Config object
	 * @param \phpbb\template\context				$context			Template context object
	 * @param \dsr\modernadminpanel\helper\jumps				$jumps				Quick jumps (users, forums, groups)
	 * @param \phpbb\language\language				$language			Language object
	 * @param \phpbb\path_helper					$path_helper		Path helper object
	 * @param \phpbb\request\request				$request			Request object
	 * @param \dsr\modernadminpanel\helper\search_capture	$search_capture		Settings capture shared with the listener
	 * @param \phpbb\template\template				$template			Template object
	 * @param array									$params				Dashboard extension parameters
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\template\context $context,
		\dsr\modernadminpanel\helper\jumps $jumps,
		\phpbb\language\language $language,
		\phpbb\path_helper $path_helper,
		\phpbb\request\request $request,
		\dsr\modernadminpanel\helper\search_capture $search_capture,
		\phpbb\template\template $template,
		array $params
	)
	{
		$this->auth				= $auth;
		$this->config			= $config;
		$this->context			= $context;
		$this->jumps			= $jumps;
		$this->language			= $language;
		$this->request			= $request;
		$this->search_capture	= $search_capture;
		$this->template			= $template;
		$this->admin_path		= $path_helper->get_phpbb_root_path() . $path_helper->get_adm_relative_path();
		$this->root_path		= $path_helper->get_phpbb_root_path();
		$this->php_ext			= $path_helper->get_php_ext();
		$this->params			= $params;
	}

	/**
	 * Handle the ACP search page (the search box in the header, posted) and the command palette request
	 * (a JSON GET, see palette()). Both only read.
	 *
	 * @return void
	 */
	public function handle(): void
	{
		if ($this->request->is_set('studio_palette'))
		{
			// Answers and ends the request: no template, no page
			$this->send_palette();

			return;
		}

		if ($this->request->is_set_post('search'))
		{
			$this->search_page();
		}
	}

	/**
	 * Find the settings and the modules that match the posted text and give them to the search page template.
	 *
	 * @return void
	 */
	protected function search_page(): void
	{
		$results = [];
		$modules = [];
		$raw_text = $this->request->variable('search', '', true);

		// htmlspecialchars_decode can scan long input; accepted text needs at most six bytes per character.
		if (strlen($raw_text) > (self::PALETTE_MAX * 6))
		{
			$this->template->assign_var('STUDIO_MODULES', []);
			return;
		}

		$text = htmlspecialchars_decode($raw_text, ENT_COMPAT);

		// Reject before parsing or capturing ACP settings; request escaping can expand characters.
		if (utf8_strlen($text) > self::PALETTE_MAX)
		{
			$this->template->assign_var('STUDIO_MODULES', []);
			return;
		}

		// Legacy haystacks retain phpBB's HTML-escaped language representation.
		$keywords = $this->parse_keywords($raw_text);
		if (!$keywords || count($keywords) > self::SEARCH_WORDS_MAX)
		{
			$this->template->assign_var('STUDIO_MODULES', []);
			return;
		}

		$data = $this->get_search_data();

		foreach ($data as $mode => $keys)
		{
			foreach ($keys['vars'] as $key => $strings)
			{
				foreach ($strings as $string)
				{
					$string = utf8_strtolower($this->language->lang($string));

					foreach ($keywords as $word)
					{
						if (strpos($string, $word) !== false)
						{
							$results[$mode][] = $key;

							// One match is enough for this setting
							break 2;
						}
					}
				}
			}
		}

		foreach ($results as $mode => $keys)
		{
			$this->template->assign_block_vars('results', [
				'TITLE'		=> $this->language->lang($data[$mode]['title']),
				'U_VIEW'	=> append_sid("{$this->admin_path}index.{$this->php_ext}", ['i' => 'acp_board', 'mode' => $mode]),
			]);

			foreach ($keys as $key)
			{
				$explain = isset($data[$mode]['vars'][$key][1]) ? $this->language->lang($data[$mode]['vars'][$key][1]) : '';

				$this->template->assign_block_vars('results.settings', [
					'TITLE'		=> $this->language->lang($data[$mode]['vars'][$key][0]),
					'EXPLAIN'	=> $explain,
					'U_VIEW'	=> append_sid("{$this->admin_path}index.{$this->php_ext}", ['i' => 'acp_board', 'mode' => $mode, 'studio_search' => $key, '#' => $key]),
				]);
			}
		}

		// The module tree core has built for the menu holds only the modules this administrator may open
		$context_data = $this->context->get_data_ref();
		$categories = $context_data['l_block1'];

		foreach ($categories as $cat)
		{
			$subcategories = false;

			if (!empty($cat['l_block2']))
			{
				foreach ($cat['l_block2'] as $sub)
				{
					$items = false;

					if (!empty($sub['l_block3']))
					{
						foreach ($sub['l_block3'] as $item)
						{
							foreach ($keywords as $word)
							{
								if ($this->in_title($word, $item['L_TITLE']))
								{
									$modules[$cat['ID']]['subcategories'][$sub['ID']]['items'][$item['ID']] = [
										'TITLE'		=> $item['L_TITLE'],
										'U_VIEW'	=> $item['U_TITLE'],
									];

									$subcategories = true;
									$items = true;

									break;
								}
							}
						}
					}

					if ($items)
					{
						// An item was found: its group is listed whether or not its own title matches
						$modules[$cat['ID']]['subcategories'][$sub['ID']]['TITLE'] = $sub['L_TITLE'];
						$modules[$cat['ID']]['subcategories'][$sub['ID']]['U_VIEW'] = $sub['U_TITLE'];
					}
					else
					{
						foreach ($keywords as $word)
						{
							if ($this->in_title($word, $sub['L_TITLE']))
							{
								$modules[$cat['ID']]['subcategories'][$sub['ID']] = [
									'TITLE'		=> $sub['L_TITLE'],
									'U_VIEW'	=> $sub['U_TITLE'],
								];

								$subcategories = true;

								break;
							}
						}
					}
				}
			}

			if ($subcategories)
			{
				// A group or an item was found: its tab is listed whether or not its own title matches
				$modules[$cat['ID']]['TITLE'] = $cat['L_TITLE'];
				$modules[$cat['ID']]['U_VIEW'] = $cat['U_TITLE'];
			}
			else
			{
				foreach ($keywords as $word)
				{
					if ($this->in_title($word, $cat['L_TITLE']))
					{
						$modules[$cat['ID']] = [
							'TITLE'		=> $cat['L_TITLE'],
							'U_VIEW'	=> $cat['U_TITLE'],
						];

						break;
					}
				}
			}
		}

		$this->template->assign_var('STUDIO_MODULES', $modules);
	}

	/**
	 * Split a search text into the keywords to look for: lower case, no duplicates,
	 * and no words of one or two characters (characters, not bytes: "más" and "año" count as three).
	 *
	 * @param string	$text		The search text
	 * @return array
	 */
	protected function parse_keywords(string $text): array
	{
		$keywords = preg_split('/\s+/', $text);
		$keywords = array_map('utf8_strtolower', $keywords);
		$keywords = array_unique($keywords);

		$keywords = array_filter($keywords, function($keyword)
		{
			return utf8_strlen($keyword) > 2;
		});

		return array_slice($keywords, 0, self::SEARCH_WORDS_MAX + 1);
	}

	/**
	 * Handle the command palette request (GET only, JSON). Ends the request.
	 *
	 * The response is built by palette_response(), so that it can be tested
	 * without sending anything: this method only sends it.
	 *
	 * @return void
	 */
	protected function send_palette(): void
	{
		// Whatever the search code (or an extension's listener) prints must not end up in the JSON
		ob_start();

		try
		{
			list($status, $data, $headers) = $this->palette_response();
		}
		finally
		{
			ob_end_clean();
		}

		$body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);

		if ($body === false)
		{
			$status = 500;
			$headers = [];
			$body = '{"error":"server_error"}';
		}

		$this->send_status($status);
		$this->send_header('Content-Type: application/json; charset=UTF-8');
		$this->send_header('Cache-Control: no-store');
		$this->send_header('X-Content-Type-Options: nosniff');

		foreach ($headers as $header)
		{
			$this->send_header($header);
		}

		$this->template->assign_var('STUDIO_PALETTE_JSON', $body);
		$this->template->set_filenames(['body' => '@dsr_modernadminpanel/palette_json.html']);
		$this->template->display('body');

		$this->finish();
	}

	/**
	 * The palette's answer: status code, data to encode and extra headers.
	 *
	 * 405 for anything but GET. 403 when the link hash is wrong or no search is on for this administrator
	 * (see palette::features()). Else 200 with the (possibly empty) matches. Nothing is written anywhere,
	 * no config value is read out, and only plain-text names, titles and links leave.
	 *
	 * @return array	[int status, array data, string[] extra headers]
	 */
	protected function palette_response(): array
	{
		if ($this->request->server('REQUEST_METHOD', '') !== 'GET')
		{
			return [405, ['error' => 'method_not_allowed'], ['Allow: GET']];
		}

		$features = palette::features($this->auth, options::read($this->config, $this->params));

		if (!check_link_hash($this->request->variable('hash', ''), 'studio_palette') || !in_array(true, $features, true))
		{
			return [403, ['error' => 'forbidden'], []];
		}

		// The request class HTML-escapes strings: back to the text that was typed
		$text = htmlspecialchars_decode($this->request->variable('q', '', true), ENT_COMPAT);
		$text = (string) preg_replace('/^\s+|\s+$/u', '', $text);

		return [200, $this->palette_answer($text, $features), []];
	}

	/**
	 * What the palette finds for the typed text.
	 *
	 * "@name" looks up users (and nothing else). Any other text of 3 characters or more looks up the settings
	 * of the pages the administrator may open, forums and groups. A kind that is not on for this administrator
	 * is not looked up, and its key is not in the answer.
	 *
	 * @param string	$text		The typed text, trimmed
	 * @param array		$features	palette::features()
	 * @return array				results and truncated (the settings), and users, forums and groups when searched
	 */
	protected function palette_answer(string $text, array $features): array
	{
		$answer = ['results' => [], 'truncated' => false];
		$length = utf8_strlen($text);

		if ($length > self::PALETTE_MAX)
		{
			return $answer;
		}

		if (strpos($text, '@') === 0)
		{
			$name = (string) preg_replace('/^\s+/u', '', substr($text, 1));

			if ($features['users'] && utf8_strlen(utf8_clean_string($name)) >= self::PALETTE_MIN_USER)
			{
				$answer['users'] = $this->jumps->users($name);
			}

			return $answer;
		}

		$keywords = $length < self::PALETTE_MIN ? [] : $this->parse_keywords($text);

		if (!$keywords)
		{
			return $answer;
		}

		if ($features['settings'])
		{
			$answer = $this->palette_search($keywords);
		}

		foreach (['forums', 'groups'] as $kind)
		{
			if ($features[$kind])
			{
				$answer[$kind] = $this->jumps->$kind($keywords);
			}
		}

		return $answer;
	}

	/**
	 * Find the settings (of the pages the administrator may open) that match the keywords.
	 *
	 * Only the titles and explanations are looked at, as plain text, and only they are
	 * returned, never a value. A title that matches comes before one that only the
	 * explanation matches; more matching keywords come first, then the page order.
	 *
	 * @param array		$keywords	The keywords (see parse_keywords())
	 * @return array				['results' => [title, explain, group, url][], 'truncated' => bool]
	 */
	protected function palette_search(array $keywords): array
	{
		$data = $this->get_search_data();
		$found = [];

		foreach ($data as $mode => $page)
		{
			foreach ($page['vars'] as $key => $strings)
			{
				// No setting of its own on the page: no row to highlight, and a duplicate of another one
				if (isset($page['hidden'][$key]))
				{
					continue;
				}

				$title = palette::plain_text($this->language->lang($strings[0]));
				$explain = isset($strings[1]) ? palette::plain_text($this->language->lang($strings[1])) : '';
				$in_title = $this->count_matches($title, $keywords);
				$in_explain = $this->count_matches($explain, $keywords);

				if ($title !== '' && ($in_title || $in_explain))
				{
					$found[] = [$in_title, $in_explain, count($found), $mode, $key, $title, $explain];
				}
			}
		}

		// Not stable before PHP 8.0, hence the position as the last criterion
		usort($found, function($a, $b)
		{
			return ($b[0] <=> $a[0]) ?: (($b[1] <=> $a[1]) ?: ($a[2] <=> $b[2]));
		});

		$truncated = count($found) > self::PALETTE_LIMIT;
		$results = [];

		foreach (array_slice($found, 0, self::PALETTE_LIMIT) as $setting)
		{
			list(, , , $mode, $key, $title, $explain) = $setting;

			$results[] = [
				'title'		=> palette::limit_text($title, palette::TITLE_MAX),
				'explain'	=> palette::limit_text($explain, self::PALETTE_EXPLAIN),
				'group'		=> palette::limit_text(palette::plain_text($this->language->lang($data[$mode]['title'])), palette::TITLE_MAX),
				'url'		=> append_sid("{$this->admin_path}index.{$this->php_ext}", ['i' => 'acp_board', 'mode' => $mode, 'studio_search' => rawurlencode($key), '#' => rawurlencode($key)], false),
			];
		}

		return ['results' => $results, 'truncated' => $truncated];
	}

	/**
	 * How many of the keywords are within a text (case-insensitive).
	 *
	 * @param string	$text		The text
	 * @param array		$keywords	The lower case keywords
	 * @return int
	 */
	protected function count_matches(string $text, array $keywords): int
	{
		$count = 0;
		$text = utf8_strtolower($text);

		foreach ($keywords as $word)
		{
			$count += strpos($text, $word) !== false ? 1 : 0;
		}

		return $count;
	}

	/**
	 * Send the HTTP status code.
	 *
	 * @param int		$status		The status code
	 * @return void
	 */
	protected function send_status(int $status): void
	{
		http_response_code($status);
	}

	/**
	 * Send an HTTP header (replacing one of the same name).
	 *
	 * @param string	$header		The header line
	 * @return void
	 */
	protected function send_header(string $header): void
	{
		header($header, true);
	}

	/**
	 * End the request after the response was sent (the only exit of the palette).
	 *
	 * @return void
	 */
	protected function finish(): void
	{
		if (function_exists('garbage_collection'))
		{
			garbage_collection();
		}

		exit_handler();

		// exit_handler() only returns when a phpBB hook took the exit over
		exit;
	}

	/**
	 * Helper function to see if a keyword is within a title string.
	 *
	 * @param string	$keyword	The keyword to search for
	 * @param string	$title		The title string to search within
	 * @return bool
	 */
	protected function in_title(string $keyword, string $title): bool
	{
		return strpos(utf8_strtolower($title), $keyword) !== false;
	}

	/**
	 * The acp_board modes the administrator may search: the board settings
	 * with a_board and the server settings with a_server, as acp_board itself requires.
	 *
	 * @return array
	 */
	protected function get_search_modes(): array
	{
		$modes = [];

		if ($this->auth->acl_get('a_board'))
		{
			$modes = array_merge($modes, ['settings', 'features', 'avatar', 'message', 'post', 'signature', 'feed', 'registration']);
		}

		if ($this->auth->acl_get('a_server'))
		{
			$modes = array_merge($modes, ['auth', 'email', 'cookie', 'load', 'server', 'security']);
		}

		return $modes;
	}

	/**
	 * Get the available settings for the various modes in acp_board
	 *
	 * Per mode: 'title' (the language key of the page title) and 'vars' (setting => [language key of its title,
	 * and of its explanation when it has one]). The settings acp_board shows no row for (type false) are also
	 * listed in 'hidden' (setting => true): the palette skips them, the ACP search page lists all of 'vars'.
	 *
	 * @return array
	 */
	protected function get_search_data(): array
	{
		if (!class_exists('acp_board'))
		{
			include($this->root_path . 'includes/acp/acp_board.' . $this->php_ext);
		}

		$data = [];
		$lang = [];
		$modes = $this->get_search_modes();
		$board = new \acp_board();

		// While capturing, the listener stores each mode's settings and aborts acp_board
		$this->search_capture->set_capturing(true);

		try
		{
			foreach ($modes as $mode)
			{
				try
				{
					$board->main('', $mode);
				}
				catch (\dsr\modernadminpanel\exception\admin_dashboard_exception $e)
				{
					$data[$mode] = $this->search_capture->get_captured();
				}
			}
		}
		finally
		{
			// Whatever acp_board did, never leave the listener intercepting real acp_board pages
			$this->search_capture->set_capturing(false);
			$this->search_capture->set_captured([]);
		}

		foreach ($data as $mode => $vars)
		{
			if (isset($vars['lang']))
			{
				$this->language->add_lang($vars['lang']);
			}

			foreach ($vars['vars'] as $key => $value)
			{
				if (is_array($value) && isset($value['lang']))
				{
					$lang[$mode]['title'] = $vars['title'];
					$lang[$mode]['vars'][$key][] = $value['lang'];

					if (!empty($value['explain']) && $this->language->is_set($value['lang'] . '_EXPLAIN'))
					{
						$lang[$mode]['vars'][$key][] = $value['lang'] . '_EXPLAIN';
					}

					if (array_key_exists('type', $value) && $value['type'] === false)
					{
						$lang[$mode]['hidden'][$key] = true;
					}
				}
			}
		}

		$this->language->add_lang('acp/board');

		return $lang;
	}
}
