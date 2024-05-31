<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2024 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Galleriesj3x;

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
	 * The page parameters
	 *
	 * @var    \Joomla\Registry\Registry|null
	 * @since  __BUMP_VERSION__
	 */
	protected $params = null;

	/**
	 * The item model state
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  __BUMP_VERSION__
	 */
	protected $state;

	/**
	 * The item object details
	 *
	 * @var    \JObject
	 * @since  __BUMP_VERSION__
	 */
	protected $items;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
        $app = Factory::getApplication();
        $input = Factory::getApplication()->input;

        $state =
        $this->state = $this->get('State');
        // Sub galleries
        $this->items = $this->get('Items');

        // parent gallery
        $this->parentGallery = $this->get('ParentGallery');

        //http://127.0.0.1/Joomla4x/index.php?option=com_rsgallery2&view=galleriesj3x
        //&gid=63
        //&images_show_title=2
        //&images_show_description=0
        //&images_show_search=0
        //&images_column_arrangement=1
        //&max_columns_in_images_view=4
        //&images_row_arrangement=2
        //&max_rows_in_images_view=5
        //&max_thumbs_in_images_view=20
        //&intro_text=%3Cp%3EIntroduction%20Text:%20J3x%20-%20Parent%20gallery%20with%20child%20galleries%3C/p%3E%20%20%3Cp%3E%20%3C/p%3E%20%20%3Cp%3E%20%3C/p%3E
        //&Itemid=160

        $params =
        $this->params = $state->get('params');

        $this->pagination = $this->get('Pagination');
        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;
        // ToDo: Why is this necessary ?
//		$this->pagination->setTotal (count($this->items));
        $this->user       = // $user = Factory::getContainer()->get(UserFactoryInterface::class);
	    $user = $app->getIdentity();

        $this->isDebugSite = $params->get('isDebugSite'); 
        $this->isDevelopSite = $params->get('isDevelop');


//		// Merge (overwrite) config parameter with menu parameter
//		$menuParams = $this->get('Rsg2MenuParams');
//		// wrong: $this->params = $menuParams->merge($this->params);
//		$this->params->merge($menuParams);

        if (count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode("\n", $errors), 500);
        }


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
}
