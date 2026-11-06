<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dsr\modernadminpanel\controller;

use dsr\modernadminpanel\helper\options;
use dsr\modernadminpanel\helper\quick_access;

/**
 * Modern Admin Panel: the ACP settings page (Extensions » Modern Admin Panel » Settings)
 */
class settings
{
	/** @var string The form field of "Show quick access in the sidebar menu": the option is stored as detach_qa (1 hides it), so on = 0 */
	protected const SHOW_QA_MENU = 'show_qa_menu';

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\log\log_interface */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string Table prefix */
	protected $table_prefix;

	/** @var string Admin path (the ACP directory under the phpBB root) */
	protected $admin_path;

	/** @var string php File extension */
	protected $php_ext;

	/** @var array Dashboard extension parameters */
	protected $params;

	/** @var string Custom form action */
	protected $u_action = '';

	/**
	 * Constructor.
	 *
	 * @param \phpbb\auth\auth						$auth			Auth object
	 * @param \phpbb\config\config					$config			Config object
	 * @param \phpbb\db\driver\driver_interface		$db				Database object
	 * @param \phpbb\language\language				$language		Language object
	 * @param \phpbb\log\log_interface				$log			Log object
	 * @param \phpbb\request\request				$request		Request object
	 * @param \phpbb\template\template				$template		Template object
	 * @param \phpbb\user							$user			User object
	 * @param string								$table_prefix	Table prefix
	 * @param string								$admin_path		Admin relative path
	 * @param string								$root_path		phpBB root path
	 * @param string								$php_ext		php File extension
	 * @param array									$params			Dashboard extension parameters
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\language\language $language,
		\phpbb\log\log_interface $log,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		string $table_prefix,
		string $admin_path,
		string $root_path,
		string $php_ext,
		array $params
	)
	{
		$this->auth			= $auth;
		$this->config		= $config;
		$this->db			= $db;
		$this->language		= $language;
		$this->log			= $log;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;

		$this->table_prefix	= $table_prefix;
		$this->admin_path	= $root_path . $admin_path;
		$this->php_ext		= $php_ext;
		$this->params		= $params;
	}

	/**
	 * Set the form action, the module's own URL.
	 *
	 * @param string	$u_action	The module URL
	 * @return void
	 */
	public function set_page_url(string $u_action): void
	{
		$this->u_action = $u_action;
	}

	/**
	 * Show and save the settings. Saving needs a valid form key and only known values are accepted;
	 * every save is written to the admin log. The module (acp\settings_module) already requires a_board.
	 *
	 * @return void
	 */
	public function handle(): void
	{
		$form_key = 'dsr_admindashboard_settings';
		add_form_key($form_key);

		$errors = [];
		$values = options::read($this->config, $this->params);

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key($form_key))
			{
				$errors[] = $this->language->lang('FORM_INVALID');
			}

			foreach (options::DEFAULTS as $name => $default)
			{
				if ($name === 'detach_qa')
				{
					$values[$name] = !$this->request->variable(self::SHOW_QA_MENU, (int) !$values[$name]);
				}
				else if (is_bool($default))
				{
					$values[$name] = (bool) $this->request->variable($name, (int) $values[$name]);
				}
				else
				{
					$value = $this->request->variable($name, $values[$name]);

					// Only a tampered form sends something else
					if (!in_array($value, options::allowed($name, $this->params), true))
					{
						$errors[] = $this->language->lang('STUDIO_DASHBOARD_SETTINGS_INVALID');
						continue;
					}

					$values[$name] = $value;
				}
			}

			if (empty($errors))
			{
				foreach ($values as $name => $value)
				{
					$key = options::PREFIX . $name;
					$value = is_bool($value) ? (int) $value : $value;

					if (!isset($this->config[$key]) || (string) $this->config[$key] !== (string) $value)
					{
						$this->config->set($key, $value);
					}
				}

				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ADMIN_DASHBOARD_SETTINGS');

				trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
			}
		}

		$choices = [];

		foreach (array_keys(options::CHOICES) as $name)
		{
			foreach (options::allowed($name, $this->params) as $value)
			{
				// Translated where a name exists ("blue-grey" => STUDIO_DASHBOARD_CHOICE_BLUE_GREY)
				$lang_key = 'STUDIO_DASHBOARD_CHOICE_' . strtoupper(str_replace('-', '_', $value));

				$choices[$name][] = [
					'VALUE'		=> $value,
					'TITLE'		=> $this->language->is_set($lang_key) ? $this->language->lang($lang_key) : $value,
				];
			}
		}

		$this->template->assign_vars([
			'S_ERROR'				=> !empty($errors),
			'ERROR_MSG'				=> implode('<br>', array_unique($errors)),

			'STUDIO_OPTIONS'		=> $values,
			'STUDIO_CHOICES'		=> $choices,

			// The link next to the quick access settings: only for an administrator who may manage modules
			'U_STUDIO_EDIT_QA'		=> quick_access::edit_url($this->auth, $this->db, $this->table_prefix, $this->admin_path, $this->php_ext),

			'U_ACTION'				=> $this->u_action,
		]);
	}
}
