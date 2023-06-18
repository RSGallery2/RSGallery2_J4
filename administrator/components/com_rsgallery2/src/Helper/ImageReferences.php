<?php
/**
 * ImageReferences collect all information about image artefacts
 *
 * @package       Rsgallery2
 * @copyright (C) 2016-2023 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author        finnern
 *                RSGallery2 is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

// no direct access
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Path;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageReference;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;

\defined('_JEXEC') or die;


/**
 * ImageReferences collect all information about image artefacts
 * The information contains appearance and properties of images
 * where at least one part is missing. Artefacts appear when a image
 * file or a database entry is missing
 * Examples: display image file is existing but no database entry is matching
 * This is used in maintConsolidateDb to fix these artefacts
 *
 * @since 4.3.0
 */
class ImageReferences
{
	/**
     * List of image references
	 * @var ImageReference []
	 */
	protected $ImageReferenceList;

    /**
     * List of image references. Contains all items where a image is missing or surplus (additional in folder)
     * @var LostAndFoundList []
     */
    protected $ImageLostAndFoundList;

	/**
	 * @var bool
	 */
	protected $IsAnyImageMissingInDB;
    /**
     * @var bool
     */
	protected $IsAnyImageMissingInDisplay;
    /**
     * @var bool
     */
	protected $IsAnyImageMissingInOriginal;
    /**
     * @var bool
     */
	protected $IsAnyImageMissingInThumb;
    /**
     * @var bool
     */
	protected $IsAnyImageMissingInSizes;
    /**
     * @var bool
     */
	protected $IsAnyImageMissingInWatermarked;
    /**
     * @var bool
     */
	protected $IsAnyOneImageMissing;
	/**
	 * @var bool
	 */
	public $UseWatermarked;

	/**
	 * ImageReferences constructor. init all variables
	 * @param bool $watermarked
	 *
	 * @since 4.3.0
	 */
	public function __construct($watermarked = false)
	{
		$this->ImageReferences = array();

		$this->IsAnyImageMissingInDB          = false;
		$this->IsAnyImageMissingInDisplay     = false;
		$this->IsAnyImageMissingInOriginal    = false;
		$this->IsAnyImageMissingInThumb       = false;
		$this->IsAnyImageMissingInSizes       = false;
		$this->IsAnyImageMissingInWatermarked = false;
		$this->IsAnyOneImageMissing           = false;

		/**
		if ($watermarked)
		{
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';
		}
        /**/
		$this->UseWatermarked = $watermarked;
	}

    /**
     *
     * @return ImageReference[]
     *
     * @since version 4.3
     */
	public function getImageReferenceList()
	{

		// ??? if empty -> CollectImageReferences ...

		return $this->ImageReferenceList;
	}

	// ToDO: Do i need this function ?
    /**
     * property accessor
     * shall only be used for IsAny...
     *
     * @param string $property
     *
     * @return mixed (mostly bool)
     *
     * @since version 4.3
     */
	public function __get($property)
	{
		if (property_exists($this, $property))
		{
			return $this->$property;
		}
	}

    /**
     * Collects all data of all images and then creates the list
     * of image artefacts
     *
     * @return string Message of creating the data if any
     *
     * @since version 4.3
     */
	public function CollectImageReferences()
	{
		global $rsgConfig;

		$msg = '';

		//--- Collect data of all expected images --------------------------------------------------

        $this->ImageReferenceList = [];

        // Create references for items from database view. Contains path to all expected images (-> original, thumb, sizes ...)
        $this->imageReferencesByDb ();
        // flag not existing images
        $this->checkList4NotExisting ();
        // search for files not in list
        $this->findOrphans_add2List ();

        // reduce list
        $this->ImageLostAndFoundList = $this->reduceList4LostAndFounds ();


        return; // $this->ImageReferenceList;
	}

