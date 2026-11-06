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

namespace dsr\modernadminpanel\helper;

/**
 * Modern Admin Panel: state shared by controller\admin and event\listener while the ACP settings search runs
 *
 * The search was written by kasimi (see the credits in docs/README.md). The controller switches capturing on and
 * runs acp_board::main() once per mode. While capturing is on, the listener catches core.acp_board_config_edit_add,
 * stores the mode's display_vars here and throws admin_dashboard_exception, so acp_board stops before it saves or
 * renders anything. The controller switches capturing off and clears the state in a finally block.
 */
class search_capture
{
	/** @var array The display_vars of the mode captured last */
	protected $captured = [];

	/** @var bool Whether the listener must capture acp_board's settings */
	protected $capturing = false;

	/**
	 * The captured display_vars.
	 *
	 * @return array
	 */
	public function get_captured(): array
	{
		return $this->captured;
	}

	/**
	 * Keep the display_vars of a mode.
	 *
	 * @param array		$display_vars	The settings of the acp_board page
	 * @return void
	 */
	public function set_captured(array $display_vars): void
	{
		$this->captured = $display_vars;
	}

	/**
	 * Whether the listener must capture acp_board's settings now.
	 *
	 * @return bool
	 */
	public function is_capturing(): bool
	{
		return $this->capturing;
	}

	/**
	 * Switch capturing on or off.
	 *
	 * @param bool		$capturing		Whether to capture
	 * @return void
	 */
	public function set_capturing(bool $capturing): void
	{
		$this->capturing = $capturing;
	}
}
