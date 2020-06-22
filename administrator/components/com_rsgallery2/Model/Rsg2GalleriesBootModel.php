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
 * Handles bootstrap of legacy
 *
 * @since version
 *
 */
class Rsg2GalleriesBootModel
{
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


