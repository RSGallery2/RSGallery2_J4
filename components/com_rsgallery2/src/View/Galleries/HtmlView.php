<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Galleries;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
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
	protected $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
        global $rsgConfig;

        if (empty ($rsgConfig)) {
            $rsgConfig = ComponentHelper::getParams('com_rsgallery2');
        }

        $state = $this->State = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
		$this->params = $this->Params = $state->get('params');
        $this->user       = Factory::getUser();

        $this->isDevelopSite = $rsgConfig->get('isDevelop'); // = $rsgConfig->isDevelopSite;


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
