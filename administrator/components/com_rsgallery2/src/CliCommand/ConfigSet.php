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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Console\Command\AbstractCommand;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfigSet extends AbstractCommand
{
	use DatabaseAwareTrait;

	/**
	 * The default command name
	 *
	 * @var    string
	 */
	protected static $defaultName = 'rsgallery2:config:set';

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
		$this->addArgument('option', InputArgument::REQUIRED, 'Name of the option');
		$this->addArgument('value', null, 'Value of the option');
		$this->addOption('verify', null, InputOption::VALUE_OPTIONAL, 'configuration ID', false);

		$help = "<info>%command.name%</info> set a parameter value in the RSG2 configuration 
  Usage: <info>php %command.full_name%</info>  <option> <value>
    * You may verify the written value with <info>--veryfy=true<info> option. This compares the given option with the resulting table value
		";
		$this->setDescription(Text::_('Sets the value of selected parameter name in configuration'));
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
		$this->ioStyle->title('RSGallery2 Set Configuration Value');

		$option   = $this->cliInput->getArgument('option');
		$value    = $this->cliInput->getArgument('value');
		$veryfyIn = $input->getOption('verify') ?? 'false';

		// $isDoVerify = true/false, 0/1;
		$isDoVerify = $this->isTrue($veryfyIn);

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		if (empty ($rsgConfig))
		{
			$this->ioStyle->error("The joomla RSG2 configuration could not be read");

			return Command::FAILURE;
		}

		// It is allowed to create new values
		$valueBare = $rsgConfig->get($option, null);
		if ($valueBare == null)
		{
			$this->ioStyle->note("Option '{$option}' was  not used before");
		}

		// ToDo: Make it sql save ....
		$sanitizeValue = $this->sanitizeValue($value);

		$rsgConfigClone = new Registry($rsgConfig);
		$rsgConfigClone->set($option, $sanitizeValue);
		// ComponentHelper::getComponent('com_rsgallery2')->setParams($rsgConfig);
		$isSuccess = $this->saveParams ($rsgConfigClone);

		if (empty($isSuccess)) {

			$this->ioStyle->error("Could not save RSG2 configuration parameters");

			return Command::FAILURE;
		}

		if ($isDoVerify)
		{
			$rsgConfigVerify = $this->readRsg2ExtensionParameterDb();

			$verifiedValue = $rsgConfigVerify [$option];
			if ($verifiedValue == null)
			{
				$this->ioStyle->error("Option '{$option}' was  not set or is null");
			}

			if ($verifiedValue != $value)
			{
				$this->ioStyle->error("Configuration set for "
					. "option: '" . $option . "' in value: '" . $value . "'" . " results in table value: '" . $verifiedValue . "'");
			}
			else
			{
				$this->ioStyle->note('Written value confirmed');
			}

		}

		return Command::SUCCESS;
	}

	/**
	 * Sanitize the options array for boolean
	 *
	 * @param   array  $option  Options array
	 *
	 * @return array
	 *
	 * @since  4.0.X
	 */
	private function sanitizeValue($value)
	{
		switch (strtolower($value))
		{
			case $value === 'false':
				$value = false;
				break;
			case $value === 'true':
				$value = true;
				break;
			case $value === 'null':
				$value = null;
				break;
		}

		return $value;
	}

	/**
	 * Check string input for true (1)
	 *
	 * @param   mixed  $veryfyIn
	 *
	 * @return bool
	 *
	 * @since  5.1.0	 */
	private function isTrue(mixed $veryfyIn)
	{
		$isTrue = false;

		if (!empty ($veryfyIn))
		{

			if (strtolower($veryfyIn) == 'true')
			{
				$isTrue = true;
			}

			if (strtolower($veryfyIn) == 'on')
			{
				$isTrue = true;
			}

			// ToDo: positive ?
			if ($veryfyIn == '1')
			{
				$isTrue = true;
			}
		}

		return $isTrue;
	}

	/**
	 * Save RSG2 configuration to db
	 * @param   Registry  $params
	 *
	 * @return bool
	 *
	 * @since  5.1.0
	 */
	public function saveParams(Registry $params)
	{
		$db = Factory::getDbo();

		return $db->setQuery(
			'UPDATE #__extensions'
			. ' SET params = ' . $db->quote((string) $params)
			. ' WHERE element = ' . $db->quote('com_rsgallery2')
		)->execute();
	}

	/**
	 * read RSG2 configuration from DB
	 *
	 * @return array|mixed
	 *
	 * @since  5.1.0
	 */
	public function readRsg2ExtensionParameterDb()
	{
		$params = [];

		try {
			// read the existing component value(s)
			$db = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db
				->createQuery()
				->select('params')
				->from($db->quoteName('#__extensions'))
				->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
			$db->setQuery($query);

			/* found in install but why reassign parameters ? registry ?
			$param_array = json_decode($db->loadResult(), true);

			// add the new variable(s) to the existing one(s)
			foreach ($param_array as $name => $value) {
				$params[(string)$name] = (string)$value;
			}
			/**/

			$jsonStr = $db->loadResult();
			if (!empty ($jsonStr)) {
				$params = json_decode($jsonStr, true);
			}
		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'ConfigSet: readRsg2ExtensionParameterDb: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$this->ioStyle->error($OutTxt);
		}

		return $params;
	}
}
