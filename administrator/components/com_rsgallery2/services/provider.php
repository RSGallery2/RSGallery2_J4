<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2024 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Rsgallery2\Component\Rsgallery2\Administrator\Extension\Rsgallery2Component;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\AssociationsHelper;

/**
 * The rsgallery2 service provider.
 * https://github.com/joomla/joomla-cms/pull/20217
 *
 * @since __BUMP_VERSION__
 */
return new class implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
	public function register(Container $container)
	{
//		$container->set(AssociationExtensionInterface::class, new AssociationsHelper);

		$container->registerServiceProvider(new CategoryFactory('\\Rsgallery2\\Component\\Rsgallery2'));
		$container->registerServiceProvider(new MVCFactory('\\Rsgallery2\\Component\\Rsgallery2'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Rsgallery2\\Component\\Rsgallery2'));
		$container->registerServiceProvider(new RouterFactory('\\Rsgallery2\\Component\\Rsgallery2'));

		$container->set(
			ComponentInterface::class,
			function (Container $container)
			{
				$component = new Rsgallery2Component($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
//				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
//				$component->setAssociationExtension($container->get(AssociationExtensionInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);
	}
};
