<?php
/**
 *
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (C) 2016-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * @author         finnern
 * RSGallery2 is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagePathsData;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagePathsJ3xData;
use RuntimeException;

use function defined;

// Include the JLog class.
//jimport('joomla.log.log');

/**
 * Collection of all data found about one image in J3x style
 * May be database entry or image file info
 *
 * @since 4.3.0
 */
class ImageReferenceJ3x extends ImageReference
{
    /**
     * ImageReference constructor. init all variables
     *
     * @since version 4.3
     */
    public function __construct()
    {
	    parent::__construct();

		$this->use_j3x_location = true;
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
	    parent::__construct($watermarked);

        $this->UseWatermarked = $watermarked;
    }

    /**
     * $Image: /name / gallery id
     *
     * @param $image
     *
     *
     * @throws Exception
     * @since version
     */
    public function assignDbItem($image)
    {
//		parent::assignDbItem($image); -> bad: create paths

        try {
            $this->IsImageInDatabase = true;

            $this->imageName = $image->name;
            $this->parentGalleryId = $image->gallery_id;
            $this->use_j3x_location = $image->use_j3x_location;

            // J3x path
            $imagePathJ3x = new ImagePathsJ3xData ();
            $imagePathJ3x->assignPathData($image);

            $this->originalFilePath = $image->OriginalFile;
            $this->displayFilePath = $image->DisplayFile;
            $this->thumbFilePath = $image->ThumbFile;

            // Helper list for faster detection of images lost and found
            $this->allImagePaths = [];

        } catch (RuntimeException $e) {
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
     * @throws Exception
     * @since version
     */
    public function check4ImageIsNotExisting()
    {
        try {
            $this->IsDisplayImageFound  = true;
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

            // J3x path
            if (!file_exists($this->displayFilePath)) {
                $this->IsDisplayImageFound = false;
                $this->IsAllSizesImagesFound = false;
            }

        } catch (RuntimeException $e) {
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

            $imagePaths = new ImagePathsJ3xModel ($this->parentGalleryId);
            // $imagePaths->createAllPaths();

            $this->originalFilePath = $imagePaths->getOriginalPath($this->imageName);
            $this->displayFilePath  = $imagePaths->getDisplayPath($this->imageName);
	        $this->thumbFilePath    = $imagePaths->getThumbPath($this->imageName);

            //--- set images to not found  -----------------------------------

            $this->IsDisplayImageFound   = false;
            $this->IsOriginalImageFound  = false;
            $this->IsThumbImageFound     = false;
            $this->IsAllSizesImagesFound = false;

            $this->allImagePaths = [];

        } catch (RuntimeException $e) {
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
    public function assignOrphanedItem($dirType, $imagePath)
    {
        $isImageAssigned = false;

        try {
            if ($imagePath === $this->originalFilePath) {
                $this->IsOriginalImageFound = true;
                $isImageAssigned            = true;
            }
            if ($imagePath === $this->displayFilePath) {
                $this->IsDisplayImageFound = true;
                $isImageAssigned           = true;
            }
            if ($imagePath === $this->thumbFilePath) {
                $this->IsThumbImageFound = true;
                $isImageAssigned         = true;
            }

        } catch (RuntimeException $e) {
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
        $this->imageUrl = '';

        // J3x path
        $imagePathJ3x = new ImagePathsJ3xData ();

        if ($this->IsDisplayImageFound) {
            // display
            $this->imageUrl = $imagePathJ3x->getDisplayUrl($this->imageName);
        } else {
            if ($this->IsThumbImageFound) {
                // display
                $this->imageUrl = $imagePathJ3x->getThumbsUrl($this->imageName);
            } else {
                if ($this->IsOriginalImageFound) {
                    // display
                    $this->imageUrl = $imagePathJ3x->getOriginalUrl($this->imageName);
                }
//                else {
//                }
            }
        }

    }

    /**
     * Tells from the data collected if any of the expected images exist
     *
     * @param   int   $careForWatermarked
     *
     * @param   bool  $careForWatermarked
     *
     * @return bool
     *
     * @return bool
     *
     * @since version 4.3
     *
     * public function IsAnyImageExisting($careForWatermarked = ImageReference::dontCareForWatermarked)
     * {
     * // toDo:
     * $IsImageExisting =
     * $this->IsDisplayImageFound
     * || $this->IsOriginalImageFound
     * || $this->IsThumbImageFound
     * || $this->IsWatermarkedImageFound;
     *
     * // Image of watermarked is only counting when no other
     * // image is missing.
     * if ($careForWatermarked)
     * {
     * if ($this->UseWatermarked)
     * {
     * $IsImageExisting |= $this->IsWatermarkedImageFound;
     * }
     * }
     *
     * return $IsImageExisting;
     * }
     *
     * /*
     * Tells from the data collected if any of the main images is missing
     * Main: Display, Original or Thumb images
     *
     * watermarked images are not missing as such. watermarked images will be created when displaying image
     * @since version 4.3
     *
     * public function IsMainImageMissing($careForWatermarked = ImageReference::dontCareForWatermarked)
     * {
     * $IsImageMissing =
     * !$this->IsDisplayImageFound
     * || !$this->IsOriginalImageFound
     * || !$this->IsThumbImageFound;
     *
     * // Image of watermarked is only counting when no other
     * // image is missing.
     * if ($careForWatermarked)
     * {
     * if ($this->UseWatermarked)
     * {
     * $IsImageMissing |= !$this->IsWatermarkedImageFound;
     * }
     * }
     *
     * return $IsImageMissing;
     * }
     * /**/

    // toDo: ? Any size image missing ? ....

}

