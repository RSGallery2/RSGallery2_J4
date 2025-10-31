<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  __BUMP_VERSION__
 */
class Pkg_FoosInstallerScript
{
	/**
	 * Extension script constructor.
	 *
	 * @since 5.1.0	 */
	public function __construct()
	{
		$this->minimumJoomla = '4.0';
		$this->minimumPhp    = JOOMLA_MINIMUM_PHP;
	}
}
