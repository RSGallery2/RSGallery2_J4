<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2025-2025 RSGallery2 Team
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

        $defaults = ['component' => 'com_rsgallery2'];
        // ToDo: Remove when tests finished, enables access without token
        // $getDefaults = array_merge(['public' => true], $defaults);
        $getDefaults = array_merge(['public' => false], $defaults);

        //          new Route(['GET'], 'v1/example/items/:slug', 'item.displayItem',
        //              ['slug' => '(.*)'], ['option' => 'com_example']),

        $this->DBGalleriesImages($router, $getDefaults);

        $this->DBConfigAndVersion($router, $getDefaults);

        $this->UploadImages($router, $getDefaults);

        //    $this->($router);

        //    $this->createContentHistoryRoutes($router);
    }

    /**
     * DB galleries
     * @param   ApiRouter  $router
     *
     *
     * @since version
     */
    public function DBGalleriesImages(ApiRouter $router, array $getDefaults): void
    {

        $router->createCRUDRoutes(
            'v1/rsgallery2/galleries',
            'galleries',
            ['component' => 'com_rsgallery2'],
            $getDefaults,
        );

        // DB images
        $router->createCRUDRoutes(
            'v1/rsgallery2/images',
            'images',
            ['component' => 'com_rsgallery2'],
            $getDefaults,
        );
    }

    /**
     * Config and version
     * @param   ApiRouter  $router
     * @param   array      $getDefaults
     *
     *
     * @since version
     */
    public function DBConfigAndVersion(ApiRouter $router, array $getDefaults): void
    {
// DB config
        $router->addRoutes([
            new Route(['GET'], 'v1/rsgallery2/config', 'config.displayList', [], $getDefaults),
            new Route(
                ['GET'],
                'v1/rsgallery2/config/:variable_name',
                'config.displayItem',
                ['variable_name' => '([A-Za-z0-9_]+)'],
                $getDefaults,
            ),
        ]);

        // RSG2 version
        $router->addRoutes([
            //      new Route(['GET'], 'v1/rsgallery2/version', 'version', [], $getDefaults),
            new Route(['GET'], 'v1/rsgallery2/version', 'version.display', [], $getDefaults),
        ]);
    }

    /**
     * @param   ApiRouter  $router
     * @param   array      $getDefaults
     *
     *
     * @since version
     */
    private function UploadImages(ApiRouter $router, array $getDefaults)
    {
        // Gid or name
        $router->addRoutes([//            new Route(['GET'], 'v1/rsgallery2/upload/:gid',
//                'UploadApi.upload_img',
//                ['id' => '(\d+)'],
//                $getDefaults),

//            new Route(['POST'], 'v1/rsgallery2/upload/:gallery_name',
            new Route(['POST'], 'v1/rsgallery2/upload',
                // 'UploadApi.upload_img',
                 'upload.upload',
                //['gallery_name' => '(.*)'],
                [],
                $getDefaults),

            new Route(['PATCH'], 'v1/rsgallery2/recreate_sizes',
                // 'UploadApi.upload_img',
                 'upload.recreate_sizes',
                //['gallery_name' => '(.*)'],
                [],
                $getDefaults),

//        // image files
//        $router->createCRUDRoutes(
//            'v1/rsgallery2/image_files',
//            'UploadApi',
//            ['component' => 'com_rsgallery2'],
//            $getDefaults,
//        );

        ]);

    }
}
