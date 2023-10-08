<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright (c) 2016-2023 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Uri\Uri;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\PathHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\UriHelper;

\defined('_JEXEC') or die;


/**
 * Keeps the file location paths of an image for PHP use and URIs for HTML use
 * The path is kept without filename . It is vaslid for all images in
 * given gallery
 *
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
 * @since       version
 */
class ImagePathsJ3xModel 
{
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

	// ToDo: watermarked path

	// root of images, image sizes from configuration build the paths
    // ToDo: watermarked path
	public function __construct() 
{
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

            $this->originalBasePath = PathHelper::join(JPATH_ROOT, $rsgConfig->get('imgPath_original'));
            $this->displayBasePath = PathHelper::join(JPATH_ROOT, $rsgConfig->get('imgPath_display'));
            $this->thumbBasePath = PathHelper::join(JPATH_ROOT, $rsgConfig->get('imgPath_thumb'));

            /*--------------------------------------------------------------------
            URIs
            --------------------------------------------------------------------*/

//            $this->originalUrl = PathHelper::join(Uri::root(), $this->originalBasePath);
//            $this->displayUrl = PathHelper::join(Uri::root(), $this->displayBasePath);
//            $this->thumbUrl = PathHelper::join(Uri::root(), $this->thumbBasePath);
            $this->originalUrl = UriHelper::join(Uri::root(), $rsgConfig->get('imgPath_original'));
            $this->displayUrl = UriHelper::join(Uri::root(), $rsgConfig->get('imgPath_display'));
            $this->thumbUrl = UriHelper::join(Uri::root(), $rsgConfig->get('imgPath_thumb'));

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

	public function getOriginalPath ($fileName='')
	{
		return PathHelper::join ($this->originalBasePath, $fileName);
	}
	public function getDisplayPath ($fileName='')
	{
		return PathHelper::join ($this->displayBasePath, $fileName . '.jpg');
	}
	public function getThumbPath ($fileName='')
	{
		return PathHelper::join ($this->thumbBasePath, $fileName . '.jpg');
	}

	/*--------------------------------------------------------------------
	URIs
	--------------------------------------------------------------------*/

	public function getOriginalUrl ($fileName='')
	{
		 return UriHelper::join($this->originalUrl, $fileName);
//		return $this->originalUrl . '/' . $fileName;
	}

	public function getDisplayUrl ($fileName='')
	{
		 return UriHelper::join($this->displayUrl, $fileName . '.jpg');
//		return $this->displayUrl . '/' . $fileName . '.jpg';
	}

	public function getThumbUrl ($fileName='')
	{
		 return UriHelper::join($this->thumbUrl, $fileName . '.jpg');
//		return $this->thumbUrl . '/' . $fileName . '.jpg';
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
    public function isPathsExisting() 
	{
        $isPathsExisting = false;

        try
        {
		    $isPathsExisting = is_dir($this->displayBasePath);

			if ($isPathsExisting)
            {
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
