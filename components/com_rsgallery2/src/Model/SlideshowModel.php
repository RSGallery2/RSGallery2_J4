<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Registry\Registry;

use function defined;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class SlideshowModel extends GalleryModel {}

//
// we don't know which parts below may be needed
//

/**
 * Model context string.
 *
 * @param   array                     $config     An optional associative array of configuration settings.
 * @param   MVCFactoryInterface|null  $factory
 * @param   string                    $ordering   An optional ordering field.
 * @param   string                    $direction  An optional direction (asc|desc).
 *
 * @param   string                    $id         A prefix for the store id.
 *
 * @return  void
 *
 * @return  string  A store id.
 *
 * @throws Exception
 * @var    string
 * @since   3.1
 *
 * public $_context = 'com_rsgallery2.slideshowJ3x';
 *
 * /**
 * The category context (allows other extensions to derived from this model).
 *
 * @var        string
 *
 * protected $_extension = 'com_rsgallery2';
 *
 * //    protected $layoutParams = null; // col/row count
 *
 * //
 * //    public function getlayoutParams ()
 * //    {
 * //        if ($this->layoutParams == null) {
 * //            $this->layoutParams = $this->CascadedLayoutParameter ();
 * //        }
 * //        return $this->layoutParams;
 * //    }
 * //
 * //
 *
 * /**
 * Constructor.
 *
 * @see     JController
 * @since   1.6
 *
 * public function __construct($config = array(), MVCFactoryInterface $factory = null)
 * {
 * if (empty($config['filter_fields'])) {
 * $config['filter_fields'] = array(
 * 'id', 'a.id',
 * 'title', 'a.title',
 * 'alias', 'a.alias',
 * 'checked_out', 'a.checked_out',
 * 'checked_out_time', 'a.checked_out_time',
 * 'catid', 'a.catid', 'category_title',
 * 'state', 'a.state',
 * 'access', 'a.access', 'access_level',
 * 'created', 'a.created',
 * 'created_by', 'a.created_by',
 * 'ordering', 'a.ordering',
 * //                'featured', 'a.featured',
 * //                'language', 'a.language',
 * 'hits', 'a.hits',
 * 'publish_up', 'a.publish_up',
 * 'publish_down', 'a.publish_down',
 * //                'images', 'a.images',
 * //                'urls', 'a.urls',
 * 'filter_tag',
 * );
 * }
 *
 * parent::__construct($config, $factory);
 * }
 *
 *
 * /**
 * Method to auto-populate the model state.
 *
 * This method should only be called once per instantiation and is designed
 * to be called on the first call to the getState() method unless the model
 * configuration flag to ignore the request is set.
 *
 * Note. Calling getState in this method will result in recursion.
 *
 * @since   3.0.1
 *
 * protected function populateState($ordering = 'ordering', $direction = 'ASC')
 * {
 * global $rsgConfig;
 *
 * $app = Factory::getApplication();
 *
 * // ToDo: move to view html and model (plugion?)
 * // gallery id
 * $gid = $app->input->get('gid', '', 'INT');
 * $this->setState('images.galleryId', $gid);
 * // ??? See above
 * $this->setState('gallery.id', $app->input->getInt('gid'));
 * $this->setState('params', $app->getParams());
 *
 * // Adjust the context to support modal layouts.
 * if ($layout = $app->input->get('layout')) {
 * $this->context .= '.' . $layout;
 * }
 *
 * //        $layoutParams = $this->getlayoutParams ();
 *
 * //        // List state information
 * //        // $value = $app->input->get('limit', $app->get('list_limit', ), 'uint');
 * //        // $this->setState('list.limit', $value);
 * //        $this->setState('list.limit', $layoutParams->limit);
 * //
 * //        $value = $app->input->get('limitstart', 0, 'uint');
 * //        $this->setState('list.start', $value);
 * //
 * //        $value = $app->input->get('filter_tag', 0, 'uint');
 * //        $this->setState('filter.tag', $value);
 * //
 * $orderCol = $app->input->get('filter_order', 'a.ordering');
 *
 * if (!in_array($orderCol, $this->filter_fields)) {
 * $orderCol = 'a.ordering';
 * }
 *
 * $this->setState('list.ordering', $orderCol);
 *
 * $listOrder = $app->input->get('filter_order_Dir', 'ASC');
 *
 * if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
 * $listOrder = 'ASC';
 * }
 *
 * $this->setState('list.direction', $listOrder);
 *
 * $params = $app->getParams();
 * $this->setState('params', $params);
 * // $user = Factory::getContainer()->get(UserFactoryInterface::class);
 * $user = $app->getIdentity();
 *
 * if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content'))) {
 * // Filter on published for those who do not have edit or edit.state rights.
 * $this->setState('filter.condition', ContentComponent::CONDITION_PUBLISHED);
 * }
 *
 * //        $this->setState('filter.language', Multilanguage::isEnabled());
 *
 * // toDo: ??? when is it needed
 * // Process show_noauth parameter
 * if ((!$params->get('show_noauth')) || (!ComponentHelper::getParams('com_content')->get('show_noauth'))) {
 * $this->setState('filter.access', true);
 * } else {
 * $this->setState('filter.access', false);
 * }
 *
 * $this->setState('layout', $app->input->getString('layout'));
 *
 * //--- RSG2 ---------------------------------
 *
 * $this->setState('rsgallery2.id', $app->input->getInt('id'));
 * }
 *
 * /**
 * Method to get a store id based on model configuration state.
 *
 * This is necessary because the model is used by the component and
 * different modules that might need different sets of data or different
 * ordering requirements.
 *
 * @since   1.6
 *
 * protected function getStoreId($id = '')
 * {
 * // Compile the store id.
 * $id .= ':' . serialize($this->getState('filter.condition'));
 * $id .= ':' . $this->getState('filter.access');
 * //        $id .= ':' . $this->getState('filter.featured');
 * $id .= ':' . serialize($this->getState('filter.article_id'));
 * $id .= ':' . $this->getState('filter.article_id.include');
 * $id .= ':' . serialize($this->getState('filter.category_id'));
 * $id .= ':' . $this->getState('filter.category_id.include');
 * $id .= ':' . serialize($this->getState('filter.author_id'));
 * $id .= ':' . $this->getState('filter.author_id.include');
 * $id .= ':' . serialize($this->getState('filter.author_alias'));
 * $id .= ':' . $this->getState('filter.author_alias.include');
 * $id .= ':' . $this->getState('filter.date_filtering');
 * $id .= ':' . $this->getState('filter.date_field');
 * $id .= ':' . $this->getState('filter.start_date_range');
 * $id .= ':' . $this->getState('filter.end_date_range');
 * $id .= ':' . $this->getState('filter.relative_date');
 * $id .= ':' . serialize($this->getState('filter.tag'));
 *
 * return parent::getStoreId($id);
 * }
 *
 *
 * /**
 * @var string item
 */
