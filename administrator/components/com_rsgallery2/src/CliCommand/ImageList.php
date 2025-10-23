<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\CliCommand;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Console\Command\AbstractCommand;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImageList extends AbstractCommand
{
	use DatabaseAwareTrait;

	/**
	 * The default command name
	 *
	 * @var    string
	 */
	protected static $defaultName = 'rsgallery2:image:list';

	/**
	 * @var   SymfonyStyle
	 */
	private $ioStyle;

	/**
	 * @var   InputInterface
	 */
	private $cliInput;

	/**
	 * Instantiate the command.
	 *
	 * @param   DatabaseInterface  $db  Database connector
	 *
	 * @since  4.0.X
	 */
	public function __construct()
	{
		parent::__construct();

		// $db = $this->getDatabase();
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$this->setDatabase($db);
	}

	/**
	 * Configure the IO.
	 *
	 * @param   InputInterface   $input   The input to inject into the command.
	 * @param   OutputInterface  $output  The output to inject into the command.
	 *
	 * @return  void
	 */
	private function configureIO(InputInterface $input, OutputInterface $output)
	{
		$this->cliInput = $input;
		$this->ioStyle  = new SymfonyStyle($input, $output);
	}

	/**
	 * Initialise the command.
	 *
	 * @return  void
	 *
	 * @since  4.0.X
	 */
	protected function configure(): void
	{
		$this->addOption('owner', null, InputOption::VALUE_OPTIONAL, 'user ID (created_by)');
		$this->addOption('created', null, InputOption::VALUE_OPTIONAL, 'created by');
		$this->addOption('gallery_id', null, InputOption::VALUE_OPTIONAL, 'gallery id');

		$help = "<info>%command.name%</info> list all existing rsgallery2 images
  Usage: <info>php %command.full_name%</info>
    * You may filter on the user id of image using the <info>--owner</info> option.
    * You may filter on created_by of image using the <info>--created</info> option.
    * You may filter on the gallery id of image using the <info>--gallery_id</info> option.
    Example: <info>php %command.full_name% --created_by=291</info>
    ";
		$this->setDescription(Text::_('List all images'));
		$this->setHelp($help);
	}


	/**
	 * Internal function to execute the command.
	 *
	 * @param   InputInterface   $input   The input to inject into the command.
	 * @param   OutputInterface  $output  The output to inject into the command.
	 *
	 * @return  integer  The command exit code
	 *
	 * @since   4.0.0
	 */
	protected function doExecute(InputInterface $input, OutputInterface $output): int
	{
		// Configure the Symfony output helper
		$this->configureIO($input, $output);
		$this->ioStyle->title('RSGallery2 Image list');

		$created_by_id = $input->getOption('created') ?? '';
		if (empty ($created_by_id))
		{
			$created_by_id = $input->getOption('owner') ?? '';
		}

		$gallery_id  = $input->getOption('gallery_id') ?? '';


		$images    = $this->getItemsFromDB($created_by_id, $gallery_id);

		// If no images are found show a warning and set the exit code to 1.
		if (empty($images))
		{
			$this->ioStyle->warning('No images found matching your criteria');

			return Command::FAILURE;
		}

		// Reshape the images into something humans can read.
		$images = array_map(
			function (object $item): array {
				return [
					$item->id,
					$item->name,
					$item->alias,

					$item->published,
					$item->gallery_id,

					$item->original_path,

					$item->ordering,

					$item->created_by,
					$item->created,
					$item->modified_by,
					$item->modified,

					// $item->,
					// $item->,
					// $item->,
					// $item->,
				];
			},
			$images
		);

		// Display the images in a table and set the exit code to 0
		$this->ioStyle->table(
			[
				'ID', 'Name', 'Alias',
				'Published', 'Gallery ID',
				'Orginal Path',
				'Ordering',
				'Created by', 'Created', 'Modified by', 'Modified',
			],
			$images
		);

		return Command::SUCCESS;
	}

	/**
	 * Retrieves extension list from DB
	 *
	 * @return array
	 *
	 * @since  4.0.X
	 */
	private function getItemsFromDB(string $userId, string $gallery_id): array
	{
		$db    = $this->getDatabase();
		$query = $db->createQuery();
		$query
			->select('*')
			->from('#__rsg2_images');

		if (!empty ($userId))
		{
			$query->where($db->quoteName('created_by') . ' = ' . (int) $userId);
		}

		if (!empty ($gallery_id))
		{
			$query->where($db->quoteName('gallery_id') . ' = ' . (int) $gallery_id);
		}

		$db->setQuery($query);
		$images = $db->loadObjectList();

		return $images;
	}


}
