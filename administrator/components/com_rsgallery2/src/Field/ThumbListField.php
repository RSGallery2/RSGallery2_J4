<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// used in upload

namespace Rsgallery2\Component\Rsgallery2\Administrator\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\Database\DatabaseInterface;




/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "-- Select --" as first entry
 * Sorted by ordering (newest first)
 *
 * @since __BUMP_VERSION__
 */
class ThumbListField extends ListField
{
    /**
     * Cached array of the category items.
     *
     * @var    array
     * @since  5.1.0     */
//	protected static $options = [];

    /**
     * The field type.
     *
     * @var string
     *
     * @since 5.1.0     */
    protected $type = 'ThumbList';

    /**
     * Method to get the field input markup for a generic list.
     * Use the multiple attribute to enable multiselect.
     *
     * @return  string  The field input markup.
     *
     * @since   5.1.0     *
	protected function getInput()
	{
		return $this->getOptions() ? parent::getInput() : '';
	}
	/**/

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  The field option objects.
     *
     * @since   5.1.0     */
    protected function getOptions()
    {
        $thumbs  = [];
        $options = [];

        try {
            $galleryId = $this->form->getValue('id');
            //$galleryName = $this->form->getValue('name');

            // $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible galleries
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->createQuery()
                ->select($db->quoteName('id', 'value'))
                ->select($db->quoteName('name', 'text'))
                ->from('#__rsg2_images')
                ->where($db->quoteName('gallery_id') . '=' . (int)$galleryId)
//				->where($db->quoteName('published') . ' = 1')
                ->order('id');

            // Get the options.
            $images = $db->setQuery($query)->loadObjectList();

//            // Create row number to Text = "Row number -> image name" assignment
//            foreach ($images as $image) {
//
//                $option = new \stdClass;
//                $option->value = $image->idx;
//                $option->text = $image->text; // ToDo escape text see gallery list / image list names
//
//                $option->style="background-image:url(male.png);";
//
//                $options[] = $option;
//            }

            $options = $images;
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

//    /**
//     * Method to get the field input markup for a generic list.
//     * Use the multiple attribute to enable multiselect.
//     *
//     * @return  string  The field input markup.
//     *
//     * @since __BUMP_VERSION__
//     */
//    protected function getInput()
//    {
//        $data = $this->getLayoutData();
//
//        $data['options']     = $this->getOptions();
//        $data['allowCustom'] = $this->allowAdd;
//
//        $renderer = $this->getRenderer($this->layout);
//        $renderer->setComponent('com_rsgallery2');
//        $renderer->setClient(1);
//
//        $test = $renderer->render($data);
//        return $renderer->render($data);
//    }
}

