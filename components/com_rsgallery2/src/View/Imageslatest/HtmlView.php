<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Imageslatest;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects


use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\Registry\Registry;

use function defined;

/**
 * HTML Rsgallery2 View class for the Rsgallery2 component
 *
     * @since      5.1.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The page parameters
     *
     * @var    Registry|null
     * @since  5.1.0     */
    protected $params = null;

    /**
     * The item model state
     *
     * @var    Registry
     * @since  5.1.0     */
    protected $state;

    /**
     * The item object details
     *
     * @var    \stdClass
     * @since  5.1.0     */
    protected $item;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null): void
    {
        $item       =
        $this->item = $this->get('Item');
        $state      =
        $this->state = $this->get('state');
        $params     =
        $this->params = $state->get('params');
        $itemparams = new registry(json_decode($item->params));

        $this->isDebugSite   = $params->get('isDebugSite');
        $this->isDevelopSite = $params->get('isDevelop');

//      $temp = clone $params;
//      $temp->merge($itemparams);
//      $item->params = $temp;
//
//      Factory::getApplication()->triggerEvent('onContentPrepare', array ('com_rsgallery2.rsgallery2', &$item));
//
//      // Store the events for later
//      $item->event = new \stdClass;
//      $results = Factory::getApplication()->triggerEvent('onContentAfterTitle', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//      $item->event->afterDisplayTitle = trim(implode("\n", $results));
//


//      $results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//      $item->event->beforeDisplayContent = trim(implode("\n", $results));
//
//      $results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//      $item->event->afterDisplayContent = trim(implode("\n", $results));
//
        parent::display($tpl);
    }
}
