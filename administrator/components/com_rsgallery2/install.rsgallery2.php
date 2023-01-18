<?php
/**
 * This file contains the install routine for RSGallery2
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2020 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 *
 */
defined('_JEXEC') or die;

// Include the JLog class.
jimport('joomla.log.log');

// Get the date for log file name
$date = JFactory::getDate()->format('Y-m-d');

// Add the logger.
JLog::addLogger(
// Pass an array of configuration options
    array(
        // Set the name of the log file
        //'text_file' => substr($application->scope, 4) . ".log.php",
        'text_file' => 'rsgallery2.install.log.' . $date . '.php',

        // (optional) you can change the directory
        // 'text_file_path' => 'logs'
    ),
    //JLog::ALL ^ JLog::DEBUG, // leave out database messages
    //JLog::ALL, //
    JLog::ALL //
// The log category/categories which should be recorded in this file
// In this case, it's just the one category from our extension, still
// we need to put it inside an array
// array('com_rsgallery2')
);

// start logging... , 'com_rsgallery2'
JLog::add('-------------------------------------------------------', JLog::DEBUG);
JLog::add('Starting to log install.rsgallery2.php for installation X', JLog::DEBUG);

/**
 * Class com_rsgallery2InstallerScript
 */
class com_rsgallery2InstallerScript
{

    // ToDo: use information on links and use it on all following functions
    // http://docs.joomla.org/J2.5:Managing_Component_Updates_%28Script.php%29

    // http://www.joomla-wiki.de/dokumentation/Joomla!_Programmierung/Programmierung/Aktualisierung_einer_Komponente/Teil_3

// ToDO: #__schemas" Tabelle reparieren ??? -> http://vi-solutions.de/de/enjoy-joomla-blog/116-knowledgbase-tutorials

    protected $newRelease;
    protected $oldRelease;
    protected $minimum_joomla_release;
    protected $actual_joomla_release;

    // 	protected $;
    // 	protected $;
    // 	protected $;

    /*-------------------------------------------------------------------------
    preflight
    ---------------------------------------------------------------------------
    This is where most of the checking should be done before install, update
    or discover_install. Preflight is executed prior to any Joomla install,
    update or discover_install actions. Preflight is not executed on uninstall.
    A string denoting the type of action (install, update or discover_install)
    is passed to preflight in the $type operand. Your code can use this string
    to execute different checks and responses for the three cases.
    -------------------------------------------------------------------------*/

