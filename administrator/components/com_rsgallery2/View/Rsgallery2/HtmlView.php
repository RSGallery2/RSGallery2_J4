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
//use Joomla\CMS\Helper\ContentHelper;
//use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Component\ComponentHelper;

use Joomla\Component\Rsgallery2\Administrator\Helper\CreditsEnumeration;
use Joomla\Component\Rsgallery2\Administrator\Helper\CreditsExternal;
use Joomla\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Joomla\Component\Rsgallery2\Administrator\Helper\Rsgallery2Version;

use Joomla\Component\Rsgallery2\Administrator\Model\ChangeLogModel;
use Joomla\Component\Rsgallery2\Administrator\Model\GalleriesModel;
use Joomla\Component\Rsgallery2\Administrator\Model\ImagesModel;


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

	protected $Rsg2Version;

	protected $lastGalleries;
	protected $lastImages;

	protected $changelogs;

	protected $credits;

	protected $externalLicenses;

	protected $isDebugBackend;
	protected $isDevelop;

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
		$this->buttons = $this->getRsg2ControlButtons();

		//--- config --------------------------------------------------------------------

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDebugBackend = $rsgConfig->get('isDebugBackend');
		$this->isDevelop = $rsgConfig->get('isDevelop');

		//---  --------------------------------------------------------------------

		$this->lastGalleries = GalleriesModel::latestGalleries(5);
		$this->lastImages =  ImagesModel::latestImages(5);

		//---  --------------------------------------------------------------------

		// Check for errors.
		/* Must load form before
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}
		/**/

		$oRsg2Version = new rsgallery2Version();
		$this->Rsg2Version = $oRsg2Version->getShortVersion(); // getLongVersion, getVersion

        $ChangeLogModel = new ChangeLogModel ();
        // ToDo: add previous version
		$jsonChangelogs = $ChangeLogModel->changeLogElements();
		// Array: Html table each log item
		$this->changelogs = $ChangeLogModel->changeLogsData2Html ($jsonChangelogs);

		$this->credits = CreditsEnumeration::CreditsEnumerationText;

		$this->externalLicenses = CreditsExternal::CreditsExternalText;

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2');
		Rsgallery2Helper::addSubmenu('control');
		$this->sidebar = \JHtmlSidebar::render();

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function addToolbar()
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// on develop show open tasks if existing
		if (!empty ($this->isDevelop))
		{
			echo '<span style="color:red">'
                . '*  Install: see maintenance<br>'
				. '*  --- Config -------<br>'
				. '*  Use _CFG_ in ?variable? names<br>'
				. '*  Last... ? trashed or not published ? <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br><br>';
		}

		// Set the title
		ToolBarHelper::title(Text::_('COM_RSGALLERY2_SUBMENU_CONTROL_PANEL'), 'home-2');

		$toolbar->preferences('com_rsgallery2');
	}


	private function getRsg2ControlButtons()
	{
		$buttons = array(
			array(

				//'link'   => Route::_('index.php?option=com_rsgallery2&view=config'),
				'link'   => Route::_('index.php?option=com_config&view=component&component=com_rsgallery2'),
				'image'  => 'fa fa-cog',
				'text'   => Text::_('COM_RSGALLERY2_MAIN_CONFIGURATION'),
				'access' => array('core.manage', 'com_rsgallery2', 'core.create', 'com_rsgallery2'),
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
