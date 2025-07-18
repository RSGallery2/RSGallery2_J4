<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (c)  2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * @author         finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Database\QueryInterface;
use Joomla\Registry\Registry;
//use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;
//use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;


/**
 * RSGallery2 Component Images Model
 *
 * @since  __BUMP_VERSION__
 */
class ImagesModel extends ListModel
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
     * @var        string
     */
    protected $_extension = 'com_rsgallery2';

    protected $layoutParams = null; // col/row count

    protected $_item = null;

    /**
     * Constructor.
     *
     * @param   array                     $config  An optional associative array of configuration settings.
     * @param   MVCFactoryInterface|null  $factory
     * @throws \Exception
     * @see     \JController
     * @since   5.0
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        //  which fields are needed for filter function
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
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
                'gallery_name',
            ];
        }

        parent::__construct($config, $factory);
    }

    public function getlayoutParams()
    {
        if ($this->layoutParams == null) {
            $this->layoutParams = $this->CascadedLayoutParameter();
        }

        return $this->layoutParams;
    }

    /**
     * checks parameters from "top -> down" for user defined values
     *  (1) General RSG2 configuration
     *  (2) Gallery definition
     *  // ??? (2) Menu definition ???
     *
     * @param         $rsgConfig
     * @param         $galleryParameter
     * @param   null  $imagesParameter
     *
     *
     * @since version
     */

    private function CascadedLayoutParameter() // For gallery images view
    {
        $layoutParameter = new \stdClass();
        $layoutParameter->images_column_arrangement  = 0; // 0: auto
        $layoutParameter->max_columns_in_images_view = 0;
        $layoutParameter->images_row_arrangement     = 0; // 0: auto
        $layoutParameter->max_rows_in_images_view    = 0;
        $layoutParameter->max_thumbs_in_images_view  = 0;

        try {
            //--- RSG2 config  parameter -------------------------------------------------

            $rsgConfig = ComponentHelper::getParams('com_rsgallery2');

            $images_column_arrangement  = $rsgConfig->get('images_column_arrangement');
            $max_columns_in_images_view = $rsgConfig->get('max_columns_in_images_view');
            $images_row_arrangement     = $rsgConfig->get('images_row_arrangement');
            $max_rows_in_images_view    = $rsgConfig->get('max_rows_in_images_view');
            $max_thumbs_in_images_view  = $rsgConfig->get('max_thumbs_in_images_view');

            //--- menu parameter -------------------------------------------------

            $app   = Factory::getApplication();
            $input = $app->input;

            // overwrite config if chosen
            $images_column_arrangement_menu = $input->get('images_column_arrangement', $images_column_arrangement, 'STRING');

            if ($images_column_arrangement_menu != 'global') {
                $images_column_arrangement = (int)$images_column_arrangement_menu;

                // toDo: switch when more selections .. (0 auto)
                if ($images_column_arrangement_menu == '1') {
                    $max_columns_in_images_view = $input->get('max_columns_in_images_view', $max_columns_in_images_view, 'INT');

                    $images_row_arrangement_menu = $input->get('images_row_arrangement', $images_row_arrangement, 'INT');
                    if ($images_row_arrangement_menu != 'global') {
                        $images_row_arrangement = (int)$images_row_arrangement_menu;

                        // toDo: switch when more selections .. (0 auto)

                        if ($images_row_arrangement_menu == '1') {
                            $max_rows_in_images_view = $input->get('max_rows_in_images_view', $max_rows_in_images_view, 'INT');
                        } else {
                            $max_thumbs_in_images_view = $input->get('max_thumbs_in_images_view', $max_thumbs_in_images_view, 'INT');
                        }
                    }
                }
            }

            //--- gallery parameter -------------------------------------------------

            // ToDo: gid: one get access function keep result ...
            // gallery parameter
            $gid           = $input->get('id', '', 'INT');
            $gallery_param = $this->gallery_parameter($gid);

            // overwrite config and new if chosen
            $images_column_arrangement_gallery = $gallery_param->get('images_column_arrangement');

            if ($images_column_arrangement_gallery != 'global') {
                $images_column_arrangement = (int)$images_column_arrangement_gallery;

                // toDo: switch when more selections .. (0 auto)
                if ($images_column_arrangement_gallery == '1') {
                    $max_columns_in_images_view = $gallery_param->get('max_columns_in_images_view');

                    $images_row_arrangement_gallery = (int)$gallery_param->get('images_row_arrangement', $images_row_arrangement);
                    if ($images_row_arrangement_gallery != 'global') {
                        $images_row_arrangement = (int)$images_row_arrangement_gallery;

                        // toDo: switch when more selections .. (0 auto)

                        if ($images_row_arrangement_gallery == '1') {
                            $max_rows_in_images_view = (int)$gallery_param->get('max_rows_in_images_view', $max_rows_in_images_view);
                        } else {
                            $max_thumbs_in_images_view = (int)$gallery_param->get('max_thumbs_in_images_view', $max_thumbs_in_images_view);
                        }
                    }
                }
            }

            $layoutParameter->images_column_arrangement  = $images_column_arrangement;
            $layoutParameter->max_columns_in_images_view = $max_columns_in_images_view;
            $layoutParameter->images_row_arrangement     = $images_row_arrangement;
            $layoutParameter->max_rows_in_images_view    = $max_rows_in_images_view;
            $layoutParameter->max_thumbs_in_images_view  = $max_thumbs_in_images_view;


            //--- determine limit --------------------------------------------------

            $limit = 0;

            // determine image limit of one page view
            if ((int)$images_column_arrangement == 0) { // auto
                $limit = 0;
            } else {
                if ((int)$images_row_arrangement == 0) { // auto
                    $limit = 0;
                } else {
                    if ((int)$images_row_arrangement == 1) { // row count
                        $limit = (int)$max_columns_in_images_view * (int)$max_rows_in_images_view;
                    } else { // max images
                        $limit = (int)$max_thumbs_in_images_view;
                    }
                }
            }

            $layoutParameter->limit = $limit;
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: CascadedLayoutParameter: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $layoutParameter;
    }

    /**
     * @var string item
     */
    /**/
    private function gallery_parameter($gid = 0)
    {
        $parameter = new \stdClass();

        // Not root gallery (tree root == 1)
        if ($gid > 1) {
            // Create a new query object.
            //$db    = Factory::getContainer()->get(DatabaseInterface::class);
            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                ->select('params') // ToDo: select single items
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where($db->quoteName('id') . '=' . $gid);

            $parameterRow = $db
                ->setQuery($query)
                ->loadResult();
            $parameter    = new Registry($parameterRow);
        }

        return $parameter;
    }

    /**/


    public function getItems()
    {
        $app  = Factory::getApplication();
        $user = $app->getIdentity();
        // $user   = Factory::getContainer()->get(UserFactoryInterface::class);
        $userId = $user->get('id');
        $guest  = $user->get('guest');
        $groups = $user->getAuthorisedViewLevels();
        $input  = Factory::getApplication()->input;

        // ToDo: ? use state instead ?
        $gid = $input->getInt('id', 0);

        if ($this->_item === null) {
            $this->_item = [];
        }

        $images = []; // new \stdClass(); // ToDo: all to (object)[];

        // not fetched already
        if (!isset($this->_item[$gid])) {
            try {
                $images = parent::getItems(); // gid ...

                $db    = $this->getDatabase();
                $query = $db->getQuery(true);

                $query
                    ->select('*')
                    //->from($db->quoteName('#__rsg2_galleries', 'a'))
                    ->from($db->quoteName('#__rsg2_images', 'a'))
                    //->where('a.id = ' . (int) $gid);
                    ->where('a.published = 1')
                    ->where('a.gallery_id = ' . (int)$gid);
                // ToDo: limit ....

                $db->setQuery($query);
                $images = $db->loadObjectList();

                if (!empty($images)) {
                    // Add image paths, image params ...
                    $this->AddLayoutData($images);
                    $data = $images;
                } else {
                    // No images defined yet
                    $data = false;
                }

                $this->_item[$gid] = $data;
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'GalleriesModel: getItems: Error executing query: "' . "" . '"' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $images = $this->_item[$gid];

        return $images;
    }

    /**
     * Method to get a list of images
     *
     * ??? Overridden to inject convert the attribs field into a Registry object.
     *
     * @param   integer  $gid  Id for the gallery
     *
     * @return  mixed  An array of objects on success, false on failure.
     *
     * @since   1.6
     */

    // toDo: rights ...
    /**
     * @param $images
     *
     *
     * @since 4.5.0.0
     */
    public function AddLayoutData($images)
    {
        try {
            foreach ($images as $image) {
                // ToDo: check for J3x style of gallery (? all in construct ?)

                $this->assignImageUrl($image);

                // ToDo: Are there situations where download should not be shown ?
                // ==> watermark or not shown single => call in inherited instead
                $this->AssignUrlDownloadImage($image);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: AddLayoutData: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $images;
    }

    /**
     * @param $image
     *
     *
     * @since 4.5.0.0
     */
    public function assignImageUrl($image)
    {
        try {
            // ToDo: watermarked file

            // J4x ?
            if (!$image->use_j3x_location) {
                $imagePaths = new ImagePathsData ($image->gallery_id);
                $imagePaths->assignPathData($image);
            } else {
                // J3x
                $imagePathJ3x = new ImagePathsJ3xData ();
                $imagePathJ3x->assignPathData($image);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: assignImageUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }


    //-----------------------------------------------------------------

    public function AssignUrlDownloadImage($image)
    {
        $image->UrlDownload = ''; // fall back

        // ToDo: use one function instead of two
        try {
            $image->UrlDownload = Route::_('index.php?option=com_rsgallery2'
                . '&task=imagefile.downloadfile&id=' . $image->id
                ,true,0,true);
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'ImagesModel: AssignUrlDownloadImage: Error executing query: "' . "" . '"' . '<br>';
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
     * This function will retrieve the data of the n last uploaded images
     *
     * @param   int  $limit  > 0 will limit the number of lines returned
     *
     * @return array rows with image name, images name, date, and user name as rows
     *
     * @throws \Exception
     * @since __BUMP_VERSION__
     */
    public function latestImages($limit)
    {
        $images = [];

        try {
            // Create a new query object.
            //$db    = Factory::getContainer()->get(DatabaseInterface::class);
            $db    = $this->getDatabase();
            $query = $db->getQuery(true);

            $query
                ->select('*')
                ->from($db->quoteName('#__rsg2_images'))
                ->where('published = 1')
                ->order($db->quoteName('id') . ' DESC')
                ->setLimit($limit);

            $db->setQuery($query);
            $rows = $db->loadObjectList();

            foreach ($rows as $image) {
                $this->assignImageUrl($image);

                $images[] = $image;
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'latestImages: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $images;
    }

    /**
     * This function will retrieve the data of n random uploaded images
     *
     * @param   int  $limit  > 0 will limit the number of lines returned
     *
     * @return array rows with image name, images name, date, and user name as rows
     *
     * @throws \Exception
     * @since __BUMP_VERSION__
     */
    public function randomImages($limit)
    {
        $images = [];

        try {
            // Create a new query object.
            // $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $db    = $this->getDatabase();
            $query = $db->getQuery(true);

            $query
                ->select('*')
                ->from($db->quoteName('#__rsg2_images'))
                ->where('published = 1')
                ->order('RAND()')
                ->setLimit($limit);

            $db->setQuery($query);
            $rows = $db->loadObjectList();

            foreach ($rows as $image) {
                $this->assignImageUrl($image);

                $images[] = $image;
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'latestImages: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $images;
    }

    public function galleryData($gid = 0)
    {
        $gallery = new \stdClass();

        // Not root gallery (tree root == 1)
        if ($gid > 1) {
            // Create a new query object.
            //$db    = Factory::getContainer()->get(DatabaseInterface::class);
            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where($db->quoteName('id') . '=' . $gid);
            $db->setQuery($query);

            $gallery = $db->loadObject();
        }

        return $gallery;
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
    protected function populateState($ordering = 'ordering', $direction = 'ASC')
    {
        $app = Factory::getApplication();

        // ToDo: ? move to view html and model (plugin?)

        // gallery id
        $galleryId = $app->input->get('id', '', 'INT');
        $this->setState('images.galleryId', $galleryId);

        // image id
        // why item ?
        $imageId = $app->input->get('item', '', 'INT');
        $this->setState('images.imageId', $imageId);

        $this->setState('params', $app->getParams());

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout')) {
            $this->context .= '.' . $layout;
        }

        // List state information
        $value = $app->input->get('limit', $app->get('list_limit'), 'uint');
        $this->setState('list.limit', $value);
        //$this->setState('list.limit', $layoutParams->limit);

        $value = $app->input->get('limitstart', 0, 'uint');
        // ToDo: check $this->setState('list.start', $value);
        $this->setState('list.start', $value);

        $value = $app->input->get('filter_tag', 0, 'uint');
        $this->setState('filter.tag', $value);

        // $orderCol = $app->input->get('filter_order', 'a.ordering');
        $orderCol = $app->input->get('filter_order', 'ordering');

        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = 'a.ordering';
        }

        $this->setState('list.ordering', $orderCol);

        $listOrder = $app->input->get('filter_order_Dir', 'ASC');

        if (!in_array(strtoupper($listOrder), ['ASC', 'DESC', ''])) {
            $listOrder = 'ASC';
        }

        $this->setState('list.direction', $listOrder);

        $params = $app->getParams();
        $this->setState('params', $params);
        // $user = Factory::getContainer()->get(UserFactoryInterface::class);
        $user = $app->getIdentity();

        if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content'))) {
            // Filter on published for those who do not have edit or edit.state rights.
            $this->setState('filter.condition', ContentComponent::CONDITION_PUBLISHED);
        }

//        $this->setState('filter.language', Multilanguage::isEnabled());

        // toDo: ??? when is it needed
        // Process show_noauth parameter
        if ((!$params->get('show_noauth')) || (!ComponentHelper::getParams('com_content')->get('show_noauth'))) {
            $this->setState('filter.access', true);
        } else {
            $this->setState('filter.access', false);
        }

        $this->setState('layout', $app->input->getString('layout'));

        //--- RSG2 ---------------------------------

        $this->setState('rsgallery2.id', $app->input->getInt('id'));
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
        $id .= ':' . serialize($this->getState('filter.condition'));
        $id .= ':' . $this->getState('filter.access');
//        $id .= ':' . $this->getState('filter.featured');
        $id .= ':' . serialize($this->getState('filter.article_id'));
        $id .= ':' . $this->getState('filter.article_id.include');
        $id .= ':' . serialize($this->getState('filter.category_id'));
        $id .= ':' . $this->getState('filter.category_id.include');
        $id .= ':' . serialize($this->getState('filter.author_id'));
        $id .= ':' . $this->getState('filter.author_id.include');
        $id .= ':' . serialize($this->getState('filter.author_alias'));
        $id .= ':' . $this->getState('filter.author_alias.include');
        $id .= ':' . $this->getState('filter.date_filtering');
        $id .= ':' . $this->getState('filter.date_field');
        $id .= ':' . $this->getState('filter.start_date_range');
        $id .= ':' . $this->getState('filter.end_date_range');
        $id .= ':' . $this->getState('filter.relative_date');
        $id .= ':' . serialize($this->getState('filter.tag'));

        return parent::getStoreId($id);
    }

    /**
     * Method to get a database query to list images.
     *
     * @return  QueryInterface object.
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
                . 'a.use_j3x_location ',
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
        if ($access = $this->getState('filter.access')) {
            $query->where('a.access = ' . (int)$access);
        }

        // Filter on the gallery Id.
        if ($gallery_id = $this->getState('filter.gallery_id')) {
            $query->where('a.gallery_id = ' . $db->quote($gallery_id));
        }

        // Implement View Level Access
        if (!$user->authorise('core.admin')) {
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
        $listDirn     = $db->escape($this->getState('list.direction', 'ASC'));

        if ($listOrdering == 'a.access') {
            $query->order('a.access ' . $listDirn . ', a.id ' . $listDirn);
        } else {
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
            . 'a.use_j3x_location ',
        /**/
        );

        return $query;
    }

}
