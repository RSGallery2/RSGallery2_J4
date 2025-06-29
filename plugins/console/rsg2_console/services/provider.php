<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 *
 * @author          RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c)  2025-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Rsgallery2\Plugin\Console\Rsg2_console\Extension\Rsg2_console;

return new class implements ServiceProviderInterface {
    public function register(Container $container)
    {
        $container->registerServiceProvider(new MVCFactory('Rsgallery2\\Component\\Rsgallery2'));

        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $config = (array)PluginHelper::getPlugin('console', 'rsg2_console');
                $subject = $container->get(DispatcherInterface::class);
                $mvcFactory = $container->get(MVCFactoryInterface::class);

                $plugin = new Rsg2_console($subject, $config);
                $plugin->setApplication(Factory::getApplication());
                $plugin->setMVCFactory($mvcFactory);

                return $plugin;
            },
        );
    }
};
