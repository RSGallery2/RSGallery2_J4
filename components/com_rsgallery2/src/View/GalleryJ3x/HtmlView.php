<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// J3x legacy view improved => gallery 

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
        //--- gallery (j4x++ try) --------------------------------------------------

        $app = Factory::getApplication();

        $input  = $app->input;
        $this->galleryId = $input->get('gid', 0, 'INT');

        /* wrong call but why ? gallery should be a number > 0 */
        if ($this->galleryId < 2)
        {
	        Factory::getApplication()->enqueueMessage("gallery id is zero or not allowed -> why does it happen ?", 'error');
        }

		// Get some data from the models
        $state =
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
		$this->user       = // $user = Factory::getContainer()->get(UserFactoryInterface::class);
	    $user = $app->getIdentity();

        $params =
        $this->params = $state->get('params');

        $this->isDebugSite = $params->get('isDebugSite');
        $this->isDevelopSite = $params->get('isDevelop');

		$model = $this->getModel();
		$this->gallery = $model->galleryData($this->galleryId);

		// ToDo: Status of images

		// Merge (overwrite) config parameter with menu parameter
		$menuParams = $this->get('Rsg2MenuParams');
		// wrong: $this->params = $menuParams->merge($this->params);
		$this->params->merge($menuParams);

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

		// on develop show open tasks if existing
		if (!empty ($this->isDevelopSite))
		{
			echo '<span style="color:red">'
				. 'Tasks: galleryJ3x view<br>'
				//	. '* <br>'
				//	. '* <br>'
				//	. '* <br>'
				//	. '* <br>'
				//	. '* <br>'
				. '</span><br><br>';
		}

		return parent::display($tpl);
	}

}
