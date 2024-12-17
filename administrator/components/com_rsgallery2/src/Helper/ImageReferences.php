<?php
/**
 * ImageReferences collect all information about image artefacts
 *
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (C) 2016-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * @author         finnern
 *                RSGallery2 is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

// no direct access
use Exception;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
use Joomla\Filesystem\Path;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;
use RuntimeException;

use function defined;

defined('_JEXEC') or die;

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
     *
     * @var ImageReference []
     */
    protected $ImageReferenceList;

    /**
     * List of image references. Contains all items where a image is missing or surplus (additional in folder)
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
     *
     * @param   bool  $watermarked
     *
     * @since 4.3.0
     */
    public function __construct($watermarked = false)
    {
        $this->ImageReferenceList = [];

        $this->IsAnyImageMissingInDB          = false;
        $this->IsAnyImageMissingInDisplay     = false;
        $this->IsAnyImageMissingInOriginal    = false;
        $this->IsAnyImageMissingInThumb       = false;
        $this->IsAnyImageMissingInSizes       = false;
        $this->IsAnyImageMissingInWatermarked = false;
        $this->IsAnyOneImageMissing           = false;

        /**
         * if ($watermarked)
         * {
         * require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';
         * }
         * /**/
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

    // ToDo: Do i need this function ?

    /**
     * property accessor
     * shall only be used for IsAny...
     *
     * @param   string  $property
     *
     * @return mixed (mostly bool)
     *
     * @since version 4.3
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return null;
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
        $this->imageReferencesByDb();

        // flag not existing images
        $this->checkList4NotExisting();

        // search for files not in list
        $this->findOrphans_add2List();

        // reduce list
        $this->ImageLostAndFoundList = $this->reduceList4LostAndFounds();

        // Add one Url to each error
        $this->AddUrlToLostAndfound ();

        return; // $this->ImageReferenceList;
    }

    /**
     *
     *
     * @throws Exception
     * @since version
     */
    private function imageReferencesByDb()
    {
        try {
            $this->ImageReferenceList = [];

            $dbImagesList = $this->getDbImagesList();  // Is tunneled to create it only once

            foreach ($dbImagesList as $dbImage) {
	            if ( ! $dbImage->use_j3x_location)
	            {
		            $ImageReference = new ImageReference ();
	            } else {
		            $ImageReference = new ImageReferenceJ3x ();
	            }
                // name / gallery id / useWatermarked
                $ImageReference->assignDbItem($dbImage);

                $this->ImageReferenceList [] = $ImageReference;
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

    private function checkList4NotExisting()
    {
        try {
            foreach ($this->ImageReferenceList as $ImageReference) {
                $ImageReference->check4ImageIsNotExisting();
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

    // search for files not in list
    private function findOrphans_Add2List()
    {
        try {
            //--- j4x style -----------------------------------

            // only base path needed so galleryId == 0
            $imagePaths             = new ImagePathsModel (0);
            $rsgImagesGalleriesPath = $imagePaths->rsgImagesGalleriesBasePath;

            // all found gallery ids in folder
            $galleryIdDirs = glob($rsgImagesGalleriesPath . '/*', GLOB_ONLYDIR);

            foreach ($galleryIdDirs as $galleryIdDir) {
                $this->testSizesDir4Orphans($galleryIdDir);
            }

            //--- j3x style -----------------------------------

            // only base path needed so galleryid == 0
            $imagePaths             = new ImagePathsJ3xModel (0); // ToDo: J3x
            $rsgImagesGalleriesPath = $imagePaths->rsgImagesGalleriesBasePath;

            // original, thumb, display ? watermarked

	        $this->testJ3xDir4Orphans($imagePaths->originalBasePath, 'original');
	        $this->testJ3xDir4Orphans($imagePaths->thumbBasePath, 'thumb');
	        $this->testJ3xDir4Orphans($imagePaths->displayBasePath, 'display');
	        // $this->testJ3xDir4Orphans($imagePaths->originalBasePath, 'watermarked');


//            $galleryIdDirs = glob($rsgImagesGalleriesPath . '/*', GLOB_ONLYDIR);
//            foreach ($galleryIdDirs as $galleryIdDir) {
//                $this->testJ3xDir4Orphans($galleryIdDir);
//            }


        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

    // search for files not in list
    private function testSizesDir4Orphans($galleryIdDir='')
    {
        try {
            // gallery ID
            $galleryId = basename($galleryIdDir);

            if (!is_numeric($galleryId)) {
                return;
            }

            // all found gallery ids in folder
            $sizeDirs = glob($galleryIdDir . '/*', GLOB_ONLYDIR);

            foreach ($sizeDirs as $sizeDir) {
                $this->testImageDir4Orphans($sizeDir, $galleryId);
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

    // search for files not in list
    private function testImageDir4Orphans($sizeDir, $galleryId)
    {
        try {
            // gallery ID
            $sizeName = basename($sizeDir);

            // all found gallery ids in folder
            $imageFiles = array_filter(glob($sizeDir . '/*'), 'is_file');
            foreach ($imageFiles as $imageFilePath) {
                $imageFilePath = Path::clean($imageFilePath);
                $imageName     = basename($imageFilePath);

				$isImage = $this->isFileAnImage($imageFilePath);

                if ($isImage) {
                    // check if image exists in list, check if other part of item exists (different size ...)
                    [$isImgFound, $isImgReferenceExist, $ImageReference] = $this->findImageInList($galleryId, $imageName, $imageFilePath);

                    // Unknown item
                    if (!$isImgFound) {
                        // No previous image from set found ?
                        if (!$isImgReferenceExist) {
                            $ImageReference = new ImageReference ();
                            $ImageReference->initOrphanedItem($galleryId, $imageName);
                            $ImageReference->assignOrphanedItem($sizeName, $imageFilePath);

                            $this->ImageReferenceList [] = $ImageReference;
                        } else {
                            // Yes -> add flags for this
                            $ImageReference->assignOrphanedItem($sizeName, $imageFilePath);
                        }
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

    // search for files not in list
    private function findImageInList($galleryId, $imageName, $ImageFilePath)
    {
        $isImgFound = false;
        $isImgReferenceExist = false;
        $ImageReference = false;

         // debug stop
         if ($imageName == 'DSC_5520.JPG') {
             $imageName  = $imageName;
         }

        try {
            foreach ($this->ImageReferenceList as $TestImageReference) {
                // gallery and image name must match
                if ($TestImageReference->parentGalleryId == $galleryId) {
                    // Any image already defined
                    if ($TestImageReference->imageName == $imageName) {

                        $isImgReferenceExist = true;
                        $ImageReference = $TestImageReference;

                        foreach ($TestImageReference->allImagePaths as $TestImagePath) {
                            // Reference Item exists already
                            if ($ImageFilePath === $TestImagePath) {

                                $isImgFound = true;
                                break;
                            }
                        }
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing findImageInList: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return [$isImgFound, $isImgReferenceExist, $ImageReference];
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
        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true);

        // ToDo: add path to original file
        // ToDo: add image sizes
        $query
            ->select($db->quoteName(['name', 'gallery_id', 'use_j3x_location']))
            ->from($db->quoteName('#__rsg2_images'))
            ->order('name');

        $db->setQuery($query);
        // $rows = $db->loadResult();
        $rows = $db->loadObjectList();

        return $rows;
    }

    /**
     * collect all items in ImageReferenceList where a image is missing or surplus (additional in folder
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    private function reduceList4LostAndFounds()
    {
        $isFound = false;

        $List4LostAndFounds = [];

        try {
            foreach ($this->ImageReferenceList as $ImageReference) {
                // Missing or additional image file in item
                if (!$ImageReference->IsAllSizesImagesFound) {
                    $List4LostAndFounds [] = $ImageReference;
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing reduceList4LostAndFounds: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $List4LostAndFounds;
    }

	/**
	 * @param   string  $galleryIdDir
	 * @param   string  $dirType ('original', 'thumb', 'display')
	 *
	 *
	 * @throws Exception
	 * @since version
	 */
    private function testJ3xDir4Orphans(string $galleryIdDir, string $dirType)
    {
        try {

	        $imageFiles = array_filter(glob($galleryIdDir . '/*'), 'is_file');

			// each file
	        foreach ($imageFiles as $imageFilePath)
	        {
		        $isImage = $this->isFileAnImage ($imageFilePath);

		        if ($isImage)
		        {
			        $imageFilePath = Path::clean($imageFilePath);
			        $imageName     = basename($imageFilePath);

					// remove extension
					if ($dirType != 'original') {
						$imageName = substr($imageName, 0, -4);
					}

			        // check if image, check if exist in list, check if other part of item exists (different size ...)
			        //$isInList = findImageInList ($galleryId, $sizeName, $imageName, $imageFilePath);
			        [$isInList, $ImageReference] = $this->findImageJ3xInList($imageName, $imageFilePath);

			        // Unknown item
			        if (!$isInList)
			        {
				        // Find item with gallery and name ?
				        // No -> create new item
				        if (!$ImageReference)
				        {
					        $ImageReference = new ImageReferenceJ3x ();
							$ImageReference->use_j3x_location = true;
						    $ImageReference->initOrphanedItem(-1, $imageName);
						    $ImageReference->assignOrphanedItem($dirType, $imageFilePath);

					        $this->ImageReferenceList [] = $ImageReference;
				        }
				        else
				        {
					        // Yes -> add flags for this

//						    $ImageReference->assignLostItem($sizeName, $imageFilePath);
				        }
			        }

		        }
	        }
        } catch(RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing imageReferencesByDb: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

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

	private function isFileAnImage(string $imageFilePath)
	{
		// For PHP >= 5.3 use finfo_open() or mime_content_type()

		// ToDo: What about webp or svg ...

		$isImage = false;
		if (@is_array(getimagesize($imageFilePath))){
			$isImage = true;
		}

		return $isImage;
	}

	private function findImageJ3xInList(string $imageName, string $imageFilePath)
	{
		$isInList = false;
		$ImageReference = false;

		try {
			foreach ($this->ImageReferenceList as $TestImageReference)
			{
				// checking j3x images
				if ($TestImageReference->use_j3x_location)
				{
                    // Reference Item exists already
					if ($TestImageReference->imageName == $imageName)
					{
						$ImageReference = $TestImageReference;

						$isInList = true;
						break;
					}
				}
			}
		} catch (RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'Error executing findImageJ3xInList: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return [$isInList, $ImageReference];
	}

	public function hasJ3xFile() {
		$hasJ3xFile = false;

		foreach ($this->ImageLostAndFoundList as $ImageReference)
		{
			if ($ImageReference->use_j3x_location)
			{
				$hasJ3xFile = true;
				break;
			}
		}

		return $hasJ3xFile;
	}

	public function hasJ4xFile() {
		$hasJ4xFile = false;

		foreach ($this->ImageLostAndFoundList as $ImageReference)
		{
			if (! $ImageReference->use_j3x_location)
			{
				$hasJ4xFile = true;
				break;
			}
		}

		return $hasJ4xFile;
	}

    private function AddUrlToLostAndfound()
    {

        foreach ($this->ImageLostAndFoundList as $ImageReference){
            $ImageReference->assignImageUrl();
        }
    }

} // class

