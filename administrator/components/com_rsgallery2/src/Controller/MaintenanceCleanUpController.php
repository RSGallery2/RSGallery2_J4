<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Input\Input;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;

/**
 * Clean up and prepare for uninstall of RSG2
 * Example: Change file uninstall.mysql.utf8.sql so it does remove the RSG2 Tables
 *
     * @since      5.1.0
 */
class MaintenanceCleanUpController extends BaseController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     *                                         Recognized key values include 'name', 'default_task', 'model_path', and
     *                                         'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input                $input    Input
     *
     * @since   5.1.0     *
     * public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
     * {
     * parent::__construct($config, $factory, $app, $input);
     *
     * //        // Map the apply task to the save method.
     * //        $this->registerTask('apply', 'save');
     * }
     * /**/

    /**
     * remove data in  tables
     *
     * @since 5.1.0     */
    public function purgeImagesAndData()
    {
        $msg     = "MaintenanceCleanUp.purgeImagesAndData: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $msg .= 'is prepared but not activated and tested yet';

                //--- Delete images references in database --------------

                $imageModel = $this->getModel('MaintRemoveAllData');
                [$isRemoved, $msgTmp] = $imageModel->removeDataInTables();
                $msg .= $msgTmp;

                //--- Delete all images --------------------------------

                if ($isRemoved) {
                    [$isRemoved, $msgTmp] = $imageModel->removeAllImageFiles();
                    $msg .= $msgTmp;

                    if ($isRemoved) {
                        //--- purge message -----------------------------------
                        $msg .= '<br><br>' . Text::_('COM_RSGALLERY2_PURGED', true);
                    }
                }

                if (!$isRemoved) {
                    //$msgType = 'warning';
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing purgeImagesAndData: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Change file uninstall.mysql.utf8.sql so it does remove the RSG2 Tables
     *
     * @throws \Exception
     *
     * @since  5.1.0     */
    public function prepareRemoveTables()
    {
        $msg     = "MaintenanceCleanUp.prepareRemoveTables: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $isUpdated = $this->activateDrop4RSG2Tables(true);

                if ($isUpdated) {
                    $msg .= "Successful activated drop of tables on uninstall";
                } else {
                    $msg     .= "Error at activation 'drop of tables on uninstall'";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing prepareRemoveTables: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Change file uninstall.mysql.utf8.sql so it does not remove the RSG2 Tables
     *
     * @since 5.1.0     */
    public function undoPrepareRemoveTables()
    {
        $msg     = "MaintenanceCleanUp.undoPrepareRemoveTables: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $isUpdated = $this->activateDrop4RSG2Tables(false);

                if ($isUpdated) {
                    $msg .= "Successful prevented drop of tables on uninstall";
                } else {
                    $msg     .= "Error at prevent 'drop of tables on uninstall'";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing undoPrepareRemoveTables: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        $this->setRedirect($link, $msg, $msgType);
    }

    // uninstall.mysql.utf8.sql

    /**
     * @param $DoDrop
     *
     * @return bool
     *
     * @throws \Exception
     * @since  5.1.0     */
    private function activateDrop4RSG2Tables($DoDrop = true)
    {
        $isOk = false;

        $dropText      = 'DROP TABLE IF EXISTS ';
        $doNotDropText = '#DROP TABLE IF EXISTS ';

        try {
            $sqlUninstallFile = JPATH_COMPONENT_ADMINISTRATOR . '/sql/uninstall.mysql.utf8.sql';

            // file found
            if (file_exists($sqlUninstallFile)) {
                // Read all lines
                $lines = file($sqlUninstallFile);

                // content found
                if (!empty($lines)) {
                    /*-------------------------------------------------------------------
                    Write back all (changed) lines
                    -------------------------------------------------------------------*/

                    $fp = fopen($sqlUninstallFile, 'w');

                    // check all lines for drop ....
                    foreach ($lines as $line) {
                        $changedLine = $line;

                        if ($DoDrop) {
                            // activate drop tables on uninstall RSG2
                            $changedLine = str_replace($doNotDropText, $dropText, $line);
                        } else {
                            // do not drop tables on uninstall RSG2

                            // ? is not already set
                            if (strpos($line, $doNotDropText) === false) {
                                $changedLine = str_replace($dropText, $doNotDropText, $line);
                            }
                        }

                        fwrite($fp, $changedLine);
                    }

                    fclose($fp);

                    $isOk = true;
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing activateDrop4RSG2Tables: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isOk;
    }

    /**
     * Extract configuration variables from RSG2 config file to reset to original values
     *
     * @throws \Exception
     *
     * @since  5.1.0     */
    public function ResetConfigToDefault()
    {
        $isOk = false;

        $msg     = "MaintenanceCleanUp.ResetConfigToDefault: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $configModel = $this->getModel('ConfigRaw');
                $isSaved     = $configModel->ResetConfigToDefault();

                if ($isSaved) {
                    // config saved message
                    $msg .= '<br><br>' . Text::_('Configuration parameters resetted to default', true);
                } else {
                    $msg     .= "Error at resetting configuration to default'";
                    $msgType = 'warning';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing ResetConfigToDefault: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        $this->setRedirect($link, $msg, $msgType);
    }
} // class
