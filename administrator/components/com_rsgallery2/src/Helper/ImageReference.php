<?php
/**
 *
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (c)  2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * @author         finnern
 * RSGallery2 is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagePathsData;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagePathsJ3xData;



// Include the JLog class.
//jimport('joomla.log.log');

/**
 * Collection of all data found about one image
 * May be database entry or image file info
 *
 * @since 4.3.0
 */
class ImageReference
{
    /**
     * @var
     * @since version
     */
    public $allImagePaths;
    /**
     * @var string
     */
    public $imageName;
    /**
     * @var string
     */
    public $imageNameDb;
    /**
     * @var string the path to the base file including image name. If exist first original, then display, thumb (? watermarked)
     *
     */
    public $imageUrl;
    /**
     * @var bool
     */
    public $IsImageInDatabase;
    /**
     * @var bool
     */
    public $IsDisplayImageFound;
    /**
     * @var bool
     */
    public $IsOriginalImageFound;
    /**
     * @var bool
     */
    public $IsThumbImageFound;
    /**
     * @var bool
     */
    public $IsAllSizesImagesFound;

    /**
     * @var array
     */
    public $IsSizes_ImageFound;

    /**
     * @var bool
     */
    public $IsWatermarkedImageFound;

    /**
     * @var int
     */
    public $parentGalleryId;

    /**
     * @var bool
     */
    public $useWatermarked;

    /**
     * @var bool
     */
    public $use_j3x_location;

    /**
     * @var bool
     */
//    public $j3x_orphan;

    /**
     * @var
     * @since version
     */
    public $originalFilePath;
    /**
     * @var
     * @since version
     */
    public $displayFilePath;
    /**
     * @var
     * @since version
     */
    public $thumbFilePath;
    /**
     * @var
     * @since version
     */
    public $sizeFilePaths; // 800x6000, ..., ? display:J3x

    //--- constants -----------------------------------------

    /**
     * @var int
     */
    const dontCareForWatermarked = 0;
    /**
     * @var int
     */
    const careForWatermarked = 0;

    /**
     * ImageReference constructor. init all variables
     *
     * @since version 4.3
     */
    public function __construct()
    {
        $this->imageName = '';
        $this->imageUrl = '';

        $this->IsImageInDatabase       = false;
        $this->IsDisplayImageFound     = false;
        $this->IsOriginalImageFound    = false;
        $this->IsThumbImageFound       = false;
        $this->IsWatermarkedImageFound = false;
        $this->IsAllSizesImagesFound   = false;

        $this->parentGalleryId = -1;

        $this->useWatermarked = false;
    }

    /**
     * Second ImageReference constructor. Tells if watermarked images shall be checked too
     *
     * @param   bool  $watermarked
     *
     * @since version 4.3
     */
    public function __construct1($watermarked)
    {
        $this->__construct();

        $this->UseWatermarked = $watermarked;
    }

