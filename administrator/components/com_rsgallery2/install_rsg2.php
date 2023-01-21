<?php
/**
 * @package        com_rsgallery2
 *
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2022 RSGallery2 Team
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @link           https://www.rsgallery2.org
 */
// No direct access to this file
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;


//JLoader::registerNamespace('Rsgallery2', __DIR__ .'/administrator/components/com_rsgallery2');
//JLoader::registerNamespace('Rsgallery2',  JPATH_ADMINISTRATOR .'/components/com_rsgallery2');

// https://github.com/asikart/windwalker-template/blob/master/admin/flower.php
//use Rsgallery2\Helper\Rsg2InstallTasks;


////use Rsgallery2\Component\Rsgallery2\Administrator\Helper\InstallMessage;
////require_once(dirname(__FILE__) . '/administrator/components/com_rsgallery2/Helper/InstallMessage.php');
//$localDir = str_replace("\\","/",dirname(__FILE__));
//$rsg2FileName = $localDir . '/administrator/components/com_rsgallery2/Helper/InstallMessage.php';

//$rsg2FileName = $localDir . '/administrator/components/com_rsgallery2/Helper/InstallMessage.php';
//$rsg2ClassName = 'InstallMessage';
//require_once($rsg2FileName);
//\JLoader::register($rsg2ClassName, $rsg2FileName);
//\JLoader::load($rsg2ClassName);
//
////use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;

// ToDo: More logs after action

/**
 * Script file of Rsgallery2 Component
 *
 * @since __BUMP_VERSION__
 *
 */
class Com_Rsgallery2InstallerScript extends InstallerScript
{
	protected $newRelease;
	protected $oldRelease;

	protected $oldManifestData;

	/**
	 * @var string
	 * @since __BUMP_VERSION__
	 */
	protected $minimumJoomla;
	/**
	 * @var string
	 * @since __BUMP_VERSION__
	 */
	protected $minimumPhp;

	// protected $rsg2_basePath;


