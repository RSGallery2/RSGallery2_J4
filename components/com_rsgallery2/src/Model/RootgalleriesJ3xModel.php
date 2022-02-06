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
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Registry\Registry;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagesModel;
use Rsgallery2\Component\Rsgallery2\Site\Model;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class RootgalleriesJ3xModel extends GalleriesModel
{
    /**
    protected $layoutParams = null; // col/row count
	/**/

    /**
     * Add "asInline" Url to each image
     * @param $galleries
     *
     *
     * @since 4.5.0.0
     */
    public function AddLayoutData($galleries)
    {
        // ToDo: check for J3x style of gallery (? all in construct ?)
        parent::AddLayoutData($galleries);

        /**
        try
        {
            foreach ($galleries as $gallery)
            {

                $this->AssignGalleryUrl($gallery);
// Maybe already done
//                $this->AssignUrl_AsInline($gallery);


            }
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: AddLayoutData: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        /**/
        return $galleries;
    }

    public static function AssignSlideshowUrl($gallery)
    {

        try {

            $gallery->UrlSlideshow = ''; // fall back

            //  Factory::getApplication()->getMenu()
            $app = Factory::getApplication();

            $active       = $app->getMenu()->getActive();
            //$currentLink = $active->link;
            $currentLink = $active->route;


            //$urlMenu  = $app->getMenu()->getActive()->link;

            // Link to single gallery in actual menu
            // /joomla3x/index.php/j3x-galleries-overview/gallery/8
/**
            $gallery->UrlSlideshow = Route::_($currentLink
                . '/galleryJ3x/' . $gallery->id . '/slideshow'
//                . '&gid=' . $image->gallery_id
//                . '&iid=' . $gallery->id
//                . '&layout=galleryJ3xAsInline'
                ,true,0,true);
/**/

            $gallery->UrlSlideshow = Route::_('index.php?option=com_rsgallery2&view=slideshowJ3x&gid=' . $gallery->id);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModelJ3x: AssignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }


    /**
     * @param $gallery
     *
     *
     * @since 4.5.0.0
     */
    public static function AssignGalleryUrl($gallery)
    {
        try {

            $gallery->UrlGallery = ''; // fall back

            //  Factory::getApplication()->getMenu()
            $app = Factory::getApplication();

            $active       = $app->getMenu()->getActive();
            //$currentLink = $active->link;
            $currentLink = $active->route;


            //$urlMenu  = $app->getMenu()->getActive()->link;
            /**

            // Link to single gallery in actual menu
            // /joomla3x/index.php/j3x-galleries-overview/gallery/8

            $gallery->UrlGallery = Route::_($currentLink
                . '/galleryJ3x/' . $gallery->id . ''
//                . '&gid=' . $image->gallery_id
//                . '&iid=' . $gallery->id
//                . '&layout=galleryJ3xAsInline'
                ,true,0,true);

            /**/

            $gallery->UrlGallery = Route::_('index.php?option=com_rsgallery2&view=galleryJ3x&gid=' . $gallery->id);


            // ToDo: watermarked file
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModelJ3x: AssignUrl_AsInline: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }


}
