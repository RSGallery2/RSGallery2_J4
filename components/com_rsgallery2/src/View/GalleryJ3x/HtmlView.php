<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\GalleryJ3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;

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

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed   A string if successful, otherwise an Error object.
     */
	public function display($tpl = null)
	{
		
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
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
		$this->user       = Factory::getUser();
        $params =
        $this->params     = $params = $this->state->get('params');
        // ToDo: Why is this necessary ?
//		$this->pagination->setTotal (count($this->items));

        $this->isDebugSite = $params->get('isDebugSite');
        $this->isDevelopSite = $params->get('isDevelop');

		$model = $this->getModel();
		$this->gallery = $model->galleryData($this->galleryId);

		// ToDo: Status of images

        $menuParams =
        $this->menuParams = $this->get('Rsg2MenuParams');

        // overwrite with param items
        $menuParams->merge($this->params);
        $this->params = $menuParams;

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

//   		$state = $this-sState = $this->get('State');
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

		// Check for layout override
		$active = Factory::getApplication()->getMenu()->getActive();

		if (isset($active->query['layout']))
		{
			$this->setLayout($active->query['layout']);
		}



//		$results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->beforeDisplayContent = trim(implode("\n", $results));
//
//		$results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->afterDisplayContent = trim(implode("\n", $results));
//
		return parent::display($tpl);
	}


}
