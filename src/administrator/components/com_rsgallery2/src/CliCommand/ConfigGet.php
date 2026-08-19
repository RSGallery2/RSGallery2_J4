<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\CliCommand;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Console\Command\AbstractCommand;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\rsg2ConfigPara;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class ConfigGet extends AbstractCommand
{
//    use DatabaseAwareTrait;

    /**
     * The default command name
     *
     * @var    string
     */
    protected static $defaultName = 'rsgallery2:config:get';

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
     * Initialise the command.
     *
     * @return  void
     *
     * @since  4.0.X
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the option');
        $this->addArgument('option', InputOption::VALUE_OPTIONAL, 'Name of the option');

        $this->setDescription(Text::_('Display value of selected parameter in configuration'));

        $help = "<info>%command.name%</info> display a value of the RSG2 configuration
  Usage: <info>php %command.full_name%</info> <name>
    * On use of the option string 'xml' the parameters in the config.xml will be used directly
    * You may restrict the value string length using the <info>--max_line_length</info> option.
      An excessively long result line disrupts the output lines.";

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
        $this->ioStyle->title('RSGallery2 Configuration Value');

        $name = $this->cliInput->getArgument('name');

        $options = $this->cliInput->getArgument('option');
        if (!empty($options)) {
            $option = $options[0];
            $this->ioStyle->note("Option found: '$option'");
        }

        //--- merge config xml and db parameter ---------------------------------

        try
        {
            $rsgallery2Config = new rsg2ConfigPara(JPATH_ADMINISTRATOR . '/components/com_rsgallery2/config.xml');
            $rsgallery2Config->extractConfigParam ();

        } catch (\Exception $e) {
            $this->ioStyle->error('ConfigGet.doExecute ' . $e->getMessage());
            return Command::FAILURE;
        }

        // No DB parameter ?
        if ($rsgallery2Config->getDbParameter ()->count() == 0) {
            $this->ioStyle->warning("RSGallery2 component parameter are not initialized yet. Please save it once.");
        }

        // db extension table may be empty
        $actParams = $rsgallery2Config->getConfigCompParameter();

        if (!empty($option)) {
            if ($option == 'xml') {
                $actParams = $rsgallery2Config->getConfigXmlParameter();

                $this->ioStyle->note("Parameter is read directly from config.xml");
            }
        }

        $valueBare = $actParams->get($name, null);
        if ($valueBare === null) {
            $this->ioStyle->error("Can't find option '{$name}' in configuration list");

            return Command::FAILURE;
        }

        $value = $this->formatConfigValue($valueBare);

        $this->ioStyle->table(['Option', 'Value'], [[$name, $value]]);

        return Command::SUCCESS;
    }

    /**
     * Formats the Configuration value
     *
     * @param   mixed  $value  Value to be formatted
     *
     * @return string
     *
     * @since  4.0.X
     */
    protected function formatConfigValue($value): string
    {
        if ($value === false) {
            return 'false';
        }

        if ($value === true) {
            return 'true';
        }

        if ($value === null) {
            return 'Not Set';
        }

        if (\is_array($value)) {
            return json_encode($value);
        }

        if (\is_object($value)) {
            return json_encode(get_object_vars($value));
        }

        return $value;
    }
}
