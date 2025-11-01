<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Image\Image;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;


//require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';

// ToDo: own file ImageFilePathsModel for merge_paths and class imagePathsModel

/**
 * Handles files of images with actions like
 * Creating Thumb, watermarked and turning and flipping of images
 *
 * @since __BUMP_VERSION__
 */
class ImageFileModel extends ListModel // BaseModel
{

    const THUMB_PORTRAIT = 0;
    const THUMB_SQUARE = 1;

    /**
     * Constructor.
     *
     * @since 5.1.0     */
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

        parent::__construct([]);
    }


    /**
     * image file attributes to handle the file paths later
     *
     * @param $ImageId
     *
     * @return array
     *
     * @since  5.1.0
    */
    public function imageFileAttrib($ImageId)
    {
        $fileName         = "";
        $galleryId        = "";
        $use_j3x_location = "";

        try {
            $db = $this->getDatabase();

            $query = $db
                ->createQuery()
                ->select($db->quoteName(['name', 'gallery_id', 'use_j3x_location']))
                ->from($db->quoteName('#__rsg2_images'))
                ->where($db->quoteName('id') . ' = ' . $db->quote($ImageId));
            $db->setQuery($query);

            $imageDb = $db->loadObject();
            // $imageDb = $db->loadRow();
            // $imageDb = $db->loadAssoc();

            $fileName         = $imageDb->name;
            $galleryId        = $imageDb->gallery_id;
            $use_j3x_location = $imageDb->use_j3x_location;
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing use_j3x_location for ImageId: "' . $ImageId . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // $next = $max +1;

        return [$fileName, $galleryId, $use_j3x_location];
    }

	/**
	 * returns file and url path by J3/j4 style
	 *
	 * @param $imageFileName
	 * @param $galleryId
	 * @param $use_j3x_location
	 *
	 * @return array
	 *
	 * @since version
	 */
    public function getOriginalPaths($imageFileName, $galleryId, $use_j3x_location) {
        $OriginalPathFileName = "";

        // J4x ?
        if (!$use_j3x_location) {
            $imagePaths = new ImagePathsModel ($galleryId);

            //---  -------------------------------------------------

            $OriginalPathFileName = $imagePaths->getOriginalPath($imageFileName);
            $OriginalFileNameUri  = $imagePaths->getOriginalUrl($imageFileName);
        } else {
            // J3x

            $ImagePathJ3x = new ImagePathsJ3xModel ();

            //---  -------------------------------------------------

            $OriginalPathFileName = $ImagePathJ3x->getOriginalPath($imageFileName);
            $OriginalFileNameUri  = $ImagePathJ3x->getOriginalUrl($imageFileName);
        }

        return [$OriginalPathFileName, $OriginalFileNameUri];
    }


	/**
	 * Use temp file to copy image file to 'original' destination
	 *
	 * @param $OriginalFilePath
	 * @param $OriginalFileUri
	 *
	 * @return false
	 *
	 * @throws \Exception
	 * @since version
	 */
    public function downloadImageFile($OriginalFilePath, $OriginalFileUri) {
        $IsDownloaded = false;

        try {
            $size     = filesize($OriginalFilePath);
            $mimeType = mime_content_type($OriginalFilePath);
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
            Factory::getApplication()->close();

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
