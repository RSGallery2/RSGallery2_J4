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

class MaintenanceModel extends BaseDatabaseModel
{

	static function J3xConfigTableExist () {return MaintenanceModel::J3xTableExist ('#__rsgallery2_config');}
	static function J3xGalleriesTableExist () {return MaintenanceModel::J3xTableExist ('#__rsgallery2_galleries');}
	static function J3xImagesTableExist () {return MaintenanceModel::J3xTableExist ('#__rsgallery2_files');}

	static function J3xTableExist ($findTable)
	{
		$tableExist = false;

		try
		{
			$existingTables = Factory::getDbo()->setQuery('SHOW TABLES')->loadColumn();
			$tableExist = array_key_exists($findTable, $existingTables);
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