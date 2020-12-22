<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Uri\Uri;

\defined('_JEXEC') or die;


class ImagePaths {
	// from config
	public $rsgImagesBasePath;

	// includes galleryid
	public $galleryRoot;

	// files gallery defined
	public $originalBasePath;
	public $thumbBasePath;
	public $sizeBasePaths; // 800x6000, ..., ? display:J3x
	//	ToDo: watermark ...

    public $imageSizes;
    // Original folder may not be needed (see config)
    public $isUsePath_Original;

    // URIs gallery defined
	protected $galleryRootUrl;
	protected $originalUrl;
	protected $thumbUrl;
	protected $sizeUrls;

	protected $rsgConfig;

    // ToDo: watermarked path
    // ToDo: original path of gallery  may be somewhere else (defined in gallery ... )
    //
    // ToDo: J3x and origpath from gallery itself
    //
    // toDo: image size to path when upload ...
	// root of images, image sizes from configuration build the paths
	public function __construct($galleryId = 0) {
		global $rsgConfig;

		try
		{
			// activate config
			if (!$rsgConfig)
			{
				$rsgConfig = ComponentHelper::getParams('com_rsgallery2');
			}

			//--- config root path --------------------------------------------

			$this->rsgImagesBasePath = $rsgConfig->get('imgPath_root');

			// Fall back
			if (empty ($this->rsgImagesBasePath))
			{
				$this->rsgImagesBasePath = "images/rsgallery2";
			}

			//--- config image sizes --------------------------------------------

			$imageSizesText   = $rsgConfig->get('image_width');
			$imageSizes       = explode(',', $imageSizesText);
			$this->imageSizes = $imageSizes;

            //--- user may keep original image --------------------------------------------

            $this->isUsePath_Original = $rsgConfig->get('keepOriginalImage');

            //--- prepare path / URI names ------------------------------------------

            // file paths and URIs derived by gallery ID
            $this->setPathsURIs_byGalleryId($galleryId);

        }
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'ImagePaths: Error executing __construct: <br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}
	}

    /**
     * @param int $galleryId
     * @param array $imageSizes
     *
     *
     * @since __BUMP_VERSION__
     */
    public function setPathsURIs_byGalleryId(int $galleryId): void
    {
        /*--------------------------------------------------------------------
        File paths
        --------------------------------------------------------------------*/

        //--- paths gallery based  --------------------------------------------

        $this->galleryRoot = $this->path_join(JPATH_ROOT, $this->rsgImagesBasePath, $galleryId);

        $this->originalBasePath = $this->path_join($this->galleryRoot, 'original');
        $this->thumbBasePath = $this->path_join($this->galleryRoot, 'thumbs');

        //--- paths for image sizes locations ---------------------------------------

        foreach ($this->imageSizes as $imageSize) {
            $this->sizeBasePaths[$imageSize] = $this->path_join($this->galleryRoot, $imageSize);
        }

        /*--------------------------------------------------------------------
        URIs
        --------------------------------------------------------------------*/

        //---  URIs gallery based --------------------------------------------

        $this->galleryRootUrl = Uri::root() . '/' . $this->rsgImagesBasePath . '/' . $galleryId;

        $this->originalUrl = $this->galleryRootUrl . '/original';
        $this->thumbUrl = $this->galleryRootUrl . '/thumbs';

        //--- URIs for image sizes locations ---------------------------------------

        foreach ($this->imageSizes as $imageSize) {
            $this->sizeUrls[$imageSize] = $this->galleryRootUrl . '/' . $imageSize;
        }
    }

    /*--------------------------------------------------------------------
    File paths
    --------------------------------------------------------------------*/

	public function getOriginalPath ($fileName=''){
		return $this->path_join ($this->originalBasePath, $fileName);
	}
	public function getThumbPath ($fileName=''){
		return $this->path_join ($this->thumbBasePath, $fileName);
	}
	public function getSizePath ($imageSize, $fileName=''){
		return $this->path_join ($this->sizeBasePaths [$imageSize], $fileName);
	}

	/*--------------------------------------------------------------------
	URIs
	--------------------------------------------------------------------*/

	public function getOriginalUrl ($fileName=''){
		return $this->originalUrl . '/' . $fileName;
	}
	public function getThumbUrl ($fileName=''){
		return $this->thumbUrl . '/' . $fileName;
	}
	public function getSizeUrl ($imageSize, $fileName=''){
		return $this->sizeUrls [$imageSize] . '/' . $fileName;
	}

	/**
	 *
	 * @return bool
	 *
	 * @since __BUMP_VERSION__
	 */
	public function createAllPaths() {
		$isCreated = false;

		try
		{
			$isCreated = Folder::create($this->galleryRoot);
			if ($isCreated)
			{
				// Original images will be kept
				if ($this->isUsePath_Original)
				{
					$isCreated = $isCreated & Folder::create($this->originalBasePath);
				}

				$isCreated = $isCreated & Folder::create($this->thumbBasePath);

				foreach ($this->sizeBasePaths as $sizePath)
				{
					$isCreated = $isCreated & Folder::create($sizePath);
				}
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'ImagePaths: Error executing createAllPaths: <br>';
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
    public function isPathsExisting() {
        $isPathsExisting = false;

        try
        {

            $isPathsExisting = is_dir($this->galleryRoot);
            if ($isPathsExisting)
            {
                // Original images will be kept
                if ($this->isUsePath_Original)
                {
                    $isPathsExisting = $isPathsExisting & is_dir($this->originalBasePath);
                }

                $isPathsExisting = $isPathsExisting & is_dir($this->thumbBasePath);

                foreach ($this->sizeBasePaths as $sizePath)
                {
                    $isPathsExisting = $isPathsExisting & is_dir($sizePath);
                }
            }
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'ImagePaths: Error executing isPathsExisting: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isPathsExisting;
    }

    /**
     * Joins paths for files or url
     * Attention: may not be perfect so check once in a while
     * @return string|string[]|null
     *
     * @since __BUMP_VERSION__
     */
    function path_join() {

        $paths = array();

        foreach (func_get_args() as $arg) {
            if ($arg !== '') { $paths[] = $arg; }
        }

        return preg_replace('#/+#','/',join('/', $paths));
    }

}

/**
 * Merge several parts of URL or filesystem path in one path
 * Examples:
 *  echo merge_paths('stackoverflow.com', 'questions');           // 'stackoverflow.com/questions' (slash added between parts)
 *  echo merge_paths('usr/bin/', '/perl/');                       // 'usr/bin/perl/' (double slashes are removed)
 *  echo merge_paths('en.wikipedia.org/', '/wiki', ' Sega_32X');  // 'en.wikipedia.org/wiki/Sega_32X' (accidental space fixed)
 *  echo merge_paths('etc/apache/', '', '/php');                  // 'etc/apache/php' (empty path element is removed)
 *  echo merge_paths('/', '/webapp/api');                         // '/webapp/api' slash is preserved at the beginnnig
 *  echo merge_paths('http://google.com', '/', '/');              // 'http://google.com/' slash is preserved at the end
/**/



