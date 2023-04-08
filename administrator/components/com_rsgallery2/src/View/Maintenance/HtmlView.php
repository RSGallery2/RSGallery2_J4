<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Maintenance;

\defined('_JEXEC') or die;

//use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageExif;



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

	protected $buttons = [];

	protected $isDebugBackend;
	protected $isDevelop;

	protected $isDangerActive;
	protected $isRawDbActive;
	protected $isUpgradeActive;
	protected $isTestActive;
	protected $isJ3xRsg2DataExisting;
	protected $developActive;

	protected $intended;

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

		// for prepared but not ready views
		$input = Factory::getApplication()->input;
		$this->intended = $input->get('intended', 'not defined', 'STRING');

		// Check for errors.
		/* Must load form before
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}
		/**/


        $this->isJ3xRsg2DataExisting = J3xExistModel::J3xConfigTableExist();


        //--- Check user rights ---------------------------------------------

		// toDo: More detailed for rsgallery admin
		$app       = Factory::getApplication();

		$user = $app->getIdentity();
		$canAdmin = $user->authorise('core.admin');
		$this->UserIsRoot = $canAdmin;

		//--- begin to display ----------------------------------------------

//		Factory::getApplication()->input->set('hidemainmenu', true);

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=maintenance');
		Rsgallery2Helper::addSubmenu('maintenance');
		$this->sidebar = \JHtmlSidebar::render();

		$Layout = Factory::getApplication()->input->get('layout');


        switch ($Layout)
        {
            case 'checkimageexif':

                // ToDo: Save last used image in session
                $input = $app->input;
                $exifImageFile = $input->get('exifImageFile', '', 'STRING');
                // $this->exifImageFile = $exifImageFile;

                $exifDataJsonified = $input->get('exifData', '', 'STRING');

                // toDo: remove
                // gallery ID , image
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/fith04bar01.jpg');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/IMG_0018.JPG');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/DSCF0258.JPG');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/DSC_0240.JPG');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/DSC_0711.jpg');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/JED_LoveLocks.jpg');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/DSC_3871.jpg');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/DSCN1956.jpg');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/2019-09-21_00126.jpg');
                $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/DSC_0377.jpg');


                // $this-> = ;
                if (empty ($this->exifImageFiles)) {
                    $this->exifDataOfFiles = [];
                } else {
                    $this->exifDataOfFiles = json_decode($exifDataJsonified);

                    // toDo: retrieve files from answer
                    // ... $this->exifImageFiles [] = array ('', JPATH_ROOT .   '/images/rsgallery2/ExifTest/fith04bar01.jpg');

                }

                $test = $this->exifDataOfFiles;
                $test = $test;


//                try
//                {
//
//
//
//
//                }
//                catch (\RuntimeException $e)
//                {
//                    $OutTxt = '';
//                    $OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
//                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//                    $app = Factory::getApplication();
//                    $app->enqueueMessage($OutTxt, 'error');
//                }

                break;

        }

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
			case 'prepared':

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . '<strong>' . $this->intended . '<strong>'
//					. ': ' . Text::_('COM_RSGALLERY2_MAINT_PREPARED_NOT_READY')
					, 'screwdriver');
				ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');
				break;

            case 'checkimageexif':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . '*  Gallery number / image name <br>'
                        . '*  collect only selected gallery id, and filenames<br>'
                        . '*  <br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
                        . '</span><br><br>';
                }


                ToolBarHelper::title(Text::_('COM_RSGALLERY2_CHECK_IMAGE_EXIF'), 'camera-retro'); // 'maintenance');
                ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');

                // https://jimpl.com/ Online EXIF data viewer
                //ToolBarHelper::custom('maintenance.checkImageExifData', ' fas fa-camera-retro', '', 'COM_RSGALLERY2_READ_IMAGE_EXIF_SELECTED', false);
                ToolBarHelper::custom('maintenance.checkImageExifData', 'none icon-image fas fa-camera-retro', '', 'COM_RSGALLERY2_READ_IMAGE_EXIF_SELECTED', false);
//                ToolBarHelper::custom('maintenance.checkImageExifData', 'camera-retro', '', 'COM_RSGALLERY2_READ_IMAGE_EXIF_SELECTED', false);
//                ToolBarHelper::custom('maintenance.checkImageExifData', 'info', '', 'COM_RSGALLERY2_READ_IMAGE_EXIF_SELECTED', false);

                break;

            default:
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . '*  Install: finish -> Move J3x images<br>'
                        . '*  Repair: Consolidade images<br>'
                        . '* !!! Purge / delete of database variables should be confirmed !!!<br>'
                        . '* Do shorten CSS by *.SCSS<br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
//                        . '* <br>'
                        . '</span><br><br>';
                }

                // Set the title
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MANAGE_MAINTENANCE'), 'cogs'); // 'maintenance');
				ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');
				// ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');
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

