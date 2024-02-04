<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Table\Table;

/**
 * Item Model for a Configuration items (options).
 *
 * @since __BUMP_VERSION__
 */
class ConfigRawModel extends BaseModel
{

	/**
	 * save raw to parameters ...
	 *
	 * @return string
	 *
	 * @since __BUMP_VERSION__
	 */
	public function saveFromForm()
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
	 * @since __BUMP_VERSION__
	 */
	public function saveItems($configurationItems): bool
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

		// ToDo: Use result
        $SecuredItems = $this->SecureConfigurationItems ($configurationItems);

        //$table->bind(array('params' => $configurationItems));
		$table->bind(array('params' => $SecuredItems));

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


    public function SecureConfigurationItems($configurationItems)
    {
        $securedItems = [];

        $filter         = \JFilterInput::getInstance();
        //$filter         = FilterInput::getInstance();

// ToDo: JFilterInput::clean Check other types in joomla doc

        foreach ($configurationItems as $key => $value) {

            $secured = ''; // preset

            // Test types in different way
            switch ($key) {
                case 'advancedSef':
                case 'isDebugBackend':
                case 'isDebugSite':
                case 'isDevelop':
                case 'thumb_size':
                case 'thumb_style':
                case 'jpegQuality':
                case 'keepOriginalImage':
                case 'useJ3xOldPaths':

                    $secured = $filter->clean ($value, 'int');
                    break;

                case 'ftp_path': // '\'images\/rsgallery2\',',
                case 'imgPath_root': //'images\/rsgallery2',
                case 'imgPath_original': //'\/images\/rsgallery\/original',
                case 'imgPath_display': //'\/images\/rsgallery\/display',
                case 'imgPath_thumb': //'\/images\/rsgallery\/thumb',

                    $secured = $filter->clean ($value, 'STRING');
                    break;

                case 'intro_text': // ''
                    $secured = $filter->clean ($value, 'html');
                    break;

                case 'image_size': // '800,600,400',
                    $secured = $filter->clean ($value, 'STRING');
                    break;

                case 'allowedFileTypes':// 'jpg,jpeg,gif,png',
                default:

                    $secured = $filter->clean ($value, 'STRING');
                break;


            }

            $inType = gettype ($value);
            $outype = gettype ($secured);

            $securedItems [$key] = strval($secured);
        }

        return $securedItems;
    }

    /**
     * Extract configuration variables from RSG2 config file to reset to original values
     *
     * @throws \Exception
     *
     * @since __BUMP_VERSION__
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
                // attribArray if it is a config xml file
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


    /**
     * Write single configuration parameter
     * Use seldom and with care ! (+ separate set ;-) )
     * @param string $param
     * @param string $value
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public static function writeConfigParam ($param='', $value='')
    {

        // Load the current component params.
        $params = ComponentHelper::getParams('com_rsgallery2');
        // Set new value of param(s)
        $params->set($param, $value);

        // Save the parameters
        $componentid = ComponentHelper::getComponent('com_rsgallery2')->id;
        $table = Table::getInstance('extension');
        $table->load($componentid);
        $table->bind(array('params' => $params->toString()));

        // check for error
        if (!$table->check()) {
            throw new \RuntimeException($table->getError());
        }
        // Save to database
        if (!$table->store()) {
            throw new \RuntimeException($table->getError());
        }

        return true;
    }


}

