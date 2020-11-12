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


class ImageJ3xPaths {
	// from config

	// files gallery defined
	public $originalBasePath;
    public $displayBasePath;
    public $thumbBasePath;
	//	ToDo: watermark ...

    // Original folder may not be needed (see config)
    public $isUsePath_Original;

    // URIs gallery defined
	protected $originalUrl;
    protected $displayUrl;
	protected $thumbUrl;

	protected $rsgConfig;

	//	ToDo: watermark ...

	// root of images, image sizes from configuration build the paths
    // ToDo: watermarked path
	public function __construct() {
		global $rsgConfig;

		try
		{
			// activate config
			if (!$rsgConfig)
			{
				$rsgConfig = ComponentHelper::getParams('com_rsgallery2');
			}

            //--- user may keep original image --------------------------------------------

            $this->isUsePath_Original = $rsgConfig->get('keepOriginalImage');

            /*--------------------------------------------------------------------
            File paths
            --------------------------------------------------------------------*/

            $this->originalBasePath = path_join(JPATH_ROOT, $rsgConfig->get('imgPath_original'));
            $this->displayBasePath = path_join(JPATH_ROOT, $rsgConfig->get('imgPath_display'));
            $this->thumbBasePath = path_join(JPATH_ROOT, $rsgConfig->get('imgPath_thumb'));

            /*--------------------------------------------------------------------
            URIs
            --------------------------------------------------------------------*/

            $this->originalUrl = path_join(Uri::root(), $this->originalBasePath);
            $this->displayUrl = path_join(Uri::root(), $this->displayBasePath);
            $this->thumbUrl = path_join(Uri::root(), $this->thumbBasePath);

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

	/*--------------------------------------------------------------------
	File paths
	--------------------------------------------------------------------*/

	public function getOriginalPath ($fileName=''){
		return path_join ($this->originalBasePath, $fileName);
	}
	public function getDisplayPath ($fileName=''){
		return path_join ($this->displayBasePath, $fileName . '.jpg');
	}
	public function getThumbPath ($fileName=''){
		return path_join ($this->thumbBasePath, $fileName . '.jpg');
	}

	/*--------------------------------------------------------------------
	URIs
	--------------------------------------------------------------------*/

	public function getOriginalUrl ($fileName=''){
		return $this->originalUrl . '/' . $fileName;
	}
	public function getDisplayUrl ($fileName=''){
		return $this->displayUrl . '/' . $fileName;
	}
	public function getThumbUrl ($fileName=''){
		return $this->thumbUrl . '/' . $fileName;
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
            $isCreated = Folder::create($this->displayBasePath);
            if ($isCreated)
            {
                // Original images will be kept
                if ($this->isUsePath_Original)
                {
                    $isCreated = $isCreated & Folder::create($this->originalBasePath);
                }

                $isCreated = $isCreated & Folder::create($this->thumbBasePath);

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
            {
                $isPathsExisting = is_dir($this->displayBasePath);

                // Original images will be kept
                if ($this->isUsePath_Original)
                {
                    $isPathsExisting = $isPathsExisting & is_dir($this->originalBasePath);
                }

                $isPathsExisting = $isPathsExisting & is_dir($this->thumbBasePath);

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



