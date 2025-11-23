<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Form\Form;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\UCM\UCMType;
use Joomla\CMS\Workflow\Workflow;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

/**
 * Rsgallery2 Component Gallery Model
 *
     * @since      5.1.0
 */
class GalleryModel extends AdminModel
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  5.1.0     */
    protected $text_prefix = 'COM_RSGALLERY2';

    /**
     * The type alias for this content type. Used for content version history.
     *
     * @var      string
     * @since    5.1.0     */
    public $typeAlias = 'com_rsgallery2.gallery';

    /**
     * The context used for the associations table
     *
     * @var      string
     * @since    5.1.0     */
    protected $associationsContext = 'com_rsgallery2.gallery';

    /**
     * Override parent constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     *
     * @see     \Joomla\CMS\MVC\Model\BaseDatabaseModel
     * @since   5.1.0     *
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        $extension = Factory::getApplication()->input->get('extension', 'com_rsgallery2');
        $this->typeAlias = $extension . '.category';

        // Add a new batch command
        $this->batch_commands['flip_ordering'] = 'batchFlipordering';

        parent::__construct($config, $factory);
    }
    /**/

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
     *
     * @since   5.1.0     */
    protected function canDelete($record)
    {
        if (empty($record->id) || $record->published != -2) {
            return false;
        }

        return Factory::getApplication()->getIdentity()->authorise(
            'core.delete',
            $record->extension . '.category.' . (int)$record->id,
        );
    }

    /**
     * Method to test whether a record can have its state changed.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
     *
     * @since   5.1.0     */
    protected function canEditState($record)
    {
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        // Check for existing category.
        if (!empty($record->id)) {
            return $user->authorise('core.edit.state', $record->extension . '.category.' . (int)$record->id);
        }

        // New category, so check against the parent.
        if (!empty($record->parent_id)) {
            return $user->authorise('core.edit.state', $record->extension . '.category.' . (int)$record->parent_id);
        }

        // Default to component settings if neither category nor parent known.
        return $user->authorise('core.edit.state', $record->extension);
    }

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  Table  A Table object
     *
     * @since   5.1.0     */
    public function getTable($type = 'Gallery', $prefix = 'Rsgallery2Table', $config = [])
    {
        return parent::getTable($type, $prefix, $config);
    }

    /**
     * Auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return  void
     *
     * @since   5.1.0     */
    protected function populateState()
    {
        $app = Factory::getApplication();

        $parentId = $app->input->getInt('parent_id');
        $this->setState('category.parent_id', $parentId);

        // Load the User state.
        $pk = $app->input->getInt('id');
        $this->setState($this->getName() . '.id', $pk);

        $extension = $app->input->get('extension', 'com_rsgallery2');
        $this->setState('category.extension', $extension);
        $parts = explode('.', $extension);

        // Extract the component name
        $this->setState('category.component', $parts[0]);

        // Extract the optional section name
        $this->setState('category.section', (count($parts) > 1) ? $parts[1] : null);

        // Load the parameters.
        $params = ComponentHelper::getParams('com_rsgallery2');
        $this->setState('params', $params);
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     *
     * @since   5.1.0     */
    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        // Load associated foo items
        $assoc = Associations::isEnabled();

        if ($assoc) {
            $item->associations = [];

            if ($item->id != null) {
                $associations = Associations::getAssociations('com_foos', '#__foos_details', 'com_foos.item', $item->id, 'id', null);

                foreach ($associations as $tag => $association) {
                    $item->associations[$tag] = $association->id;
                }
            }
        }

        $item->galleryOrdering = $item->id;

        return $item;
    }


    /**
     * Method to get the row form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  Form|boolean  A Form object on success, false on failure
     *
     * @since   5.1.0     */
    public function getForm($data = [], $loadData = true)
    {
        /**
        $extension = $this->getState('category.extension');
        $jinput = Factory::getApplication()->input;

        // A workaround to get the extension into the model for save requests.
        if (empty($extension) && isset($data['extension']))
        {
            $extension = $data['extension'];
            $parts = explode('.', $extension);

            $this->setState('category.extension', $extension);
            $this->setState('category.component', $parts[0]);
            $this->setState('category.section', @$parts[1]);
        }
        /**/
        // Get the form.
//      $form = $this->loadForm('com_rsgallery2.category' . $extension, 'category', array('control' => 'jform', 'load_data' => $loadData));
        $form = $this->loadForm('com_rsgallery2.gallery', 'gallery', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }

        /**
        // Modify the form based on Edit State access controls.
        if (empty($data['extension']))
        {
            $data['extension'] = $extension;
        }

        $categoryId = $jinput->get('id');
        $parts      = explode('.', $extension);
        $assetKey   = $categoryId ? $extension . '.category.' . $categoryId : $parts[0];

        if (!Factory::getApplication()->getIdentity()->authorise('core.edit.state', $assetKey))
        {
            // Disable fields for display.
            $form->setFieldAttribute('ordering', 'disabled', 'true');
            $form->setFieldAttribute('published', 'disabled', 'true');

            // Disable fields while saving.
            // The controller has already verified this is a record you can edit.
            $form->setFieldAttribute('ordering', 'filter', 'unset');
            $form->setFieldAttribute('published', 'filter', 'unset');
        }
        /**/
        return $form;
    }

    /**
     * A protected method to get the where clause for the reorder
     * This ensures that the row will be moved relative to a row with the same extension
     *
     * @param   Table  $table  Current table instance
     *
     * @return  array  An array of conditions to add to ordering queries.
     *
     * @since   5.1.0     */
    protected function getReorderConditions($table)
    {
        return [
            $this->_db->quoteName('extension') . ' = ' . $this->_db->quote($table->extension),
        ];
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   5.1.0     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_rsgallery2.edit.' . $this->getName() . '.data', []);

        if (empty($data)) {
            $data = $this->getItem();

            // Pre-select some filters (Status, Language, Access) in edit form if those have been selected in Category Manager
            if (!$data->id) {
                // Check for which extension the Category Manager is used and get selected fields
                $extUserState = $app->getUserState('com_rsgallery2.galleries.filter.extension');
                $extension = "";
                if (! empty($extUserState)) {
                    $extension = substr($extUserState, 4);
                }
                $filters   = (array)$app->getUserState('com_rsgallery2.galleries.' . $extension . '.filter');

                $data->set(
                    'published',
                    $app->input->getInt(
                        'published',
                        ((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null),
                    ),
                );
//              $data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
                $data->set(
                    'access',
                    $app->input->getInt(
                        'access',
                        (!empty($filters['access']) ? $filters['access'] : $app->get('access')),
                    ),
                );
            }
        }

        // $this->preprocessData('com_rsgallery2.category', $data);
        $this->preprocessData('com_rsgallery2.gallery', $data);

        return $data;
    }

    /**
     * Method to preprocess the form.
     *
     * @param   Form  $form   A Form object.
     * @param   mixed   $data   The data expected for the form.
     * @param   string  $group  The name of the plugin group to import.
     *
     * @return  void
     *
     * @throws  \Exception if there is an error in the form event.
     *
    protected function preprocessForm(\Form $form, $data, $group = 'content')
    {
    }
    /**/


    /**
     * Transform some data before it is displayed ? Saved ?
     * extension development 129 bottom
     *
     * @param   Table  $table
     *
     * @since   5.1.0     */
    /**/
    /**
     * @param $table
     *
     *
     * @throws \Exception
     * @since  5.1.0     */
    protected function prepareTable($table)
    {
        $date        = Factory::getDate()->toSql();
        $table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

        if (empty($table->id)) {
            /**
            // Set ordering to the last item if not set
            if (empty($table->ordering))
            {
                $db = $this->getDatabase();
                $query = $db->createQuery()
                    ->select('MAX(ordering)')
                    ->from($db->quoteName('#__rsg2_images'));
                $db->setQuery($query);
                $max = $db->loadResult();

                $table->ordering = $max + 1;

                // Set the values
                $table->date = $date;
                $table->userid = Factory::getApplication()->getIdentity()->id;
            }
            /**/

            //$table->ordering = $table->getNextOrder('gallery_id = ' . (int) $table->gallery_id); // . ' AND state >= 0');

            // Set the values
            $table->created    = $date;
            $table->created_by = Factory::getApplication()->getIdentity()->id;
        } else {
            // Set the values
            $table->modified    = $date;
            $table->modified_by = Factory::getApplication()->getIdentity()->id;
        }

        // Set the publish date to now
        if ($table->published == Workflow::CONDITION_PUBLISHED && (int)$table->publish_up == 0) {
            $table->publish_up = Factory::getDate()->toSql();
        }

        if ($table->published == Workflow::CONDITION_PUBLISHED && intval($table->publish_down) == 0) {
            $table->publish_down = null;
        }

        // Increment the content version number.
        // $table->version++;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   5.1.0     */
    public function save($data)
    {
        $table = $this->getTable();
        $input   = Factory::getApplication()->input;
        $pk    = (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName() . '.id');
        $isNew      = true;
        $context = $this->option . '.' . $this->name;

        if (!empty($data['tags']) && $data['tags'][0] != '') {
            $table->newTags = $data['tags'];
        }

        // Include the plugins for the save events.
        PluginHelper::importPlugin($this->events_map['save']);

        // Load the row if saving an existing category.
        if ($pk > 0) {
            $table->load($pk);
            $isNew = false;
        }

        //--- gallery ordering --------------------------------------

        // !!! see menu item model

        if (!$isNew) {

            if ($table->parent_id == $data['parent_id']) {
                // If first is chosen make the item the first child of the selected parent.
                if ($data['galleryOrdering'] == -1) {
                    $table->setLocation($data['parent_id'], 'first-child');
                } elseif ($data['galleryOrdering'] == -2) {
                    // If last is chosen make it the last child of the selected parent.
                    $table->setLocation($data['parent_id'], 'last-child');
                } elseif ($data['galleryOrdering'] && $table->id != $data['galleryOrdering'] || empty($data['id'])) {
                    // Don't try to put an item after itself. All other ones put after the selected item.
                    // $data['id'] is empty means it's a save as copy
                    $table->setLocation($data['galleryOrdering'], 'after');
                } elseif ($data['galleryOrdering'] && $table->id == $data['galleryOrdering']) {
                    // \Just leave it where it is if no change is made.
                    unset($data['galleryOrdering']);
                }
            } else {
                // Set the new parent id if parent id not matched and put in last position
                $table->setLocation($data['parent_id'], 'last-child');
            }

//            // Check if we are moving to a different gallery ???
//            if ($data['menutype'] != $table->menutype) {
//                // Add the child node ids to the children array.
//                $query->clear()
//                    ->select($db->quoteName('id'))
//                    ->from($db->quoteName('#__menu'))
//                    ->where($db->quoteName('lft') . ' BETWEEN ' . (int) $table->lft . ' AND ' . (int) $table->rgt);
//                $db->setQuery($query);
//                $children = (array) $db->loadColumn();
//            }
//
        } else {
            // We have a new item, so it is not a change.

            // $menuType = $this->getMenuType($data['menutype']);

            // $data['client_id'] = $menuType->client_id;


            $table->setLocation($data['parent_id'], 'last-child');
        }

//        // Automatic handling of alias for empty fields
//        if (
//            in_array(
//                $input->get('task'),
//                ['apply', 'save', 'save2new'],
//            ) && (!isset($data['id']) || (int)$data['id'] == 0)
//        ) {
//            if ($data['alias'] == null) {
//                if (Factory::getApplication()->get('unicodeslugs') == 1) {
//                    $data['alias'] = OutputFilter::stringURLUnicodeSlug($data['title']);
//                } else {
//                    $data['alias'] = OutputFilter::stringURLSafe($data['title']);
//                }
//
//                $content = Table::getInstance('Content', '\\Joomla\\CMS\\Table\\');
//
//                if ($content->load(['alias' => $data['alias'], 'catid' => $data['catid']])) {
//                    $msg = Text::_('COM_CONTENT_SAVE_WARNING');
//                }
//
//                [$title, $alias] = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
//                $data['alias'] = $alias;
//
//                if (isset($msg)) {
//                    Factory::getApplication()->enqueueMessage($msg, 'warning');
//                }
//            }
//        }

        // ToDo: use name instead of title ?
        // Alter the title for save as copy
        if ($input->get('task') == 'save2copy') {
            $origTable = clone $this->getTable();
            $origTable->load($input->getInt('id'));

            if ($data['title'] == $origTable->title) {
                [$title, $alias] = $this->generateNewTitle($data['parent_id'], $data['alias'], $data['title']);
                $data['title'] = $title;
                $data['alias'] = $alias;
            } else {
                if ($data['alias'] == $origTable->alias) {
                    $data['alias'] = '';
                }
            }

            $data['published'] = 0;
        }

        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());

            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());

            return false;
        }

        // Trigger the before save event.
        $result = Factory::getApplication()->triggerEvent($this->event_before_save, [$context, &$table, $isNew, $data]);

        if (\in_array(false, $result, true)) {
            $this->setError($table->getError());

            return false;
        }

        // ToDo: getassoc / tag-> id

        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());

            return false;
        }

        // Trigger the after save event.
        Factory::getApplication()->triggerEvent($this->event_after_save, [$context, &$table, $isNew]);

        // Rebuild the tree path.
        if (!$table->rebuildPath($table->id)) {
            $this->setError($table->getError());

            return false;
        }

        // Rebuild the paths of the menu item's children:
        if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path)) {
            $this->setError($table->getError());

            return false;
        }

