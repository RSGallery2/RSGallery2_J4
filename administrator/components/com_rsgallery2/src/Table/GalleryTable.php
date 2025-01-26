<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Table;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Nested;
use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;
use RuntimeException;
use UnexpectedValueException;

use function defined;

/**
 * Gallery table
 *
 * @since __BUMP_VERSION__
 */
class GalleryTable extends Nested
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since __BUMP_VERSION__
     */
    public function __construct(DatabaseDriver $db)
    {
        $this->typeAlias = 'com_rsgallery.gallery';

        parent::__construct('#__rsg2_galleries', 'id', $db);

        $this->access = (int)Factory::getApplication()->get('access');
    }

    /**
     * Overloaded bind function
     *
     * @param   array  $array   Named array
     * @param   mixed  $ignore  An optional array or space separated list of properties
     *                          to ignore while binding.
     *
     * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
     *
     * @see     \JTable::bind
     * @since   __BUMP_VERSION__
     */
    public function bind($array, $ignore = '')
    {
        if (isset($array['params']) && is_array($array['params'])) {
            $registry        = new Registry($array['params']);
            $array['params'] = (string)$registry;
        }

        return parent::bind($array, $ignore);
    }

    /**
     * Overloaded check method to ensure data integrity.
     *
     * @return  boolean  True on success.
     *
     * @throws  UnexpectedValueException
     * @since __BUMP_VERSION__
     */
    public function check()
    {
        try {
            parent::check();
        } catch (Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Check for valid name.
        if (trim($this->name) == '') {
            throw new UnexpectedValueException(sprintf('The name is empty'));
        }

        //--- alias -------------------------------------------------------------

        // ToDo: aliase must be singular see below store ?
        if (empty($this->alias)) {
            $this->alias = $this->name;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

        // just minuses -A use date
        if (trim(str_replace('-', '', $this->alias)) == '') {
            $this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
        }

        //--- parent id: check if parent exists -------------------------------------

        $this->parent_id = (int)$this->parent_id;

        // Nested does not allow parent_id = 0, override this.
        if ($this->parent_id > 0) {
            // Get the DatabaseQuery object
            $query = $this->_db
                ->getQuery(true)
                ->select('1')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('id') . ' = ' . $this->parent_id);

            $query->setLimit(1);

            if (empty ($this->_db->setQuery($query)->loadResult())) {
                $this->setError(Text::_('JLIB_DATABASE_ERROR_INVALID_PARENT_ID'));

                return false;
            }
        }

        //---   ---------------------------------------------

        // Check the publish down date is not earlier than publish up.
        if (!empty($this->publish_down) && !empty($this->publish_up) && $this->publish_down < $this->publish_up) {
            throw new UnexpectedValueException(sprintf('End publish date is before start publish date.'));
        }

        // Clean up description -- eliminate quotes and <> brackets

//        if (!empty($this->description))
//        {
//            // Only process if not empty
//            $bad_characters = array("\"", '<', '>');
//            $this->description = StringHelper::str_ireplace($bad_characters, '', $this->description);
//        }        else         {
//            $this->description = '';
//        }
        if (empty($this->description)) {
            $this->description = '';
        }

//        if (!empty($this->metadesc))
//        {
//            // Only process if not empty
//            $bad_characters = array("\"", '<', '>');
//            $this->metadesc = StringHelper::str_ireplace($bad_characters, '', $this->metadesc);
//        }
//        if (empty($this->metadesc))
//        {
//            $this->metadesc = '';
//        }

        if (empty($this->params)) {
            $this->params = '{}';
        }

        if (empty($this->sizes)) {
            $this->sizes = '';
        }

        if (!(int)$this->checked_out_time) {
            $this->checked_out_time = null;
        }

        if (!(int)$this->publish_up) {
            $this->publish_up = null;
        }

        if (!(int)$this->publish_down) {
            $this->publish_down = null;
        }

        return true;
    }

// ??? toDo: publish / unpublish parent with childs ?

    /**
     * Stores a gallery.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since __BUMP_VERSION__
     */
    public function store($updateNulls = false)
    {
        $date = Factory::getDate();
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        if ($this->id) {
            // Existing item
            $this->modified    = $date->toSql();
            $this->modified_by = $user->get('id');
        } else {
            // New tag. A tag created and created_by field can be set by the user,
            // so we don't touch either of these if they are set.
            if (!(int)$this->created) {
                $this->created = $date->toSql();
            }

            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }

            if (!(int)$this->modified) {
                $this->modified = $this->created;
            }

            if (empty($this->modified_by)) {
                $this->modified_by = $this->created_by;
            }

            // Text must be preset
            if ($this->description == null) {
                $this->description = '';
            }

            if ($this->sizes == null) {
                $this->sizes = '';
            }
        }

        // Verify that the alias is unique
        $table = new static($this->getDbo());

        if ($table->load(['alias' => $this->alias]) && ($table->id != $this->id || $this->id == 0)) {
            $this->setError(Text::_('COM_RSGALLERY2_ERROR_UNIQUE_ALIAS'));

            return false;
        }

        return parent::store($updateNulls);
    }

    /**
     * Method to delete a node and, optionally, its child nodes from the table.
     *
     * @param   integer  $pk        The primary key of the node to delete.
     * @param   boolean  $children  True to delete child nodes, false to move them up a level.
     *
     * @return  boolean  True on success.
     *
     * @since __BUMP_VERSION__
     */
    public function delete($pk = null, $children = false)
    {
        $return = parent::delete($pk, $children);

        if ($return) {
//            $helper = new TagsHelper;
//            $helper->tagDeleteInstances($pk);
        }

        return $return;
    }

    /**
     * Method to recursively rebuild the whole nested set tree.
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     *
     * @return  integer  1 + value of root rgt on success, false on failure
     *
     * @throws  RuntimeException on database error.
     * @since __BUMP_VERSION__
     */
    public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = null)
    {
        // If no parent is provided, try to find it.
        if ($parentId === null) {
            // Get the root item.
            $parentId = $this->getRootId();

            if ($parentId === false) {
                return false;
            }
        }

        $query = $this->_db->getQuery(true);

        // Build the structure of the recursive query.
        if (!isset($this->_cache['rebuild.sql'])) {
            $query
                ->clear()
                ->select($this->_tbl_key)
                ->from($this->_tbl)
                ->where('parent_id = %d');

            // If the table has an ordering field, use that for ordering.
            if ($this->hasField('ordering')) {
                $query->order('parent_id, ordering, lft');
            } else {
                $query->order('parent_id, lft');
            }

            $this->_cache['rebuild.sql'] = (string)$query;
        }

        // Make a shortcut to database object.

        // Assemble the query to find all children of this node.
        $this->_db->setQuery(sprintf($this->_cache['rebuild.sql'], (int)$parentId));

        $children = $this->_db->loadObjectList();

        // The right value of this node is the left value + 1
        $rightId = $leftId + 1;

        // Execute this function recursively over all children
        foreach ($children as $node) {
            /*
             * $rightId is the current right value, which is incremented on recursion return.
             * Increment the level for the children.
             * Add this item's alias to the path (but avoid a leading /)
             */
            $rightId = $this->rebuild($node->{$this->_tbl_key}, $rightId, $level + 1);

            // If there is an update failure, return false to break out of the recursion.
            if ($rightId === false) {
                return false;
            }
        }

        // We've got the left value, and now that we've processed
        // the children of this node we also know the right value.
        $query
            ->clear()
            ->update($this->_tbl)
            ->set('lft = ' . (int)$leftId)
            ->set('rgt = ' . (int)$rightId)
            ->set('level = ' . (int)$level)
            ->where($this->_tbl_key . ' = ' . (int)$parentId);
        $this->_db->setQuery($query)->execute();

        // Return the right value of this node + 1.
        return $rightId + 1;
    }
}
