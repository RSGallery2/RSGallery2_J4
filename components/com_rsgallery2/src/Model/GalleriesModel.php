<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Registry\Registry;

//use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;
use Rsgallery2\Component\Rsgallery2\Site\Model\ImagePathsData;

/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class GalleriesModel extends ListModel
{
    /**
     * Model context string.
     *
     * @var    string
     * @since  3.1
     */
    public $_context = 'com_rsgallery2.images';

    /**
     * The category context (allows other extensions to derived from this model).
     *
     * @var		string
     */
    protected $_extension = 'com_rsgallery2';

    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     * @param MVCFactoryInterface|null $factory
     * @throws \Exception
     * @see     \JController
     * @since   1.6
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
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
            );
        }

        parent::__construct($config, $factory);
    }


    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   3.0.1
     */
    protected function populateState($ordering = 'a.lft', $direction = 'ASC')
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
     * @since   1.6
     */
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
     * @var string item
     */
    /**/
    protected $_item = null;
    /**/

    /**
     * Method to get a database query to list galleries.
     *
     * @return  \DatabaseQuery object.
     *
     * @since __BUMP_VERSION__
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
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
                . 'a.access'
            )
        );
        $query->from('#__rsg2_galleries AS a');

        /* Count child images */
        $query->select('COUNT(img.gallery_id) as image_count')
            ->join('LEFT', '#__rsg2_images AS img ON img.gallery_id = a.id'
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
        if ($level = $this->getState('filter.level'))
        {
            $query->where('a.level <= ' . (int) $level);
        }

        // Filter by access level.
        if ($access = $this->getState('filter.access'))
        {
            $query->where('a.access = ' . (int) $access);
        }

        // Implement View Level Access
        if (!$user->authorise('core.admin'))
        {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $query->where('a.access IN (' . $groups . ')');
        }

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

        // Filter by search in name and others
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%');
            $query->where(
                'a.name LIKE ' . $search
                . ' OR a.alias LIKE ' . $search
                . ' OR a.description LIKE ' . $search
                . ' OR a.note LIKE ' . $search
                . ' OR a.created LIKE ' . $search
                . ' OR a.modified LIKE ' . $search
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
        $listDirn = $db->escape($this->getState('list.direction', 'ASC'));

        if ($listOrdering == 'a.access')
        {
            $query->order('a.access ' . $listDirn . ', a.lft ' . $listDirn);
        }
        else
        {
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
            . 'ua.name '

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
     * Method to get a list of galleries
     *
     * ??? Overridden to inject convert the attribs field into a Registry object.
     *
     * @param   integer  $gid  Id for the parent gallery
     *
     * @return  mixed  An array of objects on success, false on failure.
     *
     * @since   1.6
     */

    // toDo: rights ...

    public function getItems()
    {
        $user   = Factory::getUser();
        $userId = $user->get('id');
        $guest  = $user->get('guest');
        $groups = $user->getAuthorisedViewLevels();

        if ($this->_item === null)
        {
            $this->_item = array();
        }

        $galleries = new \stdClass(); // ToDo: all to (object)[];


        $input  = Factory::getApplication()->input;
        $gid = $input->getInt ('gid', 0);



        // not fetched already
        if ( ! isset($this->_item[$gid])) {

            try
            {
                // Root galleries, No parent is defined
                if (!$gid) {
                    $galleries  = parent::getItems();
                } else {
                    $galleries = $this->getGalleryAndChilds($gid);
                }

                if ( ! empty($galleries)) {

                    // Add image paths, image params ...
                    $data = $this->AddLayoutData ($galleries);

                }
                else
                {
                    // No galleries defined yet
                    $data = false;
                }

                $this->_item[$gid] = $data;
            }
            catch (\RuntimeException $e)
            {
                $OutTxt = '';
                $OutTxt .= 'GalleriesModel: getItems: Error executing query: "' . "" . '"' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $galleries = $this->_item[$gid];

        return $galleries;
    }

	/**
	 * @param $galleries
	 *
	 * @return mixed
	 * @since 4.5.0.0
	 */
	public function AddLayoutData($galleries)
	{
		$images = [];

		try
		{
//			// gallery parameter
//			$app = Factory::getApplication();
//			$input = $app->input;
//			$gid = $input->get('gid', '', 'INT');

			foreach ($galleries as $gallery)
			{
				// $ImagePaths = new ImagePathsData ($gallery->id);

				// Random image
				if ($gallery->thumb_id == 0) {
					$gallery->thumb_id = $this->RandomImageId ($gallery->id);
				}

				 // gallery has image
				if ($gallery->thumb_id > 0)
				{
					//$image = new \stdClass();
					$image = $this->ImageById ($gallery->thumb_id);

					if ( ! empty ($image)) {

                        $image->gallery_name = $gallery->name;
						$images[] = $image;

                        $image->isHasNoImages = false;
					}

                    $this->AssignImageUrl($image);

					// Replace image name with gallery name
                    $image->name = $image->gallery_name;
                }
				else {

                    // gallery has NO image -> Create dummy data
				    $dummyImage = new \stdClass();
                    $dummyImage->name = $gallery->name;

                    $dummyImage->isHasNoImages = true;

                    $images[] = $dummyImage;
                }
			}

		}
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: AddLayoutData: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

		return $images;
	}

//	/**
//	 * @param $images
//	 *
//	 *
//	 * @since 4.5.0.0
//	 */
//	public function AssignImageUrls($images)
//	{
//		try {
//
//			foreach ($images as $image) {
//
//                $this->AssignImageUrl($image);
//			}
//
//		}
//        catch (\RuntimeException $e)
//        {
//            $OutTxt = '';
//            $OutTxt .= 'GalleriesModel: AssignImageUrls: Error executing query: "' . "" . '"' . '<br>';
//            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//            $app = Factory::getApplication();
//            $app->enqueueMessage($OutTxt, 'error');
//        }
//
//	}

	/**
	 * @param $images
	 *
	 *
	 * @since 4.5.0.0
	 */
	public function AssignImageUrl($image)
	{

		try {

            // ToDo: check for J3x style of gallery (? all in construct ?)

            $ImagePaths = new ImagePathsData ($image->gallery_id);

            $ImagePaths->assignPathData ($image);

            // ToDo: watermarked file
		}
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: AssignImageUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

	}


	/**
	 * @param $galleryId
	 *
	 * @return mixed
	 * @since 4.5.0.0
	 */
	public function RandomImageId($galleryId)
	{
		$imageId = -1;

		try
		{
//			// gallery parameter
//			$app = Factory::getApplication();
//			$input = $app->input;
//			$gid = $input->get('gid', '', 'INT');

			// Create a new query object.
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			$limit = 1;

			// Select required fields
			$query->select('id')
				->from($db->quoteName('#__rsg2_images'))
				->where($db->quoteName('gallery_id') . '=' . (int) $galleryId)
				->setLimit((int) $limit)
				->order('RAND()')
			;

			$db->setQuery($query);

			$imageId = $db->loadResult();

//			if ($db->getErrorNum())
//			{
//				echo $db->stderr();
//				return false;
//			}
//
//			return $list;
//

		}
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: RandomImageId: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

		return $imageId;
	}

	/**
	 * @param $imageId
	 *
	 * @return mixed
	 * @since 4.5.0.0
	 */
	public function ImageById ($imageId)
	{
		$image = new \stdClass();

		try
		{

			// Create a new query object.
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			// Select required fields
			$query->select('*')
				->from($db->quoteName('#__rsg2_images'))
				->where($db->quoteName('id') . '=' . (int) $imageId)
			;

			$db->setQuery($query);

            $image = $db->loadObject();
            $testName = $image->name;
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: ImageById: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

		return $image;
	}

	/**
     * Method to get the starting number of items for the data set.
     *
     * @return  integer  The starting number of items available in the data set.
     *
     * @since   3.0.1
     */
    public function getStart()
    {
        return $this->getState('list.start');
    }

    /**
     * @param int $gid
     *
     * @return mixed
     *
     * @since version
     */
    private function getGalleryAndChilds(int $gid)
    {
        $galleries = [];

        try {
            // Select parent and child galleries
            $db = $this->getDbo();
            $query = $db->getQuery(true);

            $query->select('*')
                //->from($db->quoteName('#__rsg2_galleries', 'a'))
                ->from($db->quoteName('#__rsg2_galleries'))
                //->where('a.id = ' . (int) $gid);
                ->where('id = ' . (int)$gid, 'OR')
                ->where('parent_id = ' . (int)$gid);

            $db->setQuery($query);
            //$data = $db->loadObjectList();
            $galleries = $db->loadObjectList();
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: getGalleryAndChilds: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $galleries;
    }
}




