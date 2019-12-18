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

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class MaintenanceJ3xModel extends BaseDatabaseModel
{


	/**
	 * This function will retrieve the data of the n last uploaded images
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, gallery name, date, and user name as rows
	 *
	 * @since   4.3.0
	 */
	static function OldConfigItems()
	{
		$oldItems = array();

		try
		{
			// Create a new query object.
			$db    = Factory::getDBO();
			$query = $db->getQuery(true);

			$query
				->select('*')
				//->select('name' , 'value')
				->from($db->quoteName('#__rsgallery2_config'))
				->order($db->quoteName('name') . ' ASC');

			$db->setQuery($query);
			//$rows = $db->loadObjectList();
			$vars = $db->loadAssocList();

			//--- List of configuration items ----------------------------------------------------

			if ($vars)
			{
				foreach ($vars as $v)
				{
					if ($v['name'] != "")
					{
						$name  = $v['name'];
						$value = strlen($v['value']) ? $v['value'] : "";

						$oldItems[$name] = $value;
					}
				}
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

	static function MergeOldAndNew($OldConfigItems, $configVars)
	{
		// component parameters to array
		$compConfig = [];

		foreach ($configVars as  $key => $value)
		{
			$compConfig [$key] = $value;
		}

		// J3.5 old configuration vars
		// tOdO try ...
		$mergedConfigItems = array_merge($OldConfigItems, $compConfig);

		ksort($mergedConfigItems);

		return $mergedConfigItems;
	}




	static function copyOldItems2New ()
	{
		$isOk = false;

		try
		{
			$oldConfigItems = MaintenanceJ3xModel::OldConfigItems();
			if (count($oldConfigItems))
			{
				$isOk = MaintenanceJ3xModel::copyOldItemsList2New ($oldConfigItems);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'OldConfigItems: Error in copyOldItems2New: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isOk;
	}

	static function copyOldItemsList2New ($configItems)
	{
		$isOk = false;
		$actElement = "No element";

		try
		{
			foreach ($configItems as $NameAndValue)
			{
				// switch for special indirect behaviour
				// oldCfgName -> differnt new config name
				// switch ()
				// { case , default }
				//---------------------------------------


				// default: id oldName == new Name -> copy value



			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'OldConfigItems: Error in copyOldItemsList2New: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}



		return $isOk;
	}



}