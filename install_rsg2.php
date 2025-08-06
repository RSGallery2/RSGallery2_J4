<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 *
 * @author          RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c)  2003-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\Filesystem\Folder;

//use Joomla\CMS\File;
//use Joomla\CMS\Folder;

/**
 * Script (install file of Rsgallery2 Component)
 *
 * @since 5.0.0
 *
 */
class Com_Rsgallery2InstallerScript extends InstallerScript
{
    protected $newRelease;
    protected $oldRelease;

    protected $oldManifestData;

    /**
     * @var string
     * @since 5.0.0
     */
    protected $minimumJoomla;
    /**
     * @var string
     * @since 5.0.0
     */
    protected $minimumPhp;

    protected $actualParams;
    protected $defaultParams;
    protected $mergedParams;

    /**
     * Extension script constructor.
     *
     * @since 5.0.0
     *
     */
    public function __construct()
    {
        $this->minimumJoomla = '4.0.0';
        $this->minimumPhp    = JOOMLA_MINIMUM_PHP;   // (7.2.5)

        // Check if the default log directory can be written to, add a logger for errors to use it
        if (is_writable(JPATH_ADMINISTRATOR . '/logs')) {
            // Get the date for log file name
            $date = Factory::getDate()->format('Y-m-d');

            $logOptions['format']    = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
            $logOptions['text_file'] = 'rsg2_install.' . $date . '.php';
            $logType                 = Log::ALL;
            $logChannels             = ['rsg2']; //jerror ...
            Log::addLogger($logOptions, $logType, $logChannels);

            try {
                Log::add(Text::_('\n>>RSG2 Installer construct'), Log::INFO, 'rsg2');
            } catch (RuntimeException $e) {
                // Informational log only
            }
        }

        // when component files are copied
        // $this->rsg2_basePath = JPATH_SITE . '/administrator/components/com_rsgallery2';

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
     * @param   string            $type    Which action is happening (install|uninstall|discover_install|update)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @throws Exception
     * @since 5.0.0
     */
    public function preflight($type, $parent)
    {
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

        if ($type !== 'uninstall') {
            // Check for the minimum PHP version before continuing
            if (version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
                Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');
                Factory::getApplication()->enqueueMessage(
                    Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp)
                    . ' (' . PHP_VERSION . ')',
                    'error',
                );

                return false;
            }

            // Check for the minimum RSG/Joomla version before continuing
            if (version_compare(JVERSION, $this->minimumJoomla, '<=')) {
                Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');
                Factory::getApplication()->enqueueMessage(
                    Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla),
                    'error',
                );

                return false;
            }

            //--- new release version --------------------------------------

            $manifest         = $parent->getManifest();
            $this->newRelease = (string)$manifest->version;

            Log::add('newRelease:' . $this->newRelease, Log::INFO, 'rsg2');

            //--- old release version --------------------------------------

            $this->oldRelease = '';

            if ($type === 'update') {
                Log::add(Text::_('-> pre update'), Log::INFO, 'rsg2');

                // ToDo: use J4 $this->>oldManifestData->Version

                //--- Read manifest with old version ------------------------

                // could also be done by $xml=simplexml_load_file of manfiest on
                // 'old'==actual RSG2 admin path $this->oldRelease = $xml->version;

                $this->oldRelease = $this->getOldVersionFromManifestParam();

                // old release not found but rsgallery2 data still kept in database -> error message
                if (empty ($this->oldRelease)) {
                    $outTxt = 'Can not install RSG2: Old Rsgallery2 data found in db or RSG2 folders. Please try to deinstall previous version or remove folder artifacts';
                    Factory::getApplication()->enqueueMessage($outTxt, 'error');
                    Log::add('oldRelease:' . $outTxt, Log::WARNING, 'rsg2');

                    // May be error on install ?
                    // return false;

                    $this->oldRelease = '%';
                }

                Log::add('oldRelease:' . $this->oldRelease, Log::INFO, 'rsg2');
            } else { // $type == 'install'

                Log::add('-> pre freshInstall', Log::DEBUG);
            }

// !!! ToDo: remove !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//			$this->oldRelease = '4.5.3.0';
// !!! ToDo: remove !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

            Log::add(Text::_('newRelease:') . $this->newRelease, Log::INFO, 'rsg2');

            if ($type === 'update') {
				
                // Previous j3x version:
                if (version_compare($this->oldRelease, '5.0.0', 'lt')) {
					
                    //--- Remove lang files  ---------------------------------------------

                    // Remove old language files (RSG2 J3x) in joomla base lang folders
                    // Valid Lang files now only in the components folder
                    $this->removeAllOldLangFiles();

                    //--- delete not used files ------------------------------------------

                    $this->removeJ3xComponentFiles();
                }
            }
        } // ! uninstall

