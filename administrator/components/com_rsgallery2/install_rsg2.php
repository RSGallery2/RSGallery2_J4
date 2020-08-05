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

//JLoader::registerNamespace('Rsgallery2', __DIR__ .'/administrator/components/com_rsgallery2');
//JLoader::registerNamespace('Rsgallery2',  JPATH_ADMINISTRATOR .'/components/com_rsgallery2');

// https://github.com/asikart/windwalker-template/blob/master/admin/flower.php
//use Rsgallery2\Helper\Rsg2InstallTasks;


////use Joomla\Component\Rsgallery2\Administrator\Helper\InstallMessage;
////require_once(dirname(__FILE__) . '/administrator/components/com_rsgallery2/Helper/InstallMessage.php');
//$localDir = str_replace("\\","/",dirname(__FILE__));
//$rsg2FileName = $localDir . '/administrator/components/com_rsgallery2/Helper/InstallMessage.php';

//$rsg2FileName = $localDir . '/administrator/components/com_rsgallery2/Helper/InstallMessage.php';
//$rsg2ClassName = 'InstallMessage';
//require_once($rsg2FileName);
//\JLoader::register($rsg2ClassName, $rsg2FileName);
//\JLoader::load($rsg2ClassName);
//
////use Joomla\Component\RSGallery2\Administrator\Model\ConfigRawModel;

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


    private $rsg2_basePath = '';


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
            } catch (\RuntimeException $exception) {
                // Informational log only
            }
        }

        // when component files are copied
        $this->rsg2_basePath = JPATH_SITE . '/administrator/components/com_rsgallery2';

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

        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

        //--- new release version --------------------------------------

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
                Factory::getApplication()->enqueueMessage('Can not install RSG2: Old Rsgallery2 data found in db or RSG2 folders. Please try to deinstall previous version or remove folder artifacts', 'error');

                // May be error on install ?
                // return false;
            }

            Log::add('oldRelease:' . $this->oldRelease, Log::INFO, 'rsg2');
        }


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
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_INSTALL'), Log::INFO, 'rsg2');

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
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_UPDATE'), Log::INFO, 'rsg2');
        //Log::add(Text::_('COM_RSGALLERY2_UPDATE_TEXT'), Log::INFO, 'rsg2');

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

        switch ($type) {

            case 'install':
                // install: insert configuration standard values  ??? update 3x to 4x ???
                //ToDo: $isConfigSavedOnce; ConfigRawModel->ResetConfigToDefault();

            case 'update':

                // Nested gallery table needs a root item
                $isGalleryTreeCreated = $this->initGalleryTree();

                $installMsg = $this->installMessage($type);
                echo $installMsg;

                echo $type . ' finished';

                break;

            case 'uninstall':

                // ToDo: check existence of galleries/images table and then write
                echo 'Uninstall of RSG2 finished. <br>Configuration may be deleted. <br>'
                    . 'Galleries and images table may still exist';
                // ToDo: uninstall Message

                break;

            case 'discover_install':

                break;

            default:

                break;
        }


        // wonderworld 'good by' icons finnern
        echo '<br><h4>&oplus;&infin;&omega;<h4></h4><br>';

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
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_UNINSTALL'), Log::INFO, 'rsg2');

        return true;
    }

    /**
     * InitGalleryTree
     * Initializes the nested tree with a root element if not already exists
     *
     * @return bool
     * @throws Exception
     *
     * @since version
     */
    public function initGalleryTree()
    {
        $isGalleryTreeCreated = false;

        try {
            $GalleryTreeModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/Model/GalleryTreeModel.php';
            include ($GalleryTreeModelFileName);
            $galleryTreeModel =  new Joomla\Component\Rsgallery2\Administrator\Model\GalleryTreeModel ();

            // check for root item
            $isRootItemExisting = $galleryTreeModel->isRootItemExisting();

            if ($isRootItemExisting) {   // assume tree structure already built
                Log::add('Gallery table root record is already present', Log::INFO, 'rsg2');
                $isGalleryTreeCreated = true;
            } else {

                Log::add('init nested gallery root item', Log::INFO, 'rsg2');

                $isGalleryTreeCreated = $galleryTreeModel->reinitNestedGalleryTable();

                if ($isGalleryTreeCreated) {
                    $isGalleryTreeReset = true;
                    Log::add('Success writing tree root item into gallery database', Log::INFO, 'rsg2');
                } else {
                    //Factory::getApplication()->enqueueMessage("Failed writing root into gallery database", 'error');
                    Log::add('Failed writing tree root item into gallery database', Log::INFO, 'rsg2');
                }
            }

        } //catch (\RuntimeException $e)
        catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . ' from InitGalleryTree');
        }

        return $isGalleryTreeCreated;
    }


    /**
     * InitGalleryTree
     * Initializes the nested tree with a root element if not already exists
     *
     * @return bool
     * @throws Exception
     *
     * @since version
     */
    public function installMessage($type)
    {
        $installMsg = false;

        try {

            $installMsgHelperFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/Helper/InstallMessage.php';
            include ($installMsgHelperFileName);
            $InstallMessageHelper =  new Joomla\Component\Rsgallery2\Administrator\Helper\InstallMessage ($this->newRelease, $this->oldRelease);

            $installMsg = $InstallMessageHelper->installMessageText($type);

        } //catch (\RuntimeException $e)
        catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . ' from InitGalleryTree');
        }

        return $installMsg;
    }


    /**
     *
     * Used in preflight update when the 'new' rsg2 files are not copied
     * Can not use standard function therefore

     * @return mixed|string
     *
     * @throws Exception
     * @since version
     */
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


    /**
     * readRsg2ExtensionManifest
     * Used in preflight update when the 'new' rsg2 files are not copied
     * Can not use standard function therefore
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    function readRsg2ExtensionManifest()
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

            if (!empty ($jsonStr)) {
                $manifest = json_decode($jsonStr, true);
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'readRsg2ExtensionManifest: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $manifest;
    }


}
