<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Upload;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\FilesystemHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

//use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\UploadModel;

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

    protected $isDebugBackend;
    protected $isDevelop;

    protected $UploadLimit;
    protected $PostMaxSize;
    protected $MemoryLimit;
    protected $MaxSize;

    protected $FtpUploadPath;
    // protected $LastUsedUploadZip;
    protected $is1GalleryExisting;

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
        $this->isDevelop      = $rsgConfig->get('isDevelop');

        //--- Form --------------------------------------------------------------------

        $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/forms/upload.xml';
        $form    = Form::getInstance('upload', $xmlFile);

        // Check for errors.
        /* Must load form before */
        if ($errors = $this->get('Errors')) {
            if (count($errors)) {
                throw new GenericDataException(implode("\n", $errors), 500);
            }
        }
        /**/

        //--- Limits --------------------------------------------------------------------

        // Instantiate the media helper
        $mediaHelper = new MediaHelper;

        // Maximum allowed size in MB
        $this->UploadLimit = round($mediaHelper->toBytes(ini_get('upload_max_filesize')) / (1024 * 1024));
        $this->PostMaxSize = round($mediaHelper->toBytes(ini_get('post_max_size')) / (1024 * 1024));
        $this->MemoryLimit = round($mediaHelper->toBytes(ini_get('memory_limit')) / (1024 * 1024));
        $this->MaxSize     = FilesystemHelper::fileUploadMaxSize();

        //--- FtpUploadPath ------------------------

        // ToDo: subtract standard root path
        // ToDo: red/orange/lila when path does not exist
        // $app->input->get('install_directory', $app->get('tmp_path'))

        // Retrieve path from config
        $FtpUploadPath = $rsgConfig->get('ftp_path');
        // On empty use last successful
        if (empty ($FtpUploadPath)) {
            $FtpUploadPath = $rsgConfig->get('last_used_ftp_path');
        }
        $this->FtpUploadPath = $FtpUploadPath;

        //--- LastUsedUploadZip ------------------------

        // Not possible to set input variable in HTML so it is not collected
        // $this->LastUploadedZip = $app->getUserState('com_rsgallery2.last_used_uploaded_zip');
        // $LastUsedUploadZip->getLastUsedUploadZip();

        // register 'upload_drag_and_drop', 'upload_zip_pc', 'upload_folder_server'
        //$this->ActiveSelection = $rsgConfig->getLastUpdateType();
        $this->ActiveSelection = $rsgConfig->get('last_update_type');
        if (empty ($this->ActiveSelection)) {
            $this->ActiveSelection = 'upload_drag_and_drop';
        }

//		// 0: default, 1: enable, 2: disable
//		$isUseOneGalleryNameForAllImages = $rsgConfig->get('isUseOneGalleryNameForAllImages');
//		if (empty ($isUseOneGalleryNameForAllImages)) {
//			$isUseOneGalleryNameForAllImages = '1';
//		}
//		if ($isUseOneGalleryNameForAllImages == '2') {
//			$isUseOneGalleryNameForAllImages = '0';
//		}

        //--- Pre select latest gallery ?  ------------------------

        $IdGallerySelect = -1; //No selection

        $input = Factory::getApplication()->input;

        // coming from gallery edit -> new id
        $Id = $input->get('id', 0, 'INT');
        if (!empty ($Id)) {
            $IdGallerySelect = $Id;
        }

        $isPreSelectLatestGallery = $rsgConfig->get('isPreSelectLatestGallery');
        if ($isPreSelectLatestGallery) {
            $IdGallerySelect = UploadModel::IdLatestGallery();
        }

        // upload_zip, upload_folder
        $formParam = [
            'SelectGallery' => $IdGallerySelect,
        ];

        $form->bind($formParam);

        $this->is1GalleryExisting = UploadModel::is1GalleryExisting();

        $this->form = $form;

        /**
         * // Check for errors.
         * if (count($errors = $this->get('Errors')))
         * {
         * throw new RuntimeException(implode('<br />', $errors), 500);
         * }
         * /**/

        // Assign the Data
        // $this->form = $form;
        // $this->item = $item;


        //---  --------------------------------------------------------------------

        HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=Upload');
        Rsgallery2Helper::addSubmenu('upload');
        $this->sidebar = \Joomla\CMS\HTML\Helpers\Sidebar::render();

        //$Layout = Factory::getApplication()->input->get('layout');
        $this->addToolbar();

        return parent::display($tpl);
    }


    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since __BUMP_VERSION__
     */
    protected function addToolbar()
    {
        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . 'Tasks: <br>'
                . '* upload big batch (50) last is uploaded first<br>'
                . '* Remove bar of uploaded image after 3s when OK<br>'
                . '* Check b5 on card  on messages (error, ...)<br>'
                . '* !!! ---- more --------------<br>'
//                . '* <br>'
//				. '* check mime type<br>'
//				. '* Mime type: zip			     <br>'
//				. '* Mime type: images			 <br>'
//				. '* Mime type: zip -> images	<br>'
//				. '* Mime type: folder -> images<br>'
//				. '* Redesign upload list: flex<br>'
//				. '* typescript: redesign uplod list filling -> use php html prepared blocks and clone them<br>'
//				. '* status bar -> bootstrap ? + aria ...<br>'
//				. '* check image db for not set items <br>'
//				. '* touch ?<br>'
//				. '* use makeSafeUrlNameRSG2 see b_Release_4_5_1\admin\models\image.php<br>'
//				. '* GallerySelectField: a) Trashed deleted ?  b) ? published  <br>'
//				. '* Make /Maximum/ Element with title in hover<br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
                . '</span><br>';
        }

        // Set the title
        ToolBarHelper::title(Text::_('COM_RSGALLERY2_DO_UPLOAD'), 'upload');

//		// Get the toolbar object instance
//		$toolbar = Toolbar::getInstance('toolbar');

//		// Options button.
//		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
//		{
//	    	$toolbar->preferences('com_rsgallery2');
//		}
    }

}

