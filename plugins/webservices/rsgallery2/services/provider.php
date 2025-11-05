<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2025-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Rsgallery2\Plugin\WebServices\Rsgallery2\Extension\Rsgallery2;

return new class () implements ServiceProviderInterface {
	
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
				
				$plugin     = PluginHelper::getPlugin('webservices', 'rsgallery2');
				$dispatcher = $container->get(DispatcherInterface::class);

				/** @var \Joomla\CMS\Plugin\CMSPlugin $plugin */
				$plugin = new Rsgallery2($dispatcher, (array) $plugin);
				$plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};
