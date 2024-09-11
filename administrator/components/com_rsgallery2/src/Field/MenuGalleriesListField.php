<?php
/*
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

// used in upload


namespace Rsgallery2\Component\Rsgallery2\Administrator\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "Galleries overview" as first entry
 * Sorted by ordering (newest first)
 *
 * @since __BUMP_VERSION__
 */
class MenuGalleriesListField extends ListField
{
	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since __BUMP_VERSION__
	 */
//	protected static $options = [];

    /**
     * The field type.
     *
     * @var string
     *
     * @since __BUMP_VERSION__
     */
	protected $type = 'MenuGalleriesList';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since __BUMP_VERSION__
	 *
	protected function getInput()
	{
		return $this->getOptions() ? parent::getInput() : '';
	}
	/**/

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  The field option objects.
     *
     * @since __BUMP_VERSION__
	 */
	protected function getOptions()
	{
		$galleries = array();

		try
		{
			// $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible galleries
			$db    = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db->getQuery(true)
                ->select('id AS value, name AS text, level')
                ->from($db->quoteName('#__rsg2_galleries'))
				->where($db->quoteName('id') . ' != 1' )
//				->where($db->quoteName('published') . ' = 1')
				// ToDo: Use option in XML to select ASC/DESC
				->order('lft ASC');

			// Get the options.
			$galleries = $db->setQuery($query)->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		$options = $galleries;

        // Pad the option text with spaces using depth level as a multiplier.
        for ($i = 0, $n = count($options); $i < $n; $i++) {
            $options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . $options[$i]->text;
        }

        // Put "Select an option" on the top of the list.
		array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_RSGALLERY2_MENU_GALLERIES_OVERVIEW')));

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
	}
}



