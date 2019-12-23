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

}

