<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\View\Develop;

defined('_JEXEC') or die;

//use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Filesystem\File;

use Joomla\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Joomla\Component\Rsgallery2\Administrator\Model;
use Joomla\Component\Rsgallery2\Administrator\Model\ConfigRawModel;
use Joomla\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel;

//$path = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/install_rsg2.php';
//if (File::exists($path))
//{
//	require_once $path;
//}

/**
 * View class for a list of rsgallery2.
 *
 * @since  1.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	protected $buttons = [];

	protected $isDebugBackend;
	protected $isDevelop;

	protected $rsg2Manifest = [];

	/**
	protected $isDangerActive;
	protected $isRawDbActive;
	protected $isUpgradeActive;
	protected $isTestActive;
	protected $isJ3xRsg2DataExisting;
	protected $developActive;

	protected $intended;
	/**/
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to
	// the global config
	protected $UserIsRoot;

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

		//--- config --------------------------------------------------------------------

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDebugBackend = $rsgConfig->get('isDebugBackend');
		$this->isDevelop = $rsgConfig->get('isDevelop');

		$this->isRawDbActive   = true; // false / true;
		$this->isDangerActive  = true; // false / true;
		$this->isUpgradeActive = true; // false / true;
		if ($this->isDevelop)
		{
			$this->isTestActive    = true; // false / true;
			$this->developActive = true; // false / true;
		}

		// Check for errors.
		/* Must load form before
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}
		/**/

		/**
		$path = JPATH_ADMINISTRATOR . '/components/rsgallery2/install_rsg2.php';
		if (JFile::exists($path))
		{
			require_once $path;
		}
		/**/

//		$TestInstall = new Com_Rsgallery2InstallerScript ();
//		$this->installMessage = $TestInstall->postFlightMessage ('update');

		//--- Check user rights ---------------------------------------------

		// toDo: More detailed for rsgallery admin
		$app       = Factory::getApplication();

		$user = $app->getIdentity();
		//$user     = Factory::getUser();
		$canAdmin = $user->authorise('core.admin');
		$this->UserIsRoot = $canAdmin;

		//--- begin to display ----------------------------------------------

		$Layout = Factory::getApplication()->input->get('layout');

		// collect data dependent on layout
		switch ($Layout) {
		    /**
			case 'DebugGalleryOrder':

				$this->GalleriesOrderModel = JModelLegacy::getInstance('GalleriesOrder', 'rsgallery2Model');
				$this->OrderedGalleries = $this->GalleriesOrderModel->OrderedGalleries();
				$this->LeftJoinGalleries = $this->GalleriesOrderModel->LeftJoinGalleries();
				break;
            /**/

            case 'ManifestInfo':

                $rsg2Manifest = ConfigRawModel::readRsg2ExtensionManifest ();
                $this->rsg2Manifest = $rsg2Manifest;

                break;

            case 'Rsg2GeneralInfo':

                $rsg2Manifest = ConfigRawModel::readRsg2ExtensionManifest ();
                $this->rsg2Manifest = $rsg2Manifest;

                $rsg2configuration = ConfigRawModel::readRsg2ExtensionConfiguration ();
                $this->rsg2Configuration = $rsg2configuration;

                $this->rsg2Configuration_j3x = [];
                if (ConfigRawModel::J3xConfigTableExist ()) {

                    $rsg2configuration_j3x = MaintenanceJ3xModel::OldConfigItems ();
                    $this->rsg2Configuration_j3x = $rsg2configuration_j3x;
                }

                break;
		}


		//		Factory::getApplication()->input->set('hidemainmenu', true);

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=maintenance');
		Rsgallery2Helper::addSubmenu('maintenance');
		$this->sidebar = \JHtmlSidebar::render();

		$this->addToolbar($Layout);

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function addToolbar($Layout)
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		switch ($Layout)
		{
			case 'ManifestInfo':

                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
                        . '</span><br><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
                    . ': ' . Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView');
				break;

            case 'Rsg2GeneralInfo':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . '* Button for copy to clipboard -> add typescript copy<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
                        . '</span><br><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
                    . ': ' . Text::_('COM_RSGALLERY2_GENERAL_INFO_VIEW'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView');
				break;

			default:
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . '*  <br>'
                        . '* ? COM_RSGALLERY2_DEBUG_GALLERY_ORDER <br>'
                        . '* ? COM_RSGALLERY2_UPDATE_COMMENTS_AND_VOTING <br>'
                        . '* ?  COM_RSGALLERY2_REMOVE_INSTALLATION_LEFT_OVERS <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
                        . '</span><br><br>';
                }

                // Set the title
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DEVELOP_VIEW'), 'screwdriver'); // 'maintenance');
				//ToolBarHelper::cancel('config.cancel_rawEdit');
				//ToolBarHelper::cancel('maintenance.cancel');
				ToolBarHelper::cancel('develop.cancel');
				break;
		}


		$toolbar->preferences('com_rsgallery2');
	}

	/**
	public function getModel($name = '', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	/**/

}

