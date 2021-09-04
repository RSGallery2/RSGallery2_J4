<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsg2_imagess
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

/**
 * Script file of Rsg2_images module
 */
class plg_rsg2_imagesInstallerScript
{

	/**
	 * Extension script constructor.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		$this->minimumJoomla = '4.0';
		$this->minimumPhp = JOOMLA_MINIMUM_PHP;
	}

	/**
	 * Method to install the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 */
	function install($parent)
	{
		echo Text::_('PLG_RSG2_IMAGES_INSTALLERSCRIPT_UNINSTALL');

		return true;
	}

	/**
	 * Method to uninstall the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 */
	function uninstall($parent)
	{
		echo Text::_('PLG_RSG2_IMAGES_INSTALLERSCRIPT_UNINSTALL');

		return true;
	}

	/**
	 * Method to update the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 */
	function update($parent)
	{
		echo Text::_('PLG_RSG2_IMAGES_INSTALLERSCRIPT_UPDATE');

		return true;
	}

	/**
	 * Function called before extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 */
	function preflight($type, $parent)
	{
		// Check for the minimum PHP version before continuing
		if (!empty($this->minimumPhp) && version_compare(PHP_VERSION, $this->minimumPhp, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');

			return false;
		}

		// Check for the minimum Joomla version before continuing
		if (!empty($this->minimumJoomla) && version_compare(JVERSION, $this->minimumJoomla, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');

			return false;
		}

		echo Text::_('PLG_RSG2_IMAGES_INSTALLERSCRIPT_PREFLIGHT');

		return true;
	}

	/**
	 * Function called after extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 */
	function postflight($type, $parent)
	{
		echo Text::_('PLG_RSG2_IMAGES_INSTALLERSCRIPT_POSTFLIGHT');

		return true;
	}
}
