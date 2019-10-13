<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2s
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;

/**
 * Rsgallery2 component helper.
 *
 * @since  1.0
 */
class Rsgallery2Helper extends ContentHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function addSubmenu($vName)
	{
		/**
		if (ComponentHelper::isEnabled('com_fields') && ComponentHelper::getParams('com_rsgallery2')->get('custom_fields_enable', '1'))
		{
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELDS'),
				'index.php?option=com_fields&context=com_rsgallery2s.rsgallery2',
				$vName == 'fields.fields'
			);
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELD_GROUPS'),
				'index.php?option=com_fields&view=groups&context=com_rsgallery2s.rsgallery2',
				$vName == 'fields.groups'
			);
		}
		/**/

		echo "\$vname: $vName <br><br>";

		$link = 'index.php?option=com_rsgallery2&view=rsgallery2';
		\JHtmlSidebar::addEntry(
			'<span class="icon-images" >  </span>' .
			Text::_('COM_RSGALLERY2_MENU_CONTROL_PANEL'),
			$link,
			$vName != 'control');

		$link = 'index.php?option=com_rsgallery2&view=galleries';
		\JHtmlSidebar::addEntry(
			'<span class="icon-images" >  </span>' .
			Text::_('COM_RSGALLERY2_MENU_GALLERIES'),
			$link,
			$vName != 'galleries');

		$link = 'index.php?option=com_rsgallery2&view=upload';
		\JHtmlSidebar::addEntry(
			'<span class="icon-upload" >  </span>' .
			Text::_('COM_RSGALLERY2_MENU_UPLOAD'),
			$link,
			$vName != 'upload');

		//--- Add images view link ------------------------------------

		$link = 'index.php?option=com_rsgallery2&view=images';
		\JHtmlSidebar::addEntry(
			'<span class="icon-image" >  </span>' .
			Text::_('COM_RSGALLERY2_MENU_IMAGES'),
			// 'index.php?option=com_rsgallery2&rsgOption=images',
			$link,
			$vName != 'images');
			//false);

		//--- Add maintenance view link ------------------------------------

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		// In config add maintenance
		\JHtmlSidebar::addEntry(
			'<span class="icon-screwdriver" >  </span>' .
			Text::_('COM_RSGALLERY2_MENU_MAINTENANCE'),
			$link,
			$vName != 'maintenance');

		//--- config ------------------------------------
		/**
		$link = 'index.php?option=com_rsgallery2&view=config&task=config.edit';
		// In maintenance add config
		\JHtmlSidebar::addEntry(
			'<span class="icon-equalizer" >  </span>' .
			Text::_('COM_RSGALLERY2_MENU_CONFIG'),
			$link,
			false);
		/**/

	}
}
