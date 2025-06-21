<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// J3x legacy view => slideshow

namespace Rsgallery2\Component\Rsgallery2\Site\View\Slideshow;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;

use function defined;

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
     * @var    CMSObject
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
     * @var    Pagination
     * @since  3.1
     */
    protected $pagination;

    /**
     * The page parameters
     *
     * @var    Registry|null
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
     * @var    User|null
     * @since  4.0.0
     */
    protected $user = null;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed   A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $app             = Factory::getApplication();
        $input           = Factory::getApplication()->input;
        $this->galleryId = $input->get('id', 0, 'INT');

        /* wrong call but why ? */
        if ($this->galleryId < 2) {
            Factory::getApplication()->enqueueMessage("gallery id is zero or not allowed -> why", 'error');
        }

        // Get some data from the models
        $this->state = $this->get('State');
        $this->state->set('list.limit', 999);

        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $params           =
        $this->params = $this->state->get('params');
        $this->user       = // $user = Factory::getContainer()->get(UserFactoryInterface::class);
        $user = $app->getIdentity();

        $this->isDebugSite   = $params->get('isDebugSite');
        $this->isDevelopSite = $params->get('isDevelop');

        $model         = $this->getModel();
        $this->gallery = $model->galleryData($this->galleryId);

        // ToDo: Status of images


        $this->slides_layout = $params->get('slides_layout');
        //$this->slides_layout = "SlideshowJ3x";
        // Fix wrong / others: 			$menuParams->set('gallery_layout', $input->getBool('gallery_layout', true));
        //$this->slides_layout = ??? $input->getText('slides_layout', $this->slides_layout);

        // Standard Joomla behaviour : Layout use file parallel to default.php layout
        $layoutName = $this->getLayout();

        // Standard Joomla behaviour : Layout use file parallel to default.php layout
        $layout = $input->getWord('layout', 'default');
        if ($layout == 'default') {
            $this->setLayout($this->slides_layout);
        } else {
            $this->setLayout($layout); //     $layoutName = 'SlideshowJ3x.default';
        }


        // test routes
        // use Joomla\CMS\Router\Route;

        // ??? include actual meu item ???


        $route01 = Route::_('index.php?option=com_rsgallery2&view=galleryj3x&id=2');
        $route01 = Route::_('index.php?option=com_rsgallery2&view=galleryj3x&id=2&asSlideshow');
        $route01 = Route::_('index.php?option=com_rsgallery2&view=imagej3x&item=83&asInline');

        // J3x extract
        // http://127.0.0.1/joomla3x/index.php/j3x-single-gallery/1-love-locks/158-dsc-5504
        // http://127.0.0.1/joomla3x/index.php/j3x-galleries-overview/8-missing-thumb-imnage
        //
        // http://127.0.0.1/joomla3x//images/rsgallery/original/00071.jpg

        // http://127.0.0.1/joomla3x/index.php/rsg2-slideshow?task=downloadfile&id=86


        // pad the images with more information
        if (!empty($this->items)) {
            // Add image paths, image params ...
            $data = $model->AddLayoutData($this->items);
        }

        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;


// ToDo: more trigger

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
        parent::display($tpl);

        return;
    }


}
