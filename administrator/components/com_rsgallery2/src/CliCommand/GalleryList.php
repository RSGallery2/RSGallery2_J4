<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (c)  2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * @author         finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\CliCommand;

defined('_JEXEC') or die;

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

class GalleryList extends AbstractCommand
{
  use DatabaseAwareTrait;

  /**
   * The default command name
   *
   * @var    string
   */
  protected static $defaultName = 'rsgallery2:gallery:list';

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
    $this->addOption('created', null, InputOption::VALUE_OPTIONAL, 'created_by');
    $this->addOption('parent', null, InputOption::VALUE_OPTIONAL, 'parent gallery');

    // ToDo: option to limit by user (owner), ?parent ...

    $help = "<info>%command.name%</info>will list all rsgallery2 galleries
  Usage: <info>php %command.full_name%</info>
    * You may filter on the user id of gallery using the <info>--owner</info> option.
    * You may filter on created_by of gallery using the <info>--created</info> option.
    * You may filter on the parent id of gallery using the <info>--parent_id</info> option.
    Example: <info>php %command.full_name% --created_by=291</info>";
    $this->setDescription(Text::_('List all rsgallery2 galleries'));
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
    $this->ioStyle->title('RSGallery2 Gallery list');

    $created_by_id = $input->getOption('created') ?? '';
    if (empty ($created_by_id))
    {
      $created_by_id = $input->getOption('owner') ?? '';
    }

    $parent_id  = $input->getOption('parent') ?? '';
    $galleries = $this->getItemsFromDB($created_by_id, $parent_id);

	$this->addImagesAssigneCount ($galleries);

    // If no galleries are found show a warning and set the exit code to 1.
    if (empty($galleries))
    {
      $this->ioStyle->warning('No galleries found matching your criteria');

      return Command::FAILURE;
    }

    // Reshape the galleries into something humans can read.
    $galleries = array_map(
      function (object $item): array {
        return [
          $item->id,
          $item->name,

	      $item->published,
//          $item->publish_up,
//          $item->publish_down,

          $item->created_by,
          $item->created,
          $item->modified_by,
          $item->modified,

          $item->parent_id,
          $item->imgCount,

	      $item->level,
          $item->path,
          $item->lft,
          $item->rgt,

        // $item->,
        ];
      },
      $galleries
    );

    // Display the galleries in a table and set the exit code to 0
    $this->ioStyle->table(
      [
        'ID', 'Name',
		    'Published', // 'Published Up', 'Published Down',
		    'Created', 'Created', 'Modified by', 'Modified',
		    'Parent', 'ImgCount',
		    'O:Level', 'O:path', 'O:lft', 'O:rgt',
	    ],
      $galleries
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
  private function getItemsFromDB(string $userId, string $parent_id): array
  {
    $db    = $this->getDatabase();
    $query = $db->getQuery(true);
    $query
      ->select('*')
      ->from('#__rsg2_galleries');

    if (!empty ($userId))
    {
      $query->where($db->quoteName('created_by') . ' = ' . (int) $userId);
    }

    if (!empty ($parent_id))
    {
      $query->where($db->quoteName('parent_id') . ' = ' . (int) $parent_id);
    }

    $db->setQuery($query);
    $galleries = $db->loadObjectList();

    return $galleries;
  }

	private function addImagesAssigneCount(array $galleries)
	{
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		foreach ($galleries as $gallery)
		{
			$query->clear();

			$query
				->select('COUNT(*)')
				->from('#__rsg2_images')
				->where($db->quoteName('gallery_id') . ' = ' . $db->quote($gallery->id));

			$db->setQuery($query);
			$imgCount = $db->loadResult();

			echo "imgCount: {$imgCount}\n";
			$gallery->imgCount = $imgCount;
		}
	}

}
