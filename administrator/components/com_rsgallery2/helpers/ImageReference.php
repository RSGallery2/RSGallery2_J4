<?php
/**
 *
 * @package       Rsgallery2
 * @copyright (C) 2016-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author        finnern
 * RSGallery2 is Free Software
 */

namespace RSGallery2\Component\RSGallery2\Administrator\Helper;

\defined('_JEXEC') or die;

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
	public $IsWatermarkedImageFound;
	/**
	 * @var int
	 */
	public $ParentGalleryId;
	/**
	 * @var bool
	 */
	public $UseWatermarked;

    /**
     * @var array
     */
	public $imageSizes_expected;

    /**
     * @var array
     */
    public $imageSizes_found;

    /**
     * @var array
     */
    public $imageSizes_lost;



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

        $this->imageSizes_expected = [];
        $this->imageSizes_found    = [];
        $this->imageSizes_lost     = [];

        $this->ParentGalleryId = -1;

		$this->UseWatermarked = false;
	}

	/**
	 * Second ImageReference constructor. Tells if watermarked images shall be checked too
	 *
	 * @param bool $watermarked
	 *
	 * @since version 4.3
	 */
	public function __construct1($watermarked)
	{
		__construct();

		$this->UseWatermarked = $watermarked;
	}

	/**
	 * Tells from the data collected if any of the expected images exist
	 * @param int $careForWatermarked
	 *
	 * @return bool
	 *
	 * @since version 4.3
	 */
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
	 */
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

	// toDo: ? Any size image missing ? ....

}


