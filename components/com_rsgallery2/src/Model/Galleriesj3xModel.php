<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Registry\Registry;

/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
     * @since      5.1.0
 */
class Galleriesj3xModel extends ListModel
{
    /**
     * Model context string.
     *
     * @var    string
     * @since  3.1
     */
    public $_context = 'com_rsgallery2.galleries';

    /**
     * The category context (allows other extensions to derived from this model).
     *
     * @var        string
     */
    protected $_extension = 'com_rsgallery2';


    protected $galleryId = -1;

    protected $_item = null;

    /**
     * Constructor.
     *
     * @param   array                     $config  An optional associative array of configuration settings.
     * @param   MVCFactoryInterface|null  $factory
     * @throws \Exception
     * @see     \JController
     * @since   1.6
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'name', 'a.name',

                'created', 'a.created',
                'created_by', 'a.created_by',

                'published', 'a.published',

//              'modified', 'a.modified',
//              'modified_by', 'a.modified_by',

                'parent_id', 'a.parent_id',
                'lft', 'a.lft',

                'hits', 'a.hits',
//              'tag',
                'a.access',
                'image_count'
            ];
        }

        parent::__construct($config, $factory);
    }


    /**
     *
     * @return false|mixed|null
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function getItems()
    {
        $app    = Factory::getApplication();
        $user   = $app->getIdentity();
        $userId = $user->get('id');
        $guest  = $user->get('guest');
        $groups = $user->getAuthorisedViewLevels();

        if ($this->_item === null) {
            $this->_item = [];
        }

        $galleries = new \stdClass(); // ToDo: all to (object)[];
        $galleryId = $this->getGalleryId();

        // not fetched already
        if (!isset($this->_item[$galleryId])) {
            try {
                // Root galleries, all galleries by date
                if ($galleryId == 0) {
                    $galleries = parent::getItems();
                } else {
                    $galleries = $this->getChildGalleries($galleryId);
                }

                // see above $galleries = parent::getItems();

                if (!empty($galleries)) {
                    // Add image paths, image params ...
                    $this->AddLayoutData($galleries);
                    $data = $galleries;
                } else {
                    // No galleries defined yet
                    //$data = false;
                    $data = $galleries;
                }

                $this->_item[$galleryId] = $data;
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Galleriesj3xModel: getItems: Error executing query: "' . "" . '"' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $galleries = $this->_item[$galleryId];

        return $galleries;
    }


    /**
     * return actual gallery id or given in input
     * @var string item
     */
    /**/
    public function getGalleryId()
    {
        // Not defined
        if ($this->galleryId < 0) {
            $input           = Factory::getApplication()->input;
            $this->galleryId = $input->getInt('id', 0);
        }

        return $this->galleryId;
    }
    /**/

