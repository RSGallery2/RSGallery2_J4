<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Image\Image;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Filesystem\Path;
use LogicException;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\PathHelper;

//require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';

// ToDo: own file ImageFilePaths for merge_paths and class ImagePathsModel

/**
 * Handles files of images with actions like
 * Creating Thumb, watermarked and turning and flipping of images
 *
     * @since      5.1.0
 */
class ImageFileModel extends BaseDatabaseModel // AdminModel
{
    public const THUMB_PORTRAIT = 0;
    public const THUMB_SQUARE = 1;

    /**
     * Constructor.
     *
     * @since 5.1.0     */
    public function __construct()
    {
        global $rsgConfig, $Rsg2DebugActive;

//      parent::__construct($config = []);

        if ($Rsg2DebugActive) {
            Log::add('==>Start __construct ImageFile');
        }

        // JComponentHelper::getParams();
        // $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        //
        $rsgConfig = ComponentHelper::getParams('com_rsgallery2');

        parent::__construct([]);
    }

    /**
     * Creates a display image with size from config
     * If memory of image not given it creates and destroys the created image
     *
     * @param   string  $originalFileName  includes path (May be a different path then the original)
     * @param   image   $memImage
     *
     * @return image|bool|null if successful returns resized image handler
     *
     * @throws \Exception
     * @since  5.1.0     */
    public function createDisplayImageFile($targetFileName = '', $targetWidth = 0, $memImage = null)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $IsImageCreated = false;
        $IsImageLocal   = false;