    /**
     * $Image: /name / gallery id
     *
     * @param $image
     *
     *
     * @throws \Exception
     * @since version
     */
    public function assignDbItem($image)
    {
        // ToDo: path to original file on outside folder
        // ToDo: image sizes check local ones also
        // ToDo: watermarked files

        try {
            $this->IsImageInDatabase = true;

            $this->imageName = $image->name;
            $this->parentGalleryId = $image->gallery_id;
            $this->use_j3x_location = $image->use_j3x_location;

            // J4x path
            $imagePaths = new ImagePathsData ($this->parentGalleryId);

            $imagePaths->assignPathData($image);

            $imagePaths->createAllPaths();
            $this->sizeFilePaths = $image->SizePaths;

            $this->originalFilePath = $image->OriginalFile;
            $this->displayFilePath = $image->DisplayFile;
            $this->thumbFilePath = $image->ThumbFile;

            // Helper list for faster detection of images lost and found
            $this->allImagePaths = [];

            $this->allImagePaths [] = $this->originalFilePath;
            $this->allImagePaths [] = $this->thumbFilePath;

            // J4x path
            if (!empty ($this->sizeFilePaths)) {
                foreach ($this->sizeFilePaths as $sizePath) {
                    $this->allImagePaths [] = $sizePath;
                }
            } else {
                $OutTxt = 'assignDbItem: file sizes in config may be missing';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'notice');
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing assignDbItem: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

    /**
     * Check all epected pathes for image existance
     *
     * @throws \Exception
     * @since version
     */
    public function check4ImageIsNotExisting()
    {
        try {

//            // debug stop
//            if ($this->imageName == '2019-09-17_00305.jpg') {
//              $this->IsDisplayImageFound  = $this->IsDisplayImageFound;
//            }

            // $this->IsDisplayImageFound  = true; // -> has no display
            $this->IsOriginalImageFound = true;
            $this->IsThumbImageFound    = true;

            $this->IsAllSizesImagesFound = true;

            $this->IsSizes_ImageFound = [];

            if (!file_exists($this->originalFilePath)) {
                $this->IsOriginalImageFound  = false;
                $this->IsAllSizesImagesFound = false;
            }

            if (!file_exists($this->thumbFilePath)) {
                $this->IsThumbImageFound     = false;
                $this->IsAllSizesImagesFound = false;
            }

            // J4x path
            if (!empty ($this->sizeFilePaths)) {
                foreach ($this->sizeFilePaths as $size => $sizePath) {
                    if (!file_exists($sizePath)) {
                        $this->IsSizes_ImageFound [$size] = false;
                        $this->IsAllSizesImagesFound = false;
                    } else {
                        $this->IsSizes_ImageFound [$size] = true;
                    }
                }
            } else {
                $OutTxt = 'check4ImageIsNotExisting: file sizes in config may be missing';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'notice');
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing check4ImageIsNotExisting: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

    /**
     * Set every expected image 'size' to not existing
     *
     * @param $galleryId
     * @param $imageName
     *
     *
     * @since version
     */
    public function initOrphanedItem($galleryId, $imageName)
    {
        try {
            //--- Prepare standard -----------------------------------

            $this->IsImageInDatabase = false;
            $this->imageName         = $imageName;
            $this->parentGalleryId   = $galleryId;

            $imagePaths = new ImagePathsModel ($this->parentGalleryId); // ToDo: J3x
            $imagePaths->createAllPaths();

            $this->originalFilePath = $imagePaths->getOriginalPath($this->imageName);
            $this->displayFilePath  = $imagePaths->getDisplayPath($this->imageName);
            $this->thumbFilePath    = $imagePaths->getThumbPath($this->imageName);

            $this->sizeFilePaths = $imagePaths->getSizePaths($this->imageName);

            //--- set images to not found  -----------------------------------

            $this->IsDisplayImageFound   = false;
            $this->IsOriginalImageFound  = false;
            $this->IsThumbImageFound     = false;
            $this->IsAllSizesImagesFound = false;

	        if (!empty ($this->sizeFilePaths))
	        {
		        foreach ($this->sizeFilePaths as $size => $sizePath)
		        {
			        $this->IsSizes_ImageFound [$size] = false; // $sizePath;
		        }
	        }

            $this->allImagePaths = [];

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing initOrphanedItem: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

    /**
     * add file not in db list, remove from missing list
     *
     * @param $sizeName
     * @param $imagePath
     *
     *
     * @since version
     */
    public function assignOrphanedItem($sizeName, $imagePath)
    {
        $isImageAssigned = false;


        try {
            if ($imagePath === $this->originalFilePath) {
                $this->IsOriginalImageFound = true;
                $isImageAssigned            = true;
            }
//            if ($imagePath === $this->displayFilePath) {
//                $this->IsDisplayImageFound = true;
//                $isImageAssigned           = true;
//            }
            if ($imagePath === $this->thumbFilePath) {
                $this->IsThumbImageFound = true;
                $isImageAssigned         = true;
            }

            // size assignment
            if (!$isImageAssigned)
            {
	            if (!empty ($this->sizeFilePaths))
	            {
                    // debug stop
                    // if ($this->imageName == '2019-09-17_00305.jpg') {
                    if ($this->imageName == 'DSC_5520.JPG') {
                        $this->IsDisplayImageFound  = $this->IsDisplayImageFound;
                    }

                    foreach ($this->sizeFilePaths as $size => $sizePath)
		            {
			            if ($imagePath === $sizePath)
			            {
				            $this->IsSizes_ImageFound [$size] = true;
				            $isImageAssigned                  = true;
			            }
		            }
	            }
            }

            // size  assignment ? -> may differ from expected
            if (!$isImageAssigned) {
                $this->IsSizes_ImageFound [$sizeName] = true;
                $isImageAssigned                      = true;
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing assignOrphanedItem: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isImageAssigned;
    }

    public function assignImageUrl ()
    {
        // debug stop
        // if ($this->imageName == '2019-09-17_00305.jpg') {
        if ($this->imageName == 'DSC_5520.JPG') {
            $this->IsDisplayImageFound  = $this->IsDisplayImageFound;
        }

        $this->imageUrl = '';

        // J3x path
        $imagePath = new ImagePathsModel ($this->parentGalleryId);

        if ($this->IsThumbImageFound) {
            // display
            $this->imageUrl = $imagePath->getThumbUrl($this->imageName);
        } else {
            if ($this->IsDisplayImageFound) {
                // display
                $this->imageUrl = $imagePath->getDisplayUrl($this->imageName);
            } else {

                if (!empty ($this->sizeFilePaths))
                {
                    foreach ($this->sizeFilePaths as $size => $sizePath)
                    {
                        if ($this->IsSizes_ImageFound [$size]) // $sizePath;
                        {
                            $this->imageUrl = $imagePath->getSizeUrl($size, $this->imageName);
                        }
                    }
                }

            }
        }

    }

    /**
     * Tells from the data collected if any of the expected images exist
     * @param   int   $careForWatermarked
     * @param   bool  $careForWatermarked
     * @return bool
     * @since version 4.3
     */
     public function IsAnyImageExisting($careForWatermarked = ImageReference::dontCareForWatermarked)
     {
//         // debug stop
//         if ($this->imageName == 'DSC_5520.JPG') {
//             $this->IsDisplayImageFound  = $this->IsDisplayImageFound;
//         }
//
         $IsImageExisting = false
             // || $this->IsDisplayImageFound // Not used in j4x
             || $this->IsOriginalImageFound
             || $this->IsThumbImageFound
             || $this->IsAllSizesImagesFound;

         if (!empty ($this->IsSizes_ImageFound)) {
             foreach ($this->IsSizes_ImageFound as $size => $isFound) {
                 if ($isFound) {
                    $IsImageExisting  = true;
                    break;
                 }
             }
         }

//         // Image of watermarked is only counting when no other
//         // image is missing.
//         if ($careForWatermarked) {
//             if ($this->UseWatermarked) {
//                 $IsImageExisting |= $this->IsWatermarkedImageFound;
//             }
//         }

         return $IsImageExisting;
     }

     /**
     * Tells from the data collected if any of the main images is missing
     * Main: Display, Original or Thumb images
     *
     * watermarked images are not missing as such. watermarked images will be created when displaying image
     * @since version 4.3
     */
     public function IsMainImageMissing($careForWatermarked = ImageReference::dontCareForWatermarked)
     {
         $IsImageMissing =  false
             // || !$this->IsDisplayImageFound // Not used in j4x
             || !$this->IsOriginalImageFound
             || !$this->IsThumbImageFound
             || !$this->IsAllSizesImagesFound;

//         // Image of watermarked is only counting when no other
//         // image is missing.
//         if ($careForWatermarked) {
//             if ($this->UseWatermarked) {
//                 $IsImageMissing |= !$this->IsWatermarkedImageFound;
//             }
//         }

         return $IsImageMissing;
     }
     /**/

}

