<?php
/**
 *
 * @package       Rsgallery2
 * @copyright (C) 2016-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author        finnern
 * RSGallery2 is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;



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
	 * @var string
	 */
	public $imageName;
	/**
	 * @var string the path to the base file including image name. If exist first original, then display, thumb (? watermarked)
	 *
	 */
	public $imagePath;
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

	public $originalFilePath;
	public $displayFilePath;
	public $thumbFilePath;
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
		$this->imagePath = '';

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
		__construct();

		$this->UseWatermarked = $watermarked;
	}

	public function assignDbItem($Image)
	{

		// ToDo: path to original file on outside folder
		// ToDo: image sizes check local ones also
        // ToDo: watermarked files

		try
		{
			$this->IsImageInDatabase = true;
			$this->imageName         = $Image ['name'];
			$this->parentGalleryId   = $Image ['gallery_id'];

			$imagePaths = new ImagePaths ($this->parentGalleryId);  // ToDo: J3x
			$imagePaths->createAllPaths();

			$this->originalFilePath = $imagePaths->getOriginalPath($this->imageName);
			$this->displayFilePath  = $imagePaths->getDisplayPath($this->imageName);
			$this->thumbFilePath    = $imagePaths->getThumbPath($this->imageName);

            $this->sizeFilePaths = $imagePaths->getSizePaths($this->imageName);

            // Helper list for faster detection of images lost and found
			$this->allImagePaths = [];

			$this->allImagePaths [] = $this->originalFilePath;
			$this->allImagePaths [] = $this->displayFilePath;
			$this->allImagePaths [] = $this->thumbFilePath;

            foreach($this->sizeFilePaths as $sizePath) {

				$this->allImagePaths [] = $sizePath;
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return;
	}


	public function check4ImageIsNotExisting()
	{

		try
		{
			$this->IsDisplayImageFound    = true;
			$this->IsOriginalImageFound   = true;
			$this->IsThumbImageFound      = true;

			$this->IsAllSizesImagesFound  = true;

            $this->IsSizes_ImageFound     = [];

			if (!File::exists($this->originalFilePath))
			{
				$this->IsOriginalImageFound   = false;
                $this->IsAllSizesImagesFound  = false;
			}
			if (!File::exists($this->displayFilePath))
			{
				$this->IsDisplayImageFound    = false;
                $this->IsAllSizesImagesFound  = false;
			}
			if (!File::exists($this->thumbFilePath))
			{
				$this->IsThumbImageFound      = false;
                $this->IsAllSizesImagesFound  = false;
			}

            foreach($this->sizeFilePaths as $size => $sizePath) {

                if (!File::exists($sizePath))
                {
                    $this->IsSizes_ImageFound [$size] = false;
                    $this->IsAllSizesImagesFound  = false;
                } else {
                    $this->IsSizes_ImageFound [$size] = true;
                }

			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
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
    public function initLostItems($galleryId, $imageName)
    {
        try {

            //--- Prepare standard -----------------------------------

            $this->IsImageInDatabase = false;
            $this->imageName         = $imageName;
            $this->parentGalleryId   = $galleryId;

            $imagePaths = new ImagePaths ($this->parentGalleryId); // ToDo: J3x
            $imagePaths->createAllPaths();

            $this->originalFilePath = $imagePaths->getOriginalPath($this->imageName);
            $this->displayFilePath  = $imagePaths->getDisplayPath($this->imageName);
            $this->thumbFilePath    = $imagePaths->getThumbPath($this->imageName);

            $this->sizeFilePaths = $imagePaths->getSizePaths($this->imageName);

            //--- set images to not found  -----------------------------------

            $this->IsDisplayImageFound    = false;
            $this->IsOriginalImageFound   = false;
            $this->IsThumbImageFound      = false;
            $this->IsAllSizesImagesFound  = false;

            foreach($this->sizeFilePaths as $size => $sizePath) {

                $this->IsSizes_ImageFound [$size] = false; // $sizePath;
            }

            $this->allImagePaths = [];

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing initLostItems: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

    /**
     * add file not in db list, remove from missing list
     * @param $sizeName
     * @param $imageFilePath
     *
     *
     * @since version
     */
    public function assignLostItem($sizeName, $imageFilePath)
    {
        $isImageAssigned = false;
        
        try {


            if ($imageFilePath === $this->originalFilePath)
            {
                $this->IsOriginalImageFound   = true;
                $isImageAssigned = true;
            }
            if ($imageFilePath === $this->displayFilePath)
            {
                $this->IsDisplayImageFound    = true;
                $isImageAssigned = true;
            }
            if ($imageFilePath === $this->thumbFilePath)
            {
                $this->IsThumbImageFound      = true;
                $isImageAssigned = true;
            }

            // size  assignment
            if ( ! $isImageAssigned) {
            
                foreach($this->sizeFilePaths as $size => $sizePath) {
                    if ($imageFilePath === $sizePath)
                    {
                        $this->IsSizes_ImageFound [$size] = true;
                        $isImageAssigned = true;
                    }
                }    
            }

            // size  assignment ? -> may differ from expected  
            if ( ! $isImageAssigned) {
                $this->IsSizes_ImageFound [$sizeName] = true;
                $isImageAssigned = true;
            }

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing AssignLostItem: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isImageAssigned;
    }

	/**
	 * Tells from the data collected if any of the expected images exist
	 * @param int $careForWatermarked
	 *
	 * @return bool
	 *
	 * @since version 4.3
	 *
	public function IsAnyImageExisting($careForWatermarked = ImageReference::dontCareForWatermarked)
	{
	    // toDo:
		$IsImageExisting =
			$this->IsDisplayImageFound
			|| $this->IsOriginalImageFound
			|| $this->IsThumbImageFound
			|| $this->IsWatermarkedImageFound;

		// Image of watermarked is only counting when no other
		// image is missing.
		if ($careForWatermarked)
		{
			if ($this->UseWatermarked)
			{
				$IsImageExisting |= $this->IsWatermarkedImageFound;
			}
		}

		return $IsImageExisting;
	}

	/*
	 * Tells from the data collected if any of the main images is missing
	 * Main: Display, Original or Thumb images
	 *
	 * watermarked images are not missing as such. watermarked images will be created when displaying image
	 * @param bool $careForWatermarked
	 * @return bool
	 *
	 * @since version 4.3
	 *
	public function IsMainImageMissing($careForWatermarked = ImageReference::dontCareForWatermarked)
	{
		$IsImageMissing =
			!$this->IsDisplayImageFound
			|| !$this->IsOriginalImageFound
			|| !$this->IsThumbImageFound;

		// Image of watermarked is only counting when no other
		// image is missing.
		if ($careForWatermarked)
		{
			if ($this->UseWatermarked)
			{
				$IsImageMissing |= !$this->IsWatermarkedImageFound;
			}
		}

		return $IsImageMissing;
	}
    /**/


	// toDo: ? Any size image missing ? ....

}


