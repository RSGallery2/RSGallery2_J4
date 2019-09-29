<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\View\Rsgallery2;

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * View class for a list of rsgallery2.
 *
 * @since  1.0
 */
class HtmlView extends BaseHtmlView
{
	protected $buttons = [];

	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	protected $lastGalleries;
	protected $lastImages;

	protected $changeLog;

	protected $credits;

	protected $externalLicenses;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise an \Exception object.
	 *
	 * @since   1.0
	 */
	public function display($tpl = null)
	{

		$this->buttons = $this->getButtons();

		$this->lastGalleries = ["first gallery"];
		$this->lastImages = ["first image"];

		$this->changeLog = "Change log may be json object";

		$this->credits = ["Credits string"];

		$this->externalLicenses = ["external licenses"];

		$this->sidebar = \JHtmlSidebar::render();
		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2');

		// Set the title
		ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAIN_CONTROL_PANEL'), 'home-2');
		// Set the title
		//ToolbarHelper::title(Text::_('COM_MEDIA'), 'images mediamanager');

		return parent::display($tpl);
	}


	private function getButtons()
	{
		$buttons = array(
			array(

				//'link'   => Route::_('index.php?option=com_content&task=article.add'),
				'link'   => Route::_('index.php?option=com_rsgallery2&view=config'),
				'image'  => 'fa fa-cog',
				'text'   => Text::_('COM_RSGALLERY2_MAIN_CONFIGURATION'),
				'access' => array('core.manage', 'com_content', 'core.create', 'com_content'),
				'group'  => '',
			),
			array(
				'link'   => Route::_('index.php?option=com_rsgallery2&view=galleries'),
				'image'  => 'fa fa-th', // fa fa-th
				'text'   => Text::_('COM_RSGALLERY2_MAIN_MANAGE_GALLERIES'),
				'access' => array('core.manage', 'com_media'),
				'group'  => '',
			),
			array(
				'link'   => Route::_('index.php?option=com_rsgallery2&view=upload'),
				'image'  => 'fa fa-upload',
				'text'   => Text::_('COM_RSGALLERY2_MAIN_UPLOAD'),
				'access' => array('core.manage', 'com_config', 'core.admin', 'com_config'),
				'group'  => '',
			),
			array(
				'link'   => Route::_('index.php?option=com_rsgallery2&view=images'),
				'image'  => 'fa fa-image',
				'text'   => Text::_('COM_RSGALLERY2_MAIN_MANAGE_IMAGES'),
				'access' => array('core.manage', 'com_config', 'core.admin', 'com_config'),
				'group'  => '',
			),
			array(
				'link'   => Route::_('index.php?option=com_rsgallery2&view=maintenance'),
				'image'  => 'fa fa-cogs', // gears
				'text'   => Text::_('COM_RSGALLERY2_MAIN_MAINTENANCE'),
				'access' => array('core.manage', 'com_modules'),
				'group'  => ''
			)
		);

		return $buttons;
	}


}
