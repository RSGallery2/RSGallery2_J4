<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Registry\Registry;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class GalleryJ3xModel extends GalleryModel
{



	/**
	 * Add "asInline" Url to each image
	 * @param $images
	 *
	 *
	 * @since 4.5.0.0
	 */
	public function AddLayoutData($images)
	{
		// ToDo: check for J3x style of gallery (? all in construct ?)
		parent::AddLayoutData($images);

		try
		{
			foreach ($images as $image)
			{
                // One image on the complete page with pagination
                $this->AssignUrlImageAsInline($image);

				$this->AssignUrlDownloadImage($image);

			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'GalleryJ3xModel: AddLayoutData: Error executing query: "' . "" . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $images;
	}

	/**
	 * @param $images
	 *
	 *
	 * @since 4.5.0.0
	 */
	public function AssignUrlImageAsInline($image)
	{

		try {

			$image->UrlGallery_AsInline = ''; // fall back


            $image->UrlImageAsInline = Route::_('index.php?option=com_rsgallery2'
                . '&view=imageJ3x&&item=' . $image->id // . '/asInline'
//				. '&gid=' . $image->gallery_id
//				. '&iid=' . $image->id
                . '&layout=imagesJ3xAsInline'
                ,true,0,true);

			/**/
			// ToDo: watermarked file
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'GalleryJ3xModel: AssignUrlImageAsInline: Error executing query: "' . "" . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

	}

    public function AssignSlideshowUrl($gallery)
    {

        try {

            $gallery->UrlGallery = ''; // fall back

            $gallery->UrlSlideshow = Route::_('index.php?option=com_rsgallery2'
                . '&view=slideshowJ3x&gid=' . $gallery->id
                ,true,0,true);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GallerysModel: AssignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }

    public function AssignUrlDownloadImage($image)
    {

        try {

            $image->Urldownload = ''; // fall back


            $image->UrlDownload = Route::_('index.php?option=com_rsgallery2&&task=downloadfile&id=' . $image->id
                ,true,0,true);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GallerysModel: AssignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }





}

