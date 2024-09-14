<?php

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Rsgallery2\Plugin\Content\Rsg2_images\Extension\Rsg2_images;


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
                $plgHelper  = (array)PluginHelper::getPlugin('content', 'rsg2_images');
                $dispatcher = $container->get(DispatcherInterface::class);

                $plugin = new Rsg2_images($dispatcher, $plgHelper);
                $plugin->setApplication(Factory::getApplication());
                //$plugin->setDatabase($container->get(DatabaseInterface::class));
                //$plugin->setMyCustomService($container->get(MyCustomService::class));

                return $plugin;
            },
        );
    }
};
