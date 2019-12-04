<?php
/*
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2005-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "-- Select --" as first entry
 * Sorted by ordering (newest first)
 *
 * @since 4.3.0
 */
class GallerySelectList extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'GallerySelectList';

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
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('id As value, name As text')
				->where($db->quoteName('id') . ' != 1' )
				->where($db->quoteName('published') . ' = 1')
				->from($db->quoteName('#__rsg2_galleries'))
				// ToDo: Use option in XML to select ASC/DESC
				->order('a.lft ASC');

			// Get the options.
			$db->setQuery($query);

			$galleries = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		$options = $galleries;

        // Put "Select an option" on the top of the list.
		array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_RSGALLERY2_SELECT_GALLERY')));

        $options = array_merge(parent::getOptions(), $options);

        return $options;
	}
}

