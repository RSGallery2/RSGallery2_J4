<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class GalleryModel extends ListModel
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

    protected $layoutParams = null; // col/row count


    public function getlayoutParams ()
    {
        if ($this->layoutParams == null) {
            $this->layoutParams = $this->CascadedLayoutParameter ();
        }
        return $this->layoutParams;
    }

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
                'title', 'a.title',
                'alias', 'a.alias',
                'checked_out', 'a.checked_out',
                'checked_out_time', 'a.checked_out_time',
                'catid', 'a.catid', 'category_title',
                'state', 'a.state',
                'access', 'a.access', 'access_level',
                'created', 'a.created',
                'created_by', 'a.created_by',
                'ordering', 'a.ordering',
//                'featured', 'a.featured',
//                'language', 'a.language',
                'hits', 'a.hits',
                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down',
//                'images', 'a.images',
//                'urls', 'a.urls',
                'filter_tag',
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
    protected function populateState($ordering = 'ordering', $direction = 'ASC')
    {
        global $rsgConfig;

        $app = Factory::getApplication();

        $this->setState('gallery.id', $app->input->getInt('gid'));
        $this->setState('params', $app->getParams());
		
        // Adjust the context to support modal layouts.
        // ToDo: what about more then one gallery displayed at one page ..
        if ($layout = $app->input->get('layout')) {
            $this->context .= '.' . $layout;
        }

        $layoutParams = $this->getlayoutParams ();

        // List state information
        // $value = $app->input->get('limit', $app->get('list_limit', ), 'uint');
        // $this->setState('list.limit', $value);
        $this->setState('list.limit', $layoutParams->limit);

        //$value = $app->input->get('limitstart', 0, 'uint');
        //$this->setState('list.start', $value);
	    $offset = $app->input->get('limitstart', 0, 'uint');
	    $this->setState('list.offset', $offset);

        $value = $app->input->get('filter_tag', 0, 'uint');
        $this->setState('filter.tag', $value);

        $orderCol = $app->input->get('filter_order', 'a.ordering');

        if (!in_array($orderCol, $this->filter_fields))
        {
            $orderCol = 'a.ordering';
        }

        $this->setState('list.ordering', $orderCol);

        $listOrder = $app->input->get('filter_order_Dir', 'ASC');

        if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
        {
            $listOrder = 'ASC';
        }

        $this->setState('list.direction', $listOrder);

        $params = $app->getParams();
        $this->setState('params', $params);

        $user = Factory::getContainer()->get(UserFactoryInterface::class);
        if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content')))
        {
            // Filter on published for those who do not have edit or edit.state rights.
            $this->setState('filter.condition', ContentComponent::CONDITION_PUBLISHED);
        }

//        $this->setState('filter.language', Multilanguage::isEnabled());

        // toDo: ??? when is it needed
        // Process show_noauth parameter
        if ((!$params->get('show_noauth')) || (!ComponentHelper::getParams('com_content')->get('show_noauth')))
        {
            $this->setState('filter.access', true);
        }
        else
        {
            $this->setState('filter.access', false);
        }

        $this->setState('layout', $app->input->getString('layout'));

        //--- RSG2 ---------------------------------

        //$this->setState('rsgallery2.id', $app->input->getInt('id'));
        $this->setState('image.id', $app->input->getInt('id'));
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
	 * @var string item
	 */
    /**/
	protected $_item = null;
    /**/

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
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	public function getListQuery()
	{
		$app    = Factory::getApplication();
		$input  = Factory::getApplication()->input;
        $user   = Factory::getContainer()->get(UserFactoryInterface::class);
		$groups = $user->getAuthorisedViewLevels();
        $userId = $user->get('id');
        $guest  = $user->get('guest');
		$orderby        = $this->state->params->get('all_tags_orderby', 'title');
		$published      = (int) $this->state->params->get('published', 1);
		$orderDirection = $this->state->params->get('all_tags_orderby_direction', 'ASC');

        $gid = $input->getInt ('gid', 0);

		// Create a new query object.
		$db    = $this->getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);

		$query->select('*')
			//->from($db->quoteName('#__rsg2_galleries', 'a'))
			->from($db->quoteName('#__rsg2_images', 'a'))
			->where('a.published = 1')
			//->where('a.id = ' . (int) $gid);
			->where('a.gallery_id = ' . (int) $gid);
		// ToDo: limit ....


		// limit


		if ($this->state->params->get('show_pagination_limit'))
		{
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'uint');
		}
		else
		{
			$limit = $this->state->params->get('maximum', 20);
		}

		$this->setState('list.limit', $limit);

		$offset = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $offset);


		$query->where($db->quoteName('a.published') . ' = :published')
			->bind(':published', $published, ParameterType::INTEGER);

		$query->order($db->quoteName($orderby) . ' ' . $orderDirection . ', a.title ASC');

		return $query;

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


    //-----------------------------------------------------------------


    public function gallery_parameter($gid=0)
    {
        $parameter = new \stdClass();

        // Not root gallery (tree root == 1)
        if( $gid > 1) {

            // Create a new query object.
            $db    = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db->getQuery(true)
                ->select('params') // ToDo: select single items
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where($db->quoteName('id') . '=' . $gid );

            $parameterRow = $db->setQuery($query)
                ->loadResult();
            $parameter = new Registry($parameterRow);
        }

        return $parameter;
    }

    public function galleryData($gid=0)
    {
        $gallery = new \stdClass();

        // Not root gallery (tree root == 1)
        if( $gid > 1) {

            // Create a new query object.
            $db    = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where($db->quoteName('id') . '=' . $gid );
	        $db->setQuery($query);

	        $gallery = $db->loadObject();

            // add slidshow url
            if (! empty ($gallery)) {
                $this->AssignSlideshowUrl ($gallery);
            }

        }

        return $gallery;
    }

    /**
     * checks parameters from "top -> down" for user defined values
     *  (1) General RSG2 configuration
     *  (2) Gallery definition
     *  // ??? (2) Menu definition ???
     * @param $rsgConfig
     * @param $galleryParameter
     * @param null $imagesParameter
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
        $layoutParameter->max_images_in_images_view  = 0;

        try {

            /** @var merge registries *
            $app = JFactory::getApplication();
            $currentMenuId = JSite::getMenu()->getActive()->id;
            $menuitem   = $app->getMenu()->getItem($currentMenuId);
            $params = $menuitem->params;
            echo $params['menu_image'];
            /**

    

            $app = Factory::getApplication();
            $menuitem   = $app->getMenu()->getActive(); // get the active item
            // $menuitem   = $app->getMenu()->getItem($theid); // or get item by ID
            $params = $menuitem->params; // get the params
            print_r($params); // print all params as overview
			/**/

            //--- RSG2 config  parameter -------------------------------------------------

            $rsgConfig = ComponentHelper::getParams('com_rsgallery2');

            $images_column_arrangement = $rsgConfig->get('images_column_arrangement');
            $max_columns_in_images_view = $rsgConfig->get('max_columns_in_images_view');
            $images_row_arrangement = $rsgConfig->get('images_row_arrangement');
            $max_rows_in_images_view = $rsgConfig->get('max_rows_in_images_view');
            $max_images_in_images_view = $rsgConfig->get('max_images_in_images_view');

            //--- menu parameter -------------------------------------------------

	        /*
            $app = Factory::getApplication();
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
                            $max_images_in_images_view = $input->get('max_images_in_images_view', $max_images_in_images_view, 'INT');
                        }
                    }
                }
            }

            //--- gallery parameter -------------------------------------------------

            // ToDo: gid: one get access function keep result ...
            // gallery parameter
            $gid = $input->get('gid', '', 'INT');

			// ToDo: check gid == 0 => error or selection control

            $gallery_param = $this->gallery_parameter($gid);

            // overwrite config and new if chosen
            $images_column_arrangement_gallery = $gallery_param->get('images_column_arrangement');

            if ($images_column_arrangement_gallery != 'global') {
                $images_column_arrangement = (int)$images_column_arrangement_gallery;

                // toDo: switch when more selections .. (0 auto)
                if ($images_column_arrangement_gallery == '1') {
                    $max_columns_in_images_view = $gallery_param->get('max_columns_in_images_view');

                    $images_row_arrangement_gallery = $gallery_param->get('images_row_arrangement', $images_row_arrangement, 'INT');
                    if ($images_row_arrangement_gallery != 'global') {
                        $images_row_arrangement = (int)$images_row_arrangement_gallery;

                        // toDo: switch when more selections .. (0 auto)

                        if ($images_row_arrangement_gallery == '1') {
                            $max_rows_in_images_view = $gallery_param->get('max_rows_in_images_view', $max_rows_in_images_view, 'INT');
                        } else {
                            $max_images_in_images_view = $gallery_param->get('max_images_in_images_view', $max_images_in_images_view, 'INT');
                        }
                    }
                }
            }
			/**/

            $layoutParameter->images_column_arrangement  = $images_column_arrangement;
            $layoutParameter->max_columns_in_images_view = $max_columns_in_images_view;
            $layoutParameter->images_row_arrangement     = $images_row_arrangement;
            $layoutParameter->max_rows_in_images_view    = $max_rows_in_images_view;
            $layoutParameter->max_images_in_images_view  = $max_images_in_images_view;


            //--- determine limit --------------------------------------------------

            $limit = 0;

            // determine image limit of one page view
            if ((int) $images_column_arrangement == 0) { // auto
                $limit = 0;
            }
            else
            {
                if((int) $images_row_arrangement == 0) { // auto
                    $limit = 0;
                }
                else
                {
                    if((int) $images_row_arrangement == 1) { // row count
                        $limit = (int) $max_columns_in_images_view * (int) $max_rows_in_images_view;
                    } else { // max images
                        $limit = (int) $max_images_in_images_view;
                    }

                }

            }

            $layoutParameter->limit = $limit;

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: CascadedLayoutParameter: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $layoutParameter;
    }

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

                $this->AssignImageUrl($image);

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

    public function AssignSlideshowUrl($gallery)
    {

        try {

            $gallery->UrlSlideshow = ''; // fall back

            // Link to single gallery in actual menu
            // /joomla3x/index.php/j3x-galleries-overview/gallery/8

//            $gallery->UrlSlideshow = Route::_(index.php?option=com_rsgallery2 ....
//                . '/gallery/' . $gallery->id . '/slideshow'
////                . '&gid=' . $image->gallery_id
////                . '&iid=' . $gallery->id
////                . '&layout=galleryJ3xAsInline'
//                ,true,0,true);


            // http://127.0.0.1/joomla4x/index.php?option=com_rsgallery2&view=slideshow&gid=2

            $gallery->UrlSlideshow = Route::_('index.php?option=com_rsgallery2'
                . '&view=slideshow&gid=' . $gallery->id
                ,true,0,true);
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GallerysModel: AssignSlideshowUrl: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

    }

//    public function mergeParams ($paramRsg2Menu, $paramItem) {
//
//        // base with the lowest priority
//        $mergedParams = clone $paramRsg2Menu;
//
//        // overwrite with param items
//        $mergedParams->merge($paramItem);
//
//    }



} // class
