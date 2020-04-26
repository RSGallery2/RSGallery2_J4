<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_foos
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\RSGallery2\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Table\Table;

/**
 * Item Model for a Configuration items (options).
 *
 * @since  1.0
 */
class ConfigRawModel extends BaseDatabaseModel
{

	/**
	 * save raw to parameters ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	public function save()
	{
		// $msg = "Rsgallery2ModelConfigRaw: ";
		$isSaved = false;

		$input = Factory::getApplication()->input;
		$data    = $input->post->get('jform', array(), 'array');

		$isSaved = $this->saveItems($data);

		return $isSaved;
	}


	/**
	 * @param $configurationItems
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 * @since version
	 */
	public function SaveItems($configurationItems): bool
	{
        $isSaved = false;

		// ToDo: Remove bad injected code (Read xml -> type / check xml ..

		// ToDo: Try ...

		//$row = $this->getTable();
		$Rsg2Id = ComponentHelper::getComponent('com_rsgallery2')->id;
		$table  = Table::getInstance('extension');
		// Load the previous Data
		if (!$table->load($Rsg2Id))
		{
			throw new \RuntimeException($table->getError());
		}

		//$table->bind(array('params' => $data->toString()));
		$table->bind(array('params' => $configurationItems));

		// check for error
		if (!$table->check())
		{
			Factory::getApplication()->enqueueMessage(Text::_('ConfigRaw: Check for save failed ') . $table->getError(), 'error');
		}
		else
		{
			// Save to database
			if ($table->store())
			{
				$isSaved = true;
			}
			else
			{
				Factory::getApplication()->enqueueMessage(Text::_('ConfigRaw: Store for save failed ') . $table->getError(), 'error');
			}
		}

		return $isSaved;
	}

    // ToDo: replace all of followoing functions with call to  MaintenanceJ3xModel

    static function J3xConfigTableExist () {return self::J3xTableExist ('#__rsgallery2_config');}
    //static function J3xGalleriesTableExist () {return self::J3xTableExist ('#__rsgallery2_galleries');}
    //static function J3xImagesTableExist () {return self::J3xTableExist ('#__rsgallery2_files');}

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

    /**
	function copyOldItems2New ($oldConfigItems)
	{
		$isSaved = false;

		try
		{
			if (count($oldConfigItems))
			{
				$isSaved = $this->copyOldItemsList2New ($oldConfigItems);
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

		return $isSaved;
	}
	/**/

	public function copyOldItemsList2New ($configItems)
	{
		$isSaved = false;
		// $actElement = "No element";

		$actConfig = ComponentHelper::getParams('com_rsgallery2');

		try
		{
			foreach ($configItems as $name => $value)
			{
				// switch for special indirect behaviour
				// oldCfgName -> different new config name
				// switch ()
				// { case , default }
				//---------------------------------------
//				$name = $NameAndValue->name;
//				$value = $NameAndValue->value;

				// $valNew = $this->configVars->get($name)  ?? null;
				if ($actConfig->exists($name)) {

					$actConfig->set($name, $value);

				}
				// default: id oldName == new Name -> copy value
			}

			$mergedConfig = [];

			foreach ($actConfig as $key => $value)
			{
				$mergedConfig [$key] = $value;

			}

			// Save parameter
			$isSaved = $this->saveItems($mergedConfig);

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'OldConfigItems: Error in copyOldItemsList2New: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}


		return $isSaved;
	}

    static function readRsg2ManifestData ()
    {
        $manifest = [];

        try
        {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select('manifest_cache')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('name') . ' = ' . $db->quote('COM_RSGALLERY2'));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();
            // $result = $db->loadObjectList()

            if ( ! empty ($jsonStr))
            {
                $manifest = json_decode($jsonStr, true);
            }

        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'readRsg2ManifestData: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $manifest;
    }


    static function readRsg2Config_FromExtensionTable ()
    {
        $params = [];

        try
        {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select('params')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('name') . ' = ' . $db->quote('COM_RSGALLERY2'));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();
            if ( ! empty ($jsonStr))
            {
                $params = json_decode($jsonStr, true);
            }

        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'readConfigFromExtensionTable: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $params;
    }



}

