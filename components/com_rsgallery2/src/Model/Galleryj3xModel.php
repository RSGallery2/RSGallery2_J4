<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Registry\Registry;



/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
     * @since      5.1.0
 */
class Galleryj3xModel extends GalleryModel
{

    /**
     * Add "asInline" Url to each image
     *
     * @param $images
     *
     *
     * @since 4.5.0.0
     */
    public function AddLayoutData($images)
    {
        // ToDo: check for J3x style of gallery (? all in construct ?)
        parent::AddLayoutData($images);

        try {
            foreach ($images as $idx => $image) {
                // One image on the complete page with pagination
                $this->AssignUrlImageAsInline($image, $idx);

                $this->AssignUrlDownloadImage($image);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleryj3xModel: AddLayoutData: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $images;
    }

	/**
	 * Add url for inline layout to image data
	 *
	 * @param $image
	 * @param $idx
	 *
	 *
	 * @throws \Exception
	 * @since  5.1.0
	 */
    public function AssignUrlImageAsInline($image, $idx)
    {
        try {
            $image->UrlGallery_AsInline = ''; // fall back

            if (!empty ($image->gallery_id)) {
                $route = 'index.php?option=com_rsgallery2'
                    . '&view=slidepagej3x'
                    . '&id=' . $image->gallery_id // Todo: use instead: . '&gal_id=' . $image->gallery_id;
                    . '&img_id=' . $image->id // test bad ordering                    . '&start=' . $idx
                ;
            } else {
				// Bad gallery id missing
                $route = 'index.php?option=com_rsgallery2'
                    . '&view=slidepagej3x'
                    . '&img_id=' . $image->id // test bad ordering                    . '&start=' . $idx
                ;
            }

            $image->UrlImageAsInline = Route::_($route, true, 0, true);

            /**/
            // ToDo: watermarked file
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleryj3xModel: AssignUrlImageAsInline: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

	/**
	 * Assign slideshow url to gallery data
	 *
	 * @param $gallery
	 *
	 *
	 * @throws \Exception
	 * @since  5.1.0
	 */
    public function assignSlideshowUrl($gallery)
    {
        try {
            //$gallery->UrlSlideshow = ''; // fall back

            $gallery->UrlSlideshow = Route::_('index.php?option=com_rsgallery2'
                . '&view=slideshowj3x&id=' . $gallery->id,
                true,0,true);

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleryj3xModel: assignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

//    public function getRsg2MenuParams()
//    {
//
//        $menuParams = new Registry();
//
//        try {
//
//            $input = Factory::getApplication()->input;
//
//            $menuParams = new Registry();
//
//            $menuParams->set('gallery_show_title', $input->getBool('gallery_show_title', true));
//            $menuParams->set('gallery_show_description', $input->getBool('gallery_show_description', true));
//            $menuParams->set('gallery_show_slideshow', $input->getBool('gallery_show_slideshow', true));
//            $menuParams->set('displaySearch', $input->getBool('displaySearch', true));
//
//            $menuParams->set('images_column_arrangement', $input->getInt('images_column_arrangement', ''));
//            $menuParams->set('max_columns_in_images_view', $input->getInt('max_columns_in_images_view', ''));
//            $menuParams->set('images_row_arrangement', $input->getInt('images_row_arrangement', ''));
//            $menuParams->set('max_rows_in_images_view', $input->getInt('max_rows_in_images_view', ''));
//            $menuParams->set('max_thumbs_in_images_view', $input->getInt('max_thumbs_in_images_view', ''));
//
//            $menuParams->set('images_show_title', $input->getBool('images_show_title', true));
//            $menuParams->set('images_show_description', $input->getBool('images_show_description', true));
//	        $menuParams->set('displaySearch', $input->getBool('displaySearch', true));
//
//        } catch (\RuntimeException $e) {
//            $OutTxt = '';
//            $OutTxt .= 'GallerysModel: getRsg2MenuParams()' . '<br>';
//            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//            $app = Factory::getApplication();
//            $app->enqueueMessage($OutTxt, 'error');
//        }
//
//        return $menuParams;
//    }

} // class