        try {
            if ($Rsg2DebugActive) {
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
            if (!$memImage->isLoaded()) {
                throw new \LogicException('createDisplayImageFile: No valid image was loaded.');
            }

            // Make sure the target width is given
            if (!$targetWidth) {
                throw new \LogicException('createDisplayImageFile: Wrong target size');
            }

            //---- target size -------------------------------------

            // source sizes
            $imgHeight = $memImage->getHeight();
            $imgWidth  = $memImage->getWidth();

            $width  = $targetWidth;
            $height = $targetWidth;

            if ($imgWidth > $imgHeight) {
                // landscape
                $height = ($targetWidth / $imgWidth) * $imgHeight;
            } else {
                // portrait or square
                $width = ($targetWidth / $imgHeight) * $imgWidth;
            }

            //--- Resize and save -----------------------------------

            $IsImageCreated = $memImage->resize($width, $height, false, image::SCALE_INSIDE);
            if (!empty($IsImageCreated)) {
                //--- Resize and save -----------------------------------
                $type           = IMAGETYPE_JPEG;
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
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing createDisplayImageFile for image name: "' . $targetFileName . '" size: ' . $targetWidth . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive) {
                Log::add($OutTxt);
            }
        }

        if ($Rsg2DebugActive) {
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
     * @param   string  $originalFileName  includes path (May be a different path then the original)
     *
     * @param   image   $memImage
     *
     * @return bool true if successful
     *
     * @throws \Exception
     * @since  5.1.0     */
    public function createThumbImageFile($thumbPathFileName = '', $memImage = null)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $IsImageCreated = false;
        $IsImageLocal   = false;

        try {
            if ($Rsg2DebugActive) {
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
            if (!$memImage->isLoaded()) {
                throw new LogicException('createThumbImageFile: No valid image was loaded.');
            }

            //---- target size -------------------------------------

            $thumbSize = $rsgConfig->get('thumb_size');

            // Make sure the target width is given thumb_size
            // size not in config
            //if ( ! $thumbSize)
            if (empty($thumbSize)) {
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
            $width  = $thumbSize;
            $height = $thumbSize;

            // ToDo: ? crop (above midle left right and two ...)
            if ($thumbStyle == ImageFileModel::THUMB_PORTRAIT) {
                // ??? $thumbSize should be max ????
                if ($imgWidth > $imgHeight) {
                    // landscape
                    $height = ($thumbSize / $imgWidth) * $imgHeight;
                } else {
                    // portrait or square
                    $width = ($thumbSize / $imgHeight) * $imgWidth;
                }
            }

            //--- Create thumb and save directly -----------------------------------

            //$thumbSizes = array ('250x100');
            $thumbSizes = [$width . 'x' . $height];

            $creationMethod = image::SCALE_INSIDE;

            // generateThumbs successfully ?
            if ($thumbs = $memImage->generateThumbs($thumbSizes, $creationMethod)) {
                // Parent image properties
//              $imgProperties = Image::getImageFileProperties($imgSrcPath);
//              $imgProperties = $imgSrcPath);

                foreach ($thumbs as $thumb) {
                    if ($thumb->toFile($thumbPathFileName)) {
                        $IsImageCreated = true;
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
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing createThumbImageFile for image name: "' . $thumbPathFileName . '" size: ' . $thumbSize . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive) {
                Log::add($OutTxt);
            }
        }

        if ($Rsg2DebugActive) {
            Log::add('<== Exit createThumbImageFile: ' . (($IsImageCreated) ? 'true' : 'false'));
        }

        return $IsImageCreated;
    }


    // ToDo: add gallery ID as parameter for sub folder or sub folder itself ...
    /**
     * @param   string  $srcFileName     Origin path file name
     * @param   string  $singleFileName  Destination base file name
     * @param   int     $galleryId       May be used in destination path
     *
     * @return array
     *
     * @throws \Exception
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
    /**/
    // create watermark -> watermark has separate class

    // ToDo: The sizes may be defined (overwritten) in the gallery or image data (override) a) create gallery b) Upload image c) handling later

    public function allFilePathsOf($imageFileName, $galleryId, $use_j3x_location)
    {
        $imagePathFileNames = [];

        // J4x ?
        if (!$use_j3x_location) {
            $imagePaths = new ImagePathsModel($galleryId);

            //--- expected images of gallery -------------------------------------------------

            //$originalFileName
            $imagePathFileNames [] = $imagePaths->getOriginalPath($imageFileName);
            // $thumbFileName
            $imagePathFileNames [] = $imagePaths->getThumbPath($imageFileName);
            // $displayFileName
            $imagePathFileNames [] = $imagePaths->getDisplayPath($imageFileName);

            // $sizeFileName
            foreach ($imagePaths->imageSizes as $imageSize) {
                $imagePathFileNames [] = $imagePaths->getSizePath($imageSize, $imageFileName);
            }
        } else {
            // J3x

            $ImagePathJ3x = new ImagePathsJ3xModel();

            //--- expected images of gallery -------------------------------------------------

            //$originalFileName
            $imagePathFileNames [] = $ImagePathJ3x->getOriginalPath($imageFileName);
            // $thumbFileName
            $imagePathFileNames [] = $ImagePathJ3x->getThumbPath($imageFileName);
            // $displayFileName
            $imagePathFileNames [] = $ImagePathJ3x->getDisplayPath($imageFileName);
            // $sizeFileName
        }

        return $imagePathFileNames;
    }

    /**
     * Deletes all children of given file name of RSGallery image item
     * (original, display, thumb and watermarked representation)
     *
     * @param   string  $imageFileName  Base filename for images to be deleted
     *
     * @return array
     *
     * @throws \Exception
     * @since  5.1.0     */

    /**/
    public function deleteImgItemImages($imageFileName, $galleryId, $use_j3x_location)
    {
        global $rsgConfig, $Rsg2DebugActive;

        $deletedCount = 0;
        $failedCount  = 0;

//                  $originalFileName = PathHelper::join($imagePaths->originalBasePath, $targetFileName);
        try {
            $IsImagesDeleted = false;

            if ($Rsg2DebugActive) {
                Log::add('==>Start deleteImgItemImages: (' . $imageFileName . ' gid:' . $galleryId . ')');
            }

            //--- destination image paths ---------------------------------------------------

            $imagePathFileNames = $this->allFilePathsOf($imageFileName, $galleryId, $use_j3x_location);

            /**/

            //--- Delete all images --------------------------------------------------

            // try to delete each image, continue on fail
            foreach ($imagePathFileNames as $imageFileName) {
                // Make sure to not delete empty
                //if (strlen($imageFileName) > strlen ($this->???ImagePathsModel->rsgImagesBasePath))
                $isDeleted = File::delete($imageFileName);

                if ($isDeleted) {
                    $deletedCount += 1;
                } else {
                    $failedCount += 1;
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing deleteRowItemImages: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        if ($Rsg2DebugActive) {
            Log::add('<== Exit deleteImgItemImages: $deleted, $failed (' . $deletedCount . '/' . $failedCount . ')');
        }

        return [$deletedCount, $failedCount];
    }
    /**/

    /**
     * Delete given file
     *
     * @param   string  $filename
     *
     * @return bool True on success
     *
     * @since  5.1.0     */
    private function DeleteImage($filename = '')
    {
        global $Rsg2DebugActive;

        $IsImageDeleted = true;

        try {
            if (file_exists($filename)) {
                $IsImageDeleted = unlink($filename);
            } else {
                // it is not existing so it may be true
                $IsImageDeleted = true;
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing DeleteImage for image name: "' . $filename . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive) {
                Log::add($OutTxt);
            }
        }

        return $IsImageDeleted;
    }

    /**
     * Moves the file to rsg...Original path and creates all RSG2 images
     *
     * @param   string  $srcTempPathFileName  Origin path file name
     * @param   string  $targetFileName       Destination base file name
     * @param   int     $galleryId            May be used in destination path
     *
     * @return array ($isMoved, $urlThumbFile, $msg) Tells about success, the URL to the thumb file and a message on error
     *
     * @throws \Exception
     * @since  5.1.0     */
    public function MoveImageAndCreateRSG2Images(
        $srcTempPathFileName,
        $targetFileName,
        $galleryId,
        $uploadOrigin,
        $use_j3x_location,
    ): array {
        global $rsgConfig, $Rsg2DebugActive;

        if ($Rsg2DebugActive) {
            Log::add('==>Start MoveImageAndCreateRSG2Images: (ImageFile)');
            Log::add('    $srcTempPathFileName: "' . $srcTempPathFileName . '"');
            Log::add('    $targetFileName: "' . $targetFileName . '"');
        }

//      if (false) {
        $urlThumbFile = '';
        $isCreated    = false; // successful images
        $msg          = '';

        try {
            //--- destination image paths ---------------------------------------------------

            $isUsePath_Original = $rsgConfig->get('keepOriginalImage');

            if (!$use_j3x_location) {
                $imagePaths = new ImagePathsModel($galleryId);  // ToDo: J3x
                $imagePaths->createAllPaths();

                $urlThumbFile     = $imagePaths->getThumbUrl($targetFileName);
                $originalFileName = PathHelper::join($imagePaths->originalBasePath, $targetFileName);
            } else {
                $imagePathJ3x = new ImagePathsJ3xModel($galleryId);  // ToDo: J3x
                $imagePathJ3x->createAllPaths();

                $urlThumbFile     = $imagePathJ3x->getThumbUrl($targetFileName);
                $originalFileName = PathHelper::join($imagePathJ3x->originalBasePath, $targetFileName);
            }

            //--- create files ---------------------------------------------------

            if (!$use_j3x_location) {
                $isCreated = $this->CreateRSG2Images($imagePaths, $srcTempPathFileName, $targetFileName);
            } else {
                $isCreated = $this->CreateRSG2ImagesJ3x($imagePathJ3x, $srcTempPathFileName, $targetFileName);
            }

            if ($isCreated) {
                if ($isUsePath_Original) {
                    // Move of file on upload and not on ftp folder on server
                    if ($uploadOrigin != 'server' && $uploadOrigin != 'zip') {
                        $isCreated = File::upload($srcTempPathFileName, $originalFileName);
                    } else {
                        $isCreated = File::copy($srcTempPathFileName, $originalFileName);
                    }
                    if ($isCreated) {
                        Path::setPermissions($originalFileName, '0644');
                    }
                } else {
                    // don't delete files on folder upload ToDo: ? config ?
                    if ($uploadOrigin != 'server') {
                        if (file_exists($srcTempPathFileName)) {
                            File::delete($srcTempPathFileName);
                        }
                    }
                }

                if (!$isCreated) {
                    // File from other user may exist
                    // lead to upload at the end ....
                    $msg .= '<br>' . 'Create for file "' . $targetFileName . '"';
                    // 'failed: Other user may have tried to upload with same name at the same moment. Please try again or with different name.';

                    // ToDo: follow up this message + debug message
                }
            }
        } catch (\RuntimeException $e) {
            if ($Rsg2DebugActive) {
                Log::add('MoveImageAndCreateRSG2Images: \RuntimeException');
            }

            $OutTxt = '';
            $OutTxt .= 'Error executing MoveImageAndCreateRSG2Images: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        if ($Rsg2DebugActive) {
            Log::add('<== Exit MoveImageAndCreateRSG2Images: '
                . (($isCreated) ? 'true' : 'false')
                . ' Msg: ' . $msg);
        }

        return [$isCreated, $urlThumbFile, $msg]; // file is moved
    }

    /**
     * Moves the file to rsg...Original and creates all RSG2 images
     *
     * @param   string  $uploadPathFileName  Origin path file name
     * @param   string  $singleFileName      Destination base file name
     * @param   int     $galleryId           May be used in destination path
     *
     * @param   string  $uploadPathFileName  Origin path file name
     * @param   string  $singleFileName      Destination base file name
     * @param   int     $galleryId           May be used in destination path
     *
     * @return array ($isMoved, $urlThumbFile, $msg) Tells about success, the URL to the thumb file and a message on error
     *
     * @return array ($isMoved, $msg) Tells about success, the URL to the thumb file and a message on error
     *
     * @throws \Exception
     *
    public function CopyImageAndCreateRSG2Images( ??? $uploadPathFileName, $singleFileName, $galleryId)//: array
    {
        global $rsgConfig, $Rsg2DebugActive;

        see rotate_image J3/j4 call of create images
        May differ for in image and out image



        if ($Rsg2DebugActive)
        {
            Log::add('==>Start CopyImageAndCreateRSG2Images: (Imagefile)');
            Log::add('    $uploadPathFileName: "' . $uploadPathFileName . '"');
            Log::add('    $singleFileName: "' . $singleFileName . '"');
        }

//      if (false) {
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
                { ? J3x
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
                Log::add('CopyImageAndCreateRSG2Images: \RuntimeException');
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
     * return array($isCopied, $urlThumbFile, $msg); // file is moved
     * }
     *
     * /**
     * Delegates the creation of display, thumb and watermark images
     *
     * @throws \Exception
     * @since  5.1.0     */
    public function CreateRSG2Images(ImagePathsModel $imagePaths, $srcFileName = '', $targetFileName = ''): bool
    {
        global $rsgConfig, $Rsg2DebugActive;

        $msg = ''; // ToDo: Raise (throw) errors instead

        if ($Rsg2DebugActive) {
            Log::add('==>Start CreateRSG2Images: ' . $targetFileName);
        }

        $isCreated = false; // successful images

        // ToDo: try ... catch

        // source file exists
        if (file_exists($srcFileName)) {
            //--- Create thumb files ----------------------------------

            // Create memory image
            $memImage = new image($srcFileName);

            $srcWidth  = $memImage->getWidth();
            $srcHeight = $memImage->getHeight();

            $isCreated = $this->createThumbImageFile($imagePaths->getThumbPath($targetFileName), $memImage);

            // ? changed toDo: check and remove
            $afterWidth  = $memImage->getWidth();
            $afterHeight = $memImage->getHeight();

            $memImage->destroy();

            //--- Create display files ----------------------------------

            if ($isCreated) {
                // toDo: ajax: update state thumb created

                foreach ($imagePaths->imageSizes as $imageSize) {
                    $memImage = new image($srcFileName);

                    $isCreated = false;
                    try {
                        $isCreated = $this->createDisplayImageFile(
                            $imagePaths->getSizePath($imageSize, $targetFileName),
                            $imageSize,
                            $memImage,
                        );

                        $afterWidth  = $memImage->getWidth();
                        $afterHeight = $memImage->getHeight();
                        /**
                        if ($srcWidth != $afterWidth || $srcHeight != $afterHeight) {
                            $memImage->destroy();
                        } else {
                            $memImage->destroy();
                        }
                        /**/
                    } catch (\RuntimeException $e) {
                        $memImage->destroy();
                        throw $e;
                    }

                    if (!$isCreated) {
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
        } else {
            $OutTxt = ''; // ToDo: Raise (throw) errors instead
            $OutTxt .= 'CreateRSG2Images Error: Could not find original file: "' . $srcFileName . '"';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive) {
                Log::add($OutTxt);
            }
        }

        if ($Rsg2DebugActive) {
            Log::add(
                '<== Exit CreateRSG2Images: '
                . (($isCreated) ? 'true' : 'false'),
            );
        }

        return $isCreated; // files are created
    }

    /**
     * Delegates the creation of display, thumb and watermark images
     *
     * @param   string  $uploadPathFileName  Origin path file name
     * @param   string  $singleFileName      Destination base file name
     * @param   int     $galleryId           May be used in destination path
     *
     * @return array ($isMoved, $msg) Tells about success, the URL to the thumb file and a message on error
     *
     * @throws \Exception
     * @since  5.1.0     */
    public function CreateRSG2ImagesJ3x(
        ImagePathsJ3xModel $imagePaths,
        $srcFileName = '',
        $targetFileName = '',
    ): bool {
        global $rsgConfig, $Rsg2DebugActive;

        $msg = ''; // ToDo: Raise (throw) errors instead

        if ($Rsg2DebugActive) {
            Log::add('==>Start CreateRSG2Images J3x: ' . $targetFileName);
        }

        $isCreated = false; // successful images

        // ToDo: try ... catch

        // source file exists
        if (file_exists($srcFileName)) {
            //--- Create thumb files ----------------------------------

            // Create memory image
            $memImage = new image($srcFileName);

            $srcWidth  = $memImage->getWidth();
            $srcHeight = $memImage->getHeight();

            $isCreated = $this->createThumbImageFile($imagePaths->getThumbPath($targetFileName), $memImage);

            // ? changed toDo: check and remove
            $afterWidth  = $memImage->getWidth();
            $afterHeight = $memImage->getHeight();

            $memImage->destroy();

            //--- Create display file ----------------------------------

            if ($isCreated) {
                // toDo: ajax: update state thumb created

                $memImage = new image($srcFileName);

                $isCreated = false;
                try {
                    $imageSize = $rsgConfig->get('image_width'); // j3x value

                    // ToDo: Remove !!!
                    $imageSize = 400;

                    $isCreated = $this->createDisplayImageFile(
                        $imagePaths->getDisplayPath($targetFileName),
                        $imageSize,
                        $memImage,
                    );

                    $afterWidth  = $memImage->getWidth();
                    $afterHeight = $memImage->getHeight();
                    /**
                    if ($srcWidth != $afterWidth || $srcHeight != $afterHeight) {
                        $memImage->destroy();
                    } else {
                        $memImage->destroy();
                    }
                    /**/
                } catch (\RuntimeException $e) {
                    $memImage->destroy();
                    throw $e;
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
        } else {
            $OutTxt = ''; // ToDo: Raise (throw) errors instead
            $OutTxt .= 'CreateRSG2ImagesJ3x Error: Could not find original file: "' . $srcFileName . '"';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive) {
                Log::add($OutTxt);
            }
        }

        if ($Rsg2DebugActive) {
            Log::add('<== Exit CreateRSG2ImagesJ3x: '
                . (($isCreated) ? 'true' : 'false'));
        }

        return $isCreated; // files are created
    }

    /**
     * Selects all recognised images names in given folder
     * All other file names will be returned in the ignores list
     *
     * @param string $extractDir folder with sub folders and images
     *
     * @return array  List of valid image files and List of ignored files (directories do npt count)
     *
     * @since  5.1.0     */
    public function SelectImagesFromFolder($extractDir)//: array
    {
        global $rsgConfig; //, $Rsg2DebugActive;

        //--- Read (all) files from directory ------------------

        // $folderFiles = Folder::files($ftpPath, '');
        // $tree = Folder::listFolderTree($extractDir);
        $recurse  = true;
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

        $files   = [];
        $ignored = [];

        try {
            foreach ($folderFiles as $file) {
                // ignore folders
                if (is_dir($file)) {
                    continue;
                } else {
                    //--- File information ----------------------

                    // ToDo: Mime type check

                    // ToDo: getimagesize() sollte nicht verwendet werden, um zu überprüfen,
                    // ToDo: ob eine gegebene Datei ein Bild enthält. Statt dessen sollte
                    // ToDo: eine für diesen Zweck entwickelte Lösung wie die
                    // ToDo: Fileinfo-Extension(finfo_file) verwendet werden

                    $img_info = @getimagesize($file);

                    // check if file is definitely not an image
                    if (empty($img_info)) {
                        $ignored[] = $file;
                    } else {
                        //--- file may be an image -----------------------------

                        // $mime   = $img_info['mime']; // mime-type as string for ex. "image/jpeg" etc.

                        // ToDo: Check for allowed file types from config
                        //if (!in_array(fileHandler::getImageType($ftpPath . $file), $allowedTypes))
                        $valid_types = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP];
                        if (in_array($img_info[2], $valid_types)) {
                            //Add filename to list
                            $files[] = $file;
                        } else {
                            $ignored[] = $file;
                        }
                    }
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing SelectImagesFromFolder: "' . $file . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return [$files, $ignored];
    }

    /**
     * rotate_image rotates the master image by given degrees.
     * All dependent images will be created anew from the turned image
     *
     * @param   string  $fileName   file name of image to be turned
     * @param   int     $galleryId  May be used in destination path
     * @param   double  $angle      Angle to turn the image
     *
     * @return bool success
     *
     * @since  5.1.0     */
    public function rotate_image($ImageId, $fileName, $galleryId, $angle)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $isRotated = 0;

        try {
            $use_j3x_location = $this->use_j3x_location($ImageId);

            //--- image source ------------------------------------------

            // J4x ?
            if (!$use_j3x_location) {
                $imagePaths   = new ImagePathsModel($galleryId);
                $originalPath = $imagePaths->getOriginalPath($fileName);
                $displayDPath = $imagePaths->getDisplayPath($fileName);
            } else {
                // J3x
                $imagePathJ3x = new ImagePathsJ3xModel();
                $originalPath = $imagePathJ3x->getOriginalPath($fileName);
                $displayDPath = $imagePathJ3x->getDisplayPath($fileName);
            }

            $imgSrcPath = $originalPath;

            // fallback display file
            if (!file_exists($originalPath)) {
                // displayBasePath
                $imgSrcPath = $displayDPath;
            }

            $memImage = null;

            if (file_exists($imgSrcPath)) {
                $memImage = new image($imgSrcPath);
            }

            if (!empty($memImage)) {
                $type = IMAGETYPE_JPEG;

                //--- rotate and save ------------------

                $memImage->rotate($angle, -1, false);
                $memImage->toFile($imgSrcPath, $type);
                $memImage->destroy();

                // J4x ?
                if (!$use_j3x_location) {
                    $isRotated = $this->CreateRSG2Images($imagePaths, $imgSrcPath, $fileName);
                } else {
                    $isRotated = $this->CreateRSG2ImagesJ3x($imagePathJ3x, $imgSrcPath, $fileName);
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing rotate_image: "' . $fileName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isRotated;
    }

    /**
     * flip_images directs the master image to be flipped in given mode
     * All dependent images will be created anew from the flipped image
     *
     * @param   string  $fileName   File name of image to be flipped
     * @param   int     $galleryId  May be used in destination path
     * @param   int     $flipMode   flip direction horiontal, vertical or both
     *
     * @return bool success
     *
     * @since  5.1.0     */
    public function flip_image($ImageId, $fileName, $galleryId, $flipMode)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $isFlipped = 0;

        try {
            $use_j3x_location = $this->use_j3x_location($ImageId);

            //--- image source ------------------------------------------

            // J4x ?
            if (!$use_j3x_location) {
                $imagePaths   = new ImagePathsModel($galleryId);
                $originalPath = $imagePaths->getOriginalPath($fileName);
                $displayPath  = $imagePaths->getDisplayPath($fileName);
            } else {
                // J3x
                $imagePathJ3x = new ImagePathsJ3xModel();
                $originalPath = $imagePathJ3x->getOriginalPath($fileName);
                $displayPath  = $imagePathJ3x->getDisplayPath($fileName);
            }

            $imgSrcPath = $originalPath;

            // fallback display file
            if (!file_exists($originalPath)) {
                // displayBasePath
                $imgSrcPath = $displayPath;
            }

            $memImage = null;

            if (file_exists($imgSrcPath)) {
                $memImage = new image($imgSrcPath);
            }

            if (!empty($memImage)) {
                $type = IMAGETYPE_JPEG;

                //--- flip and save ------------------

                $memImage->flip($flipMode, false);
                $memImage->toFile($imgSrcPath, $type);
                $memImage->destroy();

                // J4x ?
                if (!$use_j3x_location) {
                    $isFlipped = $this->CreateRSG2Images($imagePaths, $imgSrcPath, $fileName);
                } else {
                    $isFlipped = $this->CreateRSG2ImagesJ3x($imagePathJ3x, $imgSrcPath, $fileName);
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing flip_image: "' . $fileName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isFlipped;
    }

    /**
     * ToDo: If needed on other sources move to ImageModel
     * Tells about save of file by J3x or j4x style
     *
     * @param $ImageId
     *
     * @return bool
     *
     * @since  5.1.0
    */
    private function use_j3x_location($ImageId)
    {
        $use_j3x = 0;

        try {
            $db    = $this->getDatabase();
            $query = $db->createQuery()
                ->select($db->quoteName('use_j3x_location'))
                ->from($db->quoteName('#__rsg2_images'))
                ->where($db->quoteName('id') . ' = ' . $db->quote($ImageId));
            $db->setQuery($query);
            $use_j3x = $db->loadResult();
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing use_j3x_location for ImageId: "' . $ImageId . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // $next = $max +1;

        return $use_j3x;
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

            $query = $db->createQuery()
                ->select($db->quoteName(['name', 'gallery_id', 'use_j3x_location']))
                ->from($db->quoteName('#__rsg2_images'))
                ->where($db->quoteName('id') . ' = ' . $db->quote($ImageId));
            $db->setQuery($query);

            $imageDb = $db->loadResult();

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
     * Depending on j3x/j4x type storage of image it retrieves path a nd url of file
     *
     * @param $imageFileName
     * @param $galleryId
     * @param $use_j3x_location
     *
     * @return array paths to 'original' file and url
     *
     * @since  5.1.0
     */
    public function getOriginalPaths($imageFileName, $galleryId, $use_j3x_location)
    {
        $OriginalPathFileName = "";

        // J4x ?
        if (!$use_j3x_location) {
            $imagePaths = new ImagePathsModel($galleryId);

            //---  -------------------------------------------------

            $OriginalPathFileName = $imagePaths->getOriginalPath($imageFileName);
            $OriginalFileNameUri  = $imagePaths->getOriginalUrl($imageFileName);
        } else {
            // J3x

            $ImagePathJ3x = new ImagePathsJ3xModel();

            //---  -------------------------------------------------

            $OriginalPathFileName = $ImagePathJ3x->getOriginalPath($imageFileName);
            $OriginalFileNameUri  = $ImagePathJ3x->getOriginalUrl($imageFileName);
        }

        return [$OriginalPathFileName, $OriginalFileNameUri];
    }

    /**
     * Download a file with copying from temp file to URL ?
     *
     * @param $OriginalFilePath
     * @param $OriginalFileUri
     *
     * @return bool
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function downloadImageFile($OriginalFilePath, $OriginalFileUri)
    {
        $IsDownloaded = false;

        try {
            //--- header ------------------------------------------------

            header("Content-Disposition: attachment; filename=" . basename($OriginalFilePath));
            header("Content-type: " . mime_content_type($OriginalFilePath));

            //--- read file to client ---------------------------------------------

            ob_end_clean();

            readfile($OriginalFileUri);

            ob_flush();

            //--- exit success ------------------------------------------------

            //  tells if successful
            $IsDownloaded = true;
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
