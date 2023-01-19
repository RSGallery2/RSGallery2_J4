<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Develop;

\defined('_JEXEC') or die;

//use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Filesystem\File;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\InstallMessage;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Version;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel;

/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;
    protected $form;

	protected $buttons = [];

	protected $isDebugBackend;
	protected $isDevelop;

	protected $rsg2Manifest = [];
    protected $readRsg2ExtensionData = [];

	protected $actualParams = [];
    protected $defaultParams = [];
    protected $mergedParams = [];

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
	 * @since __BUMP_VERSION__
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

		//--- Check user rights ---------------------------------------------

		// toDo: More detailed for rsgallery admin
		$app       = Factory::getApplication();

		$user = $app->getIdentity();
		//$user     = Factory::getApplication()->getIdentity();
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

                $rsg2Manifest = Rsg2ExtensionModel::readRsg2ExtensionManifest ();
                $this->rsg2Manifest = $rsg2Manifest;

                $readRsg2ExtensionData = Rsg2ExtensionModel::readRsg2ExtensionData();
                $this->readRsg2ExtensionData = $readRsg2ExtensionData;

                break;

            case 'InstallMessage':


                //--- Form --------------------------------------------------------------------

                $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/InstallMessage.xml';
                $this->form = Form::getInstance('upload', $xmlFile);

                $input = Factory::getApplication()->input;
                $lowerVersion = $input->get('lowerVersion', '', 'STRING');
                if (empty ($lowerVersion)) {
                    $lowerVersion = '5.0.0.3';
                }

                $this->lowerVersion = $lowerVersion;

                // actual (new) version
                $oRsg2Version = new rsgallery2Version();
                $this->Rsg2Version = $oRsg2Version->getShortVersion(); // getLongVersion, getVersion
                $this->Rsg2Version = $oRsg2Version->getVersion(); // getLongVersion, getVersion

                // ausschnitt
                $installMessage = new InstallMessage ($this->Rsg2Version, $lowerVersion);
                $this->installMessage2 = $installMessage->installMessageText('update');

                // show all
                $installMessage = new InstallMessage ('9999.9.9.9', '0.0.1.0');
                //$this->installMessage = InstallMessage::installMessageText;
                $this->installMessage = $installMessage->installMessageText('update');

                break;


            case 'Rsg2GeneralInfo':

                $rsg2Manifest = Rsg2ExtensionModel::readRsg2ExtensionManifest ();
                $this->rsg2Manifest = $rsg2Manifest;

                $rsg2configuration = Rsg2ExtensionModel::readRsg2ExtensionConfiguration ();
                $this->rsg2Configuration = $rsg2configuration;

                $this->rsg2Configuration_j3x = [];
                if (J3xExistModel::J3xConfigTableExist ()) {

                    $rsg2configuration_j3x = MaintenanceJ3xModel::j3xConfigItems ();
                    $this->rsg2Configuration_j3x = $rsg2configuration_j3x;
                }

                break;

            case 'createImages':

                //--- Form --------------------------------------------------------------------

                $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/createImages.xml';
                $form = Form::getInstance('createImages', $xmlFile);

                // Check for errors.
                /* Must load form before */
                if ($errors = $this->get('Errors'))
                {
                    if (count($errors))
                    {
                        throw new GenericDataException(implode("\n", $errors), 500);
                    }
                }
                /**/

                /**
                // upload_zip, upload_folder
                $formParam = array(
                    'all_img_in_step1_01' => $isUseOneGalleryNameForAllImages,
                    'all_img_in_step1_02' => $isUseOneGalleryNameForAllImages,
                    'SelectGalleries01_01' => $IdGallerySelect,
                    'SelectGalleries02_02' => $IdGallerySelect
                );

                $form->bind($formParam);
                /**/

                $this->form = $form;

	            break;

			case 'defaultParams':


	            $this->actualParams = Rsg2ExtensionModel::readRsg2ExtensionConfiguration ();
                $this->defaultParams = Rsg2ExtensionModel::readRsg2ExtensionDefaultConfiguration();
                $this->mergedParams = Rsg2ExtensionModel::mergeDefaultAndActualParams ($this->defaultParams, $this->actualParams);

				// ToDo: button with command on controller ;-)
