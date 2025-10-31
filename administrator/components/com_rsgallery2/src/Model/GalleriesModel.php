<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\QueryInterface;

/**
 * RSGallery2 Component Galleries Model
 *
 * @since __BUMP_VERSION__
 */
class GalleriesModel extends ListModel
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     *
     * @see     \JControllerLegacy
     * @since   5.1.0     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        //  which fields are needed for filter function
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
				'id', 'a.id',
				'name', 'a.name',

				'created', 'a.created',
				'created_by', 'a.created_by',

                'published', 'a.published',

//				'modified', 'a.modified',
//				'modified_by', 'a.modified_by',

				'parent_id', 'a.parent_id',
				'lft', 'a.lft',

				'hits', 'a.hits',
//				'tag',
				'a.access',
				'image_count'
            ];
        }

        if (Associations::isEnabled()) {
            $config['filter_fields'][] = 'association';
        }

        parent::__construct($config, $factory);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   5.1.0     */
    protected function populateState($ordering = 'a.lft', $direction = 'asc')
    {
        $app = Factory::getApplication();

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout')) {
            $this->context .= '.' . $layout;
        }

        //$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');
        //// Adjust the context to support forced languages.
        //if ($forcedLanguage)
        //{
        //	$this->context .= '.' . $forcedLanguage;
        //}

		$extension = $app->getUserStateFromRequest($this->context . '.filter.extension', 'extension', 'com_rsgallery2', 'cmd');
        $this->setState('filter.extension', $extension);
        $parts = explode('.', $extension);

        // Extract the component name
        $this->setState('filter.component', $parts[0]);

        // Extract the optional section name
        $this->setState('filter.section', (count($parts) > 1) ? $parts[1] : null);

        $search = $this->getUserStateFromRequest($this->context . '.search', 'filter_search');
        $this->setState('filter.search', $search);

        // List state information.
        parent::populateState($ordering, $direction);

        //// Force a language.
        //if (!empty($forcedLanguage))
        //{
        //	$this->setState('filter.language', $forcedLanguage);
        //}
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string  $id  A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   5.1.0     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
//		$id .= ':' . $this->getState('filter.extension');
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.access');
//		$id .= ':' . $this->getState('filter.language');
//		$id .= ':' . $this->getState('filter.level');
//		$id .= ':' . $this->getState('filter.tag');

        return parent::getStoreId($id);
    }

    /**
     * Method to get a database query to list galleries.
     *
	 * @return  Queryinterface object.
     *
	 * @since   5.1.0     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db    = $this->getDatabase();
        $query = $db->createQuery();

        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        // Select the required fields from the table.
        $query->select(
            $this->getState(
            /**/
                'list.select',
                'a.id, '
                . 'a.name, '
                . 'a.alias, '
                . 'a.description, '
                . 'a.thumb_id, '

                . 'a.note, '
                . 'a.params, '
                . 'a.published, '
                . 'a.publish_up,'
                . 'a.publish_down,'

                . 'a.hits, '

                . 'a.checked_out, '
                . 'a.checked_out_time, '
                . 'a.created, '
                . 'a.created_by, '
                . 'a.created_by_alias, '
                . 'a.modified, '
                . 'a.modified_by, '

                . 'a.parent_id,'
                . 'a.level, '
                . 'a.path, '
                . 'a.lft, '
                . 'a.rgt,'

                . 'a.approved,'
                . 'a.asset_id,'
                . 'a.access',
            ),
        );
        $query->from('#__rsg2_galleries AS a');

        /* Count child images */
		$query->select('COUNT(img.gallery_id) as image_count')
            ->join('LEFT', '#__rsg2_images AS img ON img.gallery_id = a.id');

        //// Join over the language
        //$query->select('l.title AS language_title, l.image AS language_image')
        //	->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

        // Join over the users for the checked out user.
		$query->select('uc.name AS editor')
            ->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the asset groups.
		$query->select('ag.title AS access_level')
            ->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        // Join over the users for the author.
		$query->select('ua.name AS author_name')
            ->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

