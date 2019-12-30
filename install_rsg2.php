<?php

/**
 * @package    com_rsgallery2
 *
 * @author     RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2019 RSGallery2 Team
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://www.rsgallery2.org
 */
// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

/**
 * Script file of Rsgallery2 Component
 *
 * @since  1.0.0
 *
 */
 
 
class Com_Rsgallery2InstallerScript
{
	/**
	 * Extension script constructor.
	 *
	 * @since  1.0.0
	 *
	 */
	public function __construct()
	{
		$this->minimumJoomla = '4.0';
		$this->minimumPhp = JOOMLA_MINIMUM_PHP;
		
		// Check if the default log directory can be written to, add a logger for errors to use it
		if (is_writable(JPATH_ADMINISTRATOR . '/logs'))
		{
			$logOptions['format']    = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
			$logOptions['text_file'] = 'rsg2_install.php';
			$logType = Log::ALL;
			$logChannels = ['rsg2']; //jerror ...
			Log::addLogger($logOptions, $logType, $logChannels);
			
			try
			{
				Log::add(Text::_('Installer construct'), Log::INFO, 'rsg2');
			}
			catch (RuntimeException $exception)
			{
				// Informational log only
			}
 		}
	}

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
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  1.0.0
	 *
	 */
	public function preflight($type, $parent)
	{
		// Check for the minimum PHP version before continuing
		if (!empty($this->minimumPhp) && version_compare(PHP_VERSION, $this->minimumPhp, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');

			return false;
		}

		// Check for the minimum Joomla version before continuing
		if (!empty($this->minimumJoomla) && version_compare(JVERSION, $this->minimumJoomla, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');

			return false;
		}

		// ToDo: minimum RSG2 version

		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');


		// COM_RSGALLERY2_PREFLIGHT_INSTALL_TEXT / COM_RSGALLERY2_PREFLIGHT_UPDATE_TEXT
		// COM_RSGALLERY2_PREFLIGHT_UNINSTALL_TEXT
		echo Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_PREFLIGHT');

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
	 * @since  1.0.0
	 *
	 */
	public function install($parent)
	{
		echo Text::_('COM_RSGALLERY2_INSTALL_TEXT');
		Log::add(Text::_('COM_RSGALLERY2_INSTALL_TEXT'), Log::INFO, 'rsg2');

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
	 * @since  1.0.0
	 *
	 */
	public function update($parent)
	{
		echo Text::_('COM_RSGALLERY2_UPDATE_TEXT');
		Log::add(Text::_('COM_RSGALLERY2_UPDATE_TEXT'), Log::INFO, 'rsg2');

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
	 * @since  1.0.0
	 *
	 */
	public function postflight($type, $parent)
	{

		// COM_RSGALLERY2_POSTFLIGHT_UPDATE_TEXT, COM_RSGALLERY2_POSTFLIGHT_INSTALL_TEXT
		// NO:  COM_RSGALLERY2_POSTFLIGHT_UNINSTALL_TEXT
		echo Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_POSTFLIGHT');
		Log::add(Text::_('COM_RSGALLERY2_INSTALLERSCRIPT_POSTFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');

		$isGalleryTreeCreated = $this->InitGalleryTree ();

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
	 * @since  1.0.0
	 *
	 */
	public function uninstall($parent)
	{
		echo Text::_('COM_RSGALLERY2_UNINSTALL_TEXT');
		Log::add(Text::_('COM_RSGALLERY2_UNINSTALL_TEXT'), Log::INFO, 'rsg2');

		return true;
	}

	public function InitGalleryTree()
	{
		$isGalleryTreeCreated = false;
		
		$id_galleries = '#__rsg2_galleries';
		$id_j3x_config = '#__rsgallery2_config';
		
		try
		{
			$db = Factory::getDbo();

			Log::add('InitGalleryTree', Log::INFO, 'rsg2');
			echo '<p>Checking if the root record is already present ...</p>';

			// Id of binary root element
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from($id_galleries);
			$query->where('id = 1');
			$query->where('alias = "galleries-root-alias"');
			$db->setQuery($query);
			$id = $db->loadResult();

			if ($id == '1')
			{   // assume tree structure already built
				echo '<p>Root record already present, install program exiting ...</p>';
				Log::add('Root record already present, install program exiting ...', Log::INFO, 'rsg2');
			}
			else
			{
				// -- INSERT INTO `#__rsg2_galleries` (`name`,`alias`,`description`, `parent_id`, `level`, `path`, `lft`, `rgt`) VALUES
				// -- ('galleries root','galleries-root-alias','startpoint of list', 0, 0, '', 0, 1);

				// insert root record
				$columns = array('id', 'name', 'alias', 'description', 'parent_id', 'level', 'path', 'lft', 'rgt');
				$values  = array(1, 'galleries root', 'galleries-root-alias', 'startpoint of list', 0, 0, '', 0, 1);

				// Create root element
				$query = $db->getQuery(true)
					->insert('#__rsg2_galleries')
					->columns($db->quoteName($columns))
					->values(implode(',', $db->quote($values)));
				$db->setQuery($query);
				$result = $db->execute();
				if ($result)
				{
					$isGalleryTreeCreated = true;
				}
				else
				{
					Factory::getApplication()->enqueueMessage("Failed writing root into gallery database", 'error InitGalleryTree');
				}
			}
			
			//---------------------------------------------
			
			Log::add('check for existing old J3x Tables', Log::INFO, 'rsg2');
			
			// prepare taking over old 
			if ($this->Rsg2TableExist ($id_j3x_config)) {

				Log::add('Old J3x Tables exist', Log::INFO, 'rsg2');
				
				$j3x_model = new \Joomla\Component\Rsgallery2\Administrator\Model\MaintenanceJ3x;
				Log::add('after $j3x_model', Log::INFO, 'rsg2');
				
				$doesExist = $j3x_model->Rsg2TableExist ($id_j3x_config);
				//$j3x_model->copyOldItems2New ();
				Log::add('after copyOldItems2New', Log::INFO, 'rsg2');
				Log::add('$doesExist: ' .  $doesExist, Log::INFO, 'rsg2');
			}
			else
			{
				echo '<p>Checking failed: table not existing</p>';
				Log::add('Checking failed: table not existing', Log::INFO, 'rsg2');
			}
		}
		//catch (\RuntimeException $e)
		catch (\Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error InitGalleryTree');
		}
		
		return $isGalleryTreeCreated;
	}
	
	public function Rsg2TableExist ($findTable)
	{
		$tableExist = false;

		try
		{
			$db = Factory::getDbo();
			$db->setQuery('SHOW TABLES');
			$existingTables = $db->loadColumn();

			$checkTable = $db->replacePrefix($findTable);

			$tableExist = in_array($checkTable, $existingTables);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'J3xTableExist: Error executing query: "' . "SHOW_TABLES" . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $tableExist;
	}


	
	
	
	
	
}
