<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Galleries;

defined('_JEXEC') or die;

use JObject;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
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
     * The page parameters
     *
     * @var    Registry|null
     * @since  __BUMP_VERSION__
     */
    protected $params = null;

    /**
     * The item model state
     *
     * @var    Registry
     * @since  __BUMP_VERSION__
     */
    protected $state;

    /**
     * The item object details
     *
     * @var    JObject
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

        $state       = $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $params      =
        $this->params = $state->get('params');

        $this->pagination = $this->get('Pagination');
        $this->user       =
        $user = $app->getIdentity();

        $this->isDebugSite   = $params->get('isDebugSite');
        $this->isDevelopSite = $params->get('isDevelop');


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
