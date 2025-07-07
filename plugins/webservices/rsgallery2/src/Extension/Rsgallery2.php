<?php
/**
 * @package         RSGallery2
 * @subpackage      webservice.rsgallery2
 *
 * @author          RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c)  2025-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */

namespace Rsgallery2\Plugin\WebServices\Rsgallery\Extension;

use Joomla\CMS\Event\Application\BeforeApiRouteEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\ApiRouter;
use Joomla\Event\SubscriberInterface;
use Joomla\Router\Route;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Web Services adapter for com_rsgallery.
 *
 * @since  4.0.0
 */
final class Rsgallery2 extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   5.1.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeApiRoute' => 'onBeforeApiRoute',
        ];
    }

    /**
     * Registers com_rsgallery's API's routes in the application
     *
     * @param   BeforeApiRouteEvent  $event  The event object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function onBeforeApiRoute(BeforeApiRouteEvent $event): void
    {
        $router = $event->getRouter();

	    $defaults    = ['component' => 'com_rsgallery'];
	    // $getDefaults = array_merge(['public' => false], $defaults);
	    $getDefaults = array_merge(['public' => false], $defaults); // ToDo: Remove when tests finished, enabless acces without token

//		    new Route(['GET'], 'v1/example/items/:slug', 'item.displayItem',
//			    ['slug' => '(.*)'], ['option' => 'com_example']),

	    $router->addRoutes([
		    new Route(['GET'], 'v1/rsgallery', 'rsgallery.displayItem', [], $getDefaults),
	    ]);

        // $router->createCRUDRoutes(
			// 'v1/rsgallery/project',
			// 'project',
			// ['component' => 'com_rsgallery'],
	        // true // ToDo: Remove when tests finished
		// );
	
        // $router->createCRUDRoutes(
			// 'v1/rsgallery/projects',
			// 'projects',
			// ['component' => 'com_rsgallery'],
	        // true // ToDo: Remove when tests finished
		// );
	
        // $router->createCRUDRoutes(
			// 'v1/rsgallery/subprojects',
			// 'subprojects',
			// ['component' => 'com_rsgallery'],
	        // true // ToDo: Remove when tests finished
		// );
	
        $this->createFieldsRoutes($router);

        $this->createContentHistoryRoutes($router);
	}

    /**
     * Create fields routes
     *
     * @param   ApiRouter  &$router  The API Routing object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    private function createFieldsRoutes(&$router): void
    {
        $router->createCRUDRoutes(
            'v1/fields/content/articles',
            'fields',
            ['component' => 'com_fields', 'context' => 'com_content.article']
        );

        $router->createCRUDRoutes(
            'v1/fields/content/categories',
            'fields',
            ['component' => 'com_fields', 'context' => 'com_content.categories']
        );

        $router->createCRUDRoutes(
            'v1/fields/groups/content/articles',
            'groups',
            ['component' => 'com_fields', 'context' => 'com_content.article']
        );

        $router->createCRUDRoutes(
            'v1/fields/groups/content/categories',
            'groups',
            ['component' => 'com_fields', 'context' => 'com_content.categories']
        );
    }

    /**
     * Create contenthistory routes
     *
     * @param   ApiRouter  &$router  The API Routing object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    private function createContentHistoryRoutes(&$router): void
    {
        $defaults    = [
            'component'  => 'com_contenthistory',
            'type_alias' => 'com_rsgallery.rsgallery',
            'type_id'    => 1,
        ];
        $getDefaults = array_merge(['public' => false], $defaults);

        $routes = [
            new Route(['GET'], 'v1/rsgallery/:id/contenthistory', 'history.displayList', ['id' => '(\d+)'], $getDefaults),
            new Route(['PATCH'], 'v1/rsgallery/:id/contenthistory/keep', 'history.keep', ['id' => '(\d+)'], $defaults),
            new Route(['DELETE'], 'v1/rsgallery/:id/contenthistory', 'history.delete', ['id' => '(\d+)'], $defaults),
        ];

        $router->addRoutes($routes);
    }
}