//		// Join over the associations.
//		$assoc = $this->getAssoc();
//
//		if ($assoc)
//		{
//			$query->select('COUNT(asso2.id)>1 as association')
//				->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_rsgallery2.item'))
//				->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
//				->group('a.id, l.title, uc.name, ag.title, ua.name');
//		}

        // Filter on the level.
        if ($level = $this->getState('filter.level')) {
            $query->where('a.level <= ' . (int)$level);
        }

        // Filter by access level.
        if ($access = $this->getState('filter.access')) {
            $query->where('a.access = ' . (int)$access);
        }

        // Implement View Level Access
        if (!$user->authorise('core.admin')) {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $query->where('a.access IN (' . $groups . ')');
        }

        // Filter by published state
        $published = (string)$this->getState('filter.published');

        if (is_numeric($published)) {
            $query->where('a.published = ' . (int)$published);
        } elseif ($published === '') {
            $query->where('(a.published IN (0, 1))');
        }

        // Filter by search in name and others
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%');
            $query->where(
                'a.name LIKE ' . $search
                . ' OR a.alias LIKE ' . $search
                . ' OR a.description LIKE ' . $search
                . ' OR a.note LIKE ' . $search
                . ' OR a.created LIKE ' . $search
                . ' OR a.modified LIKE ' . $search,
            );
        }

        // exclude root gallery record
