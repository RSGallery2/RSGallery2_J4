<?php
/**
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 *
 * @copyright  (c) 2005 - 2019 Astrid Günther, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later
 * @license    GNU General Public License version 2 or later
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
	 * @since   __BUMP_VERSION__
	 */
	public function __construct()
	{
		$this->minimumJoomla = '4.0';
		$this->minimumPhp    = JOOMLA_MINIMUM_PHP;
	}
}
