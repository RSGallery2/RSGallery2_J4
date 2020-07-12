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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Table\Table;


/**
 * Item Model for a Configuration items (options).
 *
 * @since  1.0
 */
class ConfigRawModel extends BaseModel
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


    /**
     * Extract configuration variables from RSG2 config file to reset to original values
     *
     * @throws \Exception
     *
     * @since version
     */
    public function ResetConfigToDefault()
    {
        $isSaved = false;

        try {

            //$xmlFile = JPATH_COMPONENT_ADMINISTRATOR . '/config.xml';
            $xmlFile = JPATH_ADMINISTRATOR . '/components/com_rsgallery2/config.xml';

            // Attempt to load the XML file.
            $xmlOuter = simplexml_load_file($xmlFile);
            // If there is nothing to load return
            if (empty($xmlOuter)) {
                $OutTxt = '';
                $OutTxt .= Text::_('Could not find config.xml file. No change applied');;

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
            else {
                // attribArray if it is an config xml file
                $xpath = "/config";
                $xmlConfig = $xmlOuter->xpath($xpath);

                // If there is nothing to load return
                if (empty($xmlConfig)) {
                    $OutTxt = Text::_('Could not read config.xml contents. No change applied');

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                } else {
                    //
                    $configFromXml = [];

                    // fetch fields
                    $result = $xmlOuter->xpath("//field");

                    // extract name and value from all fields
                    foreach ($result as $item) {

                        // convert to array
                        $fieldAttributes = current($item->attributes());

                        $type = $fieldAttributes ['type'];

                        // Valid data ?
                        if ($type != 'spacer' && $type != 'note') {

                            $name = $fieldAttributes ['name'];
                            // default existing ?
                            if (isset ($fieldAttributes ['default'])) {
                                $value = $fieldAttributes ['default'];
                            } else {
                                $value = "";
                            }

                            $configFromXml[$name] = $value;
                        }
                    };


                    // Save parameter
                    $isSaved = $this->saveItems($configFromXml);
                }
            }
        }
        catch (\RuntimeException $e)
		{
            $OutTxt = '';
            $OutTxt .= 'ConfigRawModel: Error in ResetConfigToDefault: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


		return $isSaved;
    }







        // ToDo: replace all of followoing functions with call to  MaintenanceJ3xModel

    /**
	function copyJ3xConfig2J4xOptions ($oldConfigItems)
	{
		$isSaved = false;

		try
		{
			if (count($oldConfigItems))
			{
				$isSaved = $this->copyJ3xConfigItems2J4xOptions ($oldConfigItems);
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'ConfigRawModel: Error in copyJ3xConfig2J4xOptions: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}
	/**/

	public function copyJ3xConfigItems2J4xOptions ($j4xConfigItems,
                                                   $assistedJ3xItems,
//                                                   $assistedJ4xItems,
                                                   $mergedItems)
	{
		$isSaved = false;
		// $actElement = "No element";

		try
		{
		    // copy 1:1 items
			foreach ($mergedItems as $name => $value)
			{
                $j4xConfigItems [$name] = $value;
			}

            // assisted copying
			foreach ($assistedJ3xItems as $j3xName => $var)
			{
			    list($j4xName, $j4xNewValue) = $var;
                $j4xConfigItems [$j4xName] = $j4xNewValue;
			}

			// Save parameter
			$isSaved = $this->saveItems($j4xConfigItems);

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'ConfigRawModel: Error in copyJ3xConfigItems2J4xOptions: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}


		return $isSaved;
	}



}