    /**
     * Function called before extension installation/update/removal procedure commences
     *
     * @param string $type The type of change (install, update or discover_install, not uninstall)
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @throws Exception
     * @since  1.0.0
     *
     */
    public function preflight($type, $parent)
    {
        JLog::add('preflight: ' . $type, JLog::DEBUG);

        // this component does not work with Joomla releases prior to 3.0
        // abort if the current Joomla release is older
        $jversion = new JVersion();

        // Installing component manifest file version
        $this->newRelease = $parent->get("manifest")->version;

        // Manifest file minimum Joomla version
        $this->minimum_joomla_release = $parent->get("manifest")->attributes()->version;
        $this->actual_joomla_release = $jversion->getShortVersion();

        // Show the essential information at the install/update back-end
        $NextLine = 'Installing component manifest file version = ' . $this->newRelease;
        echo '<br/>' . $NextLine;
        JLog::add($NextLine, JLog::DEBUG);
        JLog::add('Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release, JLog::DEBUG);
        JLog::add('Current Joomla version = ' . $this->actual_joomla_release, JLog::DEBUG);

        // Abort if the current Joomla release is older
        if (version_compare($this->actual_joomla_release, $this->minimum_joomla_release, 'lt')) {
            echo '    Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release;
            echo '    Current Joomla version = ' . $this->actual_joomla_release;
            JFactory::getApplication()->enqueueMessage('Cannot install com_rsgallery2 in a Joomla release prior to ' . $this->minimum_joomla_release, 'warning');
            return false;
        }

        JLog::add('After joomla version compare', JLog::DEBUG);

        if ($type == 'update') {
            JLog::add('-> pre update', JLog::DEBUG);

            $this->oldRelease = $this->getVersionFromManifestParam();

            // old release not found but rsgallery2 data still kept in database -> error message
            if (empty ($this->oldRelease)) {
                JFactory::getApplication()->enqueueMessage('Can not install: Old Rsgallery2 data found in db or RSG2 folders. Please try to deinstall previous version or remove folder artifacts', 'error');

                return false;
            }

            $NextLine = 'Old/current component version (manifest cache) = ' . $this->oldRelease;
            echo '<br/>' . $NextLine;
            JLog::add($NextLine, JLog::DEBUG);

            $rel = $this->oldRelease . ' to ' . $this->newRelease;

            // Abort if the component being installed is older than the currently installed version
            // (overwrite same version is permitted)
            if (version_compare($this->newRelease, $this->oldRelease, 'lt')) {
                JFactory::getApplication()->enqueueMessage('Incorrect version sequence. Cannot upgrade ' . $rel, 'warning');

                return false;
            }

            $NextLine = JText::_('COM_RSGALLERY2_PREFLIGHT_UPDATE_TEXT') . ' ' . $rel;
            echo '<br/>' . $NextLine . '<br/>';
            JLog::add($NextLine, JLog::DEBUG);

            //--------------------------------------------------------------------------------
            // Check if version is already set in "_schemas" table
            // Create table #__schema entry for rsgallery2 if not used before
            //--------------------------------------------------------------------------------

            //--- Determine rsgallery2 extension id ------------------
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName('extension_id'))
                ->from('#__extensions')
                ->where($db->quoteName('type') . ' = ' . $db->quote('component')
                    . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2')
                    . ' AND ' . $db->quoteName('name') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);
            $Rsg2id = $db->loadResult();
            JLog::add('Rsg2id for Schema: ' . $Rsg2id, JLog::DEBUG);

            //--- Read SchemaVersion ------------------
            //--- Check if entry in _schemas table exists ------------------

            $query->clear()
                ->select('count(*)')
                ->from($db->quoteName('#__schemas'))
                ->where($db->quoteName('extension_id') . ' = ' . $db->quote($Rsg2id));
            $db->setQuery($query);
            $SchemaVersionCount = $db->loadResult();
            JLog::add('SchemaVersionCount: ' . $SchemaVersionCount, JLog::DEBUG);

            // Create component entry (version) in __schemas
            // Rsg2id not set
            if ($SchemaVersionCount != 1) {
                JLog::add('Create RSG2 version in __schemas: ', JLog::DEBUG);

                //	UPDATE #__schemas SET version_id = 'NEWVERSION' WHERE extension_id = 700
                $query->clear()
                    ->insert($db->quoteName('#__schemas'))
                    ->columns(array($db->quoteName('extension_id'), $db->quoteName('version_id')))
                    ->values($Rsg2id . ', ' . $db->quote($this->oldRelease));
                $db->setQuery($query);
                $db->execute();
            }

            //--------------------------------------------------------------------------------
            // Check for old version where additional db action is needed
            // Shall care for issue(s) when a user directly upgrades from J1.5 to J3
            //--------------------------------------------------------------------------------

            if (version_compare($this->oldRelease, '3.2.0', 'lt')) {

                require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.upgrade.To.03.02.00.php');
                $upgrade_to_03_02_00 = new upgrade_com_rsgallery2_03_02_00 ();
                $failed = $upgrade_to_03_02_00->upgrade($this->oldRelease);
            }

        } else { // $type == 'install'
            JLog::add('-> pre freshInstall', JLog::DEBUG);
            $rel = $this->newRelease;

            // Remove accidentally left overs (Image Files or Database) -> uncomment for use
            //    Only for developers use !!!
            // RemoveManualInstallationParts ()

            $NextLine = JText::_('COM_RSGALLERY2_PREFLIGHT_INSTALL_TEXT') . ' ' . $rel;
            echo '<br/>' . $NextLine . '<br/>';
            JLog::add($NextLine, JLog::DEBUG);
        }

        JLog::add('exit preflight', JLog::DEBUG);

        return true;
    }

    /*-------------------------------------------------------------------------
    install
    ---------------------------------------------------------------------------
    Install is executed after the Joomla install database scripts have
    completed. Returning 'false' will abort the install and undo any changes
    already made. It is cleaner to abort the install during preflight, if
    possible. Since fewer install actions have occurred at preflight, there
    is less risk that that their reversal may be done incorrectly.
    -------------------------------------------------------------------------*/
    /**
     * Method to install the extension
     *
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  1.0.0
     */
    public function install($parent)
    {
        JLog::add('install', JLog::DEBUG);

        require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php');

        JLog::add('freshInstall', JLog::DEBUG);

        //Initialize install
        $rsgInstall = new rsgInstall();
        $rsgInstall->freshInstall();

        //--- install complete message --------------------------------

        // Now wish the user good luck and link to the control panel
        echo $rsgInstall->installCompleteMsg(JText::_('COM_RSGALLERY2_INSTALLATION_OF_RSGALLERY_IS_COMPLETED'));

        echo '<p>' . JText::_('COM_RSGALLERY2_INSTALL_TEXT') . '</p>';
        JLog::add('Before redirect', JLog::DEBUG);

        // Jump directly to the newly installed component configuration page
        // $parent->getParent()->setRedirectURL('index.php?option=com_rsgallery2');

        JLog::add('exit install', JLog::DEBUG);
    }

