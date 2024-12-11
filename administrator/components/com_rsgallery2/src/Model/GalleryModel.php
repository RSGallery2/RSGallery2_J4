<?php
/**
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 *
 * @copyright
 * @license    GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
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
use RuntimeException;

use function defined;

/**
 * Rsgallery2 Component Gallery Model
 *
 * @since __BUMP_VERSION__
 */
class GalleryModel extends AdminModel
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
    protected $text_prefix = 'COM_RSGALLERY2';

    /**
     * The type alias for this content type. Used for content version history.
     *
     * @var      string
     * @since __BUMP_VERSION__
     */
    public $typeAlias = 'com_rsgallery2.gallery';

    /**
     * The context used for the associations table
     *
     * @var      string
     * @since __BUMP_VERSION__
     */
    protected $associationsContext = 'com_rsgallery2.gallery';

    /**
     * Override parent constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     *
     * @see     \Joomla\CMS\MVC\Model\BaseDatabaseModel
     * @since   __BUMP_VERSION__
     *
     * public function __construct($config = array(), MVCFactoryInterface $factory = null)
     * {
     * $extension = Factory::getApplication()->input->get('extension', 'com_rsgallery2');
     * $this->typeAlias = $extension . '.category';
     *
     * // Add a new batch command
     * $this->batch_commands['flip_ordering'] = 'batchFlipordering';
     *
     * parent::__construct($config, $factory);
     * }
     * /**/

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
     *
     * @since __BUMP_VERSION__
     */
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
     * @since __BUMP_VERSION__
     */
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
     * @return  Table  A JTable object
     *
     * @since __BUMP_VERSION__
     */
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
     * @since __BUMP_VERSION__
     */
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
     * @since __BUMP_VERSION__
     */
    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        // Load associated foo items
        $assoc = Associations::isEnabled();

        if ($assoc) {
            $item->associations = [];

            if ($item->id != null) {
                $associations = Associations::getAssociations(
                    'com_foos',
                    '#__foos_details',
                    'com_foos.item',
                    $item->id,
                    'id',
                    null,
                );

                foreach ($associations as $tag => $association) {
                    $item->associations[$tag] = $association->id;
                }
            }
        }

        return $item;
    }

    /**
     * Method to get a category.
     *
     * @param   integer  $pk  An optional id of the object to get, otherwise the id from the model state is used.
     *
     * @return  mixed    Category data object on success, false on failure.
     *
     * @since __BUMP_VERSION__
     *
     * public function getItem($pk = null)
     * {
     * if ($result = parent::getItem($pk))
     * {
     * // Prime required properties.
     * if (empty($result->id))
     * {
     * $result->parent_id = $this->getState('category.parent_id');
     * $result->extension = $this->getState('category.extension');
     * }
     *
     * // Convert the metadata field to an array.
     * $registry = new Registry($result->metadata);
     * $result->metadata = $registry->toArray();
     *
     * // Convert the created and modified dates to local user time for display in the form.
     * $tz = new \DateTimeZone(Factory::getApplication()->get('offset'));
     *
     * if ((int) $result->created_time)
     * {
     * $date = new Date($result->created_time);
     * $date->setTimezone($tz);
     * $result->created_time = $date->toSql(true);
     * }
     * else
     * {
     * $result->created_time = null;
     * }
     *
     * if ((int) $result->modified_time)
     * {
     * $date = new Date($result->modified_time);
     * $date->setTimezone($tz);
     * $result->modified_time = $date->toSql(true);
     * }
     * else
     * {
     * $result->modified_time = null;
     * }
     *
     * if (!empty($result->id))
     * {
     * //                $result->tags = new TagsHelper;
     * //                $result->tags->getTagIds($result->id, $result->extension . '.category');
     * }
     * }
     *
     * /**
     * $assoc = $this->getAssoc();
     *
     * if ($assoc)
     * {
     * if ($result->id != null)
     * {
     * $result->associations = ArrayHelper::toInteger(GalleriesHelper::getAssociations($result->id, $result->extension));
     * }
     * else
     * {
     * $result->associations = array();
     * }
     * }
     * /**
     *
     * return $result;
     * }
     * /**/

    /**
     * Method to get the row form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  Form|boolean  A Form object on success, false on failure
     *
     * @since __BUMP_VERSION__
     */
    public function getForm($data = [], $loadData = true)
    {
        /**
         * $extension = $this->getState('category.extension');
         * $input = Factory::getApplication()->input;
         *
         * // A workaround to get the extension into the model for save requests.
         * if (empty($extension) && isset($data['extension']))
         * {
         * $extension = $data['extension'];
         * $parts = explode('.', $extension);
         *
         * $this->setState('category.extension', $extension);
         * $this->setState('category.component', $parts[0]);
         * $this->setState('category.section', @$parts[1]);
         * }
         * /**/
        // Get the form.
//		$form = $this->loadForm('com_rsgallery2.category' . $extension, 'category', array('control' => 'jform', 'load_data' => $loadData));
        $form = $this->loadForm('com_rsgallery2.gallery', 'gallery', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }

        /**
         * // Modify the form based on Edit State access controls.
         * if (empty($data['extension']))
         * {
         * $data['extension'] = $extension;
         * }
         *
         * $categoryId = $input->get('id');
         * $parts      = explode('.', $extension);
         * $assetKey   = $categoryId ? $extension . '.category.' . $categoryId : $parts[0];
         *
         * if (!Factory::getApplication()->getIdentity()->authorise('core.edit.state', $assetKey))
         * {
         * // Disable fields for display.
         * $form->setFieldAttribute('ordering', 'disabled', 'true');
         * $form->setFieldAttribute('published', 'disabled', 'true');
         *
         * // Disable fields while saving.
         * // The controller has already verified this is a record you can edit.
         * $form->setFieldAttribute('ordering', 'filter', 'unset');
         * $form->setFieldAttribute('published', 'filter', 'unset');
         * }
         * /**/
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
     * @since __BUMP_VERSION__
     */
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
     * @since __BUMP_VERSION__
     */
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
                if ( ! empty ($extUserState)) {
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
//				$data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
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
     * @throws  Exception if there is an error in the form event.
     *
     * protected function preprocessForm(\Form $form, $data, $group = 'content')
     * {
     * $lang = Factory::getApplication()->getLanguage();
     * $component = $this->getState('category.component');
     * $section = $this->getState('category.section');
     * $extension = Factory::getApplication()->input->get('extension', null);
     *
     * // Get the component form if it exists
     * $name = 'category' . ($section ? ('.' . $section) : '');
     *
     * // Looking first in the component forms folder
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/forms/$name.xml");
     *
     * // Looking in the component models/forms folder (J! 3)
     * if (!file_exists($path))
     * {
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/models/forms/$name.xml");
     * }
     *
     * // Old way: looking in the component folder
     * if (!file_exists($path))
     * {
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/$name.xml");
     * }
     *
     * if (file_exists($path))
     * {
     * $lang->load($component, JPATH_BASE, null, false, true);
     * $lang->load($component, JPATH_BASE . '/components/' . $component, null, false, true);
     *
     * if (!$form->loadFile($path, false))
     * {
     * throw new \Exception(Text::_('JERROR_LOADFILE_FAILED'));
     * }
     * }
     *
     * $componentInterface = Factory::getApplication()->bootComponent($component);
     *
     * if ($componentInterface instanceof CategoryServiceInterface)
     * {
     * $componentInterface->prepareForm($form, $data);
     * }
     * else
     * {
     * // Try to find the component helper.
     * $eName = str_replace('com_', '', $component);
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/helpers/category.php");
     *
     * if (file_exists($path))
     * {
     * $cName = ucfirst($eName) . ucfirst($section) . 'HelperCategory';
     *
     * \JLoader::register($cName, $path);
     *
     * if (class_exists($cName) && is_callable(array($cName, 'onPrepareForm')))
     * {
     * $lang->load($component, JPATH_BASE, null, false, false)
     * || $lang->load($component, JPATH_BASE . '/components/' . $component, null, false, false)
     * || $lang->load($component, JPATH_BASE, $lang->getDefault(), false, false)
     * || $lang->load($component, JPATH_BASE . '/components/' . $component, $lang->getDefault(), false, false);
     * call_user_func_array(array($cName, 'onPrepareForm'), array(&$form));
     *
     * // Check for an error.
     * if ($form instanceof \Exception)
     * {
     * $this->setError($form->getMessage());
     *
     * return false;
     * }
     * }
     * }
     * }
     *
     * // Set the access control rules field component value.
     * $form->setFieldAttribute('rules', 'component', $component);
     * $form->setFieldAttribute('rules', 'section', $name);
     *
     * // Association category items
     * if ($this->getAssoc())
     * {
     * $languages = LanguageHelper::getContentLanguages(false, true, null, 'ordering', 'asc');
     *
     * if (count($languages) > 1)
     * {
     * $addform = new \SimpleXMLElement('<form />');
     * $fields = $addform->addChild('fields');
     * $fields->addAttribute('name', 'associations');
     * $fieldset = $fields->addChild('fieldset');
     * $fieldset->addAttribute('name', 'item_associations');
     *
     * foreach ($languages as $language)
     * {
     * $field = $fieldset->addChild('field');
     * $field->addAttribute('name', $language->lang_code);
     * $field->addAttribute('type', 'modal_category');
     * $field->addAttribute('language', $language->lang_code);
     * $field->addAttribute('label', $language->title);
     * $field->addAttribute('translate_label', 'false');
     * $field->addAttribute('extension', $extension);
     * $field->addAttribute('select', 'true');
     * $field->addAttribute('new', 'true');
     * $field->addAttribute('edit', 'true');
     * $field->addAttribute('clear', 'true');
     * }
     *
     * $form->load($addform, false);
     * }
     * }
     *
     * // Trigger the default form events.
     * parent::preprocessForm($form, $data, $group);
     * }
     * /**@since __BUMP_VERSION__
     * @see     \FormField
     */

    /**
     * Transform some data before it is displayed ? Saved ?
     * extension development 129 bottom
     *
     * @param   Table  $table
     *
     * @since __BUMP_VERSION__
     */
    /**/
    /**
     * @param $table
     *
     *
     * @throws Exception
     * @since version
     */
    protected function prepareTable($table)
    {
        $date        = Factory::getDate()->toSql();
        $table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

        if (empty($table->id)) {
            /**
             * // Set ordering to the last item if not set
             * if (empty($table->ordering))
             * {
             * $db = $this->getDatabase();
             * $query = $db->getQuery(true)
             * ->select('MAX(ordering)')
             * ->from($db->quoteName('#__rsg2_images'));
             * $db->setQuery($query);
             * $max = $db->loadResult();
             *
             * $table->ordering = $max + 1;
             *
             * // Set the values
             * $table->date = $date;
             * $table->userid = Factory::getApplication()->getIdentity()->id;
             * }
             * /**/

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
     * @since __BUMP_VERSION__
     */
    public function save($data)
    {
        $table = $this->getTable();
        $pk    = (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName() . '.id');
        //$isNew      = true;
        $context = $this->option . '.' . $this->name;
        $input   = Factory::getApplication()->input;

        if (!empty($data['tags']) && $data['tags'][0] != '') {
            $table->newTags = $data['tags'];
        }

        /** -> table *
         * // no default value
         * if (empty($data['description']))
         * {
         * $data['description'] = '';
         * }
         *
         * // no default value
         * if (empty($data['params']))
         * {
         * $data['params'] = '';
         * }
         * /**/

        // Include the plugins for the save events.
        PluginHelper::importPlugin($this->events_map['save']);

        // Load the row if saving an existing category.
        if ($pk > 0) {
            $table->load($pk);
            $isNew = false;
        }

        // Set the new parent id if parent id not matched OR while New/Save as Copy .
        if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
            $table->setLocation($data['parent_id'], 'last-child');
        }

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

        // Automatic handling of alias for empty fields
        if (in_array(
                $input->get('task'),
                ['apply', 'save', 'save2new'],
            ) && (!isset($data['id']) || (int)$data['id'] == 0)) {
            if ($data['alias'] == null) {
                if (Factory::getApplication()->get('unicodeslugs') == 1) {
                    $data['alias'] = OutputFilter::stringURLUnicodeSlug($data['title']);
                } else {
                    $data['alias'] = OutputFilter::stringURLSafe($data['title']);
                }

                $table = Table::getInstance('Content', 'JTable');

                if ($table->load(['alias' => $data['alias'], 'catid' => $data['catid']])) {
                    $msg = Text::_('COM_CONTENT_SAVE_WARNING');
                }

                [$title, $alias] = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
                $data['alias'] = $alias;

                if (isset($msg)) {
                    Factory::getApplication()->enqueueMessage($msg, 'warning');
                }
            }
        }

//        // Bind the data.
//		if (!$table->bind($data))
//		{
//			$this->setError($table->getError());
//
//			return false;
//		}
//
//		// Bind the rules.
//		if (isset($data['rules']))
//		{
//			$rules = new Rules($data['rules']);
//			$table->setRules($rules);
//		}
//
//		// Check the data.
//		if (!$table->check())
//		{
//			$this->setError($table->getError());
//
//			return false;
//		}

        // Trigger the before save event.
//		$result = Factory::getApplication()->triggerEvent($this->event_before_save, array($context, &$table, $isNew, $data));
//
//		if (in_array(false, $result, true))
//		{
//			$this->setError($table->getError());
//
//			return false;
//		}

        // Store the data.
//		if (!$table->store())
//		{
//
//			$this->setError($table->getError());
//
//			return false;
//		}

        if (parent::save($data)) {
            /**
             * $assoc = $this->getAssoc();
             *
             * if ($assoc)
             * {
             * // Adding self to the association
             * $associations = $data['associations'] ?? array();
             *
             * // Unset any invalid associations
             * $associations = ArrayHelper::toInteger($associations);
             *
             * foreach ($associations as $tag => $id)
             * {
             * if (!$id)
             * {
             * unset($associations[$tag]);
             * }
             * }
             *
             * // Detecting all item menus
             * $allLanguage = $table->language == '*';
             *
             * if ($allLanguage && !empty($associations))
             * {
             * Factory::getApplication()->enqueueMessage(Text::_('COM_RSGALLERY2_ERROR_ALL_LANGUAGE_ASSOCIATED'), 'notice');
             * }
             *
             * // Get associationskey for edited item
             * $db    = $this->getContainer()->get(DatabaseInterface::class);
             * $query = $db->getQuery(true)
             * ->select($db->quoteName('key'))
             * ->from($db->quoteName('#__associations'))
             * ->where($db->quoteName('context') . ' = ' . $db->quote($this->associationsContext))
             * ->where($db->quoteName('id') . ' = ' . (int) $table->id);
             * $db->setQuery($query);
             * $oldKey = $db->loadResult();
             *
             * // Deleting old associations for the associated items
             * $query = $db->getQuery(true)
             * ->delete($db->quoteName('#__associations'))
             * ->where($db->quoteName('context') . ' = ' . $db->quote($this->associationsContext));
             *
             * if ($associations)
             * {
             * $query->where('(' . $db->quoteName('id') . ' IN (' . implode(',', $associations) . ') OR '
             * . $db->quoteName('key') . ' = ' . $db->quote($oldKey) . ')');
             * }
             * else
             * {
             * $query->where($db->quoteName('key') . ' = ' . $db->quote($oldKey));
             * }
             *
             * $db->setQuery($query);
             *
             * try
             * {
             * $db->execute();
             * }
             * catch (\RuntimeException $e)
             * {
             * $this->setError($e->getMessage());
             *
             * return false;
             * }
             *
             * // Adding self to the association
             * if (!$allLanguage)
             * {
             * $associations[$table->language] = (int) $table->id;
             * }
             *
             * if (count($associations) > 1)
             * {
             * // Adding new association for these items
             * $key = md5(json_encode($associations));
             * $query->clear()
             * ->insert('#__associations');
             *
             * foreach ($associations as $id)
             * {
             * $query->values(((int) $id) . ',' . $db->quote($this->associationsContext) . ',' . $db->quote($key));
             * }
             *
             * $db->setQuery($query);
             *
             * try
             * {
             * $db->execute();
             * }
             * catch (\RuntimeException $e)
             * {
             * $this->setError($e->getMessage());
             *
             * return false;
             * }
             * }
             * }
             * /**/

//            // Trigger the after save event.
//            Factory::getApplication()->triggerEvent($this->event_after_save, array($context, &$table, $isNew, $data));
//
//            // Rebuild the path for the category:
//            if (!$table->rebuildPath($table->id)) {
//                $this->setError($table->getError());
//
//                return false;
//            }
//
//            // Rebuild the paths of the category's children:
//            if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path)) {
//                $this->setError($table->getError());
//
//                return false;
//            }
//
//            $this->setState($this->getName() . '.id', $table->id);
//
//            // Clear the cache
//            $this->cleanCache();

            return true;
        } else {
            return false;
        }
    }

    // expected name/alias  is unique

    /**
     * @param $galleryName
     * @param $parentId
     * @param $description
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
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
        } catch (RuntimeException $e) {
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
     * @since __BUMP_VERSION__
     */
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
    }

    /**
     * Method rebuild the entire nested set tree.
     *
     * @return  boolean  False on failure or error, true otherwise.
     *
     * @since __BUMP_VERSION__
     */
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
     * @since __BUMP_VERSION__
     */
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
     * @since __BUMP_VERSION__
     */
    protected function batchFlipOrdering($value, $pks, $contexts)
    {
        $successful = [];

        try {
            $db    = $this->getDatabase();
            $query = $db->getQuery(true);

            /**
             * For each category get the max ordering value
             * Re-order with max - ordering
             */
            foreach ($pks as $id) {
                $query
                    ->select('MAX(ordering)')
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
        } catch (RuntimeException $e) {
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
     * @since __BUMP_VERSION__
     */
    protected function batchCopy($value, $pks, $contexts)
    {
        $type       = new UCMType;
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
        $query = $db
            ->getQuery(true)
            ->select('COUNT(id)')
            ->from($db->quoteName('#__rsg2_galleries'));
        $db->setQuery($query);

        try {
            $count = $db->loadResult();
        } catch (RuntimeException $e) {
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
     * @since __BUMP_VERSION__
     */
    protected function batchMove($value, $pks, $contexts)
    {
        $parentId   = (int)$value;
        $type       = new UCMType;
        $this->type = $type->getTypeByAlias($this->typeAlias);

        $db        = $this->getDatabase();
        $query     = $db->getQuery(true);
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
                } catch (RuntimeException $e) {
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
     * @since __BUMP_VERSION__
     *
     * protected function cleanCache($group = null, $client_id = 0)
     * {
     * $extension = Factory::getApplication()->input->get('extension');
     *
     * switch ($extension)
     * {
     * case 'com_rsgallery2':
     * parent::cleanCache('com_rsgallery2');
     * parent::cleanCache('mod_articles_archive');
     * parent::cleanCache('mod_articles_categories');
     * parent::cleanCache('mod_articles_category');
     * parent::cleanCache('mod_articles_latest');
     * parent::cleanCache('mod_articles_news');
     * parent::cleanCache('mod_articles_popular');
     * break;
     * default:
     * parent::cleanCache($extension);
     * break;
     * }
     * }
     * /**/

    /**
     * Method to change the title & alias.
     *
     * @param   integer  $parent_id  The id of the parent.
     * @param   string   $alias      The alias.
     * @param   string   $title      The title.
     *
     * @return  array    Contains the modified title and alias.
     *
     * @since __BUMP_VERSION__
     */
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
     * public function getAssoc()
     * {
     * static $assoc = null;
     *
     * if (!is_null($assoc))
     * {
     * return $assoc;
     * }
     *
     * $extension = $this->getState('category.extension');
     *
     * $assoc = Associations::isEnabled();
     * $extension = explode('.', $extension);
     * $component = array_shift($extension);
     * $cname = str_replace('com_', '', $component);
     *
     * if (!$assoc || !$component || !$cname)
     * {
     * $assoc = false;
     *
     * return $assoc;
     * }
     *
     * $componentObject = $this->bootComponent($component);
     *
     * if ($componentObject instanceof AssociationServiceInterface && $componentObject instanceof CategoryServiceInterface)
     * {
     * $assoc = true;
     *
     * return $assoc;
     * }
     *
     * $hname = $cname . 'HelperAssociation';
     * \JLoader::register($hname, JPATH_SITE . '/components/' . $component . '/helpers/association.php');
     *
     * $assoc = class_exists($hname) && !empty($hname::$category_association);
     *
     * return $assoc;
     * }
     * /**/

}
