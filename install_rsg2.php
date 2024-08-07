<?php
/**
 * @package        com_rsgallery2
 *
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright (c)  2003-2023 RSGallery2 Team
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @link           https://www.rsgallery2.org
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

//use Joomla\CMS\File;
//use Joomla\CMS\Folder;
use Joomla\CMS\Filesystem\Folder;

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
		if (is_writable(JPATH_ADMINISTRATOR . '/logs'))
		{
            // Get the date for log file name
            $date = Factory::getDate()->format('Y-m-d');

			$logOptions['format']    = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
			$logOptions['text_file'] = 'rsg2_install.' . $date . '.php';
			$logType                 = Log::ALL;
			$logChannels             = ['rsg2']; //jerror ...
			Log::addLogger($logOptions, $logType, $logChannels);

			try
			{
				Log::add(Text::_('\n>>RSG2 Installer construct'), Log::INFO, 'rsg2');
			}
			catch (\RuntimeException $exception)
			{
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

		if ($type !== 'uninstall')
		{

			// Check for the minimum PHP version before continuing
			if (version_compare(PHP_VERSION, $this->minimumPhp, '<'))
			{
				Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');
				Factory::getApplication()->enqueueMessage(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp) 
					. ' (' . PHP_VERSION . ')' , 'error');

				return false;
			}

			// Check for the minimum RSG/Joomla version before continuing
			if (version_compare(JVERSION, $this->minimumJoomla, '<='))
			{
				Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');
				Factory::getApplication()->enqueueMessage(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), 'error');

				return false;
			}

			//--- new release version --------------------------------------

			$manifest         = $parent->getManifest();
			$this->newRelease = (string) $manifest->version;

			Log::add('newRelease:' . $this->newRelease, Log::INFO, 'rsg2');

			//--- old release version --------------------------------------

			$this->oldRelease = '';

			if ($type === 'update')
			{
				Log::add(Text::_('-> pre update'), Log::INFO, 'rsg2');

				//--- Read manifest with old version ------------------------

				// could also be done by $xml=simplexml_load_file of manfiest on
				// 'old'==actual RSG2 admin path $this->oldRelease = $xml->version;

				$this->oldRelease = $this->getOldVersionFromManifestParam();

				// old release not found but rsgallery2 data still kept in database -> error message
				if (empty ($this->oldRelease))
				{
					$outTxt = 'Can not install RSG2: Old Rsgallery2 data found in db or RSG2 folders. Please try to deinstall previous version or remove folder artifacts';
					Factory::getApplication()->enqueueMessage($outTxt, 'error');
					Log::add('oldRelease:' . $outTxt, Log::WARNING, 'rsg2');

					// May be error on install ?
					// return false;

					$this->oldRelease = '%';
				}

				Log::add('oldRelease:' . $this->oldRelease, Log::INFO, 'rsg2');

			} else { // $type == 'install'

				JLog::add('-> pre freshInstall', JLog::DEBUG);
			}

// !!! ToDo: remove !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//			$this->oldRelease = '4.5.3.0';
// !!! ToDo: remove !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

			Log::add(Text::_('newRelease:') . $this->newRelease, Log::INFO, 'rsg2');

			if ($type === 'update')
			{
				// Previous j3x version:
				if (version_compare($this->oldRelease, '5.0.0', 'lt'))
				{
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

		Log::add(Text::_('exit install') , Log::INFO, 'rsg2');

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

		Log::add(Text::_('exit update') , Log::INFO, 'rsg2');

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

		switch ($type)
		{

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

				$this->upgradeSql ();

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
				echo 'Uninstall of RSG2 finished. <br>Configuration may be deleted. <br>'
					. 'Galleries and images table may still exist';
				// ToDo: uninstall Message
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
		Factory::getApplication()->enqueueMessage(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), 'error');

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

		try
		{
			Log::add('initGalleryTree: include TreeModel', Log::INFO, 'rsg2');

			$GalleryTreeModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/GalleryTreeModel.php';
			Log::add(Text::_('upd (10.2) '), Log::INFO, 'rsg2');
			$GalleryTreeClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryTreeModel';
			Log::add(Text::_('upd (10.3) '), Log::INFO, 'rsg2');
			\JLoader::register($GalleryTreeClassName, $GalleryTreeModelFileName);

//			Log::add(Text::_('upd (10.4) '), Log::INFO, 'rsg2');
//			include($GalleryTreeModelFileName);

			Log::add(Text::_('upd (10.4) '), Log::INFO, 'rsg2');
			$galleryTreeModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryTreeModel ();

			Log::add(Text::_('upd (10.5) '), Log::INFO, 'rsg2');
			Log::add('initGalleryTree: check for root item', Log::INFO, 'rsg2');

			// check for root item
			$isRootItemExisting = $galleryTreeModel->isRootItemExisting();

			if ($isRootItemExisting)
			{   // assume tree structure already built
				Log::add('initGalleryTree: Gallery table root record is already present', Log::INFO, 'rsg2');
				$isGalleryTreeCreated = true;
			}
			else
			{

				Log::add('initGalleryTree: init nested gallery root item', Log::INFO, 'rsg2');

				$isGalleryTreeCreated = $galleryTreeModel->reinitNestedGalleryTable();

				if ($isGalleryTreeCreated)
				{
					$isGalleryTreeReset = true;
					Log::add('initGalleryTree: Success writing tree root item into gallery database', Log::INFO, 'rsg2');
				}
				else
				{
					//Factory::getApplication()->enqueueMessage("Failed writing root into gallery database", 'error');
					Log::add('initGalleryTree: Failed writing tree root item into gallery database', Log::INFO, 'rsg2');
				}
			}

		} //catch (\RuntimeException $e)
		catch (\Exception $e)
		{
			Log::add('initGalleryTree: Exception: ' . $e->getMessage(), Log::INFO, 'rsg2');
			throw new \RuntimeException($e->getMessage() . ' from initGalleryTree');
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

		try
		{

			Log::add('installMessage: include Helper/InstallMessage', Log::INFO, 'rsg2');

			$installMsgHelperFileName  = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Helper/InstallMessage.php';
			$installMsgHelperClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Helper\InstallMessage';
			\JLoader::register($installMsgHelperClassName, $installMsgHelperFileName);

			$changeLogModelFileName  = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/ChangeLogModel.php';
			$changeLogModelClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\ChangeLogModel';
			\JLoader::register($changeLogModelClassName, $changeLogModelFileName);

			$InstallMessageHelper = new Rsgallery2\Component\Rsgallery2\Administrator\Helper\InstallMessage
				($this->newRelease, $this->oldRelease);

			Log::add('installMessage: create message', Log::INFO, 'rsg2');

			$installMsg = $InstallMessageHelper->installMessageText($type);

		} //catch (\RuntimeException $e)
		catch (\Exception $e)
		{
			Log::add('installMessage: Exception: ' . $e->getMessage(), Log::INFO, 'rsg2');
			throw new \RuntimeException($e->getMessage() . ' from installMessage');
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
	 * @since 5.0.0
	 */
	protected function getOldVersionFromManifestParam()
	{
		//$oldRelease = '1.0.0.999';
		$oldRelease = '';

		$this->oldManifestData = $this->readRsg2ExtensionManifest();
		if (!empty ($this->oldManifestData['version']))
		{
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

		try
		{
			// $db    = Factory::getContainer()->get(DatabaseInterface::class);
			// $db = $this->getDatabase();
			$db = Factory::getDbo();
			$query = $db->getQuery(true)
				->select('manifest_cache')
				->from($db->quoteName('#__extensions'))
				->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
			$db->setQuery($query);

			$jsonStr = $db->loadResult();

			if (!empty ($jsonStr))
			{
				$manifest = json_decode($jsonStr, true);
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'readRsg2ExtensionManifest: Error executing query: "' . "" . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $manifest;
	}

// Not used ?
//	/**
//	 *
//	 * Checks for RSG2 version j3x db tables existence
//	 *
//	 * @return bool
//	 * @throws Exception
//	 *
//	 * @since 5.0.0
//	 */
//	protected function isJ3xRsg2DataExisting() : array
//	{
//		$isJ3xTableExisting = false;
//
//		try
//		{
//
//			$J3xExistModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/J3xExistModel.php';
//			include($J3xExistModelFileName);
//			$j3xExistModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel();
//
//			$isJ3xTableExisting = $j3xExistModel->J3xConfigTableExist();
//
//		} //catch (\RuntimeException $e)
//		catch (\Exception $e)
//		{
//			Log::add('isJ3xRsg2DataExisting: Exception: ' . $e->getMessage(), Log::INFO, 'rsg2');
//			throw new \RuntimeException($e->getMessage() . ' from isJ3xRsg2DataExisting');
//		}
//
//		return $isJ3xTableExisting;
//	}

// Can't be used as boot rsg2 would be needed and is yet ? partly active ?
//	/**
//	 *
//	 * Copies J3x configuration parameter(options) to J4x version
//	 *
//	 * @return
//	 * @throws Exception
//	 *
//	 * @since 5.0.0
//	 */
//	protected function copyJ3xDbConfigParameter()
//	{
//		// Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel;
//		// use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;
//		// use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;
//		// use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;
//
//		$isCopiedConfig = false;
//
//		try
//		{
//			Log::add(Text::_('upd (30) MaintenanceJ3xModel (copy J3x config) -----------------------'), Log::INFO, 'rsg2');
//
//			Log::add(Text::_('upd (30.1) '), Log::INFO, 'rsg2');
//
//			// prepare 'use' models -----------------------------------------------------
//
//			//--- load ConfigRawModel -----------------------------------------------------
//
//			$configRawModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/ConfigRawModel.php';
//			Log::add(Text::_('upd (30.2) '), Log::INFO, 'rsg2');
//			$configRawClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel';
//			Log::add(Text::_('upd (30.3) '), Log::INFO, 'rsg2');
//			\JLoader::register($configRawClassName, $configRawModelFileName);
//
////			// Log::add(Text::_('upd (30.3) '), Log::INFO, 'rsg2');
////			Log::add('ConfigRawModel: ', Log::INFO, 'rsg2');
////			include($configRawModelFileName);
//
//			Log::add(Text::_('upd (30.4) '), Log::INFO, 'rsg2');
//			$configRawModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel();
//
//			//--- load MaintenanceJ3xModel -----------------------------------------------------
//
//			Log::add(Text::_('upd (30.11) '), Log::INFO, 'rsg2');
//			$copyConfigJ3xModelModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/CopyConfigJ3xModel.php';
//			Log::add(Text::_('upd (30.12) '), Log::INFO, 'rsg2');
//			$copyConfigJ3xClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\CopyConfigJ3xModel';
//			Log::add(Text::_('upd (30.13) '), Log::INFO, 'rsg2');
//			\JLoader::register($copyConfigJ3xClassName, $copyConfigJ3xModelModelFileName);
//
////			//Log::add(Text::_('upd (30.14) '), Log::INFO, 'rsg2');
////			Log::add('ConfigJ3xRawModel: ', Log::INFO, 'rsg2');
////			include($copyConfigJ3xModelModelFileName);
//
//			//--- use MaintenanceJ3xModel ----------------------------------------------------
//
//			Log::add(Text::_('upd (30.14) '), Log::INFO, 'rsg2');
//			$j3xModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\CopyConfigJ3xModel();
//
//			try
//			{
//				//--- copy j3x parameter ---------------------------------------------
//
//				//Log::add(Text::_('upd (30.6) '), Log::INFO, 'rsg2');
//				Log::add('collectAndCopyJ3xConfig2J4xOptions: ', Log::INFO, 'rsg2');
//
//				$isCopiedConfig = $j3xModel->collectAndCopyJ3xConfig2J4xOptions();
//
//				Log::add(Text::_('upd (30.7) '), Log::INFO, 'rsg2');
//				if (!$isCopiedConfig)
//				{
//					Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x configuration failed'), 'error');
//				}
//			}
//			catch (\RuntimeException $e)
//			{
//				Factory::getApplication()->enqueueMessage($e->getMessage() . ' Copy j3x DB config', 'error');
//			}
//
//			Log::add(Text::_('upd (30.19) '), Log::INFO, 'rsg2');
//
//		} //catch (\RuntimeException $e)
//		catch (\Exception $e)
//		{
//			Log::add('copyJ3xDbTables: Exception: ' . $e->getMessage(), Log::INFO, 'rsg2');
//			throw new \RuntimeException($e->getMessage() . ' from copyJ3xDbTables');
//		}
//
//		return $isCopiedConfig;
//	}

	/**
	 * Remove old language files of RSG2 J3x stored within joomla
	 * standard language folders. Keeping the old files would result in those
	 * being loaded instead of the new ones.
	 *
	 * @since version
	 */
	protected function removeAllOldLangFiles(): void
	{
		try
		{
			Log::add(Text::_('start: removeAllOldLangFiles: '), Log::INFO, 'rsg2');

			//--- administrator\language path ---------------------------------

			$langPath = JPATH_ROOT . '/administrator/' . 'language';

			$isOneFileDeleted = $this->removeLangFilesInSubPaths($langPath);


			//--- site\language path ---------------------------------

			$langPath = JPATH_ROOT . '/' . 'language';

			$isOneFileDeleted = $this->removeLangFilesInSubPaths($langPath);

		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: removeAllOldLangFiles: ') . $langPath, Log::INFO, 'rsg2');
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

		try
		{
			Log::add(Text::_('start: removeLangFilesInSubPaths: ') . $langPath, Log::INFO, 'rsg2');

			//--- All matching files in actual folder -------------------

			$files = array_diff(array_filter(glob($langPath . '/*'), 'is_file'), array('.', '..'));

			foreach ($files as $fileName)
			{
				// A matching lang name ...
				if (str_contains($fileName, 'com_rsgallery2'))
				{

					// ... will be deleted
					if (file_exists($fileName))
					{
						Log::add(Text::_('unlink: ') . $fileName, Log::INFO, 'rsg2');

						unlink($fileName);
						$isOneFileDeleted = true;
					}

				}
			}
		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: removeLangFilesInSubPaths (1): ') . $langPath, Log::INFO, 'rsg2');
		}

		try
		{
			#--- Search in each sub folder -------------------------------------

			// don't search, there is no sub folder
			if (!$isOneFileDeleted)
			{
				// base folder may contain lang ID folders en-GB, de-DE

				$folders = array_diff(array_filter(glob($langPath . '/*'), 'is_dir'), array('.', '..'));

				foreach ($folders as $folderName)
				{
// 				echo ('folder name: ' . $folderName . '<br>');

				// $subFolder = $langPath . "/" . $folderName;
				//$isOneFileDeleted = removeLangFilesInSubPaths($subFolder);

					$isOneFileDeleted = $this->removeLangFilesInSubPaths($folderName);

				}
			}
		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: removeLangFilesInSubPaths (2): ') . $langPath, Log::INFO, 'rsg2');
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
		try
		{
			Log::add(Text::_('start: removeJ3xComponentFiles: '), Log::INFO, 'rsg2');

			//--- administrator\language path ---------------------------------

			$adminRSG2_Path = JPATH_ROOT . '/administrator/components/' . 'com_rsgallery2';

			Log::add(Text::_('upd (50.1) '), Log::INFO, 'rsg2');

			if (is_dir($adminRSG2_Path)) {

				Log::add(Text::_('upd (50.2) '), Log::INFO, 'rsg2');
				Log::add(Text::_('del Folder: ') . $adminRSG2_Path, Log::INFO, 'rsg2');

				$isOk = Folder::delete($adminRSG2_Path);

				if (!$isOk)
				{

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

				if (!$isOk)
				{

					Log::add(Text::_('upd (50.12) RSG2 component not deleted'), Log::INFO, 'rsg2');

				}
				
				Log::add(Text::_('upd (50.13) '), Log::INFO, 'rsg2');
			}

		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: removeJ3xComponentFiles: '), Log::INFO, 'rsg2');
		}

		return;
	}


	protected function upgradeSql ()
	{
		Log::add(Text::_('start: upgradeSql: '), Log::INFO, 'rsg2');

		try
		{
			// Previous j3x version:
			if (version_compare($this->oldRelease, '5.0.12.999', 'lt'))
			{


			}
		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: upgradeSql: '), Log::INFO, 'rsg2');
		}

		Log::add(Text::_('Exit upgradeSql'), Log::INFO, 'rsg2');

		return;
	}

	protected function upgradeSql_j3x_tables ()
	{
		Log::add(Text::_('start: upgradeSql_j3x_tables: '), Log::INFO, 'rsg2');

		try
		{
			$tables =
				$db->getTableList();



		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: upgradeSql_j3x_tables: '), Log::INFO, 'rsg2');
		}

		Log::add(Text::_('Exit upgradeSql_j3x_tables'), Log::INFO, 'rsg2');

		return;
	}

	/**
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     *
     * @since version
     */
	protected function updateDefaultParams($parent)
	{
		try
		{
			Log::add(Text::_('upd (20) Rsg2ExtensionModel (update default parameter) -----------------------'), Log::INFO, 'rsg2');

			Log::add(Text::_('upd (20.1) '), Log::INFO, 'rsg2');

			// load model -----------------------------------------------------

			$Rsg2ExtensionModelFileName  = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/Rsg2ExtensionModel.php';
			Log::add(Text::_('upd (20.2) '), Log::INFO, 'rsg2');
			$Rsg2ExtensionClassName = 'Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel';
			Log::add(Text::_('upd (20.3) '), Log::INFO, 'rsg2');
			\JLoader::register($Rsg2ExtensionClassName, $Rsg2ExtensionModelFileName);

			Log::add(Text::_('upd (20.4) '), Log::INFO, 'rsg2');
            $Rsg2ExtensionClass = new Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel();

			//--- read actual config data ------------------------------------------------

			Log::add(Text::_('upd (20.5) '), Log::INFO, 'rsg2');
			$this->actualParams = $Rsg2ExtensionClass->readRsg2ExtensionConfiguration ();

			//--- read default config data ------------------------------------------------

			Log::add(Text::_('upd (20.6) '), Log::INFO, 'rsg2');
			$this->defaultParams = $Rsg2ExtensionClass->readRsg2ExtensionDefaultConfiguration();

			//--- merge default and actual config data ------------------------------------------------

			Log::add(Text::_('upd (20.7) '), Log::INFO, 'rsg2');
			$this->mergedParams = $Rsg2ExtensionClass->mergeDefaultAndActualParams ($this->defaultParams, $this->actualParams);

			//--- write as actual config data ------------------------------------------------

			Log::add(Text::_('upd (20.8) '), Log::INFO, 'rsg2');
			$Rsg2ExtensionClass->replaceRsg2ExtensionConfiguration ($this->mergedParams);

			Log::add(Text::_('upd (20.9) '), Log::INFO, 'rsg2');

		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: updateDefaultParams: '), Log::INFO, 'rsg2');
		}

		Log::add(Text::_('Exit updateDefaultParams'), Log::INFO, 'rsg2');
		
		return;
	}

} // class
