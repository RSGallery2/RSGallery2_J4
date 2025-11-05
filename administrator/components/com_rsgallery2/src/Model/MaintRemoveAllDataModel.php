<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2014-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Class MaintRemoveAllDataModel
 *
 * @package Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
 * used for removing image files and remove of all data
 *
 *
 */
class MaintRemoveAllDataModel extends BaseDatabaseModel
{

    /**
     *
     * @return array
     *
     * @throws \Exception
     * @since  5.1.0     */
    public function removeAllImageFiles()
    {
        global $rsgConfig;

        $isRemoved = false;
        $msg       = '';

        try {
            // activate config
            if (!$rsgConfig) {
                $rsgConfig = ComponentHelper::getParams('com_rsgallery2');
            }

            // define path
            $imagePaths = new ImagePathsModel (0);  // ToDo: J3x
            $removePath = $imagePaths->rsgImagesBasePath;

            $OutTxt = 'MaintRemoveAllDataModel: Executing removeAllImageFiles: <br> Remove Path: "' . $removePath . '"';
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'notice');
            $isRemoved = true;
//			$this->removeImagesInFolder($removePath);

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'MaintRemoveAllDataModel: Error executing removeAllImageFiles: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

//		return array($isRemoved, $msg); // list($isRemoved, $msg)
        return [$isRemoved, $msg]; // [$isRemoved, $msg] = ...
    }

    /**
     * Remove image files in given folder
     *
     * @param   string  $fullPath  Path to images to be deleted
     *
     * @return string Success message of folder
     *
     * @since  5.1.0     */
    private function removeImagesInFolder($fullPath = '')
    {
        $msg = 'Remove images in folder: "' . $fullPath . '"';

        //--- valid path ? ----------------------------------

        if (empty ($fullPath)) {
            $errMsg = Text::_('COM_RSGALLERY2_FOLDER_DOES_NOT_EXIST');
            $msg    .= "\n" . $errMsg;

            Factory::getApplication()->enqueueMessage($msg, 'error');

            return $msg;
        }

        // Check that path is valid
        if (!is_dir($fullPath)) {
            $errMsg = Text::_('COM_RSGALLERY2_FOLDER_DOES_NOT_EXIST') . ': "' . $fullPath . '""';
            $msg    .= "\n" . $errMsg;

            Factory::getApplication()->enqueueMessage($msg, 'error');

            return $msg;
        }

        /* ToDo: check that path is valid and not a base path to "anywhere"
        if (! ($fullPath == JPATH_ROOT))
        {
            $errMsg = Text::_('COM_RSGALLERY2_FOLDER_DOES_NOT_EXIST') . ': "' . $fullPath . '""';
            $msg .= "\n" . $errMsg;

            Factory::getApplication()->enqueueMessage($msg, 'error');
            return $msg;
        }
        /**/

        //--- remove display images ------------------------

        try {
            foreach (glob($fullPath . '\*') as $filename) {
                if (is_file($filename)) {
                    unlink($filename);
                }
            }

            $msg .= ' successfully';
        } catch (\Exception $e) {
            $msg .= '. error found: ' . $e->getMessage();
        }

        return $msg;
    }

    /**
     * @param $OldConfigItems
     * @param $configVars
     *
     * @return array
     * @throws \Exception
     */

    // ToDo: There may other merged operation needed instead of 1:1 copy
    public function removeDataInTables()
    {
        $isRemoved = false;
        $msg       = '';

        try {
            $OutTxt = 'MaintRemoveAllDataModel: Executing removeDataInTables: <br>';
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'notice');
            $isRemoved = true;

//            if (false) {
//                // [$isRemoved_acl,       $msgTmp] = $this->PurgeTable('#__rsg2_acl', Text::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_ACL')) . '<br>';
//                [$isRemoved_images, $msgTmp] = $this->PurgeTable(
//                    '#__rsg2_images',
//                    Text::_('COM_RSGALLERY2_PURGED_IMAGE_ENTRIES_FROM_DATABASE'),
//                ) . '<br>';
//                [$isRemoved_galleries, $msgTmp] = $this->PurgeTable(
//                    '#__rsg2_galleries',
//                    Text::_('COM_RSGALLERY2_PURGED_GALLERIES_FROM_DATABASE'),
//                ) . '<br>';
//                // [$isRemoved_comments,  $msgTmp] = $this->PurgeTable('#__rsg2_comments', Text::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_COMMENTS')) . '<br>';
//
//                // $isRemoved is defined by necessary existing tables
//                //$isRemoved = $isRemoved_acl && $isRemoved_images && $isRemoved_galleries && $isRemoved_comments;
//                $isRemoved = $isRemoved_images && $isRemoved_galleries;
//
//                // J3x old tables
//                [$isRemoved, $msgTmp] = $this->PurgeTable(
//                    '#__rsgallery2_acl',
//                    Text::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_ACL'),
//                ) . '<br>';
//                [$isRemoved, $msgTmp] = $this->PurgeTable(
//                    '#__rsgallery2_files',
//                    Text::_('COM_RSGALLERY2_PURGED_IMAGE_ENTRIES_FROM_DATABASE'),
//                ) . '<br>';
//                //[$isRemoved, $msgTmp] = $this->PurgeTable('#__rsgallery2_cats', Text::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_CATS')) . '<br>';
//                [$isRemoved, $msgTmp] = $this->PurgeTable(
//                    '#__rsgallery2_galleries',
//                    Text::_('COM_RSGALLERY2_PURGED_GALLERIES_FROM_DATABASE'),
//                ) . '<br>';
//                [$isRemoved, $msgTmp] = $this->PurgeTable(
//                    '#__rsgallery2_config',
//                    Text::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_CONFIG'),
//                ) . '<br>';
//                [$isRemoved, $msgTmp] = $this->PurgeTable(
//                    '#__rsgallery2_comments',
//                    Text::_('COM_RSGALLERY2_PURGED_TABLE_RSGALLERY2_COMMENTS'),
//                ) . '<br>';
//            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'MaintRemoveAllDataModel: Error executing removeDataInTables: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return [$isRemoved, $msg]; // [$isRemoved, $msg] = ...
    }

    /**
     * Delete data in given table from RSG2
     *
     * @param   string  $tableId
     *
     * @return array
     *
     * @since  5.1.0     */
    private function PurgeTable($tableId, $successMsg)
    {
        $isPurged = false;
        $msg      = 'Purge table: ' . $tableId;

        try {
            $db = Factory::getDbo();

            $db->truncateTable($tableId);
            $db->execute();

            $isPurged = true;
        } catch (\Exception $e) {
            $msg = 'Purge table failure: "' . $tableId . '" ' . $e->getCode() . ':' . $e->getMessage() . '\n';
        }

        return [$isPurged, $msg]; // [$isRemoved, $msg] = ...
    }
}


