<?php
/**
 * @package    com_rsgallery2
 *
 * @author     RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2020-2021 RSGallery2 Team
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
            $OutTxt .= 'ConfigRawModel: readRsg2ExtensionManifest: Error executing query: "' . $query . '"' . '<br>';
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
            $OutTxt .= 'ConfigRawModel: readConfigFromExtensionTable: Error executing query: "' . $query . '"' . '<br>';
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
            $OutTxt .= 'ConfigRawModel: readRsg2ExtensionData: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $extensionData;
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