	/**
	 * Extension script constructor.
	 *
	 * @since __BUMP_VERSION__
	 *
	 */
	public function __construct()
	{
		$this->minimumJoomla = '4.0.0';
		$this->minimumPhp    = JOOMLA_MINIMUM_PHP;   // (7.2.5)
		//$this->minimumPhp    = '8.0.0';   // (8.0.0)

		// Check if the default log directory can be written to, add a logger for errors to use it
		if (is_writable(JPATH_ADMINISTRATOR . '/logs'))
		{
			$logOptions['format']    = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
			$logOptions['text_file'] = 'rsg2_install.php';
			$logType                 = Log::ALL;
			$logChannels             = ['rsg2']; //jerror ...
			Log::addLogger($logOptions, $logType, $logChannels);

			try
			{
				Log::add(Text::_('\n>>Installer construct'), Log::INFO, 'rsg2');
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
	 * @param   string     $type       Which action is happening (install|uninstall|discover_install|update)
	 * @param   Installer  $installer  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @throws Exception
	 * @since __BUMP_VERSION__
	 */
	public function preflight($type, $installer)
	{
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

		// Check for the minimum PHP version before continuing
		if (version_compare(PHP_VERSION, $this->minimumPhp, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');
			Factory::getApplication()->enqueueMessage(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), 'error');

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

		$manifest         = $installer->getManifest();
		$this->newRelease = (string) $manifest->version;

		Log::add('newRelease:' . $this->newRelease, Log::INFO, 'rsg2');

		//--- old release version --------------------------------------

		$this->oldRelease = '';

		if ($type === 'update')
		{
			//--- Read manifest with old version ------------------------

			// could also be done by $xml=simplexml_load_file of manfiest on
			// 'old'==actual RSG2 admin path $this->oldRelease = $xml->version;

			$this->oldRelease = $this->getOldVersionFromManifestParam();

			// old release not found but rsgallery2 data still kept in database -> error message
			if (empty ($this->oldRelease))
			{
				Factory::getApplication()->enqueueMessage('Can not install RSG2: Old Rsgallery2 data found in db or RSG2 folders. Please try to deinstall previous version or remove folder artifacts', 'error');

				// May be error on install ?
				// return false;
			}

			Log::add('oldRelease:' . $this->oldRelease, Log::INFO, 'rsg2');
		}

		if ($type === 'update')
		{
			// Remove old language files (RSG2 J3x) before they are loaded by joomla -> "hello user message"
			if (version_compare($this->oldRelease, '5.0.0', 'lt'))
			{
				$this->removeAllOldLangFiles();
			}
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
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since __BUMP_VERSION__
	 *
	 */
	public function install($parent)
	{
//      echo Text::_('COM_RSGALLERY2_INSTALL_TEXT');
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_INSTALL'), Log::INFO, 'rsg2');

		// ToDo: debug install
		$this->addDashboardMenu('rsgallery2', 'rsgallery2');

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
	 * @since __BUMP_VERSION__
	 *
	 */
	public function update($parent)
	{
		// echo Text::_('COM_RSGALLERY2_UPDATE_TEXT');
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_UPDATE'), Log::INFO, 'rsg2');
		//Log::add(Text::_('COM_RSGALLERY2_UPDATE_TEXT'), Log::INFO, 'rsg2');

		// ToDo: debug install
		$this->addDashboardMenu('rsgallery2', 'rsgallery2');

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
	 * @since __BUMP_VERSION__
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

				$this->updateDefaultParams();

				Log::add('post->install: install message', Log::INFO, 'rsg2');

				$installMsg = $this->installMessage($type);

				Log::add('post->install: finished', Log::INFO, 'rsg2');

				break;

			case 'update':

				Log::add('post->update: init gallery tree', Log::INFO, 'rsg2');

				// Nested gallery table needs a root item
				$isGalleryTreeCreated = $this->initGalleryTree();

				Log::add('post->install: updateDefaultParams', Log::INFO, 'rsg2');

				$this->updateDefaultParams();

				// Old J3x config, galleries, images
//                $this->checkAndHandleJ3xTables();

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
				echo 'Uninstall of RSG2 finished. <br>Configuration may be deleted. <br>'
					. 'Galleries and images table may still exist';
				// ToDo: uninstall Message

				Factory::getApplication()->enqueueMessage($outText, 'info');

				Log::add('post->uninstall: finished', Log::INFO, 'rsg2');

				break;

			case 'discover_install':

				Log::add('post->discover_install: init gallery tree', Log::INFO, 'rsg2');

				// Nested gallery table needs a root item
				$isGalleryTreeCreated = $this->initGalleryTree();

				Log::add('post->discover_install: install message', Log::INFO, 'rsg2');

				$installMsg = $this->installMessage($type);

				Log::add('post->discover_install: finished', Log::INFO, 'rsg2');

				break;

			default:

				break;
		}

		echo $installMsg;

		// wonderworld 'good by' icons finnern
		echo '<br><h4>&oplus;&infin;&omega;</h4><br>';
		Log::add('--- postflight finished', Log::INFO, 'rsg2');

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
	 * @since __BUMP_VERSION__
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
	 * @since __BUMP_VERSION__
	 */
	protected function initGalleryTree()
	{
		$isGalleryTreeCreated = false;

		try
		{

			Log::add('initGalleryTree: include TreeModel', Log::INFO, 'rsg2');

			$GalleryTreeModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/GalleryTreeModel.php';
			include($GalleryTreeModelFileName);
			$galleryTreeModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryTreeModel ();

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
	 * @since __BUMP_VERSION__
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
	 * @since __BUMP_VERSION__
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
	 * @since __BUMP_VERSION__
	 */
	protected function readRsg2ExtensionManifest()
	{
		$manifest = [];

		try
		{
			$db    = Factory::getDbo();
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


	/**
	 *
	 * Checks for RSG2 version j3x db tables existence
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function isJ3xRsg2DataExisting() : array
	{
		$isJ3xTableExisting = false;

		try
		{

			$J3xExistModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/J3xExistModel.php';
			include($J3xExistModelFileName);
			$j3xExistModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel();

			$isJ3xTableExisting = $j3xExistModel->J3xConfigTableExist();

		} //catch (\RuntimeException $e)
		catch (\Exception $e)
		{
			Log::add('isJ3xRsg2DataExisting: Exception: ' . $e->getMessage(), Log::INFO, 'rsg2');
			throw new \RuntimeException($e->getMessage() . ' from isJ3xRsg2DataExisting');
		}

		return $isJ3xTableExisting;
	}


	/**
	 *
	 * Checks for RSG2 version j3x db tables existence
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function copyJ3xDbTables()
	{
		$isJ3xDbCopied = false;

		try
		{

			$j3xModelFileName = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/src/Model/MaintenanceJ3xModel.php';
			include($j3xModelFileName);
			$j3xModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel();

			//--- DB configuration ---------------------------------------------

			try
			{

				$isCopiedConfig = $j3xModel->collectAndCopyJ3xConfig2J4xOptions();
				$isJ3xDbCopied  &= $isCopiedConfig;

				if (!$isCopiedConfig)
				{
					Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x configuration failed'), 'error');
				}
			}
			catch (\RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage() . ' Copy j3x DB config', 'error');
			}

			//--- DB galleries ---------------------------------------------

			try
			{
				$isCopiedGalleries = $j3xModel->copyDbAllJ3xGalleries2J4x();
				$isJ3xDbCopied     &= $isCopiedGalleries;

				if (!$isCopiedGalleries)
				{
					Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x galleries failed'), 'error');
				}
			}
			catch (\RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage() . '  Copy j3x DB galleries', 'error');
			}

			//--- DB images ---------------------------------------------

			try
			{

				$isCopiedImages = $j3xModel->copyDbAllJ3xImages2J4x();
				$isJ3xDbCopied  &= $isCopiedImages;

				if (!$isCopiedImages)
				{
					Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x images failed'), 'error');
				}

			}
			catch (\RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage() . '  Copy j3x DB images', 'error');
			}


		} //catch (\RuntimeException $e)
		catch (\Exception $e)
		{
			Log::add('copyJ3xDbTables: Exception: ' . $e->getMessage(), Log::INFO, 'rsg2');
			throw new \RuntimeException($e->getMessage() . ' from copyJ3xDbTables');
		}

		return [$isJ3xDbCopied, $isCopiedConfig, $isCopiedGalleries, $isCopiedImages];
	}

	/**
	 *
	 *
	 * @throws Exception
	 * @since version
	 */
	protected function checkAndHandleJ3xTables(): void
	{
		// check and handle old J3x tables (Moving DB data ...)
		$isJ3xTableExisting = $this->isJ3xRsg2DataExisting();

		// Handle old RSG2 J3x DB data
		if ($isJ3xTableExisting)
		{

			//--- Not already handled ? -----------------------

			$isJ3xDbsCopied = '';

			$manifestData = $this->readRsg2ExtensionManifest();
			if (!empty ($manifestData['j3x_dbs_copied']))
			{
				$isJ3xDbsCopied = $manifestData['j3x_dbs_copied'];
			}

			// do copy DB data
			if (empty ($isJ3xDbsCopied))
			{

				[$isJ3xDbCopied, $isCopiedConfig, $isCopiedGalleries, $isCopiedImages]
					= $this->copyJ3xDbTables();

				if (!empty ($isJ3xDbCopied))
				{
					// Update Config info

				}
				else
				{
					//$isCopiedGalleries,
					if (empty ($isCopiedConfig))
					{
						Factory::getApplication()->enqueueMessage('J3x DB table: Failed copying configuration');
					}
					if (empty ($isCopiedGalleries))
					{
						Factory::getApplication()->enqueueMessage('J3x DB table: Failed copying galleries');
					}
					if (empty ($isCopiedImages))
					{
						Factory::getApplication()->enqueueMessage('J3x DB table: Failed copying images');
					}

				}

			}
		}
	}

	/**
	 * Remove old language files (RSG2 J3x as stores the files within
	 * component folders). Keeping the old files would result in those
	 * being loaded instead of the new ones
	 * The old language files from RSG2 in J3.x live side by side in
	 * the directory with the Joomla language files
	 *
	 * @since version
	 */
	protected function removeAllOldLangFiles(): void
	{
		try
		{

			//--- administrator\languages path ---------------------------------

			$langPath = JPATH_ADMINISTRATOR . '/' . 'languages';

			$isOneFileDeleted = removeLangFilesInSubPaths($langPath);


			//--- site\languages path ---------------------------------

			$langPath = JPATH_ROOT . '/' . 'languages';

			$isOneFileDeleted = removeLangFilesInSubPaths($langPath);

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
			//--- All matching files in actual folder -------------------
			//
			foreach (Folder::files($langPath) as $fileName)
			{
				// A matching lang name ...
				if (str_contains($fileName, 'com_rsgallery2'))
				{
					$langPathFileName = $langPath . '/' . $fileName;

					// ... will be deleted
					if (File::exist($langPathFileName))
					{
						unlink($langPathFileName);
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

				foreach (Folder::folders($langPath) as $folderName)
				{
					$subFolder = $langPath . "/" . $folderName;

					$isOneFileDeleted = $this->removeLangFilesInSubPaths($subFolder);

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
	 * sets parameter values in the component's row of the extension table
	 *
	 * @param $param_array
	 */
	function setParams($param_array)
	{
		try
		{
//			{}
			if (count($param_array) > 0)
			{
				// read the existing component value(s)
				$db = JFactory::getDbo();
				$db->setQuery('SELECT params FROM #__extensions WHERE name = "com_rsgallery2"');
				$params = json_decode($db->loadResult(), true);

				// add the new variable(s) to the existing one(s)
				foreach ($param_array as $name => $value)
				{
					$params[(string) $name] = (string) $value;
				}
				// store the combined new and existing values back as a JSON string
				$paramsString = json_encode($params);
				$db->setQuery('UPDATE #__extensions SET params = ' .
					$db->quote($paramsString) .
					' WHERE name = "com_rsgallery2"');
				$db->execute();
			}
		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: setParams: '), Log::INFO, 'rsg2');
		}

		return;
	}



	protected function updateDefaultParams()
	{
		try
		{

            $actualParams = $this->parent->getParams();

            if (empty($params) || $params == '{}') {
                $params = [];
            }

            //
            $extensionModel = new Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel ();

            $defaultParams = $extensionModel::readRsg2ExtensionDefaultConfiguration ();

            $mergedParams = mergeDefaultAndActualParams($defaultParams, $actualParams);

//            $extensionModel::replaceRsg2ExtensionConfiguration($mergedParams);

//
//        $params = $this->parent->getParams();
//
//        if (empty($params) || $params == '{}')
//        {
//            $defaults = (object) $this->loadDefaultParams($this->get('element'));
//            $params   = json_encode($defaults);
//        }
//
//        $row->set('params', $params);
//
//
//			$params = $this->loadDefaultParams('com_rsgallery2');
//
//			if (count ($params))
//			{
//				$this->setParams($params);
//			}


		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: updateDefaultParams: '), Log::INFO, 'rsg2');
		}

		return;
	}


	/**
	 * Original: joomlatools / joomlatools-platform github 2023.01
	 * Parses the config.xml for the given component and
	 * returns the default values for each parameter.
	 *
	 * @param   string  Element name (com_xyz)
	 *
	 * @return  array   Array of parameters
	 *
	 *
	 * @copyright   joomlatools: Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @license     joomlatools: GNU General Public License version 2 or later; see LICENSE
	 *
	 *
	protected function loadDefaultParams($element)
	{
        $params = [];

		try
		{
			$file   = JPATH_ADMINISTRATOR . '/components/' . $element . '/config.xml';

			if (!file_exists($file))
			{
				return $params;
			}

			$xml = simplexml_load_file($file);

			if (!($xml instanceof SimpleXMLElement))
			{
				return $params;
			}

			$elements = $xml->xpath('/config');

			if (empty($elements))
			{
				return $params;
			}

			foreach ($elements as $element)
			{
				$fields = $element->xpath('descendant-or-self::field');

				foreach ($fields as $field)
				{
					if (!isset($field['default']))
					{
						continue;
					}

					$name    = (string) $field['name'];
					$default = (string) $field['default'];

					$params[$name] = $default;
				}
			}
		}
		catch (\RuntimeException $exception)
		{
			Log::add(Text::_('\n>> Exception: loadDefaultParams: '), Log::INFO, 'rsg2');
		}

		return $params;
	}
    /**/
    
} // class
