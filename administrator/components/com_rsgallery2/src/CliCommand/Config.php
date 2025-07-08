<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright  (c)  2016-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\CliCommand;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
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

class Config extends AbstractCommand
{
	use DatabaseAwareTrait;

	/**
	 * The default command name
	 *
	 * @var    string
	 */
	protected static $defaultName = 'rsgallery2:config';

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
		$this->addOption('max_line_length', null, InputOption::VALUE_OPTIONAL, 'trim lenght of variable for item keeps in one line');

		$help = "<info>%command.name%</info> list variables of RSG2 configuration
  Usage: <info>php %command.full_name%</info>
    * You may restrict the value string length using the <info>--max_line_length</info> option. A result line that is too long will confuse the output lines
";
		$this->setDescription(Text::_('List all configuration variables'));
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
		$this->ioStyle->title('RSGallery2 Configuration');

		$max_line_length = $input->getOption('max_line_length') ?? null;

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();

		$configurationAssoc = $rsgConfig; // $this->getItemAssocFromDB($rsgConfig);

		if (empty ($configurationAssoc))
		{
			$this->ioStyle->error("The joomla RSG2 configuration could not be read");

			return Command::FAILURE;
		}


		$strConfigurationAssoc = $this->assoc2DefinitionList($rsgConfig, $max_line_length);

		// ToDo: Use horizontal table again ;-)
		foreach ($strConfigurationAssoc as $value)
		{
			if (!\is_array($value))
			{
				throw new \InvalidArgumentException('Value should be an array, string, or an instance of TableSeparator.');
			}

			$headers[] = key($value);
			$row[]     = current($value);
		}

		$this->ioStyle->horizontalTable($headers, [$row]);


// ToDo: check out following (original joomla config)

//		$options = [];
//
//		array_walk(
//			$configs,
//			function ($value, $key) use (&$options) {
//				$options[] = [$key, $this->formatConfigValue($value)];
//			}
//		);
//
//		$this->ioStyle->title("Current options in Configuration");
//		$this->ioStyle->table(['Option', 'Value'], $options);

		return Command::SUCCESS;
	}

	/**
	 * trim length of each value in array $configVars to max_len
	 *
	 * @param   array  $configVars
	 * @param          $max_len
	 *
	 * @return array
	 *
	 * @since version
	 */
	private function assoc2DefinitionList($configVars, $max_len = 70)
	{
		$items = [];

		if (empty($max_len))
		{
			$max_len = 70;
		}

		foreach ($configVars as $name => $value)
		{
			$items[] = [$name => mb_strimwidth((string) $value, 0, $max_len, '...')];
		}

		return $items;
	}


}
