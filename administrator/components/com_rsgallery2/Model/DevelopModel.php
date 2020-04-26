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

use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class DevelopModel extends BaseDatabaseModel
{
    /**

    // ToDo: conside move to configRawModel
	// ToDo: replace all of followoing functions with call to  MaintenanceJ3xModel

	static function J3xConfigTableExist () {return MaintenanceModel::J3xTableExist ('#__rsgallery2_config');}
	static function J3xGalleriesTableExist () {return MaintenanceModel::J3xTableExist ('#__rsgallery2_galleries');}
	static function J3xImagesTableExist () {return MaintenanceModel::J3xTableExist ('#__rsgallery2_files');}

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
    /**/
}