    /*-------------------------------------------------------------------------
    update
    ---------------------------------------------------------------------------
    Update is executed after the Joomla update database scripts have completed.
    Returning 'false' will abort the update and undo any changes already made.
    It is cleaner to abort the update during preflight, if possible. Since
    fewer update actions have occurred at preflight, there is less risk that
    that their reversal may be done incorrectly.
    -------------------------------------------------------------------------*/
    /**
     * Method to update the extension
     *
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  1.0.0
     *
     */
    public function update($parent)
    {
        JLog::add('do update', JLog::DEBUG);

        require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/VersionId.php');
        require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php');

        // now that we know a previous rsg2 was installed, we need to reload it's config
        global $rsgConfig;
        $rsgConfig = new rsgConfig();

        //--- Initialize install  --------------------------------------------

        $rsgInstall = new rsgInstall();
        $rsgInstall->writeInstallMsg(JText::sprintf('COM_RSGALLERY2_MIGRATING_FROM_RSGALLERY2', $this->oldRelease), 'ok');

        /* Removed as plugins couldn't find lang files *
        //--- delete RSG2 J!1.5 language files ------------------------------

        // .../administrator/language/
        $startDir = JPATH_ADMINISTRATOR . '/language';
        $msg = '';
        $IsDeleted = $this->findAndDelete_1_5_LangFiles ($startDir, $msg);
        if($IsDeleted) {
            // Write action to user
            $msg = 'Deleted old RSGallery2 J!1.5 admin language files: <br>' . $msg;
            $rsgInstall->writeInstallMsg ($msg, 'ok');
        }

        $startDir = JPATH_SITE . '/language';
        $msg = '';
        $IsDeleted = $this->findAndDelete_1_5_LangFiles ($startDir, $msg);
        if($IsDeleted) {
            // Write action to user
            $msg = 'Deleted old RSGallery2 J!1.5 site language files: <br>' . $msg;
            $rsgInstall->writeInstallMsg ($msg, 'ok');
        }
        /**/

        //--- install complete message --------------------------------

        // Now wish the user good luck and link to the control panel
        echo $rsgInstall->installCompleteMsg(JText::_('COM_RSGALLERY2_RSGALLERY_UPGRADE_IS_INSTALLED'));

        /* May be used later. Actual versions older then "3.2.0" are checked in preflight
            if (version_compare ($this->oldRelease, '3.2.0', 'lt' )) {

        actual
        JLog::add('Before migrate', JLog::DEBUG);

        //Initialize rsgallery migration
        $migrate_com_rsgallery = new migrate_com_rsgallery();

        JLog::add('Do migrate', JLog::DEBUG);
        //Migrate from earlier version
        $result = $migrate_com_rsgallery->migrate();

        if( $result === true ){
            $rsgInstall->writeInstallMsg( JText::sprintf('COM_RSGALLERY2_SUCCESS_NOW_USING_RSGALLERY2', $rsgConfig->get( 'version' )), 'ok');
        }
        else{
            $result = print_r( $result, true );
            $rsgInstall->writeInstallMsg( JText::_('COM_RSGALLERY2_FAILURE')."\n<br><pre>$result\n</pre>", 'error');
        }
        */

        JLog::add('view update text', JLog::DEBUG);
        echo '<p>' . JText::_('COM_RSGALLERY2_UPDATE_TEXT') . '</p>';

        JLog::add('exit update', JLog::DEBUG);
    }

