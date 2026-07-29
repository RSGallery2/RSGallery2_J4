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

class ConfigReset extends AbstractCommand
{
//    use DatabaseAwareTrait;

    /**
     * The default command name
     *
     * @var    string
     */
    protected static $defaultName = 'rsgallery2:config:reset';

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
        $this->addArgument('option', InputOption::VALUE_OPTIONAL, '');

        $this->setDescription(Text::_('Reset the RSG2 parameters to config.xml values or do clear'));

        $help = "<info>%command.name%</info> Reset the RSG2 configuration parameters to values defined in config.xml or to an empty set
  Usage: <info>php %command.full_name%</info> <option>
    * On use of the option string 'emptyDb' The parameters in the extension
      table will be removed and leave an empty table";

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
            $this->ioStyle->error('ConfigReset.doExecute ' . $e->getMessage());
            return Command::FAILURE;
        }

        //--- prepare params for writing ---------------------------------------

        // standard from config.xml
        $xmlParams = $rsgallery2Config->getConfigXmlParameter();

        // do empty extension table parameters
        if (!empty($option) && $option == 'emptyDb') {

            $xmlParams = new Registry();

            $this->ioStyle->warning("RSGallery2 component (DB) parameter are deleted and not initialized now. Please save it once.");
        } else {
            $this->ioStyle->note("RSGallery2 component (DB) parameter are now initialized from file config.xml.");
        }

        //--- save xml values ---------------------------------------------------------

        $isSuccess = $rsgallery2Config->saveDbParams($xmlParams);

        if (empty($isSuccess)) {
            $this->ioStyle->error("Could not save RSGallery2 configuration parameters");

            return Command::FAILURE;
        }

        $this->ioStyle->success('Configuration set');

        return Command::SUCCESS;
    }

}
