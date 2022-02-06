<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\RootgalleriesJ3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagesModel;
use Rsgallery2\Component\Rsgallery2\Site\Model\GalleryJ3xModel;

//use Rsgallery2\Component\Rsgallery2\Site\Model\RootgalleriesJ3xModel;

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
     * @since  __BUMP_VERSION__
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

    protected $menuParams; // (object)[];
    protected $galleryId; // (object)[];

    /**
     * Execute and display a template script.
     *
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {

        //--- root galleries --------------------------------------------------

        $input = Factory::getApplication()->input;
//        $this->galleryId = $input->get('gid', 0, 'INT');

        $state = $this->state = $this->get('State');
        $params = $this->params = $state->get('params');

        $this->mergeMenuOptions();

        // ToDo: use for limit  $this->menuParams->galleries_count in
	    $this->state      = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->user = Factory::getUser();
	    $this->params     = $params = $this->state->get('params');
	    // ToDo: Why is this necessary ?
//		$this->pagination->setTotal (count($this->items));

        $this->isDebugSite = boolval($this->params->get('isDebugSite', $input->getBool('isDebugSite')));
        $this->isDevelopSite = boolval($this->params->get('isDevelop', $input->getBool('isDevelop')));

//        $model = $this->getModel();
//        if (!empty($this->items)) {
////            $model->AddLayoutData ($this->items);
//        }

        if (count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;

        //--- random images --------------------------------------------------

        $this->randomImages = ImagesModel::randomImages($this->menuParams->random_count);
        if (!empty($this->randomImages)) {
            GalleryJ3xModel::AddLayoutData($this->randomImages);
        }
        /**/

        //--- latest images --------------------------------------------------

        $this->latestImages = ImagesModel::latestImages($this->menuParams->latest_count);
        /**/
        if (!empty($this->latestImages)) {
            GalleryJ3xModel::AddLayoutData($this->latestImages);
        }
        /**/


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

        $this->menuParams = new \stdClass();
        $this->menuParams = (object)[];
        //$this->menuParams->intro_text = $input->getHTML('intro_text', '');
        $this->menuParams->intro_text = $input->get('intro_text', '', 'RAW'); //ToDo: there should be an other filter
        $this->menuParams->gallery_layout = $input->getString('gallery_layout', '');
        $this->menuParams->galleries_description_side = $input->getInt('galleries_description_side', 0, '');
        $this->menuParams->galleries_count = $input->getBool('galleries_count', 5, 'INT');
        $this->menuParams->latest_count = $input->getInt('latest_count', 5, 'INT');
        $this->menuParams->random_count = $input->getInt('random_count', 5, 'INT');
        $this->menuParams->displaySearch = $input->getBool('displaySearch', true);
        $this->menuParams->displayRandom = $input->getBool('displayRandom', true);
        $this->menuParams->displayLatest = $input->getBool('displayLatest', true);
        $this->menuParams->display_limitbox = $input->getBool('display_limitbox', true);
        $this->menuParams->galleries_show_title = $input->getBool('galleries_show_title', true);
        $this->menuParams->galleries_show_description = $input->getBool('galleries_show_description', true);
        $this->menuParams->galleries_show_owner = $input->getBool('galleries_show_owner', true);
        $this->menuParams->galleries_show_size = $input->getBool('galleries_show_size', true);
        $this->menuParams->galleries_show_date = $input->getBool('galleries_show_date', true);
        $this->menuParams->galleries_show_pre_label = $input->getBool('galleries_show_pre_label', true);
        $this->menuParams->galleries_show_slideshow = $input->getBool('galleries_show_slideshow', true);

    }


}

























