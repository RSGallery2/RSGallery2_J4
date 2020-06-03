<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;


/**
 * Clean up and prepare for uninstall of RSG2
 * Example: Change file uninstall.mysql.utf8.sql so it does remove the RSG2 Tables
 *
 * @since  5.0.0
 */
class MaintenanceCleanUpController extends BaseController
{

    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     * Recognized key values include 'name', 'default_task', 'model_path', and
     * 'view_path' (this list is not meant to be comprehensive).
     * @param MVCFactoryInterface $factory The factory.
     * @param CMSApplication $app The JApplication for the dispatcher
     * @param Input $input Input
     *
     * @since   3.0
     *
     * public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
     * {
     * parent::__construct($config, $factory, $app, $input);
     *
     * //        // Map the apply task to the save method.
     * //        $this->registerTask('apply', 'save');
     * }
     * /**/

    /**
     * Change file uninstall.mysql.utf8.sql so it does remove the RSG2 Tables
     *
     * @since 5.0.0
     */
    public function purgeImagesAndData()
    {
        $msg = "MaintenanceCleanUp.purgeImagesAndData: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
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

            } catch (RuntimeException $e) {
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
     * @since 5.0.0
     */
    public function prepareRemoveTables()
    {
        $msg = "MaintenanceCleanUp.prepareRemoveTables: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {

                $isUpdated = $this->activateDrop4RSG2Tables(true);

                if ($isUpdated) {
                    $msg .= "Successful activated drop of tables on uninstall";
                } else {
                    $msg .= "Error at activation 'drop of tables on uninstall'";
                    $msgType = 'error';
                }

            } catch (RuntimeException $e) {
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
     * @since 5.0.0
     */
    public function undoPrepareRemoveTables()
    {
        $msg = "MaintenanceCleanUp.undoPrepareRemoveTables: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {

                $isUpdated = $this->activateDrop4RSG2Tables(false);

                if ($isUpdated) {
                    $msg .= "Successful prevented drop of tables on uninstall";
                } else {
                    $msg .= "Error at prevent 'drop of tables on uninstall'";
                    $msgType = 'error';
                }

            } catch (RuntimeException $e) {
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

    private function activateDrop4RSG2Tables($DoDrop = true)
    {

        $isOk = false;

        $dropText = 'DROP TABLE IF EXISTS `';
        $doNotDropText = '#DROP TABLE IF EXISTS `';

        try {
            $sqlUninstallFile = JPATH_COMPONENT_ADMINISTRATOR . '/sql/uninstall.mysql.utf8.sql';

            // file found
            if (File::exists($sqlUninstallFile)) {

                // Read all lines
                $lines = file($sqlUninstallFile);

                // content found
                if (!empty ($lines)) {

                    /*-------------------------------------------------------------------
                    Write back all (changed) lines
                    -------------------------------------------------------------------*/

                    $fp = fopen($sqlUninstallFile, 'w');

                    // check all lines fro drop ....
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
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing activateDrop4RSG2Tables: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


        return $isOk;
    }

    public function ResetConfigToDefault()
    {
        $isOk = false;

        $msg = "MaintenanceCleanUp.ResetConfigToDefault: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //JFactory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {

                $msg .= 'is prepared but not activated and tested yet. <br>';

                //$xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/Xchangelog.xml';
                $xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/config.xml';

                // Attempt to load the XML file.
                $xmlOuter = simplexml_load_file($xmlFile);
                // If there is nothing to load return
                if (empty($xmlOuter))
                {
                    $msg .= Text::_('Could not find config.xml file. No change applied');
                    $msgType = 'error';

                    $link = 'index.php?option=com_rsgallery2&view=Maintenance';
                    $this->setRedirect($link, $msg, $msgType);
                    return;
                }

                // attribArray if it is an config xml file
                $xpath ="/config";
                $xmlConfig = $xmlOuter->xpath($xpath);

                // If there is nothing to load return
                if (empty($xmlConfig))
                {
                    $msg .= Text::_('Could not read config.xml contents. No change applied');
                    $msgType = 'warning';
                }
                else
                {
// ToDo: put in model
                        //
                        $configFromXml = [];

                        // fetch fields
                        $result = $xmlOuter->xpath("//field");

                        // extract name and value from all fields
                        foreach ($result as $item) {

                            // convert to array
                            $fieldAttributes = current($item->attributes());

                            $type = $fieldAttributes ['type'];

                            // Valid data ?
                            if ($type != 'spacer' && $type != 'note') {

                                $name =  $fieldAttributes ['name'];
                                // default existing ?
                                if (isset ($fieldAttributes ['default'])) {
                                    $value = $fieldAttributes ['default'];
                                } else {
                                    $value = "";
                                }

                                $configFromXml[$name] = $value;
                            }
                        }

                        ;


                        /**/

                    $msg .= Text::_('<br>------------------------------------<br>');
                }




            } catch (RuntimeException $e) {
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
