<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\Develop;

defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\Registry\Registry;

use Joomla\Uri\Uri;
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
//    protected $state;

    /**
     * The item object details
     *
     * @var    \stdClass
     * @since  __BUMP_VERSION__
     */
//    protected $item;

	protected $routeTests = [];
	protected $routeResults = [];

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null) : void
    {
//        $item   = $this->item = $this->get('Item');
//        $state  =
//        $this->state = $this->get('State');
//        $params =
//        $this->params = $state->get('params');
//		$itemparams = new Registry(json_decode($item->params));
//
//        $this->isDebugSite   = $params->get('isDebugSite');
//        $this->isDevelopSite = $params->get('isDevelop');
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

	    // Define test routes
	    //
	    //option=com_content&view=article&id=123

//		$uri = Uri::getInstance();
//		$url = $uri->toString(array('scheme','host','port','path'));
//
//	    $joomla = Uri::getInstance("//www.joomla.org");
//	    $joomla->setScheme("https");
//	    $joomla->setPath("/announcements");
//	    $joomlaNews = $joomla->toString();  // https://www.joomla.org/announcements

	    $routesTest[] = 'index.php?option=com_rsgallery2&view=galleryj3x&id=4';
	    //$routesTest[] = 'index.php?option=com_rsgallery2&view=galleryj3x$id=4';
//	    $routesTest[] = 'index.php?option=com_rsgallery2&view=gallery$id=4';
//	    $routesTest[] = 'index.php?option=com_rsgallery2&view=slideshowj3x$id=4';
//	    $routesTest[] = 'index.php?option=com_rsgallery2&view=slidepagej3x$id=4&img_id=22'; // ?
//	    $routesTest[] = 'index.php?option=com_rsgallery2&view=slidepagej3x$id=4&start=5';
//	    $routesTest[] = 'index.php?option=com_rsgallery2&view=rsgallery2&id=4';
//	    $routesTest[] = 'index.php?option=com_rsgallery2&task=imagefile.downloadfile&id=22';
//	    $routesTest[] = '';
//	    $routesTest[] = '';
//	    $routesTest[] = '';

		foreach ($routesTest as $routePure) {

			$routeSef = Route::_($routePure, true, 0, true);
			$this->routeResults[$routePure] = $routeSef;
		}

        parent::display($tpl);
    }
}
