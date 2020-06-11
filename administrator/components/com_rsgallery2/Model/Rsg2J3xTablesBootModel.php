<?php
/**
 * @package    com_rsgallery2
 *
 * @author     RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2020-2020 RSGallery2 Team
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://www.rsgallery2.org
 */

namespace Joomla\Component\RSGallery2\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// required is used as classes may not be loaded on  fresh install
// !!! needed by install

/**
 * Handles bootstrap of legacy j3x tables
 *
 * @since version
 *
 */
class Rsg2J3xTablesBootModel
{

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
            $OutTxt .= 'ConfigRawModel: J3xTableExist: Error executing query: "' . "SHOW_TABLES" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $tableExist;
    }



} // class