	private function imageReferencesByDb () {

        try {

            $this->ImageReferenceList = [];

            $dbImagesList = $this->getDbImagesList();  // Is tunneled to create it only once

            foreach ($dbImagesList as $dbImage) {

                $ImageReference = new ImageReference ();
                $ImageReference->assignDbItem ($dbImage);

                $this->ImageReferenceList [] = $ImageReference;
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

	    return ;
    }

	private function checkList4NotExisting ()
	{

		try
		{

			foreach ($this->ImageReferenceList as $ImageReference) {

				$ImageReference->check4ImageIsNotExisting();

			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return;
	}

	// search for files not in list
	private function findOrphans_Add2List ()
	{

		try
		{
            // go through all


		    // toDo: Outside originals ....

			// only base path needed so galleryid == 0
            $imagePaths = new ImagePaths (0); // ToDo: J3x
            $rsgImagesGalleriesPath = $imagePaths->rsgImagesGalleriesBasePath;


            // all found gallery ids in folder
            $galleryIdDirs = glob($rsgImagesGalleriesPath . '/*', GLOB_ONLYDIR);

            foreach ($galleryIdDirs as $galleryIdDir) {

                $this->testSizesDir4Orphans ($galleryIdDir);

            }


		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return;
	}


    // search for files not in list
    private function testSizesDir4Orphans ($galleryIdDir)
    {

        try
        {
            // gallery ID
            $galleryId = basename ($galleryIdDir);

            if ( ! is_numeric($galleryId))
            {
                return;
            }

	        // all found gallery ids in folder
	        $sizeDirs = glob($galleryIdDir . '/*', GLOB_ONLYDIR);

	        foreach ($sizeDirs as $sizeDir) {

		        $this->testImageDir4Orphans ($sizeDir, $galleryId);

	        }


        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }


    // search for files not in list
    private function testImageDir4Orphans ($sizeDir, $galleryId)
    {


        try
        {
            // gallery ID
            $sizeName = basename ($sizeDir);

            // all found gallery ids in folder
            $imageFiles = array_filter(glob($sizeDir . '/*'), 'is_file');
            foreach ($imageFiles as $imageFilePath)
            {
                $imageFilePath = Path::clean ($imageFilePath);
	            $imageName = basename ($imageFilePath);

//	            $testClean = Path::clean ($imageFilePath);
//	            $testResolve = Path::resolve ($imageFilePath);


                // toDo: check extension by config
                // $ext =  File::getExt($filename);

                // toDo: check for valid image file
                //---
                // "Do not use getimagesize() to check that a given file is a valid image.Use a purpose-built solution such as the Fileinfo extension instead."
                //
                //Here is an example:
                //
                //$finfo = finfo_open(FILEINFO_MIME_TYPE);
                //$type = finfo_file($finfo, "test.jpg");
                //
                //if (isset($type) && in_array($type, array("image/png", "image/jpeg", "image/gif"))) {
                //    echo 'This is an image file';
                //} else {
                //    echo 'Not an image :(';
                //}
                //---
                //    $a = getimagesize($path);
                //    $image_type = $a[2];
                //
                //    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
                //    {
                //        return true;
                //    }
                //    return false;
                //---
                // exif_imagetype is much faster than getimagesize and doesn't use gd-Lib (leaving a leaner mem footprint)
                //
                //function isImage($pathToFile)
                //{
                //  if( false === exif_imagetype($pathToFile) )
                //   return FALSE;
                //
                //   return TRUE;
                //}


                $isImage = true;

                if ($isImage) {


                    // check if image, check if exist in list, check if other part of item exists (different size ...)
                    //$isInList = findImageInList ($galleryId, $sizeName, $imageName, $imageFilePath);
                    [$isInList, $ImageReference] = $this->findImageInList($galleryId, $imageName, $imageFilePath);

                    // Unknown item
                    if (!$isInList) {


                        // Find item with gallery and name ?
                        // No -> create new item
                        if (!$ImageReference) {

                            $ImageReference = new ImageReference ();
                            $ImageReference->initLostItems($galleryId, $imageName);
                            $ImageReference->assignLostItem($sizeName, $imageFilePath);

                            $this->ImageReferenceList [] = $ImageReference;
                        } else {
                            // Yes -> add flags for this

                            $ImageReference->assignLostItem($sizeName, $imageFilePath);

                        }
                    }
                }
            }
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

	// search for files not in list
	private function findImageInList ($galleryId, $imageName, $ImageFilePath)
	{
		$isFound = false;

        $partlyItem = false;

		try
		{

			foreach ($this->ImageReferenceList as $ImageReference) {

				// gallery and image name must match
				if ($ImageReference->parentGalleryId == $galleryId) {
					if ($ImageReference->imageName == $imageName)
					{

                        $partlyItem = $ImageReference;

						foreach ($ImageReference->allImagePaths as $TestImagePath) {

							if ($ImageFilePath === $TestImagePath) {

								$isFound = true;
								break;
							}
						}

						if( ! $isFound) {
                            break;
                        }

                        // matched galleryId ... no further search needed
						// actual image name is checked
						break;
					}
				}
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return [$isFound, $partlyItem];
	}

/**
     * Collects existing image name list with gallery_id from database
     *
     * @return array of object (name and gallery_id)
     *
     * @since version 4.3
     */
	private function getDbImagesList()
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

        // ToDo: add path to original file
        // ToDo: add image sizes
		$query->select($db->quoteName(array('name', 'gallery_id')))
			->from($db->quoteName('#__rsg2_images'))
			->order('name');

		$db->setQuery($query);
		$rows =  $db->loadAssocList();

		return $rows;
	}


    /**
     * collect all items in ImageReferenceList where a image is missing or surplus (additional in folder
     * @return array
     *
     * @throws \Exception
     * @since version
     */
    private function reduceList4LostAndFounds ()
    {
        $isFound = false;

        $List4LostAndFounds = [];

        try
        {
            foreach ($this->ImageReferenceList as $ImageReference) {

                // Missing or additional image file in item
                if ( ! $ImageReference->IsAllSizesImagesFound) {

                    $List4LostAndFounds [] = $ImageReference;
                }
            }
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing reduceList4LostAndFounds: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $List4LostAndFounds;
    }


} // class

