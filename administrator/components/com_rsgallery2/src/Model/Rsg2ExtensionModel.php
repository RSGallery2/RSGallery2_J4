<?php
/**
 * @package    com_rsgallery2
 *
 * @author     RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2020-2022 RSGallery2 Team
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://www.rsgallery2.org
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseModel;

// required is used as classes may not be loaded on  fresh install
// !!! needed by install

/**
 * Handles RSG2 manifest data
 *
 * @since __BUMP_VERSION__
 *
 */
//class Rsg2ExtensionModel
class Rsg2ExtensionModel extends BaseModel
{

    static function readRsg2ExtensionManifest ()
    {
        $manifest = [];

        try
        {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select('manifest_cache')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();

            if ( ! empty ($jsonStr))
            {
                $manifest = json_decode($jsonStr, true);
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: readRsg2ExtensionManifest: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $manifest;
    }


    static function readRsg2ExtensionConfiguration ()
    {
        $params = [];

        try
        {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select('params')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();
            if ( ! empty ($jsonStr))
            {
                $params = json_decode($jsonStr, true);
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: readConfigFromExtensionTable: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $params;
    }

    static function readRsg2ExtensionData ()
    {
        $extensionData = [];

        try
        {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_rsgallery2'));
            $db->setQuery($query);

            $extensionData = $db->loadAssoc();

        }
        catch (\RuntimeException $e)
        {
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
    static function readRsg2DefaultParams ($component='com_rsgallery2')
    {
        $params = [];

        try
        {
			$file   = JPATH_ADMINISTRATOR . '/components/' . $component . '/config.xml';

			if (!file_exists($file))
			{
				return $params;
			}

			$xml = simplexml_load_file($file);

			if (!($xml instanceof SimpleXMLElement))
			{
				return $params;
			}

			$elements = $xml->xpath('/config');

			if (empty($elements))
			{
				return $params;
			}

			foreach ($elements as $element)
			{
				$fields = $element->xpath('descendant-or-self::field');

				foreach ($fields as $field)
				{
					if (!isset($field['default']))
					{
						continue;
					}

					$name    = (string) $field['name'];
					$default = (string) $field['default'];

					$params[$name] = $default;
				}
			} // elements

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Rsg2ExtensionModel: readRsg2DefaultParams: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $params;
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