//				replaceRsg2ExtensionConfiguration($this->mergedParams);
			break;
		}


		//		Factory::getApplication()->input->set('hidemainmenu', true);

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=maintenance');
		Rsgallery2Helper::addSubmenu('develop');
		$this->sidebar = \JHtmlSidebar::render();

		$this->addToolbar($Layout);

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
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
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
						. '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DEVELOP')
					. ': ' . Text::_('COM_RSGALLERY2_MANIFEST_INFO_VIEW'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');
				break;

			case 'Rsg2GeneralInfo':
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. '* Button for copy to clipboard -> add typescript copy<br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
						. '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DEVELOP')
					. ': ' . Text::_('COM_RSGALLERY2_GENERAL_INFO_VIEW'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView');
				break;

			case 'InstallMessage':
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
                        . '* button goto "create galleries"<br>'
						. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
						. '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DEVELOP')
					. ': ' . Text::_('COM_RSGALLERY2_DEV_INSTALL_MSG_TEXT'), 'screwdriver');
                // ToDo: write into manifest value; read first -> Rsg2ExtensionModel
                // ToolBarHelper::custom ('develop.assignVersion','arrow-up-4','','Assign version to RSG2', false);
                ToolBarHelper::custom ('develop.useOldVersion','pencil-2','','Use old version', false);
				ToolBarHelper::cancel('config.cancel_rawView');
				break;

            case 'createGalleries':
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . '* Implement parent gallery selection<br>'
                        . '* button goto "create images"<br>'
                        . '* count indicator -> badge<br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
                        . '</span><br><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DEVELOP') . ' create galleries');

                ToolBarHelper::custom ('develop.createGalleries_001','upload','','Create 1 ', false);
                ToolBarHelper::custom ('develop.createGalleries_010','upload','','Create 10 ', false);
                ToolBarHelper::custom ('develop.createGalleries_100','upload','','Create 100 ', false);
                ToolBarHelper::custom ('develop.createGalleries_random','upload','','Create random (>10) ', false);

                ToolBarHelper::cancel('config.cancel_rawView');
                break;

            case 'createImages':
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . '* Implement parent gallery selection<br>'
                        . '* button goto "create images"<br>'
                        . '* count indicator -> badge<br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
                        . '</span><br><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_DEVELOP') . ' create images');
                ToolBarHelper::custom ('develop.createImages_001','upload','','Create 1 ', false);
                ToolBarHelper::custom ('develop.createImages_010','upload','','Create 10 ', false);
                ToolBarHelper::custom ('develop.createImages_100','upload','','Create 100 ', false);
                ToolBarHelper::custom ('develop.createImages_random','upload','','Create random (>10) ', false);

                ToolBarHelper::cancel('config.cancel_rawView');
                break;

            default:
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. '* <br>'
						. '* ? COM_RSGALLERY2_DEBUG_GALLERY_ORDER <br>'
						. '* ? COM_RSGALLERY2_UPDATE_COMMENTS_AND_VOTING <br>'
						. '* ?  COM_RSGALLERY2_REMOVE_INSTALLATION_LEFT_OVERS <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
						. '</span><br><br>';
				}

				// Set the title
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_DEVELOP_VIEW'), 'cube'); // 'maintenance');
				//ToolBarHelper::cancel('config.cancel_rawEdit');
				//ToolBarHelper::cancel('maintenance.cancel');
				ToolBarHelper::cancel('develop.cancel');
				break;
		}


		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}
	}

	/**
	public function getModel($name = '', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	/**/

}

