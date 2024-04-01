<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2024 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Maintenance;

\defined('_JEXEC') or die;

//use JModelLegacy;
use Finnern\Component\Lang4dev\Administrator\Helper\langFileNamesSet;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Image\Image;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Joomla\Utilities\ArrayHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageExif;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel;
//use Rsgallery2\Component\Rsgallery2\Administrator\Model\Image;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel;



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
        $this->isDevelop      = $rsgConfig->get('isDevelop');

        $this->isRawDbActive   = true; // false / true;
        $this->isDangerActive  = true; // false / true;
        $this->isUpgradeActive = true; // false / true;
        if ($this->isDevelop) {
            $this->isTestActive  = true; // false / true;
            $this->developActive = true; // false / true;
        }

        // for prepared but not ready views
        $input          = Factory::getApplication()->input;
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
        $app = Factory::getApplication();

        //$user             = $app->getIdentity();
	    $user  = $this->getCurrentUser();
        $canAdmin         = $user->authorise('core.admin');
        $this->UserIsRoot = $canAdmin;

        //--- begin to display ----------------------------------------------

//		Factory::getApplication()->input->set('hidemainmenu', true);

        //---  --------------------------------------------------------------

        HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=maintenance');
        Rsgallery2Helper::addSubmenu('maintenance');
        $this->sidebar = \JHtmlSidebar::render();

        $Layout = Factory::getApplication()->input->get('layout');

        switch ($Layout) {
            case 'checkimageexif':

                // ToDo: Save last used image in session
                $input = $app->input;

                // fall back
                $this->exifDataRawOfFiles = [];

                //--- files and ids from last call --------------------------------------

                $cids         = ArrayHelper::toInteger($input->get('cids', array(), 'ARRAY'));
                $inGalleryIds = ArrayHelper::toInteger($input->get('galIds', array(), 'array'));
                //$inFileNames  = ArrayHelper::toString($input->get('imgNames', array(), 'array'));
                $inFileNames  = $input->get('imgNames', array(), 'array');

                //--- self call by user -------------------------------------------------
                // (self call with user input )

                if ( ! empty ($inFileNames)) {

                    $test1 = json_encode($inFileNames);

                    //--- reuse files ---------------------------------------------------

                    $this->exifImageFiles = [];
                    foreach ($inFileNames as $idx => $fileName) {

                        $GalleryId = '';
                        if ($inGalleryIds[$idx] > 0) {
                            $GalleryId = $inGalleryIds[$idx];
                        }
                        $this->exifImageFiles [] = array($GalleryId, $fileName);
                    }

                    //--- collect files exif data --------------------------------

                    // selections
                    if (!empty($cids)) {

                        // create absolute paths (ToDo: improve for galleries ...
                        $pathFileNames = $this->filenamesByGalIdAndImgName($inGalleryIds, $inFileNames);
                        // select chosen files
                        $exifFileNames = $this->selectedFileNames($cids, $pathFileNames);

                        if (!empty ($exifFileNames)) {
                            //--- extract exif data -----------------------------

                            $imgModel                 = new \Rsgallery2\Component\Rsgallery2\Administrator\Model\ImageModel ();
                            $this->exifDataRawOfFiles = $imgModel->exifDataAllOfFiles($exifFileNames);

                            //--- match exif names with enabled / supported --------------------------------

                            // ToDo: rename name to tags
                            $exifTags = [];
                            foreach ($this->exifDataRawOfFiles as $exifDataOfFile) {

                                if ( ! empty ($exifDataOfFile[1])) {

                                    // $fileName = $exifDataOfFile[0];
                                    $exifData = $exifDataOfFile[1];

                                    foreach ($exifData as $exifTagFull => $exifItem) {
                                        $exifTag = $exifTagFull;

                                        if (!in_array($exifTag, $exifTags)) {
                                            $exifTags [] = $exifTag;
                                        }
//                                    $exifDataFullName = $exifItem[0];

//                                    // ToDo: ? use part/full name
//                                    $exifName = explode('.', $exifDataFullName);
//
//                                    if (!in_array($exifTags, $exifName[1])) {
//                                        $exifTags [] = $exifDataFullName;
//                                    }

                                    }
                                }
                            }

                            $this->exifAllTagsCollected = $exifTags;
                            $this->exifIsNotSupported    = imageExif::checkTagsNotSupported($exifTags);
                            $this->exifIsNotUserSelected = imageExif::checkNotUserSelected($exifTags);

                        }
                    }

                } else {
                    //--- first call -------------------------------------------------

                    if ($this->isDevelop) {
                        // preset file list
                        $this->exifImageFiles = $this->presetExifFileList();
                    } else {


                    }
                }


                $this->exifUserSelected      = imageExif::userExifTagsJ3x();
                $this->exifTagsSupported     = imageExif::supportedExifTags();

                $this->exifTagsTranslationIds = [];
                if (!empty ($this->exifTagsSupported)) {
                    foreach ($this->exifTagsSupported as $exifTag) {
                        [$type, $name] = ImageExif::tag2TypeAndName ($exifTag);
                        $this->exifTagsTranslationIds [] = imageExif::exifTranslationId($name);
                    }

                    // ToDo: read ini file for not found translations

                    $neededIds = imageExif::neededTranslationIds();

                    $this->exifMissingTranslations = $this->CheckExifMissingTranslationIds ($neededIds);

                }

                //--- prepare empty input for files -------------------------------------

                for ($idx = count($this->exifImageFiles); $idx < 10; $idx++) {
                    $this->exifImageFiles [] = array('', '');
                }

                break;

        } // switch

        $this->addToolbar($Layout);

        parent::display($tpl);

        return ;
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

        switch ($Layout) {
            case 'prepared':

                ToolBarHelper::title(
                    Text::_('COM_RSGALLERY2_MAINTENANCE')
                    . ': ' . '<strong>' . $this->intended . '<strong>'
//					. ': ' . Text::_('COM_RSGALLERY2_MAINT_PREPARED_NOT_READY')
                    ,
                    'screwdriver'
                );
                ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');
                break;

            case 'checkimageexif':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
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

                ToolBarHelper::title(
                    Text::_('COM_RSGALLERY2_CHECK_IMAGE_EXIF'),
                    'fas fa-camera-retro'
                ); // 'maintenance');
                ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');

                ToolBarHelper::custom('maintenance.checkImageExifData', 'none fas fa-camera-retro',
                    'image', 'COM_RSGALLERY2_READ_IMAGE_EXIF_SELECTED', false);
                ToolBarHelper::link(
                    'index.php?option=com_rsgallery2&view=maintenance&layout=checkimageexif',
                    'COM_RSGALLERY2_READ_IMAGE_EXIF_SELECTED',
                    'none fas fa-camera-retro'
                );

                break;

            default:
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop)) {
                    echo '<span style="color:red">'
	                    . '* ! Db J3x gallery transfer: enable single transfers <br>'
	                    . '* <br>'
                        . '* Install: finish -> Move J3x images<br>'
                        . '* Repair: Consolidade images<br>'
	                    . '* Raw J3x galleries list<br>'
	                    . '* Raw J3x images list<br>'
	                    . '* Raw J3x .... list<br>'
                        . '* !!! Purge / delete of database variables should be confirmed !!!<br>'
                        . '* Do shorten CSS by *.SCSS<br>'
//                        . '* <br>'
//                        . '* <br>'
                        . '</span><br><br>';
                }

                // Set the title
                ToolBarHelper::title(Text::_('COM_RSGALLERY2_MANAGE_MAINTENANCE'), 'cogs'); // 'maintenance');
                ToolBarHelper::cancel('maintenance.cancel_rsg2', 'JTOOLBAR_CLOSE');
                // ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

	            ToolBarHelper::custom('MaintenanceCleanUp.undoPrepareRemoveTables', 'none fas fa-undo fa-delete',
		            'icon-undo', 'Undo prepare remove of RSG2', false);


                break;
        }

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2')) {
            $toolbar->preferences('com_rsgallery2');
        }
    }

    /** ToDo: put into model (?which ?)
     * list of filenames from gallery ID and image name
     * if no gallery ID is given then the filename is passed directly
     *    path: root of J! installation or absolut (on server)
     *
     * @param   $galleryIds
     * @param   $ImageNames
     *
     *
     * @since version
     */
    public function filenamesByGalIdAndImgName($galleryIds = [], $ImageOrFileNames = [])
    {
        $fileNames = [];

        foreach ($ImageOrFileNames as $idx => $imageOrFileName) {
            // no gallery specified => filename given
            if (empty ($galleryIds[$idx])) {
                $fileNames [] = $imageOrFileName;
            } else {
                // ToDo:
                $fileNames [] = "";
            }
        }

        return $fileNames;
    }

    /** ToDo: put into model (image(s))
     *
     * @param   $cids
     * @param   $fileNames
     *
     *
     * @since version
     */
    public function selectedFileNames($cids = [], $inFileNames=[])
    {
        $fileNames = [];

        foreach ($inFileNames as $idx => $fileName) {
            if (in_array($idx, $cids)) {
                if ($fileName != '') {
                    $fileNames [] = $fileName;
                }
            }
        }

        return $fileNames;
    }

    public function presetExifFileList()
    {
        $exifImageFiles = [];

        // gallery ID , image
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/2019-09-21_00126.jpg');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/8054.jpg');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/DSC_0240.JPG');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/DSC_0377.JPG');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/DSC_0711.JPG');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/DSC_3871.JPG');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/DSCF0258.JPG');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/DSCN1956.JPG');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/fith04bar01.jpg');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/IMG_0018.JPG');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/IMG_20230114_094341.jpg');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/IMG_20230323_120558.jpg');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/IMG_20230401_115447.jpg');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/IMG-20230106-WA0002.jpg');
        $exifImageFiles [] = array('', JPATH_ROOT . '/images/rsgallery2/ExifTest/Screenshot_20200613_150114_com.huawei.android.launcher.jpg');

        return $exifImageFiles;
    }

    private function CheckExifMissingTranslationIds(array $neededIds)
    {
        $existingIds = [];
        $missingIds = [];

        //--- read exif language file ---------------------------------------------

        $changeLogModelFileName  = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/language/en-GB/com_rsg2_exif.ini';

        $handle = fopen($changeLogModelFileName, "r");
        if ($handle) {

	        //--- extract language items ---------------------------------------------

	        while (($line = fgets($handle)) !== false) {

                $existingId = $this->lineExtractTransId ($line);

                if ( ! empty ($existingId)) {

                    $existingIds [] = $existingId;
                }
            }

            fclose($handle);
        }

        if (count($existingIds) > 0) {

            foreach ($neededIds as $neededId) {

                if ( ! in_array ($neededId, $existingIds)){

                    $missingIds [] = $neededId;
                }
            }
        }

        return $missingIds;
    }

    private function lineExtractTransId(bool|string $line)
    {
        $transId = '';

        // COM_RSGALLERY2_EXIF_TAG_FILEMODIFIEDDATE="File modified date"

        if (str_starts_with ($line, 'COM_RSGALLERY2_EXIF_TAG')) {

            $parts = explode('=', $line);

            if ( ! empty($parts[0])) {

                $transId = $parts[0];
            }

        }

        return $transId;
    }

}

