<?php
/*
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2005-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

// used in upload


namespace Joomla\Component\Rsgallery2\Administrator\Field;

defined('_JEXEC') or die;

use JHtml;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

//use Joomla\CMS\HTML\HTMLHelper;
//use Joomla\CMS\Language\Text;
//use Joomla\Utilities\ArrayHelper;

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "-- Select --" as first entry
 * Sorted by ordering (newest first)
 *
 * @since 4.3.0
 */
class GallerySelectField extends ListField
{
	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since  3.9.0
	 */
//	protected static $options = [];

    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'GallerySelect';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.7.0
	 *
	protected function getInput()
	{
		return $this->getOptions() ? parent::getInput() : '';
	}
	/**/

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
     *
     * @since 4.3.0
	 */
	protected function getOptions()
	{
		$galleries = array();

		try
		{
			// $user = JFactory::getUser(); // Todo: Restrict to accessible galleries
			$db    = Factory::getDbo();
			$query = $db->getQuery(true)
				->select('id As value, name As text')
				->where($db->quoteName('id') . ' != 1' )
//				->where($db->quoteName('published') . ' = 1')
				->from($db->quoteName('#__rsg2_galleries'))
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

        // Put "Select an option" on the top of the list.
		array_unshift($options, JHtml::_('select.option', '0', Text::_('COM_RSGALLERY2_SELECT_GALLERY')));

        $options = array_merge(parent::getOptions(), $options);

        return $options;
	}
}

