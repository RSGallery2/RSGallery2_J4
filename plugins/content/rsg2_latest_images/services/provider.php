<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 *
 * @author          RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c)  2020-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */


defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Rsgallery2\Plugin\Content\Rsg2_latest_images\Extension\Rsg2_latest_images;


return new class () implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   4.3.0
     */
    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plgHelper  = (array)PluginHelper::getPlugin('content', 'rsg2_latest_images');
                $dispatcher = $container->get(DispatcherInterface::class);

                $plugin = new Rsg2_latest_images($dispatcher, $plgHelper);
                $plugin->setApplication(Factory::getApplication());
                //$plugin->setDatabase($container->get(DatabaseInterface::class));
                //$plugin->setMyCustomService($container->get(MyCustomService::class));

                return $plugin;
            },
        );
    }
};
