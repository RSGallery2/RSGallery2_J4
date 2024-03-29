<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\SlideshowJ3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

/**
 * HTML Rsgallery2 View class for the Rsgallery2 component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The model state
     *
     * @var    \JObject
     * @since  3.1
     */
    protected $state;

    /**
     * The list of tags
     *
     * @var    array|false
     * @since  3.1
     */
    protected $items;

    /**
     * The pagination object
     *
     * @var    \Joomla\CMS\Pagination\Pagination
     * @since  3.1
     */
    protected $pagination;

    /**
     * The page parameters
     *
     * @var    \Joomla\Registry\Registry|null
     * @since  3.1
     */
    protected $params = null;

    /**
     * The page class suffix
     *
     * @var    string
     * @since  4.0.0
     */
    protected $pageclass_sfx = '';

    /**
     * The logged in user
     *
     * @var    \JUser|null
     * @since  4.0.0
     */
    protected $user = null;

    /**
     *
     *
     * @var    string
     * @since  4.0.0
     */
    protected $slides_layout = '';

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed   A string if successful, otherwise an Error object.
     */
	public function display($tpl = null)
	{
        $app = Factory::getApplication();
        $input  = Factory::getApplication()->input;
        $this->galleryId = $input->get('gid', 0, 'INT');

        /* wrong call but why ? */
        if ($this->galleryId < 2)
        {
            Factory::getApplication()->enqueueMessage("gallery id is zero or not allowed -> why", 'error');
        }


        $this->mergeMenuOptions();

        // Get some data from the models
        $this->state      = $this->get('State');
		$this->state->set('list.limit', 999);

        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $params =
        $this->params     = $this->state->get('params');
        $this->user       = // $user = Factory::getContainer()->get(UserFactoryInterface::class);
	    $user = $app->getIdentity();

        $this->isDebugSite = $params->get('isDebugSite'); 
        $this->isDevelopSite = $params->get('isDevelop');

        $model = $this->getModel();
        $this->gallery = $model->galleryData($this->galleryId);



        // ToDo: Status of images


        // ToDo: slides_layout from menu parameters
        $this->slides_layout = "SlideshowJ3x";
        // Fix wrong / others: 			$menuParams->set('gallery_layout', $input->getBool('gallery_layout', true));
        //$this->slides_layout = ??? $input->getText('slides_layout', $this->slides_layout);

        $layoutName = $this->getLayout();

        $layout = $input->getWord('layout', '');
        if (empty($layout)) {
            $this->setLayout($this->slides_layout . ".default");
        } else {
            $this->setLayout ($layout); //     $layoutName = 'SlideshowJ3x.default';
        }

        if ( ! empty($this->items)) {
            // Add image paths, image params ...
            $data = $model->AddLayoutData ($this->items);
        }

        if (count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;

//   		$state = $this->state = $this->get('State');
//		$params = $this->params = $state->get('params');
//		$itemparams = new Registry(json_decode($item->params));
//
//		$temp = clone $params;
//		$temp->merge($itemparams);
//		$item->params = $temp;
//
//		Factory::getApplication()->triggerEvent('onContentPrepare', array ('com_rsgallery2.rsgallery2', &$item));
//
//		// Store the events for later
//		$item->event = new \stdClass;
//		$results = Factory::getApplication()->triggerEvent('onContentAfterTitle', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->afterDisplayTitle = trim(implode("\n", $results));
//






//		$results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->beforeDisplayContent = trim(implode("\n", $results));
//
//		$results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->afterDisplayContent = trim(implode("\n", $results));
//
		return parent::display($tpl);
	}



    public function mergeMenuOptions()
    {
        /**
        $app = Factory::getApplication();

        if ($menu = $app->getMenu()->getActive())
        {
        $menuParams = $menu->getParams();
        }
        else
        {
        $menuParams = new Registry;
        }

        $mergedParams = clone $this->params;
        $mergedParams->merge($menuParams);

        $this->params = $mergedParams;
        /**/

        // gid should be zero ToDo: is this really needed *?
        $input = Factory::getApplication()->input;
        //$this->galleryId = $input->get('gid', 0, 'INT');

        // $this->menuParams = new \stdClass();
        $this->menuParams = (object)[];
//        $this->menuParams->gallery_show_title = $input->getBool('gallery_show_title', true);
//        $this->menuParams->gallery_show_description = $input->getBool('gallery_show_description', true);
//        $this->menuParams->gallery_show_slideshow = $input->getBool('gallery_show_slideshow', true);
//        $this->menuParams->displaySearch = $input->getBool('displaySearch', true);

//        $this->menuParams->images_column_arrangement = $input->getInt('images_column_arrangement', '');
//        $this->menuParams->max_columns_in_images_view= $input->getInt('max_columns_in_images_view', '');
//        $this->menuParams->images_row_arrangement = $input->getInt('images_row_arrangement', '');
//        $this->menuParams->max_rows_in_images_view = $input->getInt('max_rows_in_images_view', '');
//        $this->menuParams->max_thumbs_in_images_view = $input->getInt('max_thumbs_in_images_view', '');
//
//        $this->menuParams->images_show_title = $input->getBool('images_show_title', true);
//        $this->menuParams->images_show_description = $input->getBool('images_show_description', true);

    }

}
