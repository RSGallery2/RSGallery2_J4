<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

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
	protected $imagePaths = null;

	const THUMB_PORTRAIT = 0;
	const THUMB_SQUARE = 1;
	
	/**
	 * Constructor.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function __construct()
	{
		global $rsgConfig, $Rsg2DebugActive;

//		parent::__construct($config = array());

		if ($Rsg2DebugActive)
		{
			Log::add('==>Start __construct ImageFile');
		}

		// JComponentHelper::getParams();
		// $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//
		$rsgConfig = ComponentHelper::getParams('com_rsgallery2');

//		$this->imagePaths = new imagePaths ($rootPath, $galleryId, , 'false');

	}

	/**
	 * Creates a display image with size from config
	 * If memory of image not given it creates and destroys the created image
	 *
	 * @param string $originalFileName includes path (May be a different path then the original)
	 * @param  image $memImage
	 *
	 * @return image|bool|null if successful returns resized image handler
	 *
	 * @throws Exception
	 * @since __BUMP_VERSION__
	 */
	public function createDisplayImageFile($targetFileName = '', $targetWidth = 0, $memImage = null)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$IsImageCreated = false;
		$IsImageLocal = false;

		try
		{
			if ($Rsg2DebugActive)
			{
				Log::add('==> start createDisplayImageFile: "' . $targetFileName . '"');
			}

			/** not prepared *
			// Create memory image if not given
			//if ($memImage == null)
			if (empty ($memImage))
			{
				$IsImageLocal = True;
				$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $baseName;
				$memImage = new image ($imgSrcPath);
			}
			/**/

			// Make sure the resource handle is valid.
			if (!$memImage->isLoaded())
			{
				throw new \LogicException('createDisplayImageFile: No valid image was loaded.');
			}

			// Make sure the target width is given
			if (!$targetWidth)
			{
				throw new \LogicException('createDisplayImageFile: Wrong target size');
			}


			//---- target size -------------------------------------

			// source sizes
			$imgHeight = $memImage->getHeight();
			$imgWidth  = $memImage->getWidth();

			$width = $targetWidth;
			$height = $targetWidth;

			if ($imgWidth > $imgHeight)
			{
				// landscape
				$height = ($targetWidth / $imgWidth) * $imgHeight;
			}
			else
			{
				// portrait or square
				$width  = ($targetWidth / $imgHeight) * $imgWidth;
			}

			//--- Resize and save -----------------------------------

			$IsImageCreated = $memImage->resize ($width, $height, false, image::SCALE_INSIDE);
			if (!empty($IsImageCreated))
			{
				//--- Resize and save -----------------------------------
				$type = IMAGETYPE_JPEG;
				$IsImageCreated = $memImage->toFile($targetFileName, $type);
			}

			/** see above *
			// Release memory if created locally
			if ($IsImageLocal)
			{
				if (!empty($IsImageCreated))
				{
					$IsImageCreated = True;
				}
				$memImage->destroy();
			}
			/**/
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing createDisplayImageFile for image name: "' . $targetFileName . '" size: ' . $targetWidth . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				Log::add($OutTxt);
			}
		}

		if ($Rsg2DebugActive)
		{
			Log::add('<== Exit createDisplayImageFile: ' . (($IsImageCreated) ? 'true' : 'false'));
		}

		return $IsImageCreated;
	}

	/**
	 * Creates a thumb image with size from config
	 * THe folder used is either orignal or display image.
	 * One of these must exist
	 * If memory of image not given it creates and destroys the created image
	 *
	 * @param string $originalFileName includes path (May be a different path then the original)
	 *
	 * @param image $memImage
	 *
	 * @return image if successful
	 *
	 * @throws Exception
	 * @since __BUMP_VERSION__
	 */
	public function createThumbImageFile($thumbPathFileName = '', $memImage = null)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$IsImageCreated = false;
		$IsImageLocal = false;

		try
		{
			if ($Rsg2DebugActive)
			{
				Log::add('==>start createThumbImageFile: "' . $thumbPathFileName . '"');
			}

			/** not prepared *
			// Create memory image if not given
			//if ($memImage == null)
			if (empty ($memImage))
			{
				$IsImageLocal = True;
				$memImage = new image ($imgSrcPath);
			}
			/**/

			// Make sure the resource handle is valid.
			if (!$memImage->isLoaded())
			{
				throw new \LogicException('createThumbImageFile: No valid image was loaded.');
			}

			//---- target size -------------------------------------
            
			$thumbSize = $rsgConfig->get('thumb_size');

			// Make sure the target width is given thumb_size
			// size not in config
			//if ( ! $thumbSize)
			if (empty($thumbSize))
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing createThumbImageFile: No value given for "Thumb Size"  in configuration';

				throw new \LogicException($OutTxt);
			}

			// source sizes
			$imgHeight = $memImage->getHeight();
			$imgWidth  = $memImage->getWidth();

			// ToDo: Use thumb styles from Joomla image
			// toDo: align thumb styles to the ones in joomla image.lib
			// 0->PROPORTIONAL 1->SQUARE
			$thumbStyle = $rsgConfig->get('thumb_style');

			// ToDo: use joomla image.lib dimensions instead
			// Is thumb style square // ToDo: Thumb style -> enum  // ToDo: general: Config enums
			$width = $thumbSize;
			$height = $thumbSize;

			// ToDo: ? crop (above midle left right and two ...)
			if ($thumbStyle == ImageFileModel::THUMB_PORTRAIT)
			{
				// ??? $thumbSize should be max ????
				if ($imgWidth > $imgHeight)
				{
					// landscape
					$height = ($thumbSize / $imgWidth) * $imgHeight;
				}
				else
				{
					// portrait or square
					$width  = ($thumbSize / $imgHeight) * $imgWidth;
				}
			}

			//--- Create thumb and save directly -----------------------------------

			//$thumbSizes = array ('250x100');
			$thumbSizes = array ($width . 'x' . $height);

			$creationMethod = image::SCALE_INSIDE;

			// generateThumbs successfully ?
			if ($thumbs = $memImage->generateThumbs($thumbSizes, $creationMethod))
			{
				// Parent image properties
//				$imgProperties = Image::getImageFileProperties($imgSrcPath);
//				$imgProperties = $imgSrcPath);

				foreach ($thumbs as $thumb)
				{
					if ($thumb->toFile($thumbPathFileName))
					{
						$IsImageCreated = True;
					}
				}
			}

			/** see above *
			// Release memory if created locally
			if ($IsImageLocal)
			{
				if (!empty($IsImageCreated))
				{
					$IsImageCreated = True;
				}
				$memImage->destroy();
			}
			/**/
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing createThumbImageFile for image name: "' . $thumbPathFileName . '" size: ' . $thumbSize . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				Log::add($OutTxt);
			}
		}

		if ($Rsg2DebugActive)
		{
			Log::add('<== Exit createThumbImageFile: ' . (($IsImageCreated) ? 'true' : 'false'));
		}

		return $IsImageCreated;
	}


    // ToDo: add gallery ID as parameter for sub folder or sub folder itself ...
	/**
	 * @param string $srcFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return bool success
	 *
	 * @since __BUMP_VERSION__
	 * @throws Exception
	 *
    public function copyFile2OriginalDir($srcFileName, $singleFileName, $galleryId)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $isCopied = false;

        try
        {
	        $dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/'  .  $singleFileName;

            if ($Rsg2DebugActive)
            {
                Log::add('==> start copyFile2OrignalDir: "' . $dstFileName . '"');
            }

            $isCopied = File::copy($srcFileName, $dstFileName);
	        if ($isCopied)
	        {
		        // int fileowner ( string $filename )
		        //$user = get_current_user();
		        //chown($dstFileName, $user);
		        JPath::setPermissions($dstFileName, '0644');
	        }
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'copyFile2OrignalDir: "' . $srcFileName . '" -> "' . $dstFileName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive)
            {
                Log::add($OutTxt);
            }
        }

	    if ($Rsg2DebugActive)
	    {
		    Log::add('<== Exit copyFile2OrignalDir: ' . (($isCopied) ? 'true' : 'false'));
	    }

	    return $isCopied;
    }

    // create watermark -> watermark has separate class


	/**
	 * Deletes all children of given file name of RSGallery image item
	 * (original, display, thumb and watermarked representation)
	 *
	 * @param string $imageName Base filename for images to be deleted
	 * @return bool True on success
	 *
	 * @since __BUMP_VERSION__
	 * @throws Exception
	 */
	public function deleteImgItemImages($imageName)
	{
		global $rsgConfig, $Rsg2DebugActive;

		$IsImagesDeleted = false;

// 					$originalFileName = PathHelper::join($imagePaths->originalBasePath, $targetFileName);
		try
		{
			$IsImagesDeleted = true;

			if ($Rsg2DebugActive)
			{
				Log::add('==> start deleteImgItemImages: "' . $imageName .'"');
			}

			// Delete existing images
			$imgPath        = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $imageName;
			$IsImageDeleted = $this->DeleteImage($imgPath);
			if (!$IsImageDeleted)
			{
				$IsImagesDeleted = false;
			}

			$imgPath        = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $imageName . '.jpg';
			$IsImageDeleted = $this->DeleteImage($imgPath);
			if (!$IsImageDeleted)
			{
				$IsImagesDeleted = false;
			}

			$imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . $imageName . '.jpg';;
			$IsImageDeleted = $this->DeleteImage($imgPath);
			if (!$IsImageDeleted)
			{
				$IsImagesDeleted = false;
			}


			// destination  path file name
			$watermarkFilename = ImgWatermarkNames::createWatermarkedPathFileName($imageName, 'original');
			$IsWatermarkDeleted = $this->DeleteImage($watermarkFilename);
			if (!$IsWatermarkDeleted)
			{
				$watermarkFilename = ImgWatermarkNames::createWatermarkedPathFileName($imageName, 'display');
				$IsWatermarkDeleted = $this->DeleteImage($watermarkFilename);
				if (!$IsWatermarkDeleted)
				{

				}
			}

            // Delete filename like original0817254a99efa36171c98a96a81c7214.jpg
            $imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . '/' . $imageName;
            $IsImageDeleted = $this->DeleteImage($imgPath);
            if (!$IsImageDeleted)
            {
                // $IsImagesDeleted = false;
            }
        }
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing deleteRowItemImages: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		if ($Rsg2DebugActive)
		{
			Log::add('<== Exit deleteImgItemImages: ' . (($IsImagesDeleted) ? 'true' : 'false'));
		}

		return $IsImagesDeleted;
	}

	/**
	 * Delete given file
	 * @param string $filename
	 *
	 * @return bool True on success
	 *
	 * @since __BUMP_VERSION__
	 */
	private function DeleteImage($filename='')
	{
		global $Rsg2DebugActive;

		$IsImageDeleted = true;

		try
		{
			if (file_exists($filename))
			{
				$IsImageDeleted = unlink($filename);
			}
			else
			{
				// it is not existing so it may be true
				$IsImageDeleted = true;
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing DeleteImage for image name: "' . $filename . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				Log::add($OutTxt);
			}

		}

		return $IsImageDeleted;
	}

	/**
	 * Moves the file to rsg...Original path and creates all RSG2 images
	 *
	 * @param string $srcTempPathFileName Origin path file name
	 * @param string $targetFileName      Destination base file name
	 * @param int    $galleryId           May be used in destination path
	 *
	 * @return array ($isMoved, $urlThumbFile, $msg) Tells about success, the URL to the thumb file and a message on error
	 *
	 * @throws Exception
	 *@since __BUMP_VERSION__
	 */
	public function MoveImageAndCreateRSG2Images($srcTempPathFileName, $targetFileName, $galleryId,
	                                             $uploadOrigin): array
	{
		global $rsgConfig, $Rsg2DebugActive;

		if ($Rsg2DebugActive)
		{
			Log::add('==>Start MoveImageAndCreateRSG2Images: (ImageFile)');
			Log::add('    $srcTempPathFileName: "' . $srcTempPathFileName . '"');
			Log::add('    $targetFileName: "' . $targetFileName . '"');
		}

//		if (false) {
		$urlThumbFile = '';
		$isCreated = false; // successful images
		$msg = '';

		try {

			//--- destination image paths ---------------------------------------------------

			// ToDo: J3x style paths -> other class ? // , $isJ3xStylePaths = false or own path class similar
			// ToDo: ask gallery for old style and use it in imagePaths

			$this->imagePaths =
			$imagePaths = new ImagePaths ($galleryId);
			$imagePaths->createAllPaths();

            $isUsePath_Original = $imagePaths->isUsePath_Original;

			//--- create files ---------------------------------------------------

			$isCreated = $this->CreateRSG2Images($imagePaths, $srcTempPathFileName, $targetFileName);
			$urlThumbFile = $imagePaths->getThumbUrl($targetFileName);

			if ($isCreated)
			{
				if ($isUsePath_Original)
				{
					$originalFileName = PathHelper::join($imagePaths->originalBasePath, $targetFileName);
					// Move of file on upload and not on ftp folder on server
					if($uploadOrigin != 'server')
					{
						$isCreated = File::upload($srcTempPathFileName, $originalFileName);
					}
					else
					{
						$isCreated = File::copy($srcTempPathFileName, $originalFileName);
					}
					if ($isCreated)
					{
						Path::setPermissions($originalFileName, '0644');
					}
				}
				else
				{
					// don't delete files on folder upload ToDo: ? config ?
					if ($uploadOrigin != 'server')
					{
						if (File::exists($srcTempPathFileName))
						{
							File::delete($srcTempPathFileName);
						}
					}
				}

				if (!$isCreated)
				{
					// File from other user may exist
					// lead to upload at the end ....
					$msg .= '<br>' . 'Create for file "' . $targetFileName . '"';
					// 'failed: Other user may have tried to upload with same name at the same moment. Please try again or with different name.';
				}
			}
		}
		catch (\RuntimeException $e)
		{
			if ($Rsg2DebugActive)
			{
				Log::add('MoveImageAndCreateRSG2Images: RuntimeException');
			}

			$OutTxt = '';
			$OutTxt .= 'Error executing MoveImageAndCreateRSG2Images: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		if ($Rsg2DebugActive)
		{
			Log::add('<== Exit MoveImageAndCreateRSG2Images: '
				. (($isCreated) ? 'true' : 'false')
				. ' Msg: ' . $msg);
		}

		return array($isCreated, $urlThumbFile, $msg); // file is moved
	}

	/**
	 * Moves the file to rsg...Original and creates all RSG2 images
	 *
	 * @param string $uploadPathFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return array ($isMoved, $urlThumbFile, $msg) Tells about success, the URL to the thumb file and a message on error
	 *
	 * @since __BUMP_VERSION__
	 * @throws Exception
	 *
	public function CopyImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId)//: array
	{
		global $rsgConfig, $Rsg2DebugActive;

		if ($Rsg2DebugActive)
		{
			Log::add('==>Start CopyImageAndCreateRSG2Images: (Imagefile)');
			Log::add('    $uploadPathFileName: "' . $uploadPathFileName . '"');
			Log::add('    $singleFileName: "' . $singleFileName . '"');
		}

//		if (false) {
		$urlThumbFile = '';
		$isCopied = false; // successful images
		$msg = '';

		try {
			$singlePathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $singleFileName;
			if ($Rsg2DebugActive)
			{
				Log::add('    $singlePathFileName: "' . $singlePathFileName . '"');
				$Empty = empty ($this);
				Log::add('    $Empty: "' . $Empty . '"');
			}

			$isCopied = $this->copyFile2OriginalDir($uploadPathFileName, $singleFileName, $galleryId);

			if (true) {

				if ($isCopied)
				{
					list($isCopied, $urlThumbFile, $msg) = $this->CreateRSG2Images($singleFileName, $galleryId);
				}
				else
				{
					// File from other user may exist
					// lead to upload at the end ....
					$msg .= '<br>' . 'Move for file "' . $singleFileName . '" failed: Other user may have tried to upload with same name at the same moment. Please try again or with different name.';
				}
			}
		}
		catch (\RuntimeException $e)
		{
			if ($Rsg2DebugActive)
			{
				Log::add('CopyImageAndCreateRSG2Images: RuntimeException');
			}

			$OutTxt = '';
			$OutTxt .= 'Error executing CopyImageAndCreateRSG2Images: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		if ($Rsg2DebugActive)
		{
			Log::add('<== Exit CopyImageAndMoveImageAndCreateRSG2Images: '
				. (($isCopied) ? 'true' : 'false')
				. ' Msg: ' . $msg);
		}

		return array($isCopied, $urlThumbFile, $msg); // file is moved
	}

	/**
	 * Delegates the creation of display, thumb and watermark images
	 *
	 * @param string $uploadPathFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return array ($isMoved, $msg) Tells about success, the URL to the thumb file and a message on error
	 *
	 * @since __BUMP_VERSION__
	 * @throws Exception
	 */
	public function CreateRSG2Images(ImagePaths $imagePaths, $srcFileName='', $targetFileName='')//: array
	{
		global $rsgConfig, $Rsg2DebugActive;

		$msg          = ''; // ToDo: Raise (throw) errors instead

		if ($Rsg2DebugActive)
		{
			Log::add('==>Start CreateRSG2Images: ' . $targetFileName);
		}


		$isCreated = false; // successful images

		// ToDo: try ... catch

		// source file exists
		if (File::exists($srcFileName))
		{
			//--- Create thumb files ----------------------------------

			// Create memory image
			$memImage = new image ($srcFileName);

			$srcWidth  = $memImage->getWidth();
			$srcHeight = $memImage->getHeight();

			$isCreated = $this->createThumbImageFile($imagePaths->getThumbPath($targetFileName), $memImage);

			// ? changed toDo: check and remove
			$afterWidth  = $memImage->getWidth();
			$afterHeight = $memImage->getHeight();

			$memImage->destroy ();

			//--- Create display files ----------------------------------

			if ($isCreated)
			{
				// toDo: ajax: update state thumb created

				foreach ($imagePaths->imageSizes as $imageSize)
				{
					$memImage = new image ($srcFileName);

					$isCreated = false;
					try
					{
					$isCreated = $this->createDisplayImageFile($imagePaths->getSizePath($imageSize, $targetFileName), $imageSize, $memImage);

					$afterWidth  = $memImage->getWidth();
					$afterHeight = $memImage->getHeight();
					/**
					if ($srcWidth != $afterWidth || $srcHeight != $afterHeight) {
						$memImage->destroy();
					} else {
						$memImage->destroy();
					}
					/**/
					}
					catch (\RuntimeException $e)
					{
						$memImage->destroy ();
						throw $e;
					}

					if (!$isCreated)
					{
						break;
					}
				}
			}


			/**  ToDo: watermark file $isWatermarkActive *
			//--- Create watermark file ----------------------------------
			if ( $isCreated) {

				$isWatermarkActive = $rsgConfig->get('watermark');
				if (!empty($isWatermarkActive))
				{
					//$modelWatermark = $this->getModel('ImgWaterMark');
					$modelWatermark = $this->getInstance('imgwatermark', 'RSGallery2Model');

					$isCreated = $modelWatermark->createMarkedFromBaseName(basename($srcFileName), 'original');
					if (!$isCreated)
					{
						//
						$msg .= '<br>' . 'Create Watermark File for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
					}
				}
				else
				{
					// successful transfer
					$isCreated = true;
				}
			}
			/**/
		}
		else
		{
			$OutTxt = ''; // ToDo: Raise (throw) errors instead
			$OutTxt .= 'CreateRSG2Images Error. Could not find original file: "' . $srcFileName . '"';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				Log::add($OutTxt);
			}
		}

		if ($Rsg2DebugActive)
		{
			Log::add('<== Exit CreateRSG2Images: '
				. (($isCreated) ? 'true' : 'false'));
		}

		return $isCreated; // files are created
	}

	/**
	 * Selects all recognised images names in given folder
	 * All other file names will be returned in the ignores list
	 *
	 * @param $extractDir folder with sub folders and images
	 *
	 * @return array  List of valid image files and List of ignored files (directories do npt count)
	 *
	 * @since __BUMP_VERSION__
	 */
	public function SelectImagesFromFolder ($extractDir)//: array
	{
		global $rsgConfig; //, $Rsg2DebugActive;

		//--- Read (all) files from directory ------------------

		// $folderFiles = Folder::files($ftpPath, '');
		// $tree = Folder::listFolderTree($extractDir);
		$recurse = true;
		$fullPath = true;
		//$folderFiles = Folder::files($extractDir, $filter = '.', $recurse, $fullPath);
		$folderFiles = Folder::files($extractDir, $filter = '.', $recurse, $fullPath);

		//--- Allowed file types ------------------

		// wrong: $this->allowedFiles = array('jpg', 'gif', 'png', 'avi', 'flv', 'mpg');
		// $imageTypes   = explode(',', $params->get('image_formats'));

		// ToDo: remove "allowed files" from config
		// Use all files which are identified as images
		// $allowedTypes = strtolower($rsgConfig->allowedFileTypes);
		// $allowedTypes = explode(',', strtolower($rsgConfig->allowedFileTypes));

		//--- select images ------------------

		$files = array ();
		$ignored = array ();

		try
		{
			foreach ($folderFiles as $file)
			{
				// ignore folders
				if (is_dir($file))
				{
					continue;
				}
				else
				{
					//--- File information ----------------------

					// ToDo: Mime type check

					// ToDo: getimagesize() sollte nicht verwendet werden, um zu überprüfen,
					// ToDo: ob eine gegebene Datei ein Bild enthält. Statt dessen sollte
					// ToDo: eine für diesen Zweck entwickelte Lösung wie die
					// ToDo: Fileinfo-Extension(finfo_file) verwendet werden

					$img_info = @getimagesize($file);

					// check if file is definitely not an image
					if (empty ($img_info))
					{
						$ignored[] = $file;
					}
					else
					{
						//--- file may be an image -----------------------------

						// $mime   = $img_info['mime']; // mime-type as string for ex. "image/jpeg" etc.

						// ToDo: Check for allowed file types from config
						//if (!in_array(fileHandler::getImageType($ftpPath . $file), $allowedTypes))
						$valid_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);
						if(in_array($img_info[2],  $valid_types))
						{
							//Add filename to list
							$files[] = $file;
						}
						else
						{
							$ignored[] = $file;
						}
					}
				}
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing SelectImagesFromFolder: "' . $file . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return array ($files, $ignored);
	}


	/**
	 * rotate_image rotates the master image by given degrees.
	 * All dependent images will be created anew from the turned image
	 *
	 * @param string $fileName file name of image to be turned
	 * @param int $galleryId May be used in destination path
	 * @param double $angle Angle to turn the image
	 *
	 * @return bool success
	 *
	 * @since __BUMP_VERSION__
	 */
	public function rotate_image($fileName, $galleryId, $angle)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$isRotated = 0;

		try
		{
			//--- image source ------------------------------------------

			$imagePaths = new ImagePaths ($galleryId);

			// $originalFileName
			$imgSrcPath = PathHelper::join($imagePaths->originalBasePath, $fileName);

			// fallback display file
			if ( ! File::exists($imgSrcPath))
			{
				// displayBasePath
				$imgSrcPath = PathHelper::join($imagePaths->displayBasePath, $fileName);
			}

			$memImage = null;

			if (File::exists($imgSrcPath))
			{
				$memImage = new image ($imgSrcPath);
			}

			if ( ! empty ($memImage))
			{
				$type = IMAGETYPE_JPEG;

				//--- rotate and save ------------------

				$memImage->rotate($angle, -1, false);
				$memImage->toFile($imgSrcPath, $type);
				$memImage->destroy();

				$isRotated = $this->CreateRSG2Images($imagePaths, $imgSrcPath, $fileName);
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing rotate_image: "' . $fileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isRotated;
	}

//	/**
//	 * flip_images directs the master image and all dependent images to be flipped in given mode
//	 *
//	 * @param string [] $fileNames list of file names of images to be flipped
//	 * @param int $galleryId May be used in destination path
//	 * @param int $flipMode flip direction horiontal, vertical or both
//	 *
//	 * @return int Number of successful turned images
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	public function flip_images($fileNames, $galleryId, $flipMode)
//	{
//		$ImgCount = 0;
//
//		$msg = "model images: flip_images: " . '<br>';
//
//		foreach ($fileNames as $fileName)
//		{
//			$IsSaved = $this->flip_image($fileName, $galleryId, $flipMode);
//			if ($IsSaved){
//				$ImgCount++;
//			}
//		}
//
//		return $ImgCount;
//	}

	/**
	 * flip_images directs the master image to be flipped in given mode
	 * All dependent images will be created anew from the flipped image
	 *
	 * @param string $fileName File name of image to be flipped
	 * @param int $galleryId May be used in destination path
	 * @param int $flipMode flip direction horiontal, vertical or both
	 *
	 * @return bool success
	 *
	 * @since __BUMP_VERSION__
	 */
	public function flip_image($fileName, $galleryId, $flipMode)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$isFlipped = 0;

		try
		{
			//--- image source ------------------------------------------

            $imagePaths = new ImagePaths ($galleryId);

            // $originalFileName
            $imgSrcPath = PathHelper::join($imagePaths->originalBasePath, $fileName);

            // fallback display file
            if ( ! File::exists($imgSrcPath))
            {
                // displayBasePath
                $imgSrcPath = PathHelper::join($imagePaths->displayBasePath, $fileName);
            }

            $memImage = null;

            if (File::exists($imgSrcPath))
            {
                $memImage = new image ($imgSrcPath);
            }

            if ( ! empty ($memImage))
			{
				$type = IMAGETYPE_JPEG;

				//--- rotate and save ------------------

				$memImage->flip($flipMode, false);
				$memImage->toFile($imgSrcPath, $type);
				$memImage->destroy();

                $isFlipped = $this->CreateRSG2Images($imagePaths, $imgSrcPath, $fileName);
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing flip_image: "' . $fileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isFlipped;
	}

}

