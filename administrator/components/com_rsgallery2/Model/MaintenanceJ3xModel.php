<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Rsgallery2\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Class MaintenanceJ3xModel
 * @package Joomla\Component\Rsgallery2\Administrator\Model
 *
 * Handles old J3x RSG23 data structures. Especially for transferring the config data
 *
 *
 */

class MaintenanceJ3xModel extends BaseDatabaseModel
{

    /**
     * @return array|mixed
     * @throws \Exception
     */
	static function OldConfigItems()
	{
		$oldItems = array();

		try
		{
			if (MaintenanceJ3xModel::J3xConfigTableExist())
			{
				// Create a new query object.
				$db    = Factory::getDbo();
				$query = $db->getQuery(true);

				$query
					//->select('*')
					->select($db->quoteName(array('name', 'value')))
					->from($db->quoteName('#__rsgallery2_config'))
					->order($db->quoteName('name') . ' ASC');
				$db->setQuery($query);

				$oldItems = $db->loadAssocList('name', 'value');
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'OldConfigItems: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $oldItems;
	}

    /**
     * @param $OldConfigItems
     * @param $configVars
     * @return array
     * @throws \Exception
     */

    // ToDo: There may other merged operation needed instead of 1:1 copy
	static function MergeOldAndNew($OldConfigItems, $configVars)
	{
		// component parameters to array
		$compConfig = [];
		$mergedConfigItems = [];

		try
		{

			foreach ($configVars as  $key => $value)
			{
				$compConfig [$key] = $value;
			}

			// tell about merge
            $compConfig ['j3x_merged_cfg_version'] = '0.1';

			// J3.5 old configuration vars
			$mergedConfigItems = array_merge($OldConfigItems, $compConfig);

			ksort($mergedConfigItems);

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'OldConfigItems: Error executing MergeOldAndNew: <br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		// ToDo: who writes update

		return $mergedConfigItems;
	}


	static function J3xConfigTableExist () {return MaintenanceJ3xModel::J3xTableExist ('#__rsgallery2_config');}
	static function J3xGalleriesTableExist () {return MaintenanceJ3xModel::J3xTableExist ('#__rsgallery2_galleries');}
	static function J3xImagesTableExist () {return MaintenanceJ3xModel::J3xTableExist ('#__rsgallery2_files');}

	static function J3xTableExist ($findTable)
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