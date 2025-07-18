<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 *
 * @author          RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c)  2025-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */

namespace Rsgallery2\Plugin\Console\Rsg2_console\Extension;

/**
 * @package         Joomla.Plugin
 * @subpackage      Content.contact
 *
 * @copyright  (c)  2019-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */
// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;

use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\CMS\MVC\Factory\MVCFactoryAwareTrait;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\Config;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\ConfigGet;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\ConfigSet;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\Gallery;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\GalleryList;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\GalleryParams;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\Image;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\ImageList;
use Rsgallery2\Component\Rsgallery2\Administrator\CliCommand\ImageParams;

class Rsg2_console extends CMSPlugin implements SubscriberInterface
{
	use MVCFactoryAwareTrait;

	/**
	 * Global application object
	 *
	 * @var array of command class definition
	 *
	 * @since   4.1.0
	 */

	// Todo: image:check / state ?? -> image: is existing, pathes ...
	// Todo: galleries by ordering gallery:list:ordering
    // ToDo: layout json for image/gallery /
	// ToDo: check jsonify out put JsonResponse echo new JsonResponse($output);
	// ToDo: ResetConfigToDefault
	// ToDo: ImageMetadata::class, // ? exif

	private static $commands = [
		Gallery::class,
//        GalleryAdd::class,
		GalleryList::class,
        GalleryParams::class,
		Config::class,
		ConfigGet::class,
		ConfigSet::class,
        Image::class,
        ImageList::class,
        ImageParams::class,
	];

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 *
	 * @since 4.1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * load language on init
	 *
	 * @var    boolean
	 *
	 * @since  4.1.0
	 */
	public function init(): void
	{
		$this->loadLanguage();
	}

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return array
	 *
	 * @since   4.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			ApplicationEvents::BEFORE_EXECUTE => 'registerCLICommands',
		];
	}

	/**
	 * load command classes and add valid ones
	 *
	 * @return void
	 *
	 * @since   4.0.0
	 */
	public function registerCLICommands(ApplicationEvent $event): void
	{
		// all commands are class definitions
		foreach (self::$commands as $commandFQN)
		{
			try
			{
				if (!class_exists($commandFQN))
				{
					continue;
				}

				// create command (class)
				$command = new $commandFQN();

				if (method_exists($command, 'setMVCFactory'))
				{
					$command->setMVCFactory($this->getMVCFactory());
				}

				// tell the command
				$this->getApplication()->addCommand($command);
			}
			catch (\Throwable $e)
			{
				print ($commandFQN . ': error ' . $e->getMessage());
				continue;
			}
		}
	}

} // class
