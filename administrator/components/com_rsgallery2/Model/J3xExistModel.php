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
class J3xExistModel extends BaseModel
{

    // ToDo: attention a double of this function exist. Remove either of them

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
            $db = Factory::getDbo();
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

