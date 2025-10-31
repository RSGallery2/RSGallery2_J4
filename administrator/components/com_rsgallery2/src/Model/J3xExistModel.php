<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\Database\DatabaseInterface;



/**
 * Item Model for a Configuration items (options).
 *
 * @since __BUMP_VERSION__
 */
class J3xExistModel extends BaseModel
{

    // ToDo: attention a double of this function exist. Remove either of them

    /**
     *
     * @return bool
     *
     * @since  5.1.0     */
    static function J3xConfigTableExist()
    {
        return self::J3xTableExist('#__rsgallery2_config');
    }

    static function J3xGalleriesTableExist()
    {
        return self::J3xTableExist('#__rsgallery2_galleries');
    }

    static function J3xImagesTableExist()
    {
        return self::J3xTableExist('#__rsgallery2_files');
    }

    static function J3xTableExist($findTable)
    {
        $tableExist = false;

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);
            $db->setQuery('SHOW TABLES');
            $existingTables = $db->loadColumn();

            $checkTable = $db->replacePrefix($findTable);

            $tableExist = in_array($checkTable, $existingTables);
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'J3xExistModel: J3xTableExist: Error executing query: "' . "SHOW_TABLES" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $tableExist;
    }

}