//        // ToDO:
//        // Process the child rows
//        if (!empty($children)) {
//            // Remove any duplicates and sanitize ids.
//            $children = array_unique($children);
//            $children = ArrayHelper::toInteger($children);
//
//            // Update the menutype field in all nodes where necessary.
//            $query = $db->getQuery(true)
//                ->update($db->quoteName('#__menu'))
//                ->set($db->quoteName('menutype') . ' = :menutype')
//                ->whereIn($db->quoteName('id'), $children)
//                ->bind(':menutype', $data['menutype']);
//
//            try {
//                $db->setQuery($query);
//                $db->execute();
//            } catch (\RuntimeException $e) {
//                $this->setError($e->getMessage());
//
//                return false;
//            }
//        }

        $this->setState($this->getName() . '.id', $table->id);

        // ToDo: Load associated (not menu) items, for now not supported for admin menu… may be later

        // Clean the cache
        $this->cleanCache();

        return true;
    }

    // expected name/alias  is unique

    /**
     * @param $galleryName
     * @param $parentId
     * @param $description
     *
     * @return bool
     *
     * @throws \Exception
     * @since  5.1.0     */
    public function createGallery($galleryName, $parentId = 1, $description = '')
    {
        $isCreated = false;

        try {
            $data = [];

            $data ['name']        = $galleryName;
            $data ['alias']       = $galleryName;
            $data ['parent_id']   = $parentId;
            $data ['description'] = $description;

            $data ['note'] = '';

            $isCreated = $this->save($data);
            // $isCreated = true;

            // Check for errors.
            if (count($errors = $this->get('_errors'))) {
                throw new GenericDataException(implode("\n", $errors), 500);
            }
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isCreated;
    }

    /**
     * Method to change the published state of one or more records.
     *
     * @param   array    &$pks    A list of the primary keys to change.
     * @param   integer   $value  The value of the published state.
     *
     * @return  boolean  True on success.
     *
     * @since   5.1.0     */
    public function publish(&$pks, $value = 1)
    {
        if (parent::publish($pks, $value)) {
            $extension = Factory::getApplication()->input->get('extension');

            // Include the content plugins for the change of category state event.
            PluginHelper::importPlugin('content');

            // Trigger the onCategoryChangeState event.
            Factory::getApplication()->triggerEvent('onCategoryChangeState', [$extension, $pks, $value]);

            return true;
        }

        return false;
    }

    /**
     * Method rebuild the entire nested set tree.
     *
     * @return  boolean  False on failure or error, true otherwise.
     *
     * @since   5.1.0     */
    public function rebuild()
    {
        // Get an instance of the table object.
        $table = $this->getTable();

        if (!$table->rebuild()) {
            $this->setError($table->getError());

            return false;
        }

        // Clear the cache
        $this->cleanCache();

        return true;
    }

    /**
     * Method to save the reordered nested set tree.
     * First we save the new order values in the lft values of the changed ids.
     * Then we invoke the table rebuild to implement the new ordering.
     *
     * @param   array    $idArray    An array of primary key ids.
     * @param   integer  $lft_array  The lft value
     *
     * @return  boolean  False on failure or error, True otherwise
     *
     * @since   5.1.0     */
    public function saveorder($idArray = null, $lft_array = null)
    {
        // Get an instance of the table object.
        $table = $this->getTable();

        if (!$table->saveorder($idArray, $lft_array)) {
            $this->setError($table->getError());

            return false;
        }

        // Clear the cache
        $this->cleanCache();

        return true;
    }

    /**
     * Batch flip category ordering.
     *
     * @param   integer  $value     The new category.
     * @param   array    $pks       An array of row IDs.
     * @param   array    $contexts  An array of item contexts.
     *
     * @return  mixed    An array of new IDs on success, boolean false on failure.
     *
     * @since   5.1.0     */
    protected function batchFlipOrdering($value, $pks, $contexts)
    {
        $successful = [];

        try {
            $db    = $this->getDatabase();
            $query = $db->createQuery();

            /**
             * For each category get the max ordering value
             * Re-order with max - ordering
             */
            foreach ($pks as $id) {
                $query->select('MAX(ordering)')
                    ->from('#__content')
                    ->where($db->quoteName('catid') . ' = ' . $db->quote($id));

                $db->setQuery($query);

                $max = (int)$db->loadresult();
                $max++;

                $query->clear();

                $query
                    ->update('#__content')
                    ->set($db->quoteName('ordering') . ' = ' . $max . ' - ' . $db->quoteName('ordering'))
                    ->where($db->quoteName('catid') . ' = ' . $db->quote($id));

                $db->setQuery($query);

                if ($db->execute()) {
                    $successful[] = $id;
                }
            }
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return empty($successful) ? false : $successful;
    }

    /**
     * Batch copy galleries to a new gallery.
     *
     * @param   integer  $value     The new category.
     * @param   array    $pks       An array of row IDs.
     * @param   array    $contexts  An array of item contexts.
     *
     * @return  mixed    An array of new IDs on success, boolean false on failure.
     *
     * @since   5.1.0     */
    protected function batchCopy($value, $pks, $contexts)
    {
        $type       = new UCMType();
        $this->type = $type->getTypeByAlias($this->typeAlias);

        // $value comes as {parent_id}.{extension}
        $parts    = explode('.', $value);
        $parentId = (int)ArrayHelper::getValue($parts, 0, 1);

        $db        = $this->getDatabase();
        $extension = Factory::getApplication()->input->get('extension', '', 'word');
        $newIds    = [];

        // Check that the parent exists
        if ($parentId) {
            if (!$this->table->load($parentId)) {
                if ($error = $this->table->getError()) {
                    // Fatal error
                    $this->setError($error);

                    return false;
                } else {
                    // Non-fatal error
                    $this->setError(Text::_('JGLOBAL_BATCH_MOVE_PARENT_NOT_FOUND'));
                    $parentId = 0;
                }
            }

            // Check that user has create permission for parent category
            if ($parentId == $this->table->getRootId()) {
                $canCreate = $this->user->authorise('core.create', $extension);
            } else {
                $canCreate = $this->user->authorise('core.create', $extension . '.category.' . $parentId);
            }

            if (!$canCreate) {
                // Error since user cannot create in parent category
                $this->setError(Text::_('COM_RSGALLERY2_BATCH_CANNOT_CREATE'));

                return false;
            }
        }

        // If the parent is 0, set it to the ID of the root item in the tree
        if (empty($parentId)) {
            if (!$parentId = $this->table->getRootId()) {
                $this->setError($this->table->getError());

                return false;
            } // Make sure we can create in root
            elseif (!$this->user->authorise('core.create', $extension)) {
                $this->setError(Text::_('COM_RSGALLERY2_BATCH_CANNOT_CREATE'));

                return false;
            }
        }

        // We need to log the parent ID
        $parents = [];

        // Calculate the emergency stop count as a precaution against a runaway loop bug
        $query = $db->createQuery()
            ->select('COUNT(id)')
            ->from($db->quoteName('#__rsg2_galleries'));
        $db->setQuery($query);

        try {
            $count = $db->loadResult();
        } catch (\RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Parent exists so let's proceed
        while (!empty($pks) && $count > 0) {
            // Pop the first id off the stack
            $pk = array_shift($pks);

            $this->table->reset();

            // Check that the row actually exists
            if (!$this->table->load($pk)) {
                if ($error = $this->table->getError()) {
                    // Fatal error
                    $this->setError($error);

                    return false;
                } else {
                    // Not fatal error
                    $this->setError(Text::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
                    continue;
                }
            }

            // Copy is a bit tricky, because we also need to copy the children
            $query
                ->clear()
                ->select('id')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where('lft > ' . (int)$this->table->lft)
                ->where('rgt < ' . (int)$this->table->rgt);
            $db->setQuery($query);
            $childIds = $db->loadColumn();

            // Add child ID's to the array only if they aren't already there.
            foreach ($childIds as $childId) {
                if (!in_array($childId, $pks)) {
                    $pks[] = $childId;
                }
            }

            // Make a copy of the old ID and Parent ID
            $oldId       = $this->table->id;
            $oldParentId = $this->table->parent_id;

            // Reset the id because we are making a copy.
            $this->table->id = 0;

            // If we a copying children, the Old ID will turn up in the parents list
            // otherwise it's a new top level item
            $this->table->parent_id = $parents[$oldParentId] ?? $parentId;

            // Set the new location in the tree for the node.
            $this->table->setLocation($this->table->parent_id, 'last-child');

            // @TODO: Deal with ordering?
            // $this->table->ordering = 1;
            $this->table->level    = null;
            $this->table->asset_id = null;
            $this->table->lft      = null;
            $this->table->rgt      = null;

            //? title -> ? name
            // Alter the title & alias
            [$title, $alias] = $this->generateNewTitle(
                $this->table->parent_id,
                $this->table->alias,
                $this->table->title,
            );
            $this->table->title = $title;
            $this->table->alias = $alias;

            // Unpublish because we are making a copy
            $this->table->published = 0;

            // Store the row.
            if (!$this->table->store()) {
                $this->setError($this->table->getError());

                return false;
            }

            // Get the new item ID
            $newId = $this->table->get('id');

            // Add the new ID to the array
            $newIds[$pk] = $newId;

            // Now we log the old 'parent' to the new 'parent'
            $parents[$oldId] = $this->table->id;
            $count--;
        }

        // Rebuild the hierarchy.
        if (!$this->table->rebuild()) {
            $this->setError($this->table->getError());

            return false;
        }

        // Rebuild the tree path.
        if (!$this->table->rebuildPath($this->table->id)) {
            $this->setError($this->table->getError());

            return false;
        }

        return $newIds;
    }

    /**
     * Batch move galleries to a new gallery.
     *
     * @param   integer  $value     The new category ID.
     * @param   array    $pks       An array of row IDs.
     * @param   array    $contexts  An array of item contexts.
     *
     * @return  boolean  True on success.
     *
     * @since   5.1.0     */
    protected function batchMove($value, $pks, $contexts)
    {
        $parentId   = (int)$value;
        $type       = new UCMType();
        $this->type = $type->getTypeByAlias($this->typeAlias);

        $db        = $this->getDatabase();
        $query     = $db->createQuery();
        $extension = Factory::getApplication()->input->get('extension', '', 'word');

        // Check that the parent exists.
        if ($parentId) {
            if (!$this->table->load($parentId)) {
                if ($error = $this->table->getError()) {
                    // Fatal error.
                    $this->setError($error);

                    return false;
                } else {
                    // Non-fatal error.
                    $this->setError(Text::_('JGLOBAL_BATCH_MOVE_PARENT_NOT_FOUND'));
                    $parentId = 0;
                }
            }

            // Check that user has create permission for parent category.
            if ($parentId == $this->table->getRootId()) {
                $canCreate = $this->user->authorise('core.create', $extension);
            } else {
                $canCreate = $this->user->authorise('core.create', $extension . '.category.' . $parentId);
            }

            if (!$canCreate) {
                // Error since user cannot create in parent category
                $this->setError(Text::_('COM_RSGALLERY2_BATCH_CANNOT_CREATE'));

                return false;
            }

            // Check that user has edit permission for every category being moved
            // Note that the entire batch operation fails if any category lacks edit permission
            foreach ($pks as $pk) {
                if (!$this->user->authorise('core.edit', $extension . '.category.' . $pk)) {
                    // Error since user cannot edit this category
                    $this->setError(Text::_('COM_RSGALLERY2_BATCH_CANNOT_EDIT'));

                    return false;
                }
            }
        }

        // We are going to store all the children and just move the category
        $children = [];

        // Parent exists so let's proceed
        foreach ($pks as $pk) {
            // Check that the row actually exists
            if (!$this->table->load($pk)) {
                if ($error = $this->table->getError()) {
                    // Fatal error
                    $this->setError($error);

                    return false;
                } else {
                    // Not fatal error
                    $this->setError(Text::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
                    continue;
                }
            }

            // Set the new location in the tree for the node.
            $this->table->setLocation($parentId, 'last-child');

            // Check if we are moving to a different parent
            if ($parentId != $this->table->parent_id) {
                // Add the child node ids to the children array.
                $query
                    ->clear()
                    ->select('id')
                    ->from($db->quoteName('#__rsg2_galleries'))
                    ->where(
                        $db->quoteName('lft') . ' BETWEEN ' . (int)$this->table->lft . ' AND ' . (int)$this->table->rgt,
                    );
                $db->setQuery($query);

                try {
                    $children = array_merge($children, (array)$db->loadColumn());
                } catch (\RuntimeException $e) {
                    $this->setError($e->getMessage());

                    return false;
                }
            }

            // Store the row.
            if (!$this->table->store()) {
                $this->setError($this->table->getError());

                return false;
            }

            // Rebuild the tree path.
            if (!$this->table->rebuildPath()) {
                $this->setError($this->table->getError());

                return false;
            }
        }

        // Process the child rows
        if (!empty($children)) {
            // Remove any duplicates and sanitize ids.
            $children = array_unique($children);
            $children = ArrayHelper::toInteger($children);
        }

        return true;
    }

    /**
     * Custom clean the cache of com_rsgallery2 and content modules
     *
     * @param   string   $group      Cache group name.
     * @param   integer  $client_id  Application client id.
     *
     * @return  void
     *
     * @since   5.1.0     *
    protected function cleanCache($group = null, $client_id = 0)
    {
        $extension = Factory::getApplication()->input->get('extension');

        switch ($extension)
        {
            case 'com_rsgallery2':
                parent::cleanCache('com_rsgallery2');
                parent::cleanCache('mod_articles_archive');
                parent::cleanCache('mod_articles_categories');
                parent::cleanCache('mod_articles_category');
                parent::cleanCache('mod_articles_latest');
                parent::cleanCache('mod_articles_news');
                parent::cleanCache('mod_articles_popular');
                break;
            default:
                parent::cleanCache($extension);
                break;
        }
    }
    /**/

    /**
     * Method to change the title & alias.
     *
     * @param   integer  $parent_id  The id of the parent.
     * @param   string   $alias      The alias.
     * @param   string   $title      The title.
     *
     * @return  array    Contains the modified title and alias.
     *
     * @since   5.1.0     */
    //? title -> ? name
    protected function generateNewTitle($parent_id, $alias, $title)
    {
        // Alter the title & alias
        $table = $this->getTable();

        while ($table->load(['alias' => $alias, 'parent_id' => $parent_id])) {
            $title = StringHelper::increment($title);
            $alias = StringHelper::increment($alias, 'dash');
        }

        return [$title, $alias];
    }

    /**
     * Method to determine if a category association is available.
     *
     * @return  boolean True if a category association is available; false otherwise.
     *
    public function getAssoc()
    {
       // ? needed
    }
    /**/
}
