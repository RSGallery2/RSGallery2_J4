<?php
/*
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2005-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

// used in edit gallery

namespace Joomla\Component\Rsgallery2\Administrator\Field;

defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Includes "No parent" as first entry
 * Sorted by ordering (newest first)
 *
 * @since 4.3.0
 */
class GalleryEditField extends ListField
{
	/**
	 * To allow creation of new galleries.
	 *
	 * @var    integer
	 * @since  3.6
	 */
	protected $allowAdd;

	/**
	 * A flexible gallery list that respects access controls
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'GalleryEdit';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $layout = 'joomla.form.field.galleryedit';


	/**
	 * Method to get a list of galleries (?that respects access controls and can be used for
	 * either gallery assignment or parent gallery assignment in edit screens?).
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
        $galleries = array();

        $ActGalleryId = (string) $this->element['id'];

        try
        {
            // $name = (string) $this->element['name'];

		    $db   = Factory::getDbo();

		    $query = $db->getQuery(true)
			    //->select('a.id AS value, a.name AS text, a.level, a.published, a.lft, a.language')
			    ->select('id AS value, name AS text, level')
                ->from('#__rsg2_galleries AS a')
			    ->where('a.id != 1' )
                ->where('a.id !=' . (int) $ActGalleryId);

		    // Filter on the published state
		    // $query->where('a.published IN (' . implode(',', ArrayHelper::toInteger($published)) . ')');
    
		    $query->order('a.lft ASC');

		    // Get the options.
		    $db->setQuery($query);

            $galleries = $db->loadObjectList();
        }
		catch (\RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

        $options = $galleries;

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			// Translate ROOT
			if ($this->element['parent'] == true)
			{
				if ($options[$i]->level == 0)
				{
					$options[$i]->text = Text::_('JGLOBAL_ROOT_PARENT');
				}
			}

//			if ($options[$i]->published == 1)
//			{
				$options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . $options[$i]->text;
//			}
//			else
//			{
//				$options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . '[' . $options[$i]->text . ']';
//			}

			/**
			// Displays language code if not set to All
			if ($options[$i]->language !== '*')
			{
				$options[$i]->text = $options[$i]->text . ' (' . $options[$i]->language . ')';
			}
			/**/
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

        // Tell about no parent
        //$parent = new \stdClass;
        //$parent->text = Text::_('JGLOBAL_ROOT_PARENT');
        //array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('JGLOBAL_ROOT')));
        array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('JGLOBAL_ROOT_PARENT')));

        // Merge any additional options in the XML definition.
		return array_merge(parent::getOptions(), $options);
	}

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.6
	 */
	protected function getInput()
	{
		$data = $this->getLayoutData();

		$data['options']     = $this->getOptions();
		$data['allowCustom'] = $this->allowAdd;

		$renderer = $this->getRenderer($this->layout);
		$renderer->setComponent('com_rsgallery2');
		$renderer->setClient(1);

		$test = $renderer->render($data);
		return $renderer->render($data);
	}
}
