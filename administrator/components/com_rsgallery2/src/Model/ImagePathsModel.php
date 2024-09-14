<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright  (c)  2016-2024 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;
use Joomla\CMS\Uri\Uri;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\PathHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\UriHelper;

\defined('_JEXEC') or die;

/**
 * Keeps the file location paths of an image for PHP use and URIs for HTML use
 * The path is kept without filename. It is valid for all images in
 * given gallery
 *
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
 * @since       version
 */
class ImagePathsModel
{
    // from config
    public $rsgImagesBasePath;
    public $rsgImagesBaseUrl;
    public $rsgImagesGalleriesBasePath; // ToDo: Single gallery name ? used for search path ?

    // includes galleryid
    public $galleryRoot;

    // files gallery defined
    public $originalBasePath;
    public $displayBasePath;
    public $thumbBasePath;
    public $sizeBasePaths; // 800x6000, ..., ? display:J3x
    //	ToDo: watermark ...

    public $imageSizes;
    // Original folder may not be needed (see config)
    public $isUsePath_Original;

    // URIs gallery defined
    protected $galleryRootUrl;
    protected $originalUrl;
    protected $displayUrl;
    protected $thumbUrl;
    protected $sizeUrls;

    protected $rsgConfig;

    // ToDo: watermarked path
    // ToDo: original path of gallery  may be somewhere else (defined in gallery ... )
    //
    // toDo: image size to path when upload ...
    // root of images, image sizes from configuration build the paths
    public function __construct($galleryId = 0)
    {
        global $rsgConfig;

        try {
            // activate config
            if (!$rsgConfig) {
                $rsgConfig = ComponentHelper::getParams('com_rsgallery2');
            }

            //--- config root path --------------------------------------------

            $this->rsgImagesBasePath = $rsgConfig->get('imgPath_root');
            // Fall back
            if (empty ($this->rsgImagesBasePath)) {
                $this->rsgImagesBasePath = "images/rsgallery2";
            }
            $this->rsgImagesBasePath = Path::Clean($this->rsgImagesBasePath);

            $this->rsgImagesGalleriesBasePath = PathHelper::join(JPATH_ROOT, $this->rsgImagesBasePath);

            // remove starting slah or backslash for URL
            if ($this->rsgImagesBasePath[0] == '\\' || $this->rsgImagesBasePath[0] == '/') {
                $this->rsgImagesBaseUrl = substr($this->rsgImagesBasePath, 1);
            } else {
                $this->rsgImagesBaseUrl = $this->rsgImagesBasePath;
            }

            //--- config image sizes --------------------------------------------

            $imageSizesText   = $rsgConfig->get('image_size');
            $imageSizes       = explode(',', $imageSizesText);
            $this->imageSizes = $imageSizes;

            //--- user may keep original image --------------------------------------------

            $this->isUsePath_Original = $rsgConfig->get('keepOriginalImage');

            //--- prepare path / URI names ------------------------------------------

            // file paths and URIs derived by gallery ID
            $this->setPaths_URIs_byGalleryId($galleryId);
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'ImagePathsModel: Error executing __construct: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

    /**
     * @param   int    $galleryId
     * @param   array  $imageSizes
     *
     *
     * @since __BUMP_VERSION__
     */
    public function setPaths_URIs_byGalleryId(int $galleryId): void
    {
        /*--------------------------------------------------------------------
        File paths
        --------------------------------------------------------------------*/

        //--- paths gallery based  --------------------------------------------

        $this->galleryRoot = PathHelper::join(JPATH_ROOT, $this->rsgImagesBasePath, $galleryId);

        $this->originalBasePath = PathHelper::join($this->galleryRoot, 'original');
        $this->thumbBasePath    = PathHelper::join($this->galleryRoot, 'thumbs');

        //--- paths for image sizes locations ---------------------------------------

        foreach ($this->imageSizes as $imageSize) {
            $this->sizeBasePaths[$imageSize] = PathHelper::join($this->galleryRoot, $imageSize);
        }

        // biggest is display
        $this->displayBasePath = $this->sizeBasePaths [$this->imageSizes[0]];

        /*--------------------------------------------------------------------
        URIs
        --------------------------------------------------------------------*/

        //---  URIs gallery based --------------------------------------------


        //$this->galleryRootUrl = UriHelper::join(Uri::root(),  $this->rsgImagesBaseUrl, $galleryId);
        $this->galleryRootUrl = UriHelper::join(Uri::root() . $this->rsgImagesBaseUrl, $galleryId);

        $this->originalUrl = UriHelper::join($this->galleryRootUrl, 'original');
        $this->thumbUrl    = UriHelper::join($this->galleryRootUrl, 'thumbs');

        //--- URIs for image sizes locations ---------------------------------------

        foreach ($this->imageSizes as $imageSize) {
            $this->sizeUrls[$imageSize] = UriHelper::join($this->galleryRootUrl, $imageSize);
        }

        // biggest is display
        $this->displayUrl = $this->sizeUrls[$this->imageSizes[0]];
    }

    /*--------------------------------------------------------------------
    File paths
    --------------------------------------------------------------------*/

    public function getOriginalPath($fileName = '')
    {
        return PathHelper::join($this->originalBasePath, $fileName);
    }

    public function getDisplayPath($fileName = '')
    {
        return PathHelper::join($this->displayBasePath, $fileName);
    }

    public function getThumbPath($fileName = '')
    {
        return PathHelper::join($this->thumbBasePath, $fileName);
    }

    public function getSizePath($imageSize, $fileName = '')
    {
        return PathHelper::join($this->sizeBasePaths [$imageSize], $fileName);
    }

    public function getSizePaths($fileName = '')
    {
        $sizePaths = [];

        foreach ($this->imageSizes as $imageSize) {
            $sizePaths[$imageSize] = PathHelper::join($this->sizeBasePaths[$imageSize], $fileName);
        }

        return $sizePaths;
    }

    /*--------------------------------------------------------------------
    URIs
    --------------------------------------------------------------------*/

    public function getOriginalUrl($fileName = '')
    {
        return UriHelper::join($this->originalUrl, $fileName);
    }

    public function getDisplayUrl($fileName = '')
    {
        return UriHelper::join($this->displayUrl, $fileName);
    }

    public function getThumbUrl($fileName = '')
    {
        return UriHelper::join($this->thumbUrl, $fileName);
    }

    public function getSizeUrl($imageSize, $fileName = '')
    {
        return UriHelper::join($this->sizeUrls [$imageSize], $fileName);
    }

    public function getSizeUrls($fileName = '')
    {
        $sizeUrls = [];

        foreach ($this->imageSizes as $imageSize) {
            $sizeUrls[$imageSize] = UriHelper::join($this->sizeUrls[$imageSize], $fileName);
        }

        return $sizeUrls;
    }

    /**
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public function createAllPaths()
    {
        $isCreated = false;

        try {
            $isCreated = Folder::create($this->galleryRoot);
            if ($isCreated) {
                // Original images will be kept
                if ($this->isUsePath_Original) {
                    $isCreated = $isCreated & Folder::create($this->originalBasePath);
                }

                $isCreated = $isCreated & Folder::create($this->thumbBasePath);

                foreach ($this->sizeBasePaths as $sizePath) {
                    $isCreated = $isCreated & Folder::create($sizePath);
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'ImagePathsModel: Error executing createAllPaths: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isCreated;
    }

    /**
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public function isPathsExisting()
    {
        $isPathsExisting = false;

        try {
            $isPathsExisting = is_dir($this->galleryRoot);

            if ($isPathsExisting) {
                // Original images will be kept
                if ($this->isUsePath_Original) {
                    $isPathsExisting = $isPathsExisting & is_dir($this->originalBasePath);
                }

                $isPathsExisting = $isPathsExisting & is_dir($this->thumbBasePath);

                foreach ($this->sizeBasePaths as $sizePath) {
                    $isPathsExisting = $isPathsExisting & is_dir($sizePath);
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'ImagePathsModel: Error executing isPathsExisting: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isPathsExisting;
    }

}
