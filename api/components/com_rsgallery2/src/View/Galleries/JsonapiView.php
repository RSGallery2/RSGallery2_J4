<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */
 
namespace Rsgallery2\Component\Rsgallery2\Api\View\Galleries;

//use Rsgallery2\Component\Rsgallery2\Api\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Api\Serializer\Rsgallery2Serializer;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The galleries view
 *
 * @since  4.0.0
 */
class JsonapiView extends BaseApiView
{
    /**
     * The fields to render item in the documents
     *
     * @var  array
     * @since  4.0.0
     */
    protected $fieldsToRenderItem = [
      'id',
      'name',
      'alias',
      'description',
      'thumb_id',
      'base_path',

      'note',
      'params',
      'published',
      'publish_up',
      'publish_down',

      'hits',

      'parent_id',
//      'level',
//      'path',
//      'lft',
//      'rgt',

      'approved',
      'asset_id',
      'access',

      'version',
      'sizes',
    ];

    /**
     * The fields to render items in the documents
     *
     * @var  array
     * @since  4.0.0
     */
    protected $fieldsToRenderList = [
        'id',
        'title',
        'name',

        'alias',
        'notes',
        'root_path',
        'prjType',

	    'params',

	    'checked_out',
	    'checked_out_time',
	    'created',
	    'created_by',
	    'created_by_alias',
	    'modified',
	    'modified_by',

	    'twin_id',

	    'approved',
	    'asset_id',
	    'access',

	    'version',

	    'ordering',
    ];

//    /**
//     * The relationships the item has
//     *
//     * @var    array
//     * @since  4.0.0
//     */
//    protected $relationship = [
//        'category',
//        'created_by',
//        'tags',
//    ];

    /**
     * Constructor.
     *
     * @param   array  $config  A named configuration array for object construction.
     *                          contentType: the name (optional) of the content type to use for the serialization
     *
     * @since   4.0.0
     */
    public function __construct($config = [])
    {
        if (\array_key_exists('contentType', $config)) {
            $this->serializer = new Rsgallery2Serializer($config['contentType']);
        }

        parent::__construct($config);
    }

    /**
     * Execute and display a template script.
     *
     * @param   ?array  $items  Array of items
     *
     * @return  string
     *
     * @since   4.0.0
     */
    public function displayList(?array $items = null)
    {
        foreach (FieldsHelper::getFields('com_rsgallery2.galleries') as $field) {
            $this->fieldsToRenderList[] = $field->name;
        }

        return parent::displayList();
    }

    /**
     * Execute and display a template script.
     *
     * @param   object  $item  Item
     *
     * @return  string
     *
     * @since   4.0.0
     */
    public function displayItem($item = null)
    {
        $this->relationship[] = 'modified_by';

        foreach (FieldsHelper::getFields('com_rsgallery2.project') as $field) {
            $this->fieldsToRenderItem[] = $field->name;
        }

        if (Multilanguage::isEnabled()) {
            $this->fieldsToRenderItem[] = 'languageAssociations';
            $this->relationship[]       = 'languageAssociations';
        }

        return parent::displayItem();
    }

    /**
     * Prepare item before render.
     *
     * @param   object  $item  The model item
     *
     * @return  object
     *
     * @since   4.0.0
     */
    protected function prepareItem($item)
    {
        if (!$item) {
            return $item;
        }

        $item->text = $item->introtext . ' ' . $item->fulltext;

        // Process the rsgallery2 plugins.
        PluginHelper::importPlugin('rsgallery2');
        Factory::getApplication()->triggerEvent('onContentPrepare', ['com_rsgallery2.project', &$item, &$item->params]);

        foreach (FieldsHelper::getFields('com_rsgallery2.project', $item, true) as $field) {
            $item->{$field->name} = $field->apivalue ?? $field->rawvalue;
        }

        if (Multilanguage::isEnabled() && !empty($item->associations)) {
            $associations = [];

            foreach ($item->associations as $language => $association) {
                $itemId = explode(':', $association)[0];

                $associations[] = (object) [
                    'id'       => $itemId,
                    'language' => $language,
                ];
            }

            $item->associations = $associations;
        }

        if (!empty($item->tags->tags)) {
            $tagsIds    = explode(',', $item->tags->tags);
            $item->tags = $item->tagsHelper->getTags($tagsIds);
        } else {
            $item->tags = [];
            $tags       = new TagsHelper();
            $tagsIds    = $tags->getTagIds($item->id, 'com_rsgallery2.project');

            if (!empty($tagsIds)) {
                $tagsIds    = explode(',', $tagsIds);
                $item->tags = $tags->getTags($tagsIds);
            }
        }

        if (isset($item->images)) {
            $registry     = new Registry($item->images);
            $item->images = $registry->toArray();

            if (!empty($item->images['image_intro'])) {
                $item->images['image_intro'] = Rsgallery2Helper::resolve($item->images['image_intro']);
            }

            if (!empty($item->images['image_fulltext'])) {
                $item->images['image_fulltext'] = Rsgallery2Helper::resolve($item->images['image_fulltext']);
            }
        }

        return parent::prepareItem($item);
    }

// ToDo: Later The hidden gem of the API view is another string array property, $relationship. In that view you list all the field names returned by your model which refer to related data.


}
