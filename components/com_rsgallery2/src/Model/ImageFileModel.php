<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright (c) 2016-2023 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
//use Joomla\CMS\Image;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Image\Image;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\MVC\Model\ListModel;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\PathHelper;

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';

// ToDo: own file ImageFilePaths for merge_paths and class imagePaths

/**
 * Handles files of images with actions like
 * Creating Thumb, watermarked and turning and flipping of images
 *
 * @since __BUMP_VERSION__
 */
class ImageFileModel extends BaseModel // AdminModel
{

	/**
	 * Constructor.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function __construct()
	{
//		global $rsgConfig, $Rsg2DebugActive;

//		parent::__construct($config = array());

//		if ($Rsg2DebugActive)
//		{
//			Log::add('==>Start __construct ImageFile');
//		}

		// JComponentHelper::getParams();
		// $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//
//		$rsgConfig = ComponentHelper::getParams('com_rsgallery2');

        parent::__construct(array());

	}


    /**
     * image file attributes to handle the file paths later
     * @param $ImageId
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    /**/
    public function imageFileAttrib($ImageId)
    {
        $fileName = "";
        $galleryId = "";
        $use_j3x_location = "";

        try
        {
            $db    =  Factory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName(array('name', 'gallery_id', 'use_j3x_location')))
                ->from($db->quoteName('#__rsg2_images'))
                ->where($db->quoteName('id') . ' = ' . $db->quote($ImageId));
            $db->setQuery($query);

            $imageDb = $db->loadObject();
            // $imageDb = $db->loadRow();
            // $imageDb = $db->loadAssoc();

            $fileName = $imageDb->name;
            $galleryId = $imageDb->gallery_id;
            $use_j3x_location = $imageDb->use_j3x_location;

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing use_j3x_location for ImageId: "' . $ImageId . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // $next = $max +1;

        return [$fileName, $galleryId, $use_j3x_location];
    }
    /**/


    public function getOriginalPaths($imageFileName, $galleryId, $use_j3x_location) {

        $OriginalPathFileName = "";

        // J4x ?
        if( ! $use_j3x_location) {

            $imagePaths = new ImagePaths ($galleryId);

            //---  -------------------------------------------------

            $OriginalPathFileName = $imagePaths->getOriginalPath($imageFileName);
            $OriginalFileNameUri = $imagePaths->getOriginalUrl($imageFileName);

        } else {

            // J3x

            $ImagePathJ3x = new ImagePathsJ3x ();

            //---  -------------------------------------------------

            $OriginalPathFileName = $ImagePathJ3x->getOriginalPath($imageFileName);
            $OriginalFileNameUri = $ImagePathJ3x->getOriginalUrl($imageFileName);
        }

        return [$OriginalPathFileName, $OriginalFileNameUri];
    }


    public function downloadImageFile($OriginalFilePath, $OriginalFileUri) {
        $IsDownloaded = false;

        try {

            $size = filesize($OriginalFilePath);
            $mimeType =  mime_content_type($OriginalFilePath);
            $fileName = basename($OriginalFilePath);

            //--- header ------------------------------------------------

            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header("Content-type: " . $mimeType);
            header("Pragma: no-cache");
            header('Pragma: public');
            header("Expires: 0");

            header('Content-Length: ' . $size);

//            ob_end_clean();

            //--- read file to client ---------------------------------------------

            readfile($OriginalFileUri);

            // get my db data and echo it as csv data

            // Close the application gracefully.
            Factory::getApplication()->close();            //--- exit success ------------------------------------------------
            
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing rebuild: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IsDownloaded;
    }

}
