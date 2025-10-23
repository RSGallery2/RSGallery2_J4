<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2020-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\Database\DatabaseInterface;

// required is used as classes may not be loaded on fresh install
// !!! needed by install

/**
 * Handles RSG2 manifest data
 *
 * @since __BUMP_VERSION__
 *
 */
class Rsg2ExtensionModel extends BaseModel
{

    /**
     *
     * @return array|mixed
     *
     * @throws \Exception
     * @since  5.1.0     */
    static function readRsg2ExtensionManifest()
    {
        $manifest = [];

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db->createQuery()
                ->select('manifest_cache')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();

            if (!empty ($jsonStr)) {
                $manifest = json_decode($jsonStr, true);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: readRsg2ExtensionManifest: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $manifest;
    }

    static function readRsg2ExtensionConfiguration()
    {
        $params = [];

        try {
            // read the existing component value(s)
            $db = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db->createQuery()
                ->select('params')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);

            /* found in install but why reassign parameters ? registry ?
            $param_array = json_decode($db->loadResult(), true);

            // add the new variable(s) to the existing one(s)
            foreach ($param_array as $name => $value) {
                $params[(string)$name] = (string)$value;
            }
            /**/

            $jsonStr = $db->loadResult();
            if (!empty ($jsonStr)) {
                $params = json_decode($jsonStr, true);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: readRsg2ExtensionConfiguration: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            Log::add(Text::_('\n>> \Exception: readRsg2ExtensionConfiguration: '), Log::INFO, 'rsg2');
        }

        return $params;
    }

    static function readRsg2ExtensionData()
    {
        $extensionData = [];

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db->createQuery()
                ->select('*')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);

            $extensionData = $db->loadAssoc();
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: readRsg2ExtensionData: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $extensionData;
    }

    /**
     * Original: joomlatools / joomlatools-platform github 2023.01
     * Parses the config.xml for the given component and
     * returns the default values for each parameter.
     *
     * @param   $component string  component name (com_xyz)
     *
     * @return  array   Array of parameters
     *
     *
     * @copyright   joomlatools: Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
     * @license     joomlatools: GNU General Public License version 2 or later; see LICENSE
     *
     */
    static function readRsg2ExtensionDefaultConfiguration($component = 'com_rsgallery2')
    {
        $params = [];

        try {
            $file = JPATH_ADMINISTRATOR . '/components/' . $component . '/config.xml';

            if (!file_exists($file)) {
                return $params;
            }

            $xml = simplexml_load_file($file);

            // wrong content ?
            if (empty($xml)) {
                return $params;
            }

            $elements = $xml->xpath('/config');

            // wrong content ?
            if (empty($elements)) {
                return $params;
            }

            foreach ($elements as $element) {
                $fields = $element->xpath('descendant-or-self::field');

                foreach ($fields as $field) {
                    if (!isset($field['default'])) {
                        continue;
                    }

                    $name    = (string)$field['name'];
                    $default = (string)$field['default'];

                    $params[$name] = $default;
                }
            } // elements

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: readRsg2ExtensionDefaultConfiguration: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            Log::add(Text::_('\n>> \Exception: readRsg2ExtensionDefaultConfiguration: '), Log::INFO, 'rsg2');
        }

        return $params;
    }

    /**
     * mergeDefaultAndActualParams
     * reduces the parameter set to default vaues
     * overwrites default by existing actual values
     *
     * @param $actual
     * @param $default
     *
     * @return mixed
     *
     * @throws \Exception
     * @since  5.1.0     */
    public static function mergeDefaultAndActualParams($default, $actual)
    {
        $merged = [];

        try {
            $merged = $default;

            foreach ($default as $name => $value) {
                if ($name == 'isDebugBackend') {
                    $test = true;
                    if (!empty ($merged [$name])) {
                        $test2 = $merged [$name];
                    }
                }

                // overwrite on existing parameter
                if (!empty ($actual [$name])) {
                    $merged [$name] = $actual [$name];
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: mergeDefaultAndActualParams: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            Log::add(Text::_('\n>> \Exception: mergeDefaultAndActualParams: '), Log::INFO, 'rsg2');
        }

        return $merged;
    }

    /**
     * write parameter set into the component's row of the extension table
     *
     * @param $params
     */
	public static function replaceRsg2ExtensionConfiguration($params)
    {
        $isWritten = false;

        try {
            // parameter exist
            if ((!empty ($params)) && count($params) > 0) {
                // store the combined new and existing values back as a JSON string
                $paramsString = json_encode($params);

                $db = Factory::getContainer()->get(DatabaseInterface::class);

				$query = $db->createQuery()
                    ->update($db->quoteName('#__extensions'))
                    ->set($db->quoteName('params') . ' = ' . $db->quote($paramsString))
                    ->where($db->quoteName('name') . ' = ' . $db->quote('com_rsgallery2'));

                if ($db->execute()) {
                    $successful = true;
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: replaceRsg2ExtensionConfiguration: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            Log::add(Text::_('\n>> \Exception: replaceRsg2ExtensionConfiguration: '), Log::INFO, 'rsg2');
        }

        return $isWritten;
    }




//    static function getVersionFromManifestParam()
//    {
//        //$version = '1.0.0.999';
//        $version = '';
//
//
//        $ManifestData = self::readRsg2ExtensionManifest();
//        if (!empty ($ManifestData['version'])) {
//            $version = $ManifestData['version'];
//        }
//
//        return $version;
//    }

} // class