        Log::add(Text::_('exit preflight') . $this->newRelease, Log::INFO, 'rsg2');

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
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since 5.0.0
     *
     */
    public function install($parent)
    {
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_INSTALL'), Log::INFO, 'rsg2');

        $this->addDashboardMenu('rsgallery2', 'rsgallery2');

        Log::add(Text::_('exit install'), Log::INFO, 'rsg2');

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
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since 5.0.0
     *
     */
    public function update($parent)
    {
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_UPDATE'), Log::INFO, 'rsg2');

        $this->addDashboardMenu('rsgallery2', 'rsgallery2');

        Log::add(Text::_('exit update'), Log::INFO, 'rsg2');

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
     * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since 5.0.0
     *
     */
    public function postflight($type, $parent)
    {
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_POSTFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

        // fall back
        $installMsg = '';

        switch ($type) {
            case 'install':

                Log::add('post->install: init gallery tree', Log::INFO, 'rsg2');

                // Nested gallery table needs a root item
                $isGalleryTreeCreated = $this->initGalleryTree();

                Log::add('post->install: updateDefaultParams', Log::INFO, 'rsg2');

                $this->updateDefaultParams($parent);

                Log::add('post->install: install message', Log::INFO, 'rsg2');

                //--- install message  ----------------------------------------------------

                $installMsg = $this->installMessage($type);

                Log::add('post->install: finished', Log::INFO, 'rsg2');

                break;

            case 'update':

                Log::add('post->update: init gallery tree', Log::INFO, 'rsg2');

                // Nested gallery table needs a root item
                $isGalleryTreeCreated = $this->initGalleryTree();

                Log::add('post->update: updateDefaultParams', Log::INFO, 'rsg2');

                //--- include new default Parameter ----------------------------------------------------

                // Merge existing with default parameter
                $this->updateDefaultParams($parent);

                //--- Previous j3x version: ----------------------------------------------------

// Can't be used as boot rsg2 would be needed and is yet ? partly active ?
//				if (version_compare($this->oldRelease, '5.0.0', 'lt'))
//				{
//					//--- Old J3x config (not galleries, not images) -------------------------------
//
//					// Would like to update galleries and move images too, but it would be
//					// time-consuming. So left out
//
//					// copy J3xConfigParameter config (includes transfer to new names
//					$isCopiedConfig = $this->copyJ3xDbConfigParameter ();
//
//				}

                //--- upgradeSql ----------------------------------------------------

                $hasError = $this->upgradeSql();
                if ($hasError) {
                    // The script failed, tell about it
                    throw new RuntimeException(
                        Text::_('RSG2 upgrade sql fails on install_rsg2. More see log file'),
                    );
                }

                // Merge existing with default parameter
                $this->updateDefaultParams($parent);

                //--- install message  ----------------------------------------------------

                Log::add('post->update: install message', Log::INFO, 'rsg2');
                $installMsg = $this->installMessage($type);

                Log::add('post->update: finished', Log::INFO, 'rsg2');

                break;

            case 'uninstall':

                $outText = 'Uninstall of RSG2 finished. <br>'
                    . 'Configuration was deleted. <br>'
                    . 'Galleries and images table may still exist';
                Log::add('post->uninstall: ' . $outText, Log::INFO, 'rsg2');
                // ToDo: check existence of galleries/images table and then write
                /**
                 * echo 'Uninstall of RSG2 finished. <br>Configuration may be deleted. <br>'
                 * . 'Galleries and images table may still exist';
                 * // ToDo: uninstall Message
                 */
                Factory::getApplication()->enqueueMessage($outText, 'info');

                // $installMsg = $this->uninstallMessage);

                Log::add('post->uninstall: finished', Log::INFO, 'rsg2');

                break;

            case 'discover_install':

                Log::add('post->discover_install: updateDefaultParams', Log::INFO, 'rsg2');

                $this->updateDefaultParams($parent);

                Log::add('post->discover_install: init gallery tree', Log::INFO, 'rsg2');

                // Nested gallery table needs a root item
                $isGalleryTreeCreated = $this->initGalleryTree();

                Log::add('post->discover_install: install message', Log::INFO, 'rsg2');

                //--- install message  ----------------------------------------------------

                $installMsg = $this->installMessage($type);

                Log::add('post->discover_install: finished', Log::INFO, 'rsg2');

                break;

            default:

                break;
        }

        echo $installMsg;

        // wonderworld 'good bye' icons finnern
        echo '<br><h4>&oplus;&infin;&omega;</h4><br>';
        Log::add(Text::_('--- exit postflight ------------'), Log::INFO, 'rsg2');

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
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since 5.0.0
     *
     */
    public function uninstall($parent)
    {
        Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_UNINSTALL'), Log::INFO, 'rsg2');

        // ToDo: enquire .. message to user
        Factory::getApplication()->enqueueMessage(
            Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp),
            'error',
        );

        Log::add(Text::_('exit uninstall'), Log::INFO, 'rsg2');

        return true;
    }

    /**
     * InitGalleryTree
     * Initializes the nested tree with a root element if not already exists
     *
     * @return bool
     * @throws Exception
     *
     * @since 5.0.0
     */
    protected function initGalleryTree()
    {
        $isGalleryTreeCreated = false;

        try {
            Log::add('initGalleryTree: include TreeModel', Log::INFO, 'rsg2');

            $GalleryTreeModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/GalleryTreeModel.php';
            Log::add(Text::_('upd (10.2) '), Log::INFO, 'rsg2');
            $GalleryTreeClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryTreeModel';
            Log::add(Text::_('upd (10.3) '), Log::INFO, 'rsg2');
            JLoader::register($GalleryTreeClassName, $GalleryTreeModelFileName);

//			Log::add(Text::_('upd (10.4) '), Log::INFO, 'rsg2');
//			include($GalleryTreeModelFileName);

            Log::add(Text::_('upd (10.4) '), Log::INFO, 'rsg2');
            $galleryTreeModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryTreeModel ();

            Log::add(Text::_('upd (10.5) '), Log::INFO, 'rsg2');
            Log::add('initGalleryTree: check for root item', Log::INFO, 'rsg2');

            // check for root item
            $isRootItemExisting = $galleryTreeModel->isRootItemExisting();

            if ($isRootItemExisting) {   // assume tree structure already built
                Log::add('initGalleryTree: Gallery table root record is already present', Log::INFO, 'rsg2');
                $isGalleryTreeCreated = true;
            } else {
                Log::add('initGalleryTree: init nested gallery root item', Log::INFO, 'rsg2');

                $isGalleryTreeCreated = $galleryTreeModel->reinitNestedGalleryTable();

                if ($isGalleryTreeCreated) {
                    $isGalleryTreeReset = true;
                    Log::add(
                        'initGalleryTree: Success writing tree root item into gallery database',
                        Log::INFO,
                        'rsg2',
                    );
                } else {
                    //Factory::getApplication()->enqueueMessage("Failed writing root into gallery database", 'error');
                    Log::add('initGalleryTree: Failed writing tree root item into gallery database', Log::INFO, 'rsg2');
                }
            }
        } catch (Exception $e) {
            Log::add(
                Text::_('Exception in initGalleryTree: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );
            throw new RuntimeException($e->getMessage() . ' from initGalleryTree');
        }

        return $isGalleryTreeCreated;
    }

    /**
     * InstallMessage
     * Initializes the nested tree with a root element if not already exists
     *
     * @return bool
     * @throws Exception
     *
     * @since 5.0.0
     */
    protected function installMessage($type)
    {
        $installMsg = false;

        try {
            Log::add('installMessage: include Helper/InstallMessage', Log::INFO, 'rsg2');

            $installMsgHelperFileName  = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Helper/InstallMessage.php';
            $installMsgHelperClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Helper\InstallMessage';
            JLoader::register($installMsgHelperClassName, $installMsgHelperFileName);

            $changeLogModelFileName  = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/ChangeLogModel.php';
            $changeLogModelClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\ChangeLogModel';
            JLoader::register($changeLogModelClassName, $changeLogModelFileName);

            $InstallMessageHelper = new Rsgallery2\Component\Rsgallery2\Administrator\Helper\InstallMessage
            (
                $this->newRelease, $this->oldRelease,
            );

            Log::add('installMessage: create message', Log::INFO, 'rsg2');

            $installMsg = $InstallMessageHelper->installMessageText($type);
        } catch (Exception $e) {
            Log::add(
                Text::_('Exception in installMessage: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );
            throw new RuntimeException($e->getMessage() . ' from installMessage');
        }

        return $installMsg;
    }

    /**
     *
     * Used in preflight update when the 'new' rsg2 files are not copied
     * Can not use standard function therefore
     *
     * @return mixed|string
     *
     * @throws Exception
     * @since 5.0.0
     */
    protected function getOldVersionFromManifestParam()
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
     * @since 5.0.0
     */
    protected function readRsg2ExtensionManifest()
    {
        $manifest = [];

        try {
            // ToDo: !!! $db = Factory::getContainer()->get('DatabaseDriver'); !!!

            // $db    = Factory::getContainer()->get(DatabaseInterface::class);
            // $db = $this->getDatabase();
            $db    = Factory::getDbo();
            $query = $db
                ->getQuery(true)
                ->select('manifest_cache')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();

            if (!empty ($jsonStr)) {
                $manifest = json_decode($jsonStr, true);
            }
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception in readRsg2ExtensionManifest: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );

            $OutTxt = '';
            $OutTxt .= 'readRsg2ExtensionManifest: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $manifest;
    }

    /**
     * Remove old language files of RSG2 J3x stored within joomla
     * standard language folders. Keeping the old files would result in those
     * being loaded instead of the new ones.
     *
     * @since version
     */
    protected function removeAllOldLangFiles(): void
    {
        try {
            Log::add(Text::_('start: removeAllOldLangFiles: '), Log::INFO, 'rsg2');

            //--- administrator\language path ---------------------------------

            $langPath = JPATH_ROOT . '/administrator/' . 'language';

            $isOneFileDeleted = $this->removeLangFilesInSubPaths($langPath);

            //--- site\language path ---------------------------------

            $langPath = JPATH_ROOT . '/' . 'language';

            $isOneFileDeleted = $this->removeLangFilesInSubPaths($langPath);
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception in removeAllOldLangFiles: ') . $e->getMessage()
                . ' \n' . $langPath,
                Log::INFO,
                'rsg2',
            );
        }

        return;
    }

    /**
     * Remove RSG2 language files in given folder or in subfolder
     * (recursive call)
     *
     * @param $langPath
     *
     *
     * @since version
     */
    protected function removeLangFilesInSubPaths(string $langPath): bool
    {
        $isOneFileDeleted = false;

        try {
            Log::add(Text::_('start: removeLangFilesInSubPaths: ') . $langPath, Log::INFO, 'rsg2');

            //--- All matching files in actual folder -------------------

            $files = array_diff(array_filter(glob($langPath . '/*'), 'is_file'), ['.', '..']);

            foreach ($files as $fileName) {
                // A matching lang name ...
                if (str_contains($fileName, 'com_rsgallery2')) {
                    // ... will be deleted
                    if (file_exists($fileName)) {
                        Log::add(Text::_('unlink: ') . $fileName, Log::INFO, 'rsg2');

                        unlink($fileName);
                        $isOneFileDeleted = true;
                    }
                }
            }
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception in removeLangFilesInSubPaths (1): ') . $e->getMessage()
                . ' \n' . $langPath,
                Log::INFO,
                'rsg2',
            );
        }

        try {
            #--- Search in each sub folder -------------------------------------

            // don't search, there is no sub folder
            if (!$isOneFileDeleted) {
                // base folder may contain lang ID folders en-GB, de-DE

                $folders = array_diff(array_filter(glob($langPath . '/*'), 'is_dir'), ['.', '..']);

                foreach ($folders as $folderName) {
// 				echo ('folder name: ' . $folderName . '<br>');

                    // $subFolder = $langPath . "/" . $folderName;
                    //$isOneFileDeleted = removeLangFilesInSubPaths($subFolder);

                    $isOneFileDeleted = $this->removeLangFilesInSubPaths($folderName);
                }
            }
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception in removeLangFilesInSubPaths (2): ') . $e->getMessage()
                . ' \n' . $langPath,
                Log::INFO,
                'rsg2',
            );
        }

        return $isOneFileDeleted;
    }

    /**
     * Remove old component files of j3x start with clean directories
     *
     * @since version
     */
    protected function removeJ3xComponentFiles(): void
    {
        try {
            Log::add(Text::_('start: removeJ3xComponentFiles: '), Log::INFO, 'rsg2');

            //--- administrator\language path ---------------------------------

            $adminRSG2_Path = JPATH_ROOT . '/administrator/components/' . 'com_rsgallery2';

            Log::add(Text::_('upd (50.1) '), Log::INFO, 'rsg2');

            if (is_dir($adminRSG2_Path)) {
                Log::add(Text::_('upd (50.2) '), Log::INFO, 'rsg2');
                Log::add(Text::_('del Folder: ') . $adminRSG2_Path, Log::INFO, 'rsg2');

                $isOk = Folder::delete($adminRSG2_Path);

                if (!$isOk) {
                    Log::add(Text::_('upd (50.3) RSG2 admin not deleted'), Log::INFO, 'rsg2');
                }

                Log::add(Text::_('upd (50.4) '), Log::INFO, 'rsg2');
            }

            //--- site\language path ---------------------------------

            $componentRSG2_Path = JPATH_ROOT . '/components/' . 'com_rsgallery2';

            Log::add(Text::_('upd (50.11) '), Log::INFO, 'rsg2');

            if (is_dir($componentRSG2_Path)) {
                Log::add(Text::_('upd (50.12) '), Log::INFO, 'rsg2');
                Log::add(Text::_('del Folder: ') . $componentRSG2_Path, Log::INFO, 'rsg2');

                $isOk = Folder::delete($componentRSG2_Path);

                if (!$isOk) {
                    Log::add(Text::_('upd (50.12) RSG2 component not deleted'), Log::INFO, 'rsg2');
                }

                Log::add(Text::_('upd (50.13) '), Log::INFO, 'rsg2');
            }

        } catch (RuntimeException $e) {
            Log::add(
                Text::_('\n>> Exception: removeJ3xComponentFiles: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );
        }

        return;
    }

    protected function upgradeSql()
    {
        Log::add(Text::_('start: upgradeSql: '), Log::INFO, 'rsg2');

        $hasError = false;

        try {
            // Previous j3x version:
            if (version_compare($this->oldRelease, '5.0.12.999', 'lt')) {
                $hasError = $this->upgradeSql_j3x_tables();
            }
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception: upgradeSql: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );
        }

        Log::add(Text::_('Exit upgradeSql'), Log::INFO, 'rsg2');

        return $hasError;
    }

    /**
     * Following could not be done in stanadrd SQL script
     * IF (EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = `#__rsgallery2_galleries`))
     * so
     *
     *
     * @return bool
     * @throws Exception
     */
    protected function upgradeSql_j3x_tables()
    {
        Log::add(Text::_('start: upgradeSql_j3x_tables: '), Log::INFO, 'rsg2');

        $hasError = false;

        try {
            // ToDo: !!! // When used in the component's Model
            //           $db = $this->getDatabase(); !!!

            // $db = Factory::getDbo();
            $db     = Factory::getContainer()->get('DatabaseDriver');
            $tables = $db->getTableList();
            $prefix = $db->getPrefix();

            if (in_array($prefix . 'rsgallery2_galleries', $tables)) {
                // #	UPDATE `#__rsgallery2_galleries` SET `checked_out_time` = '1980-01-01 00:00:00' WHERE `checked_out_time` = '0000-00-00 00:00:00';
                $hasError |= $this->j3x_tables_fix_datatime('#__rsgallery2_galleries', 'checked_out_time', $db);
                $hasError |= $this->j3x_tables_fix_datatime('#__rsgallery2_galleries', 'date', $db);

                // ALTER TABLE IF EXISTS `#__rsgallery2_galleries` MODIFY `checked_out_time` datetime NOT NULL;
                // Not needed ? ALTER TABLE `#__rsgallery2_galleries` MODIFY `created` datetime NOT NULL;
                // and below

            }

            if (in_array($prefix . 'rsgallery2_files', $tables)) {
                // #	UPDATE `#__rsgallery2_galleries` SET `checked_out_time` = '1980-01-01 00:00:00' WHERE `checked_out_time` = '0000-00-00 00:00:00';
                $hasError |= $this->j3x_tables_fix_datatime('#__rsgallery2_files', 'checked_out_time', $db);
                $hasError |= $this->j3x_tables_fix_datatime('#__rsgallery2_files', 'date', $db);
            }

            if (in_array($prefix . 'rsgallery2_comments', $tables)) {
                // #	UPDATE `#__rsgallery2_galleries` SET `checked_out_time` = '1980-01-01 00:00:00' WHERE `checked_out_time` = '0000-00-00 00:00:00';
                $hasError |= $this->j3x_tables_fix_datatime('#__rsgallery2_comments', 'checked_out_time', $db);
                $hasError |= $this->j3x_tables_fix_datatime('#__rsgallery2_comments', 'datetime', $db);
            }
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception: upgradeSql_j3x_tables: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );

            return true;
        }

        Log::add(Text::_('Exit upgradeSql_j3x_tables'), Log::INFO, 'rsg2');

        return $hasError;
    }

    /**
     * mysql fix for datetime NOT NULL default '0000-00-00 00:00:00' => '1980-01-01 00:00:00'
     *
     * @param   string  $tableName
     * @param   string $
     *
     * @return bool
     */
    protected function j3x_tables_fix_datatime(string $tableName, string $variableName, $db)
    {
        Log::add(Text::_('      * j3x_tables_fix_datatime: ' . $tableName . '.' . $variableName), Log::INFO, 'rsg2');

        $query = $db
            ->getQuery(true)
            ->update($db->quoteName($tableName))
            ->set($db->quoteName($variableName) . " = '1980-01-01 00:00:00'")
            ->where($db->quoteName($variableName) . " = '0000-00-00 00:00:00'");

        $db->setQuery($query);

        try {
            $db->execute();
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception: j3x_tables_fix_datatime: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );

            $app = Factory::getApplication();
            $app->enqueueMessage($e->getMessage(), 'error');

            return true;
        }

        return false;
    }

    /**
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     *
     * @since version
     */
    protected function updateDefaultParams($parent)
    {
        try {
            Log::add(
                Text::_('upd (20) Rsg2ExtensionModel (update default parameter) -----------------------'),
                Log::INFO,
                'rsg2',
            );

            Log::add(Text::_('upd (20.1) '), Log::INFO, 'rsg2');

            // load model -----------------------------------------------------

            $Rsg2ExtensionModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/Rsg2ExtensionModel.php';
            Log::add(Text::_('upd (20.2) '), Log::INFO, 'rsg2');
            $Rsg2ExtensionClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel';
            Log::add(Text::_('upd (20.3) '), Log::INFO, 'rsg2');
            JLoader::register($Rsg2ExtensionClassName, $Rsg2ExtensionModelFileName);

            Log::add(Text::_('upd (20.4) '), Log::INFO, 'rsg2');
            $Rsg2ExtensionClass = new Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel();

            //--- read actual config data ------------------------------------------------

            Log::add(Text::_('upd (20.5) '), Log::INFO, 'rsg2');
            $this->actualParams = $Rsg2ExtensionClass->readRsg2ExtensionConfiguration();

            //--- read default config data ------------------------------------------------

            Log::add(Text::_('upd (20.6) '), Log::INFO, 'rsg2');
            $this->defaultParams = $Rsg2ExtensionClass->readRsg2ExtensionDefaultConfiguration();

            //--- merge default and actual config data ------------------------------------------------

            Log::add(Text::_('upd (20.7) '), Log::INFO, 'rsg2');
            $this->mergedParams = $Rsg2ExtensionClass->mergeDefaultAndActualParams(
                $this->defaultParams,
                $this->actualParams,
            );

            //--- write as actual config data ------------------------------------------------

            Log::add(Text::_('upd (20.8) '), Log::INFO, 'rsg2');
            $Rsg2ExtensionClass->replaceRsg2ExtensionConfiguration($this->mergedParams);

            Log::add(Text::_('upd (20.9) '), Log::INFO, 'rsg2');
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception: upgradeSql: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );
        }

        Log::add(Text::_('Exit updateDefaultParams'), Log::INFO, 'rsg2');

        return;
    }

} // class
