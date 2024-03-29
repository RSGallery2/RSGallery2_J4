<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright (c) 2016-2024 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\DatabaseInterface;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImageModel;


/**
 * RSGallery2 Component Images Model
 *
 * @since __BUMP_VERSION__
 */
class ImagesModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * @param   MVCFactoryInterface  $factory  The factory.
	 *
     * @param MVCFactoryInterface|null $factory
     * @throws \Exception
     * @see     \JController
     * @since   5.0
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null)
	{
		//  which fields are needed for filter function
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'name', 'a.name',
				'gallery_id', 'a.gallery_id',

                'published', 'a.published',

                'created', 'a.created',
                'created_by', 'a.created_by',

                'modified', 'a.modified',
                'modified_by', 'a.modified_by',

                'ordering', 'a.ordering',

                'hits', 'a.hits',
                'rating', 'a.rating',
                'votes', 'a.votes',
                'comments', 'a.comments',
				'tag',
				'gallery_name'
			);
		}

		if (Associations::isEnabled())
		{
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
	 * @since __BUMP_VERSION__
	 */
	protected function populateState($ordering = 'a.ordering', $direction = 'desc')
	{
		$app = Factory::getApplication();


		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
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

		$search   = $this->getUserStateFromRequest($this->context . '.search', 'filter_search');
		$this->setState('filter.search', $search);

		$gallery_id = $this->getUserStateFromRequest($this->context . '.filter.gallery_id', 'filter_gallery_id');
		$this->setState('filter.gallery_id', $gallery_id);

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
	 * @since __BUMP_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.extension');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.gallery_id');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.access');
//		$id .= ':' . $this->getState('filter.language');
//		$id .= ':' . $this->getState('filter.level');
		$id .= ':' . $this->getState('filter.tag');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get a database query to list images.
	 *
	 * @return  \DatabaseQuery object.
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDatabase();

		$query = $db->getQuery(true);

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
                . 'a.note, '
				. 'a.gallery_id, '
                . 'a.title, '

				. 'a.params, '
				. 'a.published, '

                . 'a.hits, '
				. 'a.rating, '
				. 'a.votes, '
				. 'a.comments, '

 //               . 'a.publish_up,'
 //               . 'a.publish_down,'

                . 'a.checked_out, '
				. 'a.checked_out_time, '
				. 'a.created, '
				. 'a.created_by, '
				. 'a.created_by_alias, '
				. 'a.modified, '
				. 'a.modified_by, '

				. 'a.ordering, '
                . 'a.approved, '
                . 'a.asset_id, '
				. 'a.access, '
				. 'a.use_j3x_location '
			)
		);
		$query->from('#__rsg2_images as a');

		/* parent gallery name */
		$query->select('gal.name as gallery_name')
			->join('LEFT', '#__rsg2_galleries AS gal ON gal.id = a.gallery_id'
			);

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

		/**
		// Join over the associations.
		$assoc = $this->getAssoc();

		if ($assoc)
		{
			$query->select('COUNT(asso2.id)>1 as association')
				->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_rsgallery2.item'))
				->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
				->group('a.id, l.title, uc.name, ag.title, ua.name');
		}
		/**/

        /**
		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('a.level <= ' . (int) $level);
		}
        /**/

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

        // Filter on the gallery Id.
        if ($gallery_id = $this->getState('filter.gallery_id'))
        {
            $query->where('a.gallery_id = ' . $db->quote($gallery_id));
        }

        // Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		/* 2023.09.19
		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published IN (0, 1))');
		}

		/* 2023.09.19
        // Filter by search in name and others
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'a.name LIKE ' . $search
				. ' OR a.title LIKE ' . $search
				. ' OR a.alias LIKE ' . $search
				. ' OR a.description LIKE ' . $search
				. ' OR gal.name LIKE ' . $search
				. ' OR a.note LIKE ' . $search
				. ' OR a.created LIKE ' . $search
				. ' OR a.modified LIKE ' . $search
			);
		}

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

/**
		// changes need changes above too -> populateState
		$orderCol  = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		if ($orderCol == 'a.ordering' || $orderCol == 'ordering')
		{
			$orderCol = 'a.gallery_id ' . $orderDirn . ', a.ordering';
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
/**/

		$listOrdering = $this->getState('list.ordering', 'a.ordering');
		$listDirn = $db->escape($this->getState('list.direction', 'DESC'));

		if ($listOrdering == 'a.access')
		{
			$query->order('a.access ' . $listDirn . ', a.id ' . $listDirn);
		}
		else
		{
			$query->order($db->escape($listOrdering) . ' ' . $listDirn);
		}

		// Group by on Images for \JOIN with component tables to count items
		$query->group(
			/**/
			'a.id, '
			. 'a.name, '
			. 'a.alias, '
			. 'a.description, '
			. 'a.gallery_id, '
			. 'a.title, '

			. 'a.note, '
			. 'a.params, '
			. 'a.published, '
//            . 'a.published_up, '
//            . 'a.published_down, '

			. 'a.hits, '
			. 'a.rating, '
			. 'a.votes, '
			. 'a.comments, '

			. 'a.checked_out, '
			. 'a.checked_out_time, '
			. 'a.created, '
			. 'a.created_by, '
			. 'a.created_by_alias, '
			. 'a.modified, '
			. 'a.modified_by, '

			. 'a.ordering, '
            . 'a.approved, '
			. 'a.asset_id, '
			. 'a.access, '
			. 'a.use_j3x_location '
			/**/
		);

		return $query;
	}

    /**
     * Prepare and sanitize the table prior to saving.
     *
     * @param   \Table  $table  A Table object.
     *
     * @return  void
     *
     * @since __BUMP_VERSION__
     */
    protected function prepareTable($table)
    {
        $date = Factory::getDate();
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        if (empty($table->id))
        {
            // Set the values
            $table->created    = $date->toSql();
            $table->created_by = $user->id;

            // Set ordering to the last item if not set
            if (empty($table->ordering))
            {
	            $db = $this->getDatabase();

                $query = $db->getQuery(true)
                    ->select('MAX(ordering)')
                    ->from('#__rsg2_images');

                $db->setQuery($query);
                $max = $db->loadResult();

                $table->ordering = $max + 1;
            }
        }
        else
        {
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
	 * @since __BUMP_VERSION__
	 */
	public function getAssoc()
	{
		static $assoc = null;

		if (!is_null($assoc))
		{
			return $assoc;
		}

		$extension = $this->getState('filter.extension');

		$assoc = Associations::isEnabled();
		$extension = explode('.', $extension);
		$component = array_shift($extension);
		$cname = str_replace('com_', '', $component);

		if (!$assoc || !$component || !$cname)
		{
			$assoc = false;

			return $assoc;
		}

		$componentObject = $this->bootComponent($component);

		if ($componentObject instanceof AssociationServiceInterface && $componentObject instanceof CategoryServiceInterface)
		{
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
	 * @since __BUMP_VERSION__
	 */
	public function getItems()
	{
		$items = parent::getItems();

		if ($items != false)
		{
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
	 * @param   \stdClass[]  &$items     The category items
	 * @param   string       $extension  The category extension
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
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
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, gallery name, date, and user name as rows
	 *
	 * @since __BUMP_VERSION__
	 * @throws Exception
	 */
	public static function latestImages($limit)
	{
		$latest = array();

		try
		{
			// Create a new query object.
			$db    = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true);

			$query
				->select('*')
				->from($db->quoteName('#__rsg2_images'))
				->order($db->quoteName('id') . ' DESC');

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			foreach ($rows as $row)
			{
				$ImgInfo            = array();
				$ImgInfo['name']    = $row->name;
				$ImgInfo['gallery'] = ImagesModel::GalleryName($row->gallery_id);
				$ImgInfo['date']    = $row->created;

				//$ImgInfo['user'] = rsgallery2ModelImages::getUsernameFromId($row->userid);
				$user            = Factory::getUser($row->created_by);
				$ImgInfo['user'] = $user->get('username');

				$latest[] = $ImgInfo;
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'latestImages: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $latest;
	}

	/**
	 * This function will retrieve the data of all uploaded images
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, gallery name, date, and user name as rows
	 *
	 * @since __BUMP_VERSION__
	 */
	static function allImages()
	{
		$latest = array();

		try
		{
			// Create a new query object.
			$db    = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true);

			//$query = 'SELECT * FROM `#__rsgallery2_files` WHERE (`date` >= '. $database->quote($lastweek)
			//	.' AND `published` = 1) ORDER BY `id` DESC LIMIT 0,5';

			$query
				->select('*')
				->from($db->quoteName('#__rsg2_images'))
				//->where date ... ???
				->order($db->quoteName('id') . ' DESC');

			$db->setQuery($query);
			$rows = $db->loadObjectList();

			/**
			foreach ($rows as $row)
			{
			$ImgInfo         = array();
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
			$OutTxt .= 'allImages: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $rows;
	}

	protected static function GalleryName($id)
	{
		// Create a new query object.
		$db    = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);

		//$sql = 'SELECT `name` FROM `#__rsgallery2_galleries` WHERE `id` = '. (int) $id;
		$query
			->select('name')
			->from('#__rsg2_galleries')
			->where($db->quoteName('id') . ' = ' . (int) $id);

		$db->setQuery($query);
		$db->execute();

		// http://docs.joomla.org/Selecting_data_using_JDatabase
		$name = $db->loadResult();
		$name = $name ? $name : Text::_('COM_RSGALLERY2_GALLERY_ID_ERROR');

		return $name;
	}

    /**
     * Reset images table to empty state
     * Deletes all galleries and initialises the root item of the nested tree
     *
     * @param int $rgt
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public static function reinitImagesTable($rgt = 1)
    {
        $isImagesReset = false;

        $id_images = '#__rsg2_images';

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            //--- delete old rows -----------------------------------------------

            $query = $db->getQuery(true);

            $query->delete($db->quoteName($id_images));
            // all rows
            //$query->where($conditions);

            $db->setQuery($query);

            $isImagesReset = $db->execute();

        } //catch (\RuntimeException $e)
        catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . ' from InitImages');
        }

        return $isImagesReset;
    }

    /**
     *
     *
     * @param   object  $pks  The primary key related to the contents that was deleted.
     *
     * @return  boolean
     *
     * @since   3.7.0
     */
    /** see below
    public function delete(&$pks)
    {
        $return = parent::delete($pks);

        if ($return)
        {

            // Now check to see if this articles was featured if so delete it from the #__content_frontpage table
            $db = $this->>getDatabase();
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__content_frontpage'))
                ->whereIn($db->quoteName('content_id'), $pks);
            $db->setQuery($query);
            $db->execute();

            $this->workflow->deleteAssociation($pks);
        }

        return $return;
    }

	/**
	 * Fetches base file names identified by the list of given image ids
	 *
	 * @param $ImageIds array List of image ids from database
	 *
	 * @return string [] file names
	 *
	 * @since 4.3.2
	 * @throws Exception
	 */
	public function ids2FileData($ImageIds)
	{
		$fileNames = [];

		try
		{
			$query = $db->getQuery(true)
                ->select($db->quoteName(array('id', 'name', 'gallery_id')))
                ->from($db->quoteName('#__rsg2_images'))
				->where($db->quoteName('id') . ' IN ' . ' (' . implode(',', $ImageIds) . ')');
			$db->setQuery($query);

			$fileNames = $db->loadObjectList(); // wrong $db->loadObjectList();
		}

		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing query: "' . $query . '" in fileNamesFromIds $ImageIds count:' . count ($ImageIds) . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $fileNames;
	}

//	/**
//	 * Fetches base file names identified by the list of given image ids
//	 *
//	 * @param $ImageIds array List of image ids from database
//	 *
//	 * @return string [] file names
//	 *
//	 * @since 4.3.2
//	 * @throws Exception
//	 */
//	public function fileNamesFromIds($ImageIds)
//	{
//		$fileNames = [];
//
//		try
//		{
//			$db = $this->getDatabase();
//			$query = $db->getQuery(true);
//
//			$query->select($db->quoteName('name'))
//				->from($db->quoteName('#__rsg2_images'))
//				->where($db->quoteName('id') . ' IN ' . ' (' . implode(',', $ImageIds) . ')');
//			$db->setQuery($query);
//
//			$fileNames = $db->loadColumn(); // wrong $db->loadObjectList();
//		}
//
//		catch (\RuntimeException $e)
//		{
//			$OutTxt = '';
//			$OutTxt .= 'Error executing query: "' . $query . '" in fileNamesFromIds $ImageIds count:' . count ($ImageIds) . '<br>';
//			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//			$app = Factory::getApplication();
//			$app->enqueueMessage($OutTxt, 'error');
//		}
//
//		return $fileNames;
//	}
//

	/**
	 * Fetches base file name identified by the given image id
	 *
	 * @param $ImageId
	 *
	 * @return string filename
	 *
	 * @since 4.3.2
	 * @throws Exception
	 */
	public function galleryIdFromId($ImageId)
	{
		$galleryId = -1;

		try
		{
			$db = $this->getDatabase();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('gallery_id'))
				->from($db->quoteName('#__rsg2_images'))
				->where(array($db->quoteName('id') . '=' . $ImageId));
			$db->setQuery($query);
			$db->execute();

			$galleryId = $db->loadResult();
		}

		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing query: "' . $query . '" in galleryIdFromId $ImageId: "' . $ImageId .  '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $galleryId;
	}




} // class