/**
 * protected $_item = null;
 * /**/

/**
 * Method to get a list of images
 *
 * ??? Overridden to inject convert the attribs field into a Registry object.
 *
 * @param   integer  $gid  Id for the gallery
 *
 * @param            $images
 *
 *
 * @return  integer  The starting number of items available in the data set.
 *
 * @return  mixed  An array of objects on success, false on failure.
 *
 * @since   3.0.1
 *
 * public function getStart()
 * {
 * return $this->getState('list.start');
 * }
 *
 *
 * /**
 * @since   1.6
 *
 *
 * // toDo: rights ...
 *
 * public function getItems()
 * {
 * $items = parent::getItems();
 * // $user = Factory::getContainer()->get(UserFactoryInterface::class);
 * $user = $app->getIdentity();
 * $userId = $user->get('id');
 * $guest = $user->get('guest');
 * $groups = $user->getAuthorisedViewLevels();
 * $input = Factory::getApplication()->input;
 *
 * $gid = $input->getInt('gid', 0);
 *
 * if ($this->_item === null) {
 * $this->_item = array();
 * }
 *
 * $images = new \stdClass(); // ToDo: all to (object)[];
 *
 * // not fetched already
 * if (!isset($this->_item[$gid])) {
 *
 * try {
 * $db = $this->getDatabase();
 * $query = $db->getQuery(true);
 *
 * $query->select('*')
 * //->from($db->quoteName('#__rsg2_galleries', 'a'))
 * ->from($db->quoteName('#__rsg2_images', 'a'))
 * //->where('a.id = ' . (int) $gid);
 * ->where('a.gallery_id = ' . (int)$gid);
 * // ToDo: limit ....
 *
 * $db->setQuery($query);
 * $data = $db->loadObjectList();
 *
 * if (!empty($data)) {
 *
 * $this->_item[$gid] = $data;
 *
 * // Add image paths, image params ...
 * $this->assignImageUrls($data);
 * } else {
 * // may< be empty
 * $this->_item[$gid] = [];  // false;
 * // throw new \Exception(Text::_('COM_RSGALLERY2_ERROR_RSGALLERY2_NOT_FOUND'), 404);
 * }
 * } catch (\Exception $e) {
 * $this->setError($e);
 * $this->_item[$gid] = false;
 * }
 * }
 *
 * $images = $this->_item[$gid];
 *
 * return $images;
 * }
 *
 * /**
 * Method to get the starting number of items for the data set.
 *
 * @since   4.5.0.0
 *
 * public function assignImageUrls($images)
 * {
 * try {
 *
 * // ToDo: gid: one get access function keep result ...
 * // gallery parameter
 * $app = Factory::getApplication();
 * $input = $app->input;
 * $gid = $input->get('gid', '', 'INT');
 *
 * $ImagePaths = new ImagePathsModel ($gid);
 *
 * foreach ($images as $image) {
 * // ToDo: check for J3x style of gallery (? all in construct ?)
 *
 * $image->UrlThumbFile = $ImagePaths->getThumbUrl($image->name);
 * // $image->UrlDisplayFile = $ImagePaths->getSizeUrl ('400', $image->name); // toDo: image size to path
 * $image->UrlDisplayFiles = $ImagePaths->getSizeUrls($image->name);
 * $image->UrlOriginalFile = $ImagePaths->getOriginalUrl($image->name);
 *
 * // ToDo: watermarked file
 * }
 *
 * } catch (\Exception $e) {
 * $this->setError($e);
 * }
 *
 * }
 *
 * }
 * /**/