//		$query->where('a.id > 1');

        /**
		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}
		/**/

        // Filter by a single tag.
        /**
		$tagId = $this->getState('filter.tag');

		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote($extension . '.category')
				);
		}
		/**/

        // Add the list ordering clause
        $listOrdering = $this->getState('list.ordering', 'a.lft');
        $listDirn     = $db->escape($this->getState('list.direction', 'ASC'));

        if ($listOrdering == 'a.access') {
            $query->order('a.access ' . $listDirn . ', a.lft ' . $listDirn);
        } else {
            $query->order($db->escape($listOrdering) . ' ' . $listDirn);
        }

        // Group by on Galleries for \JOIN with component tables to count items
        $query->group(
        /**/
            'a.id, '
            . 'a.name, '
            . 'a.alias, '
            . 'a.description, '
            . 'a.thumb_id, '

            . 'a.note, '
            . 'a.params, '
            . 'a.published, '
            . 'a.publish_up,'
            . 'a.publish_down,'

            . 'a.hits, '

            . 'a.checked_out, '
            . 'a.checked_out_time, '
            . 'a.created, '
            . 'a.created_by, '
            . 'a.created_by_alias, '
            . 'a.modified, '
            . 'a.modified_by, '

            . 'a.parent_id, '
            . 'a.level, '
            . 'a.path, '
            . 'a.lft, '
            . 'a.rgt, '

            . 'a.approved,'
            . 'a.asset_id,'
            . 'a.access, '

            . 'uc.name, '
            . 'ua.name ',

//				. 'a.language, '
//			. 'ag.title, '
//			. 'l.title, '
//			. 'l.image, '
//no good			. 'image_count '
        /**/
        );

        return $query;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param   Table  $table  A Table object.
     *
     * @return  void
     *
	 * @since      5.1.0     */
    protected function prepareTable($table)
    {
        $date = Factory::getDate();
        $user = Factory::getApplication()->getIdentity();

        if (empty($table->id)) {
            // Set the values
            $table->created    = $date->toSql();
            $table->created_by = $user->id;

//			// Set ordering to the last item if not set
//			if (empty($table->ordering))
//			{
//				$db = $this->getDatabase();
//				$query = $db->createQuery()
//					->select('MAX(ordering)')
//					->from('#__banners');
//
//				$db->setQuery($query);
//				$max = $db->loadResult();
//
//				$table->ordering = $max + 1;
//			}
        } else {
            // Set the values
            $table->modified    = $date->toSql();
            $table->modified_by = $user->id;
        }

        // Increment the content version number.
        $table->version++;
    }

    /**
     * Method to determine if an association exists
     *
     * @return  boolean  True if the association exists
     *
	 * @since      5.1.0     */
    public function getAssoc()
    {
        static $assoc = null;

        if (!is_null($assoc)) {
            return $assoc;
        }

        $extension = $this->getState('filter.extension');

        $assoc     = Associations::isEnabled();
        $extension = explode('.', $extension);
        $component = array_shift($extension);
        $cname     = str_replace('com_', '', $component);

        if (!$assoc || !$component || !$cname) {
            $assoc = false;

            return $assoc;
        }

        $componentObject = $this->bootComponent($component);

        if ($componentObject instanceof AssociationServiceInterface && $componentObject instanceof CategoryServiceInterface) {
            $assoc = true;

            return $assoc;
        }

        $hname = $cname . 'HelperAssociation';
		\JLoader::register($hname, JPATH_SITE . '/components/' . $component . '/helpers/association.php');

        $assoc = class_exists($hname) && !empty($hname::$category_association);

        return $assoc;
    }

    /**
     * Method to get an array of data items.
     *
     * @return  mixed  An array of data items on success, false on failure.
     *
	 * @since      5.1.0     */
    public function getItems()
    {
        $items = parent::getItems();

        if ($items != false) {
            /**
			$extension = $this->getState('filter.extension');

			$this->countItems($items, $extension);
			/**/
        }

        return $items;
    }

    /**
     * Method to load the countItems method from the extensions
     *
     * @param   \stdClass[]  &$items      The category items
     * @param   string        $extension  The category extension
     *
     * @return  void
     *
	 * @since      5.1.0     */
    /**
	public function countItems(&$items, $extension)
	{
		$parts     = explode('.', $extension, 2);
		$section   = '';

		if (count($parts) > 1)
		{
			$section = $parts[1];
		}

		$component = Factory::getApplication()->bootComponent($parts[0]);

		if ($component instanceof CategoryServiceInterface)
		{
			$component->countItems($items, $section);
		}
	}
	/**/

    /**
     * This function will retrieve the data of the n last uploaded images
     *
     * @param   int  $limit  > 0 will limit the number of lines returned
     *
     * @return array rows with image name, gallery name, date, and user name as rows
     *
     * @throws \Exception
	 * @since     5.1.0     */
    public static function latestGalleries($limit)
    {
        $latest = [];

        try {
            // Create a new query object.
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->createQuery();

            //$query = 'SELECT * FROM `#__rsgallery2_files` WHERE (`date` >= '. $database->quote($lastweek)
            //	.' AND `published` = 1) ORDER BY `id` DESC LIMIT 0,5';

            $query
                ->select('*')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->order($db->quoteName('id') . ' DESC');

            $db->setQuery($query, 0, $limit);
            $rows = $db->loadObjectList();

            foreach ($rows as $row) {
                $ImgInfo         = [];
                $ImgInfo['name'] = $row->name;
                $ImgInfo['id']   = $row->id;

                //$ImgInfo['user'] = rsgallery2ModelGalleries::getUsernameFromId($row->uid);
                $user = Factory::getUser($row->created_by);
                //$ImgInfo['user'] = $user->get('username');
                $ImgInfo['user'] = $user->name;
                //$ImgInfo['user'] = "*Finnern was auch immer";

                $latest[] = $ImgInfo;
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'latestGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $latest;
    }

    /**
     * This function will retrieve the data of the n last uploaded images
     *
     * @param   int  $limit  > 0 will limit the number of lines returned
     *
     * @return array rows with image name, gallery name, date, and user name as rows
     *
	 * @since     5.1.0     */
    public static function allGalleries()
    {
        $latest = [];

        try {
            // Create a new query object.
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->createQuery();

            //$query = 'SELECT * FROM `#__rsgallery2_files` WHERE (`date` >= '. $database->quote($lastweek)
            //	.' AND `published` = 1) ORDER BY `id` DESC LIMIT 0,5';

            $query
                ->select('*')
                ->from($db->quoteName('#__rsg2_galleries'))
                //->where date ... ???
                ->order($db->quoteName('id') . ' DESC');

            $db->setQuery($query);
            $rows = $db->loadObjectList();
            /**
			foreach ($rows as $row)
			{
				$ImgInfo         = [];
				$ImgInfo['name'] = $row->name;
				$ImgInfo['id']   = $row->id;

				//$ImgInfo['user'] = rsgallery2ModelGalleries::getUsernameFromId($row->uid);
				$user            = Factory::getUser($row->created_by);
				//$ImgInfo['user'] = $user->get('username');
				$ImgInfo['user'] = $user->name;
				//$ImgInfo['user'] = "*Finnern was auch immer";

				$latest[] = $ImgInfo;
			}
			 /**/
		}
		catch (\RuntimeException $e)
		{
            $OutTxt = '';
            $OutTxt .= 'latestGalleries: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $rows;
    }

} // class
