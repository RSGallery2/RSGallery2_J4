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
	public $missingSizesImages;

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

	public $originalBasePath;
	public $displayBasePath;
	public $thumbBasePath;
	public $sizeBasePaths; // 800x6000, ..., ? display:J3x

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

		$this->imageSizes_expected = [];
		$this->imageSizes_found    = [];
		$this->imageSizes_lost     = [];

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

	public function AssignDbItem($Image)
	{

		// ToDo: path to original file
		// ToDo: image sizes check local ones also

		try
		{


			$this->IsImageInDatabase = false;
			$this->imageName         = $Image ['name'];
			$this->parentGalleryId   = $Image ['gallery_id'];

			$imagePaths = new ImagePaths ($this->parentGalleryId);
			$imagePaths->createAllPaths();

			$this->originalBasePath = $imagePaths->originalBasePath;
			$this->displayBasePath  = $imagePaths->displayBasePath;
			$this->thumbBasePath    = $imagePaths->thumbBasePath;

            $this->sizeBasePaths = [];
            foreach($imagePaths->sizeBasePaths as $size => $sizePath) {

                $this->sizeBasePaths[$size] = $sizePath . '/' .  $this->imageName;;
            }

			$this->allImagePaths = [];

			$this->allImagePaths [] = $this->originalBasePath . '/' . $this->imageName;
			$this->allImagePaths [] = $this->displayBasePath . '/' . $this->imageName;
			$this->allImagePaths [] = $this->thumbBasePath . '/' . $this->imageName;

            foreach($this->sizeBasePaths as $sizePath) {

				$this->allImagePaths [] = $sizePath;
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
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

			$this->IsAllSizesImagesFound  = true;
            $this->missingSizesImages     = [];

			if (!File::exists($this->originalBasePath))
			{
				$IsOriginalImageFound   = false;
			}
			if (!File::exists($this->displayBasePath))
			{
				$IsDisplayImageFound    = false;
			}
			if (!File::exists($this->thumbBasePath))
			{
				$IsThumbImageFound      = false;
			}

            foreach($this->sizeBasePaths as $size => $sizePath) {

                if (!File::exists($sizePath))
                {
                    $IsAllSizesImagesFound  = false;
                    $this->missingSizesImages [$size] = $sizePath;
                }

			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return;
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


