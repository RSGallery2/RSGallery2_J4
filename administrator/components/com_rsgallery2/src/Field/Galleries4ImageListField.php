<?php
/*
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

// used in edit gallery

namespace Rsgallery2\Component\Rsgallery2\Administrator\Field;

\defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;
use Joomla\Utilities\ArrayHelper;

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "No parent" as first entry
 * Sorted by ordering (newest first)
 *
 * @since __BUMP_VERSION__
 */
class Galleries4ImageListField extends ListField
{
    /**
     * To allow creation of new galleries.
     *
     * @var    integer
     * @since __BUMP_VERSION__
     */
    protected $allowAdd;

    /**
     * A flexible gallery list that respects access controls
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
    public $type = 'Galleries4ImageList';

    /**
     * Name of the layout being used to render the field
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
//	protected $layout = 'joomla.form.field.ParentList';


    /**
     * Method to get a list of galleries (?that respects access controls and can be used for
     * either gallery assignment or parent gallery assignment in edit screens?).
     *
     * @return  array  The field option objects.
     *
     * @since __BUMP_VERSION__
     */
    protected function getOptions()
    {
        $galleries = [];

        $galleryId = (string)$this->form->getValue('id');

        try {
            // $name = (string) $this->element['name'];
            // $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible galleries
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db
                ->getQuery(true)
                //->select('id AS value, name AS text, level, published, lft, language')
                ->select('id AS value, name AS text, level')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where($db->quoteName('id') . ' != 1')
                // Filter on the published state
                // ->where('published IN (' . implode(',', ArrayHelper::toInteger($published)) . ')');
                // ToDo: Use option in XML to select ASC/DESC
                ->order('lft ASC');

            // Get the options.
            $galleries = $db->setQuery($query)->loadObjectList();
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        $options = $galleries;

        // Pad the option text with spaces using depth level as a multiplier.
        for ($i = 0, $n = count($options); $i < $n; $i++) {
//			// Translate ROOT
//			if ($this->element['parent'] == true)
//			{
//				if ($options[$i]->level == 0)
//				{
//				    // -- No Root parent --
//					$options[$i]->text = Text::_('JGLOBAL_ROOT_PARENT');
//				}
//			}

//			if ($options[$i]->published == 1)
//			{
            $options[$i]->text = str_repeat(
                    '- ',
                    !$options[$i]->level ? 0 : $options[$i]->level - 1,
                ) . $options[$i]->text;
//			}
//			else
//			{
//				$options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . '[' . $options[$i]->text . ']';
//			}
            /**
             * // Displays language code if not set to All
             * if ($options[$i]->language !== '*')
             * {
             * $options[$i]->text = $options[$i]->text . ' (' . $options[$i]->language . ')';
             * }
             * /**/
        }

//		foreach ($options as $i => $option)
//		{
//			/*
//			 * To take save or create in a gallery you need to have create rights for that gallery unless the item is already in that gallery.
//			 * Unset the option if the user isn't authorised for it. In this field assets are always galleries.
//			 */
//			if ($option->level != 0 && !$user->authorise('core.create', 'com_rsgallery2' . '.gallery.' . $option->value))
//			{
//				unset($options[$i]);
//			}
//		}


        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
