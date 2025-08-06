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



class Image extends AbstractCommand
{
  use DatabaseAwareTrait;

  /**
   * The default command name
   *
   * @var    string
   */
  protected static $defaultName = 'rsgallery2:image';

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
	  $this->addOption('id', null, InputOption::VALUE_REQUIRED, 'image ID');
	  $this->addOption('max_line_length', null, InputOption::VALUE_OPTIONAL, 'trim lenght of variable for item keeps in one line');

    $help = "<info>%command.name%</info> list variables of one rsgallery image
  Usage: <info>php %command.full_name%</info>
    * You must specify an ID of the image with the <info>--id<info> option. Otherwise, it will be requested
    * You may restrict the value string length using the <info>--max_line_length</info> option. A result line that is too long will confuse the output lines
  ";
    $this->setDescription(Text::_('List all variables of selected image'));
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
    $this->ioStyle->title('RSGallery2 Image');

	  $ImageId      = $input->getOption('id') ?? '';
	  $max_line_length = $input->getOption('max_line_length') ?? null;

	  if (empty ($ImageId))
	  {
		  $this->ioStyle->error("The image id '" . $ImageId . "' is invalid (empty) !");

		  return Command::FAILURE;
	  }

	  $galleryAssoc = $this->getItemAssocFromDB($ImageId);

	  // If no categories are found show a warning and set the exit code to 1.
	  if (empty($galleryAssoc))
	  {
		  $this->ioStyle->error("The image id '" . $ImageId . "' is invalid, No image found matching your criteria!");

		  return Command::FAILURE;
	  }

	  $strGalleryAssoc = $this->assoc2DefinitionList($galleryAssoc, $max_line_length);

	  // ToDo: Use horizontal table again ;-)
	  foreach ($strGalleryAssoc as $value)
	  {
		  if (!\is_array($value))
		  {
			  throw new \InvalidArgumentException('Value should be an array, string, or an instance of TableSeparator.');
		  }

		  $headers[] = key($value);
		  $row[]     = current($value);
	  }

	  $this->ioStyle->horizontalTable($headers, [$row]);

	  return Command::SUCCESS;
  }

	/**
	 * Retrieves extension list from DB
	 *
	 * @return array
	 *
	 * @since  4.0.X
	 */
	private function getItemAssocFromDB(string $ImageId): array
	{
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from('#__rsg2_images')
			->where($db->quoteName('id') . ' = ' . (int) $ImageId);

		$db->setQuery($query);
		$imageAssoc = $db->loadAssoc();

		return $imageAssoc;
	}

	/**
	 * Trim length of each value in array $galleryAssoc to max_len
	 *
	 * @param   array  $galleryAssoc  in data as association key => val
	 * @param          $max_len
	 *
	 * @return array
	 *
	 * @since version
	 */
	private function assoc2DefinitionList(array $galleryAssoc, $max_len = 70)
	{
		$items = [];

		if (empty($max_len))
		{
			$max_len = 70;
		}

		foreach ($galleryAssoc as $key => $value)
		{
			$items[] = [$key => mb_strimwidth((string) $value, 0, $max_len, '...')];
		}

		return $items;
	}

}
