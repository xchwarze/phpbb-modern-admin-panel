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
 * Modern Admin Panel: board-wide options
 *
 * Stored as "admin_dashboard_<name>" config keys (added by migrations\install_200), changed on the ACP settings
 * page (controller\settings) and read by the listener and the controllers.
 */
class options
{
	/** @var string Config key prefix */
	public const PREFIX = 'admin_dashboard_';

	/**
	 * @var array Option name => default. Booleans are yes/no options, strings pick from CHOICES.
	 *
	 * The header and the sidebar each have a colour, a preset of colours.css (the sidebar colour also gives the accent of the
	 * content area: buttons, links, focus rings, the palette). The shape of the highlighted menu entry is "sidebar_corner". The header always
	 * sticks and the sidebar state (pinned or icon rail) is kept per browser (js/studio.js), so neither is an option. Keep
 * migrations\install_200 in step: it seeds every default on a fresh install.
	 *
	 * "detach_qa" is stored as it always was (1 hides the Quick access category from the sidebar); the settings page asks it the other way
	 * round, "Show quick access in the sidebar menu" (controller\settings and the settings template do the inversion).
	 */
	public const DEFAULTS = [
		'header_colour'		=> 'white',
		'sidebar_colour'	=> 'purple-indigo',
		'sidebar_corner'	=> 'full',
		'palette_settings'	=> true,
		'palette_jumps'		=> true,
		'palette_pins'		=> true,
		'display_pending'	=> true,
		'display_stats'		=> true,
		'remodel_stats'		=> true,
		'display_notes'		=> true,
		'display_qa'		=> false,
		'detach_qa'			=> false,
		'display_logs'		=> true,
		'display_users'		=> true,
	];

	/** @var array Text option => the key of its list of allowed values in the extension parameters (services.yml) */
	public const CHOICES = [
		'header_colour'		=> 'colours',
		'sidebar_colour'	=> 'colours',
		'sidebar_corner'	=> 'corners',
	];

	/**
	 * Read the options from the board configuration.
	 *
	 * The templates print the text options unescaped (as class names), so only known values pass:
	 * anything else (a tampered key) is read as the default, and so is a missing key.
	 *
	 * @param \phpbb\config\config	$config		Config object
	 * @param array					$params		Dashboard extension parameters
	 * @return array							Option name => value (bool for the yes/no options)
	 */
	public static function read(\phpbb\config\config $config, array $params): array
	{
		$options = [];

		foreach (self::DEFAULTS as $name => $default)
		{
			$value = isset($config[self::PREFIX . $name]) ? $config[self::PREFIX . $name] : $default;

			if (is_bool($default))
			{
				$options[$name] = (bool) $value;
			}
			else
			{
				$options[$name] = in_array($value, self::allowed($name, $params), true) ? $value : $default;
			}
		}

		return $options;
	}

	/**
	 * The allowed values of a text option.
	 *
	 * @param string	$name		The option name
	 * @param array		$params		Dashboard extension parameters
	 * @return array
	 */
	public static function allowed(string $name, array $params): array
	{
		$list = self::CHOICES[$name] ?? '';

		return !empty($params[$list]) ? array_values((array) $params[$list]) : [];
	}
}
