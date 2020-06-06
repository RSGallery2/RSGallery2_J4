<?php

/**
 * @package    com_rsgallery2
 *
 * @author     RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2020 RSGallery2 Team
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://www.rsgallery2.org
 */
// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

use Joomla\Component\Rsgallery2\Administrator\Helper\InstallMessage;
use Joomla\Component\RSGallery2\Administrator\Model\ConfigRawModel;

// ToDo: More logs after action

/**
 * Script file of Rsgallery2 Component
 *
 * @since  version
 *
 */
class Com_Rsgallery2InstallerScript
{
    protected $newRelease;
    protected $oldRelease;

    protected $oldManifestData;

    /**
     * @var string
     * @since version
     */
    private $minimumJoomla;
    /**
     * @var string
     * @since version
     */
    private $minimumPhp;


    /**
     * Extension script constructor.
     *
     * @since  version
     *
     */
    public function __construct()
    {
        $this->minimumJoomla = '4.0';
        $this->minimumPhp = JOOMLA_MINIMUM_PHP;

        // Check if the default log directory can be written to, add a logger for errors to use it
        if (is_writable(JPATH_ADMINISTRATOR . '/logs')) {
            $logOptions['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
            $logOptions['text_file'] = 'rsg2_install.php';
            $logType = Log::ALL;
            $logChannels = ['rsg2']; //jerror ...
            Log::addLogger($logOptions, $logType, $logChannels);

            try {
                Log::add(Text::_('Installer construct'), Log::INFO, 'rsg2');
            } catch (RuntimeException $exception) {
                // Informational log only
            }
        }
    }

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
     * Function to act prior to installation process begins
     *
     * @param string $type Which action is happening (install|uninstall|discover_install|update)
     * @param Installer $installer The class calling this method
     *
     * @return  boolean  True on success
     *
     * @throws Exception
     * @since   3.7.0
     */
    public function preflight($type, $installer)
    {
        // Check for the minimum PHP version before continuing
        if (!empty($this->minimumPhp) && version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
            Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');

            return false;
        }

        // Check for the minimum Joomla version before continuing
        if (!empty($this->minimumJoomla) && version_compare(JVERSION, $this->minimumJoomla, '<')) {
            Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');

            return false;
        }

        //--- new release version --------------------------------------

        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

        $manifest = $installer->getManifest();
        $this->newRelease = (string)$manifest->version;

        Log::add('newRelease:' . $this->newRelease, Log::INFO, 'rsg2');

        //--- old release version --------------------------------------

        $this->oldRelease = '';

        if ($type === 'update') {
            //--- Read manifest  with old version ------------------------

            $this->oldRelease = $this->getVersionFromManifestParam();

            // old release not found but rsgallery2 data still kept in database -> error message
            if (empty ($this->oldRelease)) {
                JFactory::getApplication()->enqueueMessage('Can not install RSG2: Old Rsgallery2 data found in db or RSG2 folders. Please try to deinstall previous version or remove folder artifacts', 'error');

                return false;
            }
        }

        Log::add('oldRelease:' . $this->oldRelease, Log::INFO, 'rsg2');

        // COM_RSGALLERY2_PREFLIGHT_INSTALL_TEXT / COM_RSGALLERY2_PREFLIGHT_UPDATE_TEXT
        // COM_RSGALLERY2_PREFLIGHT_UNINSTALL_TEXT
        // echo Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT');

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
     * @since  version
     *
     */
    public function install($parent)
    {
//		echo Text::_('COM_RSGALLERY2_INSTALL_TEXT');
        Log::add(Text::_('COM_RSGALLERY2_INSTALL_TEXT'), Log::INFO, 'rsg2');

        //
        $isGalleryTreeCreated = $this->InitGalleryTree();

        return true;
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
     * @since  version
     *
     */
    public function update($parent)
    {
        // echo Text::_('COM_RSGALLERY2_UPDATE_TEXT');
        Log::add(Text::_('COM_RSGALLERY2_UPDATE_TEXT'), Log::INFO, 'rsg2');

        // ToDo: move installler / update
        $isGalleryTreeCreated = $this->InitGalleryTree();

        return true;
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
     * @since  version
     *
     */
    public function postflight($type, $parent)
    {
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_POSTFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

        $installMessage = new InstallMessage ($this->newRelease, $this->oldRelease);
        $msg = $installMessage->installMessageText($type);
        echo $msg;

        switch ($type)
        {

            case 'install':
                // insert configuration standard values
/**
                //$configModel = $this->getModel('ConfigRaw');
                $configModel = $this->getModel('ConfigRaw', 'Rsgallery2Model', array('ignore_request' => true))
                $isSaved = $configModel->ResetConfigToDefault();

                if ($isSaved) {
                    // config saved message
                    $msg .= '<br><br>' . Text::_('Configuration parameters resetted to default', true);
                }
                else
                {
                    $msg .= "Error at resetting configuration to default'";
                    $msgType = 'warning';
                }
                echo $msg;

                break;
/**/
            case 'update':

                break;

            case 'discover_install':

                break;

            default:

                break;
        }



        echo '<br>&oplus;&infin;&omega;';


        return true;
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
     * @since  version
     *
     */
    public function uninstall($parent)
    {
        //echo Text::_('COM_RSGALLERY2_UNINSTALL_TEXT');
        Log::add(Text::_('COM_RSGALLERY2_UNINSTALL_TEXT'), Log::INFO, 'rsg2');

        return true;
    }

    /**
     * InitGalleryTree
     * Intializes the nested tree with a root element
     *
     * @return bool
     * @throws Exception
     *
     * @since
     */
    public function InitGalleryTree()
    {
        $isGalleryTreeCreated = false;

        $id_galleries = '#__rsg2_galleries';

        try {
            $db = Factory::getDbo();

            Log::add('InitGalleryTree', Log::INFO, 'rsg2');
            // echo '<p>Checking if the root record is already present ...</p>';

            // Id of binary root element
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from($id_galleries);
            $query->where('id = 1');
            $query->where('alias = "galleries-root-alias"');
            $db->setQuery($query);
            $id = $db->loadResult();

            if ($id == '1') {   // assume tree structure already built
                Log::add('Gallery table root record already present exiting ...', Log::INFO, 'rsg2');
            } else {
                // -- INSERT INTO `#__rsg2_galleries` (`name`,`alias`,`description`, `parent_id`, `level`, `path`, `lft`, `rgt`) VALUES
                // -- ('galleries root','galleries-root-alias','startpoint of list', 0, 0, '', 0, 1);

                // insert root record
                // Missing
                $columns = array('id', 'name', 'alias', 'description', 'note', 'params', 'parent_id', 'level', 'path', 'lft', 'rgt');
                $values = array(1, 'galleries root', 'galleries-root-alias', 'root element of nested list', '', '', 0, 0, '', 0, 1);

                // Create root element
                $query = $db->getQuery(true)
                    ->insert('#__rsg2_galleries')
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $db->quote($values)));
                $db->setQuery($query);
                $result = $db->execute();
                if ($result) {
                    $isGalleryTreeCreated = true;
                } else {
                    Factory::getApplication()->enqueueMessage("Failed writing root into gallery database", 'error');
                }
            }
        } //catch (\RuntimeException $e)
        catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . ' from InitGalleryTree');
        }

        return $isGalleryTreeCreated;
    }

    /**
     * are_RSG2_J3x_Tables_Existing
     * Checks for old config table. If it exists it is assumed that all joomla 3 x and older tables
     * @return bool
     * @throws Exception
     *
     * @since version
     */
    public function update_config_On_RSG2_J3x_Tables_Existing()
    {
        $isOldGalleryTableExisting = false;

        try {
            Log::add('Check for existing old J3x Tables', Log::INFO, 'rsg2');

            $j3x_model = new \Joomla\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel;
            Log::add('after $j3x_model', Log::INFO, 'rsg2');

            $isOldGalleryTableExisting = $j3x_model->J3xConfigTableExist();

            // prepare taking over old
            if ($isOldGalleryTableExisting) {

                Log::add('!!! Old J3x tables do exist !!!', Log::INFO, 'rsg2');

//			    // already updated ?
//
//                $rsgConfig = ComponentHelper::getParams('com_rsgallery2');
//                $j3xConfigVersion = $rsgConfig->get('j3x_merged_cfg_version');
//
//                // config not set already
//                if (empty ($j3xConfigVersion)) {
//                    Log::add('Merge J3x config required', Log::INFO, 'rsg2');
//
//
//
//
//                    //$j3x_model->copyOldItems2New ();
//                    Log::add('after copyOldItems2New', Log::INFO, 'rsg2');
//                    Log::add('$doesExist: ' .  $doesExist, Log::INFO, 'rsg2');
//
//
//
//                }
//                else
//                {
//                    Log::add('Merge J3x config already done: cfg version: ' . $j3xConfigVersion, Log::INFO, 'rsg2');
//                }
            } else {
                Log::add('Old J3x tables do NOT exist', Log::INFO, 'rsg2');
            }
        } //catch (\RuntimeException $e)
        catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . ' from update_config_On_RSG2_J3x_Tables_Existing');
        }

        return $isOldGalleryTableExisting;
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
            $db = Factory::getDbo();
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

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $manifest;
    }


}