    /**
     * @param   int  $gid
     *
     * @param   int  $gid
     *
     * @return mixed
     *
     * @return mixed
     *
     * @since  5.1.0     *
     * private function getGalleryAndChilds(int $gid)
     * {
     * $galleries = [];
     *
     * try {
     * // Select parent and child galleries
     * $db = $this->getDatabase();
     * $query = $db->createQuery();
     *
     * $query->select('*')
     * //->from($db->quoteName('#__rsg2_galleries', 'a'))
     * ->from($db->quoteName('#__rsg2_galleries'))
     * //->where('a.id = ' . (int) $gid);
     * ->where('id = ' . (int)$gid, 'OR')
     * ->where('parent_id = ' . (int)$gid);
     *
     * $db->setQuery($query);
     * //$data = $db->loadObjectList();
     * $galleries = $db->loadObjectList();
     * }
     * catch (\RuntimeException $e)
     * {
     * $OutTxt = '';
     * $OutTxt .= 'Galleriesj3xModel: getGalleryAndChilds: Error executing query: "' . "" . '"' . '<br>';
     * $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
     *
     * $app = Factory::getApplication();
     * $app->enqueueMessage($OutTxt, 'error');
     * }
     *
     * return $galleries;
     * }
     *
     * /    /**
     * @since 5.1.0     */
    public function getChildGalleries(int $gid)
    {
        $parentGallery = null;

        try {
            // old: $gid = $this->galleryId;

            // Select parent and child galleries
            $db = $this->getDatabase();

            $query = $db->createQuery();

            $query
                ->select('*')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where('id = ' . (int)$gid);

            $db->setQuery($query);
            //$data = $db->loadObjectList();
            //$galleries = $db->loadObjectList();
            $parentGallery = $db->loadObject();
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: getParentGallery: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $parentGallery;
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
    /**
     * @param $galleries
     *
     * @return
     * @since 4.5.0.0
     */
    public function AddLayoutData($galleries)
    {
        try {
//          // gallery parameter
//          $app = Factory::getApplication();
//          $input = $app->input;
//          $gid = $input->get('id', '', 'INT');


            foreach ($galleries as $gallery) {
                // $ImagePaths = new ImagePathsData ($gallery->id);

                // Random image
                if ($gallery->thumb_id == 0) {
                    $gallery->thumb_id = $this->RandomImageId($gallery->id);
                }

                // gallery has image
                if ($gallery->thumb_id > 0) {
                    //$image = new \stdClass();
                    $image = $this->ImageById($gallery->thumb_id);

                    if (!empty($image)) {
                        $image->gallery_id    = $gallery->id;
                        $image->isHasNoImages = false;

                        $this->AssignImagePaths($image);

                        $gallery->UrlThumbFile = $image->UrlThumbFile;
                    }
                    // Replace image name with gallery name
                } else {
                    // gallery has NO image -> Create dummy data
                    // toDo: there is an example for dummy link
                    $gallery->isHasNoImages = true;

                    $gallery->UrlThumbFile = ''; //not existent: $image->UrlThumbFile;
                }

                // Info about sub galleries
                $this->AssignSubGalleryList($gallery);

                // view single gallery on click
                $this->AssignGalleryUrl($gallery);

                // view single gallery on click
                $this->assignSlideshowUrl($gallery);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: AddLayoutData: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
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

        try {
//          // gallery parameter
//          $app = Factory::getApplication();
//          $input = $app->input;
//          $gid = $input->get('id', '', 'INT');

            // Create a new query object.
            $db = $this->getDatabase();

            $query = $db->createQuery();

            $limit = 1;

            // Select required fields
            $query
                ->select('id')
                ->from($db->quoteName('#__rsg2_images'))
                ->where($db->quoteName('gallery_id') . '=' . (int)$galleryId)
                ->setLimit((int)$limit)
                ->order('RAND()');

            $db->setQuery($query);

            $imageId = $db->loadResult();

//          if ($db->getErrorNum())
//          {
//              echo $db->stderr();
//              return false;
//          }
//
//          return $list;
//
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: RandomImageId: Error executing query: "' . "" . '"' . '<br>';
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
    public function ImageById($imageId)
    {
        $image = new \stdClass();

        try {
            // Create a new query object.
            $db = $this->getDatabase();

            $query = $db->createQuery();

            // Select required fields
            $query
                ->select('*')
                ->from($db->quoteName('#__rsg2_images'))
                ->where($db->quoteName('id') . '=' . (int)$imageId);

            $db->setQuery($query);

            $image    = $db->loadObject();
            $testName = $image->name;
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: ImageById: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $image;
    }

    /**
     * @param $images
     *
     *
     * @since 4.5.0.0
     */
    public function AssignImagePaths($image)
    {
        try {
            // ToDo: watermarked file

            // J4x ?
            if (!$image->use_j3x_location) {
                $imagePathsData = new ImagePathsData($image->gallery_id);
                $imagePathsData->assignPathData($image);
            } else {
                // J3x
                $imagePathsJ3xData = new ImagePathsJ3xData();
                $imagePathsJ3xData->assignPathData($image);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: assignImageUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

    /**
     * Assign subgallery list to gallery data
     *
     * @param $gallery
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function AssignSubGalleryList($gallery)
    {
        try {
            $gallery->subGalleryList = []; // fall back

            // Select parent and child galleries
            $db = $this->getDatabase();

            $query = $db->createQuery();

            $query
                ->select('id, name')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where('parent_id = ' . (int)$gallery->id);

            $db->setQuery($query);
            $subGalleries = $db->loadObjectList();

            foreach ($subGalleries as $subGallery) {
                $subData = (object)[];

                $subData->id   = $subGallery->id;
                $subData->name = $subGallery->name;

                $subData->imgCount = $this->imageCount($subGallery->id);

                $gallery->subGalleryList[] = $subData;
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: AssignSubGalleryList: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

    /**
     * return image count with parent of given gallery
     *
     * @param $galleryId
     *
     * @return int|mixed
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function imageCount($galleryId)
    {
        $imageCount = 0;

        try {
            $db = $this->getDatabase();

            $query = $db->createQuery();
            // count gallery items
            $query
                ->select('COUNT(*)')
                ->from('#__rsg2_images')
                ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($galleryId));
            $db->setQuery($query);

            $imageCount = $db->loadResult();
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: imageCount: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $imageCount;
    }

    /**
     * Assign url to gallery to gallery data
     *
     * @param $gallery
     *
     *
     * @since 4.5.0.0
     */
    public function AssignGalleryUrl($gallery)
    {
        try {
            $gallery->UrlGallery = ''; // fall back

            // Link to single gallery in actual menu
            // /joomla3x/index.php/j3x-galleries-overview/gallery/8

//            $gallery->UrlGallery = Route::_(index.php?option=com_rsgallery2 ....
//                . '/gallery/' . $gallery->id . ''
////                . '&id=' . $image->gallery_id
////                . '&iid=' . $gallery->id
////                . '&layout=galleryJ3xAsInline'
//                ,true,0,true);

            // http://127.0.0.1/joomla4x/index.php?option=com_rsgallery2&view=galleries&id=0


            $gallery->UrlGallery = Route::_(
                'index.php?option=com_rsgallery2'
                . '&view=galleryj3x&id=' . $gallery->id,
                true,
                0,
                true
            );

            /**/
            // ToDo: watermarked file
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: AssignGalleryUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

    /**
     * Assign slideshow url to gallery data
     *
     * @param $gallery
     *
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function assignSlideshowUrl($gallery)
    {
        try {
            //$gallery->UrlSlideshow = ''; // fall back

            // Link to single gallery in actual menu
            // /joomla3x/index.php/j3x-galleries-overview/gallery/8

//            $gallery->UrlSlideshow = Route::_(index.php?option=com_rsgallery2 ....
//                . '/gallery/' . $gallery->id . '/slideshow'
////                . '&id=' . $image->gallery_id
////                . '&iid=' . $gallery->id
////                . '&layout=galleryJ3xAsInline'
//                ,true,0,true);

            // http://127.0.0.1/joomla4x/index.php?option=com_rsgallery2&view=slideshow&id=2&slides_layout=default&Itemid=130

            $gallery->UrlSlideshow = Route::_(
                'index.php?option=com_rsgallery2'
                . '/gallery&id=' . $gallery->id . '/slideshow',
                true,
                0,
                true
            );
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Galleriesj3xModel: assignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
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
        parent::populateState($ordering, $direction);

        $app = Factory::getApplication();

        $this->setState('gallery.id', $app->input->getInt('id'));
        $this->setState('params', $app->getParams());

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout')) {
            $this->context .= '.' . $layout;
        }

        $extension = $app->getUserStateFromRequest(
            $this->context . '.filter.extension',
            'extension',
            'com_rsgallery2',
            'cmd',
        );
        $this->setState('filter.extension', $extension);
        $parts = explode('.', $extension);

        // Extract the component name
        $this->setState('filter.component', $parts[0]);

        // Extract the optional section name
        $this->setState('filter.section', (count($parts) > 1) ? $parts[1] : null);

        $search = $this->getUserStateFromRequest($this->context . '.search', 'filter_search');
        $this->setState('filter.search', $search);

        // List state information

        // J3x ToDo: use galcountNrs
        $configLimit = $app->input->get('galcountNrs', $app->get('list_limit'), 'uint');

        // J4x: use cols * Rows
        // ToDo: needs a function as config allows several sources
        // max_columns_in_galleries_view, max_rows_in_galleries_view

        $userLimit = $app->input->get('[galleries_count', $configLimit, 'uint');
        //$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'uint');
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $configLimit, 'uint');
        $this->setState('list.limit', $limit);

        $limitstart = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $limitstart);


        //// Force a language.
        //if (!empty($forcedLanguage))
        //{
        //  $this->setState('filter.language', $forcedLanguage);
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
//      $id .= ':' . $this->getState('filter.extension');
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.access');
//      $id .= ':' . $this->getState('filter.language');
//      $id .= ':' . $this->getState('filter.level');
//      $id .= ':' . $this->getState('filter.tag');

        return parent::getStoreId($id);
    }

    /**
     * Method to get a database query to list galleries.
     *
     * @return
     *
     * @since   5.1.0     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDatabase();

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
        $query
            ->select('COUNT(img.gallery_id) as image_count')
            ->join(
                'LEFT',
                '#__rsg2_images AS img ON img.gallery_id = a.id',
            );

        //// Join over the language
        //$query->select('l.title AS language_title, l.image AS language_image')
        //  ->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

        // Join over the users for the checked out user.
        $query
            ->select('uc.name AS editor')
            ->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the asset groups.
        $query
            ->select('ag.title AS access_level')
            ->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        // Join over the users for the author.
        $query
            ->select('ua.name AS author_name')
            ->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

        $query->where('a.published = 1');

//      // Join over the associations.
//      $assoc = $this->getAssoc();
//
//      if ($assoc)
//      {
//          $query->select('COUNT(asso2.id)>1 as association')
//              ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_rsgallery2.item'))
//              ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
//              ->group('a.id, l.title, uc.name, ag.title, ua.name');
//      }

        // Filter on the level.
        if ($level = $this->getState('filter.level')) {
            $query->where('a.level <= ' . (int)$level);
        }

        /**
         * // Filter by access level.
         * if ($access = $this->getState('filter.access')) {
         * $query->where('a.access = ' . (int)$access);
         * }
         *
         * // Implement View Level Access
         * if (!$user->authorise('core.admin')) {
         * $groups = implode(',', $user->getAuthorisedViewLevels());
         * $query->where('a.access IN (' . $groups . ')');
         * }
         * /**/

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
            $query
                ->where(
                    'a.name LIKE ' . $search
                    . ' OR a.alias LIKE ' . $search
                    . ' OR a.description LIKE ' . $search
                    . ' OR a.note LIKE ' . $search
                    . ' OR a.created LIKE ' . $search
                    . ' OR a.modified LIKE ' . $search,
                )
                ->where('a.published = 1');
        }

        // exclude root gallery record
        $query->where('a.id > 1');

        /**
         * // Filter on the language.
         * if ($language = $this->getState('filter.language'))
         * {
         * $query->where('a.language = ' . $db->quote($language));
         * }
         * /**/

        // Filter by a single tag.
        /**
         * $tagId = $this->getState('filter.tag');
         *
         * if (is_numeric($tagId))
         * {
         * $query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
         * ->join(
         * 'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
         * . ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
         * . ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote($extension . '.category')
         * );
         * }
         * /**/

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
            //              . 'a.language, '
            //          . 'ag.title, '
            //          . 'l.title, '
            //          . 'l.image, '
            //no good           . 'image_count '
            /**/
        );

        return $query;
    }

//    public function getRsg2MenuParams () {
//
//        // see rootgalleriesJ3x\default.xml
//
//        /*
//            gid
//            gallery_show_title
//            gallery_show_description
//            gallery_show_slideshow
//            intro_text
//            menu_show_intro_text
//            gallery_layout
//
//            images_show_title
//            images_show_description
//            images_show_search
//            images_layout
//
//            images_column_arrangement
//            max_columns_in_images_view
//            images_row_arrangement
//            max_rows_in_images_view
//            max_thumbs_in_images_view
//        /**/
//
//
//        $menuParams = new Registry();
//
//        try {
////            $activeMenu = Factory::getApplication()->getMenu()->getActive();
////            if ($activeMenu) {
////                $menuParams = $activeMenu->getParams();
////            } else {
////                $menuParams = new Registry();
////            }
//
//            $input = Factory::getApplication()->input;
//
////            $menuParams->set('id', $input->getInt('id', true));
////            $menuParams->set('galleries_show_title', $input->getBool('galleries_show_title', true));
////            $menuParams->set('galleries_show_description', $input->getBool('galleries_show_description', true));
////
////            $menuParams->set('galleries_show_owner', $input->getBool('galleries_show_owner', true));
////            $menuParams->set('galleries_show_size', $input->getBool('galleries_show_size', true));
////            $menuParams->set('galleries_show_date', $input->getBool('galleries_show_date', true));
////
////            $menuParams->set('galleries_show_slideshow', $input->getBool('galleries_show_slideshow', true));
////            $menuParams->set('galleries_description_side', $input->getInt('galleries_description_side', true));
////            $menuParams->set('intro_text', $input->get('intro_text','','RAW'));
////            $menuParams->set('menu_show_intro_text', $input->getBool('menu_show_intro_text', true));
////            $menuParams->set('gallery_layout', $input->getBool('gallery_layout', true));
////
////            $menuParams->set('images_show_title', $input->getBool('images_show_title', true));
////            $menuParams->set('images_show_description', $input->getBool('images_show_description', true));
////            $menuParams->set('images_show_search', $input->getBool('images_show_search', true));
////            $menuParams->set('images_layout', $input->getBool('images_layout', true));
////
////            $menuParams->set('images_column_arrangement', $input->getInt('images_column_arrangement', ??? true));
////            $menuParams->set('max_columns_in_images_view', $input->getInt('max_columns_in_images_view', ??? true));
////            $menuParams->set('images_row_arrangement', $input->getInt('images_row_arrangement', ??? true));
////            $menuParams->set('max_rows_in_images_view', $input->getInt('max_rows_in_images_view', ??? true));
////            $menuParams->set('max_thumbs_in_images_view', $input->getInt('max_thumbs_in_images_view', ??? true));
//
//        }
//        catch (\RuntimeException $e)
//        {
//            $OutTxt = '';
//            $OutTxt .= 'Galleriesj3xModel: getRsg2MenuParams()' . '<br>';
//            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//            $app = Factory::getApplication();
//            $app->enqueueMessage($OutTxt, 'error');
//        }
//
//
//
//        return $menuParams;
//    }
//
} // class
