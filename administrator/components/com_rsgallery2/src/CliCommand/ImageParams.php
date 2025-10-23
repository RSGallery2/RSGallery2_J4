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

class ImageParams extends AbstractCommand
{
	use DatabaseAwareTrait;

	/**
	 * The default command name
	 *
	 * @var    string
	 */
	protected static $defaultName = 'rsgallery2:image:parameters';

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

		$help = "<info>%command.name%</info> display parameters of params field from table of selected image
  Usage: <info>php %command.full_name%</info>
    * You must specify an ID of the image with the <info>--id<info> option. Otherwise, it will be requested
    * You may restrict the value string length using the <info>--max_line_length</info> option. A result line that is too long will confuse the output lines
  ";
		$this->setDescription(Text::_('List all variables in params field of selected image'));
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
		$this->ioStyle->title('RSGallery2 Image Parameter Field');

		$galleryId = $input->getOption('id') ?? '';

		if (empty ($galleryId))
		{
			$this->ioStyle->error("The image id '" . $galleryId . "' is invalid (empty) !");

			return Command::FAILURE;
		}

		$galleryParams = $this->getParamsAsJsonFromDB($galleryId);


		// If no categories are found show a warning and set the exit code to 1.
		if (empty($galleryParams))
		{
			$this->ioStyle->error("The image id '" . $galleryId . "' is invalid, No image found matching your criteria!");

			return Command::FAILURE;
		}

		// pretty print json data
		$encoded    = json_decode($galleryParams);
		$jsonParams = json_encode($encoded, JSON_PRETTY_PRINT);

		$this->ioStyle->writeln($jsonParams);

		return Command::SUCCESS;
	}

	/**
	 * Retrieves extension list from DB
	 *
	 * @return array
	 *
	 * @since  4.0.X
	 */
	private function getParamsAsJsonFromDB(string $galleryId): string
	{
		$sParams = '';
		try
		{
			$db    = $this->getDatabase();
			$query = $db->createQuery();
			$query
				->select('params')
				->from('#__rsg2_galleries')
				->where($db->quoteName('id') . ' = ' . (int) $galleryId);

			$db->setQuery($query);
			$sParams = $db->loadResult();
		}
		catch (\Exception $e)
		{
			$this->ioStyle->error(
				Text::sprintf(
					'Retrieving params from DB failed for ID: "' . $galleryId . '\n%s',
					$e->getMessage()
				)
			);
		}

		return $sParams;
	}

	/**
	 * Trim length of each value in array $categoryAssoc to max_len
	 *
	 * @param   array  $categoryAssoc  in data as association key => val
	 * @param          $max_len
	 *
	 * @return array
	 *
	 * @since  5.1.0	 */
	private function assoc2DefinitionList(array $categoryAssoc, $max_len = 70)
	{
		$items = [];

		if (empty($max_len))
		{
			$max_len = 70;
		}

		foreach ($categoryAssoc as $key => $value)
		{
			$items[] = [$key => mb_strimwidth((string) $value, 0, $max_len, '...')];
		}

		return $items;
	}


}
