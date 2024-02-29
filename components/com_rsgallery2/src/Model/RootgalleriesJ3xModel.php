<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
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
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class RootgalleriesJ3xModel extends GalleriesJ3xModel
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

    public function assignSlideshowUrl($gallery)
    {

        try {

            // $gallery->UrlSlideshow = ''; // fall back

            // Link to single gallery in actual menu
            // /joomla3x/index.php/j3x-galleries-overview/gallery/8
/**
            $gallery->UrlSlideshow = Route::_(index.php?option=com_rsgallery2 ....
                . '/galleryJ3x/' . $gallery->id . '/slideshow'
//                . '&gid=' . $image->gallery_id
//                . '&iid=' . $gallery->id
//                . '&layout=galleryJ3xAsInline'
                ,true,0,true);
/**/

            $gallery->UrlSlideshow = Route::_('index.php?option=com_rsgallery2'
                . '&view=slideshowJ3x&gid=' . $gallery->id);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'RootgalleriesJ3xModel: assignSlideshowUrl ()' . '<br>';
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
     *
    public function AssignGalleryUrl($gallery)
    {
        try {

            parent::AssignGalleryUrl($gallery);

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'RootgalleriesJ3xModel: AssignUrl_AsInline: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }
    /**/

    public function randomImages ($random_count)
    {
        $randomImages = [];

        // ToDo: try catch ...

        if ($random_count > 0) {

            // toDo: create imagesJ3x model which does set the url to j3x
            $imagesModel = $this->getInstance('Images', 'RSGallery2Model');
            $randomImages = $imagesModel->randomImages($random_count);

            $galleryJ3xModel = $this->getInstance('GalleryJ3x', 'RSGallery2Model');
            $galleryJ3xModel->AddLayoutData($randomImages);
        }
        return $randomImages;
    }

    public function latestImages ($latest_count)
    {
        $latestImages = [];

        // ToDo: try catch ...

        if ($latest_count > 0) {
            // toDo: create imagesJ3x model which does set the url to j3x
            $imagesModel = $this->getInstance('Images', 'RSGallery2Model');
            $latestImages = $imagesModel->latestImages($latest_count);

            $galleryJ3xModel = $this->getInstance('GalleryJ3x', 'RSGallery2Model');
            $galleryJ3xModel->AddLayoutData($latestImages);
        }

        return $latestImages;
    }

    public function getRsg2MenuParams ()
    {

        // see rootgalleriesJ3x\default.xml

        /*
        displaySearch
        displayRandom
        displayLatest
        intro_text
        menu_show_intro_text
        gallery_layout
        ---
        galleries_count
        display_limitbox
        galleries_show_title
        galleries_show_description
        galleries_show_owner
        galleries_show_size
        galleries_show_date
        galleries_show_pre_label
        galleries_show_slideshow
        galleries_description_side

        latest_count
        random_count
        /**/

	    /* ToDo: whats wrong with */
		$app = Factory::getApplication();
		$menu = $app->getMenu()->getActive() ;
		$itemId = $menu->id;
		$menu_params = $menu->getParams($itemId);
		/**/

	    $menuParams = new Registry();

        try {

            $input = Factory::getApplication()->input;

            $menuParams->set('displaySearch', $input->getBool('displaySearch', true));
            $menuParams->set('displayRandom', $input->getBool('displayRandom', true));
            $menuParams->set('displayLatest', $input->getBool('displayLatest', true));
            //$menuParams->set('intro_text', $input->get('intro_text', 'intro_text', 'HTML'));
            $menuParams->set('intro_text', $input->get('intro_text', '', 'RAW'));
            $menuParams->set('menu_show_intro_text', $input->getBool('menu_show_intro_text', true));
            $menuParams->set('gallery_layout', $input->getString('gallery_layout', true));
            //---
            $menuParams->set('galleries_count', $input->getInt('galleries_count', 5));
            $menuParams->set('display_limitbox', $input->getBool('display_limitbox', true));
            $menuParams->set('galleries_show_title', $input->getBool('galleries_show_title', true));
            $menuParams->set('galleries_show_description', $input->getBool('galleries_show_description', true));
            $menuParams->set('galleries_show_owner', $input->getBool('galleries_show_owner', true));
            $menuParams->set('galleries_show_size', $input->getInt('galleries_show_size', true));
            $menuParams->set('galleries_show_date', $input->getBool('galleries_show_date', true));
            $menuParams->set('galleries_show_pre_label', $input->getBool('galleries_show_pre_label', true));
            $menuParams->set('galleries_show_slideshow', $input->getBool('galleries_show_slideshow', true));
            $menuParams->set('galleries_description_side', $input->getInt('galleries_description_side', 0));

            $menuParams->set('latest_count', $input->getInt('latest_count', 5));
            $menuParams->set('random_count', $input->getInt('random_count', 5));

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'RootgalleriesJ3xModel: getRsg2MenuParams()' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $menuParams;
    }

} // class
