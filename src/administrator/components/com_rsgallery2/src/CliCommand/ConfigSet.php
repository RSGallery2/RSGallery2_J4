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
//use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\rsg2ConfigPara;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfigSet extends AbstractCommand
{
//    use DatabaseAwareTrait;

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
        $this->addArgument('option', InputArgument::REQUIRED, 'Name of the option');
        $this->addArgument('value', null, 'Value of the option');
        $this->addOption('verify', null, InputOption::VALUE_OPTIONAL, 'configuration ID', false);

        $this->setDescription(Text::_('Sets the value of selected parameter name in configuration'));

        $help = "<info>%command.name%</info> set a parameter value in the RSG2 configuration
  Usage: <info>php %command.full_name%</info>  <option> <value>
    * You may verify the written value with <info>--verify=true<info> option.
      This compares the given option with the resulting table value";

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
        $verifyIn = $input->getOption('verify') ?? 'false';

        // $isDoVerify = true/false, 0/1;
        $isDoVerify = $this->isTrue($verifyIn);

        //--- merge config xml and db parameter ---------------------------------

        try
        {
            $rsgallery2Config = new rsg2ConfigPara(JPATH_ADMINISTRATOR . '/components/com_rsgallery2/config.xml');
            $rsgallery2Config->extractConfigParam ();

        } catch (\Exception $e) {
            $this->ioStyle->error('ConfigSet.doExecute ' . $e->getMessage());
            return Command::FAILURE;
        }

        // No DB parameter ?
        if ($rsgallery2Config->getDbParameter ()->count() == 0) {
            $this->ioStyle->warning("RSGallery2 component parameter were not initialized yet\n"
                . "File config.xml value were saved with the new parameter/value");
        }

        //--- Assign value ---------------------------------------------------

        // ToDo: Make it sql save ....
        $sanitizeValue = $this->sanitizeValue($value);

        $newValue = new Registry([$option => $sanitizeValue]);
        $actPara = $rsgallery2Config->getConfigMergedParameter();

        // Merge ew values into actual. Accepts only known values
        $merged = $actPara->merge($newValue);

        //--- save value ---------------------------------------------------------

        $isSuccess = $rsgallery2Config->saveDbParams($merged);

        if (empty($isSuccess)) {
            $this->ioStyle->error("Could not save RSGallery2 configuration parameters");

            return Command::FAILURE;
        }

        if ($isDoVerify) {
            $pt_ConfigVerify = $rsgallery2Config->readDbExtensionParaDirect();

            $verifiedValue = $pt_ConfigVerify [$option];
            if ($verifiedValue == null) {
                $this->ioStyle->error("Option '{$option}' was  not set or is null");
            }

            if ($verifiedValue != $value) {
                $this->ioStyle->error(
                    "Configuration set for "
                    . "option: '" . $option . "' in value: '" . $value . "'" . " results in table value: '" . $verifiedValue . "'",
                );
            } else {
                $this->ioStyle->note('Written value confirmed');
            }
        }

        $this->ioStyle->success('Configuration set');

        return Command::SUCCESS;
    }

    /**
     * Sanitize the options array for boolean
     *
     * @param   array  $option  Options array
     *
     *dat
     * @return mixed
     *
     * @since  4.0.X
     */
    private function sanitizeValue($value): mixed
    {
        $value = match (true) {
            $value === 'false' => false,
            $value === 'true' => true,
            $value === 'null' => null,
            default => $value,
        };

        return $value;
    }

    /**
     * Check string input for true (1)
     *
     * @param   mixed  $verifyIn
     *
     * @return bool
     *
     * @since  5.1.0
     */
    private function isTrue(mixed $verifyIn)
    {
        $isTrue = false;

        if (!empty($verifyIn)) {
            if (strtolower((string)$verifyIn) == 'true') {
                $isTrue = true;
            }

            if (strtolower((string)$verifyIn) == 'on') {
                $isTrue = true;
            }

            // ToDo: positive ?
            if ($verifyIn == '1') {
                $isTrue = true;
            }
        }

        return $isTrue;
    }
}
