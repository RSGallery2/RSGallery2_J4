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

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;

/**
 * Collects available ordering and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "-- first --" as first entry
 * Sorted by ordering (newest first)
 *
 * See MenuOrderingField Field code
 * @since      5.1.0
 */
class GalleryOrderLftField extends ListField
{
    /**
     * Cached array of the category items.
     *
     * @var    array
     * @since  5.1.0     */
//  protected static $options = [];

    /**
     * The field type.
     *
     * @var string
     *
     * @since 5.1.0     */
    protected $type = 'GalleryOrderLft';

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
     * @return   array The field option objects.
     *
     * @since    5.1.0     */
    protected function getOptions()
    {
        $galleries = [];

//        // Get the parent
//        $parent_id = (int) $this->form->getValue('parent_id', 0);
//
//        if (!$parent_id) {
//            return false;
//        }

        // $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible galleries
        $db = Factory::getContainer()->get(DatabaseInterface::class);

        $query = $db->createQuery()
            ->select('id AS value, name AS text')
            ->from($db->quoteName('#__rsg2_galleries'))
//                ->where($db->quoteName('id') . ' != 1')
//              ->where($db->quoteName('published') . ' = 1')
            ->order('lft ASC');

        // Get the galleries.
        $db->setQuery($query);

        try {
            $options = $db->loadObjectList();
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        // Put "- first -" on the top of the list.
        array_unshift($options, HTMLHelper::_('select.option', '-1',
            Text::_('COM_RSGALLERY2_ORDERING_AS_FIRST')));

        // Put "- last -" on the top of the list.
        array_push($options, HTMLHelper::_('select.option', '-2',
            Text::_('COM_RSGALLERY2_ORDERING_AS_LAST')));

//        $options = array_merge(
//            [['value' => '-1', 'text' => Text::_('COM_MENUS_ITEM_FIELD_ORDERING_VALUE_FIRST')]],
//            $options,
//            [['value' => '-2', 'text' => Text::_('COM_MENUS_ITEM_FIELD_ORDERING_VALUE_LAST')]]
//        );

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
