<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @author          RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c)  2020-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\Database\DatabaseInterface;
use RuntimeException;

use function defined;

// required may be needed as classes may not be loaded on fresh install
// !!! needed by install

/**
 * Handles basic functions for nested gallery tree
 * Nested gallery tree needs a root element in the database to start
 *
 * @since __BUMP_VERSION__
 *
 */
class GalleryTreeModel extends BaseModel
{

    /**
     * Check if at least one gallery exists
     * Regards the nested structure (ID=1 is only root of tree and no gallery)
     *
     * @return true on galleries found
     *
     * @since __BUMP_VERSION__
     */
    // ToDo: change to static ?
    /**
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function isRootItemExisting()
    {
        $is1GalleryExisting = false;

        try {
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->getQuery(true);

            // count gallery items
            $query
                ->select('COUNT(*)')
                // ignore root item  where id is "1"
                ->where($db->quoteName('id') . ' = 1')
                ->from('#__rsg2_galleries');

            $db->setQuery($query, 0, 1);
            $IdGallery = $db->loadResult();

            // > 0 galleries exist
            $is1GalleryExisting = !empty ($IdGallery);
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'GalleryTreeModel::is1GalleryRootItemExisting: Error count in "__rsg2_galleries" table' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $is1GalleryExisting;
    }

    /**
     * Reset gallery table to empty state
     * Deletes all galleries and initialises the root item of the nested tree
     *
     * @param   int  $rgt
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public static function reinitNestedGalleryTable($rgt = 1)
    {
        $isGalleryTreeReset = false;

        $id_galleries = '#__rsg2_galleries';

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            //--- delete old rows -----------------------------------------------

            $query = $db->getQuery(true);

            $query->delete($db->quoteName($id_galleries));
            // all rows
            //$query->where($conditions);

            $db->setQuery($query);

            $isRowsDeleted = $db->execute();

            //--- insert root of nested list ------------------------------------

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

            $lft  = 0;
            $path = '';

            $name  = 'galleries root';
            $alias = 'groot';

            $date = Factory::getDate();
            $user = Factory::getApplication()->getIdentity();

            // insert root record
            // Missing
            $columns = [
                'id',
                'name',
                'alias',
                'description',
                'note',
                'params',
                'parent_id',
                'level',
                'path',
                'lft',
                'rgt',
                'created',
                'created_by',
                'modified',
                'modified_by',
                'sizes',
            ];
            $values  = [
                1,
                $name,
                $alias,
                'root element of nested gallery list',
                '',
                '',
                0,
                0,
                $path,
                $lft,
                $rgt,
                $date,
                $user->id,
                $date,
                $user->id,
                '',
            ];

            // Create root element
            $query = $db
                ->getQuery(true)
                ->insert('#__rsg2_galleries')
                ->columns($db->quoteName($columns))
                ->values(implode(',', $db->quote($values)));
            $db->setQuery($query);

            $result = $db->execute();
            if ($result) {
                $isGalleryTreeReset = true;
            } else {
                Factory::getApplication()->enqueueMessage(
                    "Failed writing tree root item into gallery database",
                    'error',
                );
            }
        } //catch (\RuntimeException $e)
        catch (Exception $e) {
            throw new RuntimeException($e->getMessage() . ' from InitGalleryTree');
        }

        return $isGalleryTreeReset;
    }

} // class
