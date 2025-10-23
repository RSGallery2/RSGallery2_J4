<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\View\Config;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Database\DatabaseInterface;
use Rsgallery2\Component\Rsgallery2\Api\Serializer\Rsgallery2Serializer;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The images view
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
//        'image_size',
//        'keepOriginalImage',
    ];

    /**
     * The fields to render items in the documents
     *
     * @var  array
     * @since  4.0.0
     */
    protected $fieldsToRenderList = [];

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

        $this->fieldsToRenderItem = $this->getConfigParameterNames();

        parent::__construct($config);
    }

//    /**
//     * Execute and display a template script.
//     *
//     * @param   object  $item  Item
//     *
//     * @return  string
//     *
//     * @since   4.0.0
//     */
//    public function displayItem($item = null)
//    {
//        $this->relationship[] = 'modified_by';
//
//        foreach (FieldsHelper::getFields('com_rsgallery2.images') as $field) {
//            $this->fieldsToRenderItem[] = $field->name;
//        }
//
//        if (Multilanguage::isEnabled()) {
//            $this->fieldsToRenderItem[] = 'languageAssociations';
//            $this->relationship[]       = 'languageAssociations';
//        }
//
//        return parent::displayItem();
//    }

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
        // Media resources have no id.
        $item->id = '0';

        return $item;
    }

// ToDo: Later The hidden gem of the API view is another string array property, $relationship. In that view you list all the field names returned by your model which refer to related data.

    /**
     * Method to get all configuration names
     *
     * @return  \stdClass  A file or folder object.
     *
     * @since   4.1.0
     * @throws  ResourceNotFound
     */
    public function getConfigParameterNames() {

        $componentName = 'com_rsgallery2';

        $params = [];
        $params[] = "img size";
        $params[] = "set";

        try {

            $db = Factory::getContainer()->get(DatabaseInterface::class);
//            $db = $this->database;

            $query = $db->createQuery()
                ->select($db->quoteName('params'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote($componentName));
            $db->setQuery($query);

            $jsonStr = $db->loadResult();
            if (!empty ($jsonStr)) {
                $params = json_decode($jsonStr, true);
            }

            foreach($params as $name=>$value) {
                $params[] = $name;
            }

        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $params;
    }

}
