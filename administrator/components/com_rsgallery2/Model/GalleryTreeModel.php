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
use Joomla\CMS\MVC\Model\BaseModel;

// required may be needed as classes may not be loaded on  fresh install
// !!! needed by install

/**
 * Handles basic functions for nested gallery tree
 * Nested gallery tree needs a root element in the database to start
 *
 * @since version
 *
 */

class GalleryTreeModel extends BaseModel
{

    /**
     * Reset gallery table to empty state
     * Deletes all galleries and initialises the root item of the nested tree
     *
     * @param int $rgt
     *
     * @return bool
     *
     * @since version
     */
    public static function reinitNestedGalleryTable($rgt=1)
    {
        $isGalleryTreeReset = false;

        $id_galleries = '#__rsg2_galleries';

        try {
            $db = Factory::getDbo();

            //--- delete old rows -----------------------------------------------

            $query = $db->getQuery(true);

            $query->delete($db->quoteName($id_galleries));
            // all rows
            //$query->where($conditions);

            $db->setQuery($query);

            $isRowsDeleted = $db->execute();

            //--- insert root of nested list ------------------------------------

            $lft = 0;
            $path = '';

            // ToDo: adjust columns to below and use this form
            // -- INSERT INTO `#__rsg2_galleries` (`name`,`alias`,`description`, `parent_id`, `level`, `path`, `lft`, `rgt`) VALUES
            // -- ('galleries root','galleries-root-alias','startpoint of list', 0, 0, '', 0, 1);

            // insert root record
//            $query = $db->getQuery(true)
//                ->insert('#__profiles')
//                ->set('parent_id = 0')
//                ->set('lft = 0')
//                ->set('rgt = 1')
//                ->set('level = 0')
//                ->set('title = ' . $db->quote('galleries root'))
//                ->set('alias = ' . $db->quote('galleries root'))
//                ->set('access = 1')
//                ->set('path = ' . $db->quote(''));

            $name = 'galleries root';
            $alias = 'groot';

            $date = Factory::getDate();
            $user = Factory::getUser();

            // insert root record
            // Missing
            $columns = array('id', 'name', 'alias', 'description', 'note', 'params', 'parent_id',
                'level', 'path', 'lft', 'rgt', 'created', 'created_by', 'modified', 'modified_by', );
            $values =  array(1, $name, $alias, 'root element of nested gallery list', '', '', 0,
                0, $path, $lft, $rgt, $date, $user->id, $date, $user->id);

            // Create root element
            $query = $db->getQuery(true)
                ->insert('#__rsg2_galleries')
                ->columns($db->quoteName($columns))
                ->values(implode(',', $db->quote($values)));
            $db->setQuery($query);

            $result = $db->execute();
            if ($result) {
                $isGalleryTreeReset = true;
            } else {
                Factory::getApplication()->enqueueMessage("Failed writing root into gallery database", 'error');
            }

        } //catch (\RuntimeException $e)
        catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . ' from InitGalleryTree');
        }

        return $isGalleryTreeReset;
    }












































    //    /**
//     * InitGalleryTree
//     * Intializes the nested tree with a root element
//     *
//     * @return bool
//     * @throws Exception
//     *
//     * @since
//     */
//    public static function InitGalleryTree()
//    {
//        $isGalleryTreeCreated = false;
//
//        $id_galleries = '#__rsg2_galleries';
//
//        try {
//            $db = Factory::getDbo();
//
//            Log::add('InitGalleryTree', Log::INFO, 'rsg2');
//            // echo '<p>Checking if the root record is already present ...</p>';
//
//            // Check Id of binary root element existing
//            $query = $db->getQuery(true);
//            $query->select('id');
//            $query->from($id_galleries);
//            $query->where('id = 1');
//            $query->where('alias = "galleries-root-alias"');
//            $db->setQuery($query);
//
//            $id = $db->loadResult();
//
//            // tree structure already built ?
//            if ($id == '1') {
//                Log::add('Gallery table root record already present exiting ...', Log::INFO, 'rsg2');
//            } else {
//
//                // -- INSERT INTO `#__rsg2_galleries` (`name`,`alias`,`description`, `parent_id`, `level`, `path`, `lft`, `rgt`) VALUES
//                // -- ('galleries root','galleries-root-alias','startpoint of list', 0, 0, '', 0, 1);
//
//                // insert root record
//                // Missing
//                $columns = array('id', 'name', 'alias', 'description', 'note', 'params', 'parent_id', 'level', 'path', 'lft', 'rgt');
//                $values = array(1, 'galleries root', 'galleries-root-alias', 'root element of nested list', '', '', 0, 0, '', 0, 1);
//
//                // Create root element
//                $query = $db->getQuery(true)
//                    ->insert('#__rsg2_galleries')
//                    ->columns($db->quoteName($columns))
//                    ->values(implode(',', $db->quote($values)));
//                $db->setQuery($query);
//
//                $result = $db->execute();
//                if ($result) {
//                    $isGalleryTreeCreated = true;
//                } else {
//                    Factory::getApplication()->enqueueMessage("Failed writing root into gallery database", 'error');
//                }
//            }
//        } //catch (\RuntimeException $e)
//        catch (\Exception $e) {
//            throw new \RuntimeException($e->getMessage() . ' from InitGalleryTree');
//        }
//
//        return $isGalleryTreeCreated;
//    }

//    /**
//     * ResetGalleryTree
//     * Delete content of gallery table and init with nesetd ...
//     *
//     * @return bool
//     * @throws Exception
//     *
//     * @since
//     */
//    public static function ResetGalleryTree()
//    {
//        $isGalleryTreeResetted = false;
//
//
//        try {
//            $db = Factory::getDbo();
//
//            Log::add('ResetGalleryTree', Log::INFO, 'rsg2');
//            // echo '<p>Checking if the root record is already present ...</p>';
//
//            // ToDO: fillout ResetGalleryTree()
//
//
//
//
//        } //catch (\RuntimeException $e)
//        catch (\Exception $e) {
//            throw new \RuntimeException($e->getMessage() . ' from ResetGalleryTree');
//        }
//
//        return $isGalleryTreeResetted;
//    }



} // class


