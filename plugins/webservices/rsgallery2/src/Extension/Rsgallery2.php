<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2025-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Plugin\WebServices\Rsgallery2\Extension;

use Joomla\CMS\Event\Application\BeforeApiRouteEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\ApiRouter;
use Joomla\Event\SubscriberInterface;
use Joomla\Router\Route;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Web Services adapter for com_rsgallery2.
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
        return ['onBeforeApiRoute' => 'onBeforeApiRoute',];
    }

    /**
     * Registers com_rsgallery API's routes in the application
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

        $defaults = ['component' => 'com_rsgallery2'];
        // ToDo: Remove when tests finished, enables access without token
        // $getDefaults = array_merge(['public' => true], $defaults);
        $getDefaults = array_merge(['public' => false], $defaults);

        $this->DBGalleriesImages($router, $getDefaults);

        $this->DBConfigAndVersion($router, $getDefaults);

        $this->UploadImages($router, $getDefaults);

        //    $this->($router);

        //    $this->createContentHistoryRoutes($router);
    }

    /**
     * DB galleries
     *
     * @param ApiRouter  $router
     * @param array $getDefaults
     *
     * @since 5.0.0.10
     */
    public function DBGalleriesImages(ApiRouter $router, array $getDefaults): void
    {

        $router->createCRUDRoutes('v1/rsgallery2/galleries', 'galleries', ['component' => 'com_rsgallery2'], $getDefaults);

        // DB images
        $router->createCRUDRoutes('v1/rsgallery2/images', 'images', ['component' => 'com_rsgallery2'], $getDefaults);
    }

    /**
     * Config and version
     *
     * @param   ApiRouter  $router
     * @param   array      $getDefaults
     *
     *
     * @since version
     */
    public function DBConfigAndVersion(ApiRouter $router, array $getDefaults): void
    {
		//--- RSG2 J standard config -------------------------------------------

	    $baseName = 'v1/rsgallery2/config';
	    $controller = 'config';
	    $defaults = ['component' => 'com_rsgallery2'];

	    $routes = [
			// List
		    new Route(['GET'], $baseName, $controller . '.displayList', [], $getDefaults),
		    // Single tem
		    new Route(['GET'], $baseName . '/:para', $controller . '.displayItem', ['para' => '([A-Za-z0-9_]+)'], $getDefaults),

	        // Create item(s)
            new Route(['POST'], $baseName, $controller . '.add', [], $defaults),

		    // Change item(s)
//            new Route(['PATCH'], $baseName . '/:para' . '/:value', $controller . '.edit', ['para' => '(.*)', 'value' => '(.*)'], $defaults),
// ok when id in route  new Route(['PATCH'], $baseName . '/:para', $controller . '.edit', ['para' => '(.*)'], $defaults),
            new Route(['PATCH'], $baseName, $controller . '.edit', [], $defaults),

		    // Delete single item
            new Route(['DELETE'], $baseName, $controller . '.delete', [], $defaults),
		    new Route(['DELETE'], $baseName . '/:para', $controller . '.delete', ['para' => '([A-Za-z0-9_]+)'], $getDefaults),
        ];
	    $router->addRoutes($routes);

        //--- RSG2 version in db manifest -----------------------------

        $router->addRoutes(
		[
			// version, creationDate
			new Route(['GET'], 'v1/rsgallery2/version', 'version.display', [], $getDefaults),
			new Route(['PATCH'], 'v1/rsgallery2/version', 'version.edit', [], $defaults),
		]);
    }

    /**
     * @param   ApiRouter  $router
     * @param   array      $getDefaults
     *
     * @since version
     */
    private function UploadImages(ApiRouter $router, array $getDefaults): void
    {
        // Gid or name
        $router->addRoutes([// ToDo: use upload_file as command

            // ToDo: Upload in one go
            //  new Route(['GET'], 'v1/rsgallery2/upload/:gid',
            //      'uploadimgatonce.upload_imgage_at_once',
            //      ['id' => '(\d+)'],
            //      $getDefaults),

            new Route(['GET'], 'v1/rsgallery2/latestgallery', 'latestgallery.displayItem', [], $getDefaults),

            //new Route(['POST'], 'v1/rsgallery2/db_reserve_image_id', 'images.add', [], $getDefaults),
            new Route(['POST'], 'v1/rsgallery2/db_reserve_image_id/:gallery_id', 'reserveimgid.db_reserve_image_id', ['gallery_id' => '(\d+)'], $getDefaults),
            new Route(['POST'], 'v1/rsgallery2/db_reserve_image_id/:gallery_name', 'reserveimgid.db_reserve_image_id', ['gallery_name' => '(\d+)'], $getDefaults),
            new Route(['POST'], 'v1/rsgallery2/db_reserve_image_id', 'reserveimgid.db_reserve_image_id', [], $getDefaults),

            new Route(['POST'], 'v1/rsgallery2/upload_image_file', 'uploadimagefile.upload_image_file', [], $getDefaults),

            new Route(['PATCH'], 'v1/rsgallery2/recreate_sizes', 'recreatesizes.recreate_sizes', [], $getDefaults),

        ]);

    }
}
