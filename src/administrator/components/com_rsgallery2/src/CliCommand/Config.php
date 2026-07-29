<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\CliCommand;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Console\Command\AbstractCommand;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\rsg2ConfigPara;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Config extends AbstractCommand
{
//    use DatabaseAwareTrait;

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
     *
     * @since  4.0.X
     */
    public function __construct()
    {
        parent::__construct();
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
     * Initialize the command.
     *
     * @return  void
     *
     * @since  4.0.X
     */
    protected function configure(): void
    {
        $this->addArgument('option', InputOption::VALUE_OPTIONAL, 'Name of the option');
        // $this->addOption('max_line_length', null, InputOption::VALUE_OPTIONAL, 'trim length of variable for item keeps in one line');

        $this->setDescription(Text::_('List all configuration parameter'));

        $help = "<info>%command.name%</info> list parameters of RSG2 configuration
  Usage: <info>php %command.full_name%</info>
    * On use of the option string 'xml' the parameters in the config.xml will be shown
    * On use of the option string 'merged' the parameters in the config.xml will be
      merged with the db parameter which will show the next save of config without changes";
//        * You may restrict the value string length using the <info>--max_line_length</info> option.
//    An excessively long result line disrupts the output lines.

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

        $options   = $this->cliInput->getArgument('option');
        if (!empty($options)) {
            $option = $options[0];
            $this->ioStyle->note("Option found: '$option'");
        }

        // $max_line_length = $input->getOption('max_line_length') ?? null;

        //--- merge config xml and db parameter ---------------------------------

        try
        {
            $rsgallery2Config = new rsg2ConfigPara(JPATH_ADMINISTRATOR . '/components/com_rsgallery2/config.xml');
            $rsgallery2Config->extractConfigParam ();

        } catch (\Exception $e) {
            $this->ioStyle->error('Config.doExecute ' . $e->getMessage());
            return Command::FAILURE;
        }

        // db extension table may be empty
        $actParams = $rsgallery2Config->getConfigDbParameter();

        if (!empty($option)) {
            if ($option == 'merged') {
                $actParams = $rsgallery2Config->getConfigMergedParameter();

                $this->ioStyle->note("Displayed parameter are merged from config.xml and DB table");

            } elseif ($option == 'xml') {
                $actParams = $rsgallery2Config->getConfigXmlParameter();

                $this->ioStyle->note("Displayed parameter are direct from config.xml");
            }
        }

        [$headers, $row] = $rsgallery2Config::namesValuesArrays($actParams);

        if (count($headers) == 0) {
            $this->ioStyle->warning("RSGallery2 component parameter are not initialized yet. Please save it once.\n"
                . "    No parameter to show.\n"
                . "    Please try option 'xml' or 'merged'");
        }

//        $headers = $rsgallery2Config->getConfigNames();
//        $rows = $rsgallery2Config->getConfigValues();

        $this->ioStyle->horizontalTable($headers, [$row]);

        return Command::SUCCESS;
    }

    /**
     * trim length of each value in array $configVars to max_len
     *
     * @param   Registry  $configVars
     * @param          $max_len
     *
     * @return array
     *
     * @since  5.1.0     */
    // ToDo: $configVars -> $rsgConfig is Registry
    // ToDo: assoc2DefinitionList is declared multiple
    private function assoc2DefinitionList($configVars, $max_len = 70)
    {
        $items = [];

        if (empty($max_len)) {
            $max_len = 70;
        }

        foreach ($configVars as $name => $value) {
            $items[] = [$name => mb_strimwidth((string) $value, 0, $max_len, '...')];
        }

        return $items;
    }
}