    /*-------------------------------------------------------------------------
    postflight
    ---------------------------------------------------------------------------
    Postflight is executed after the Joomla install, update or discover_update
    actions have completed. It is not executed after uninstall. Postflight is
    executed after the extension is registered in the database. The type of
    action (install, update or discover_install) is passed to postflight in
    the $type operand. Postflight cannot cause an abort of the Joomla
    install, update or discover_install action.
    -------------------------------------------------------------------------*/
    /**
     * Function called after extension installation/update/removal procedure commences
     *
     * @param string $type The type of change (install, update or discover_install, not uninstall)
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  1.0.0
     *
     */
    public function postflight($type, $parent)
    {
        JLog::add('postflight', JLog::DEBUG);
        echo '<p>' . JText::_('COM_RSGALLERY2_POSTFLIGHT_' . strtoupper($type) . '_TEXT') . '</p>';

        if ($type == 'update') {
            JLog::add('-> post update', JLog::DEBUG);

            // $this->installComplete(JText::_('COM_RSGALLERY2_UPGRADE_SUCCESS'));
        } else { // $type == 'install'
            JLog::add('-> post freshInstall', JLog::DEBUG);

            //$this->installComplete(JText::_('COM_RSGALLERY2_INSTALLATION_OF_RSGALLERY_IS_COMPLETED'));
        }

        JLog::add('exit postflight', JLog::DEBUG);
    }

    /*-------------------------------------------------------------------------
    uninstall
    ---------------------------------------------------------------------------
    The uninstall method is executed before any Joomla uninstall action,
    such as file removal or database changes. Uninstall cannot cause an
    abort of the Joomla uninstall action, so returning false would be a
    waste of time
    -------------------------------------------------------------------------*/
    /**
     * Method to uninstall the extension
     *
     * @param InstallerAdapter $parent The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  1.0.0
     */
    public function uninstall($parent)
    {
        JLog::add('uninstall', JLog::DEBUG);
        echo '<p>' . JText::_('COM_RSGALLERY2_UNINSTALL_TEXT') . '</p>';
        JLog::add('exit uninstall', JLog::DEBUG);
    }

    function getVersionFromManifestParam()
    {
        //$oldRelease = '1.0.0.999';
        $oldRelease = '';

        $this->oldManifestData = $this->readRsg2ExtensionManifest();
        if (!empty ($this->oldManifestData['version'])) {
            $oldRelease = $this->oldManifestData['version'];
        }

        return $oldRelease;
    }

    static function readRsg2ExtensionManifest()
    {
        $manifest = [];

        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('manifest_cache')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('name') . ' = ' . $db->quote('COM_RSGALLERY2'));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();
            // $result = $db->loadObjectList()

            if (!empty ($jsonStr)) {
                $manifest = json_decode($jsonStr, true);
            }

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'readRsg2ExtensionManifest: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $manifest;
    }





    /*
     * sets parameter values in the component's row of the extension table
     */
    /**
     * @param $param_array
     */
    function setParams($param_array)
    {
        if (count($param_array) > 0) {
            // read the existing component value(s)
            $db = JFactory::getDbo();
            $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_rsgallery2"');
            $params = json_decode($db->loadResult(), true);

            // add the new variable(s) to the existing one(s)
            foreach ($param_array as $name => $value) {
                $params[(string)$name] = (string)$value;
            }
            // store the combined new and existing values back as a JSON string
            $paramsString = json_encode($params);
            $db->setQuery('UPDATE #__extensions SET params = ' .
                $db->quote($paramsString) .
                ' WHERE name = "com_rsgallery2"');
            $db->execute();
        }
    }


    /**
     * @param $startDir Example: \administrator\language\
     * recursive delete joomla 1.5 version or older style component language files
     * @since 4.3
     */
    public function findAndDelete_1_5_LangFiles($startDir, &$msg)
    {

        $IsDeleted = false;

        if ($startDir != '') {
            // ...original function code...
            // ...\en-GB\en-GB.com_rsgallery2.ini
            // ...\en-GB\en-GB.com_rsgallery2.sys.ini
            $files = array();

            $Directories = new RecursiveDirectoryIterator($startDir, FilesystemIterator::SKIP_DOTS);
            $Files = new RecursiveIteratorIterator($Directories);
            $LangFiles = new RegexIterator($Files, '/^.+\.com_rsgallery2\..*ini$/i', RecursiveRegexIterator::GET_MATCH);

            $msg = '';
            $IsFileFound = false;
            foreach ($LangFiles as $LangFile) {
                $IsFileFound = true;

                $msg .= '<br>' . $LangFile[0];
                $IsDeleted = unlink($LangFile[0]);
                if ($IsDeleted) {
                    $msg .= ' is deleted';

                } else {
                    $msg .= ' is not deleted';
                }
            }

            return $IsFileFound;
        }
    }


}
