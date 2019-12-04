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
	 * Method to attach a JForm object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     FormField::setup()
	 * @since   3.2
	 */
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			$this->allowAdd = $this->element['allowAdd'] ?? '';
		}

		return $return;
	}

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to get the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.6
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'allowAdd':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to set the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.6
	 */
	public function __set($name, $value)
	{
		$value = (string) $value;

		switch ($name)
		{
			case 'allowAdd':
				$value = (string) $value;
				$this->$name = ($value === 'true' || $value === $name || $value === '1');
				break;
			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to get a list of galleries that respects access controls and can be used for
	 * either gallery assignment or parent gallery assignment in edit screens.
	 * Use the parent element to indicate that the field will be used for assigning parent galleries.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$options = array();
		$published = $this->element['published'] ? explode(',', (string) $this->element['published']) : array(0, 1);
		$name = (string) $this->element['name'];

		// Let's get the id for the current item, either gallery or content item.
		$jinput = Factory::getApplication()->input;

		/**
		// Load the gallery options for a given extension.

		// For galleries the old gallery is the gallery id or 0 for new gallery.
		if ($this->element['parent'] || $jinput->get('option') == 'com_rsgallery2')
		{
			$oldCat = $jinput->get('id', 0);
			$oldParent = $this->form->getValue($name, 0);
			$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('extension', 'com_content');
		}
		else
			// For items the old gallery is the gallery they are in when opened or 0 if new.
		{
			$oldCat = $this->form->getValue($name, 0);
			$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('option', 'com_content');
		}

		// Account for case that a submitted form has a multi-value gallery id field (e.g. a filtering form), just use the first gallery
		$oldCat = is_array($oldCat)
			? (int) reset($oldCat)
			: (int) $oldCat;
		/**/

		try
		{
		    $db   = Factory::getDbo();
		    $user = Factory::getUser();
    
		    $query = $db->getQuery(true)
			    //->select('a.id AS value, a.name AS text, a.level, a.published, a.lft, a.language')
			    ->select('a.id AS value, a.name AS text, a.level, a.published, a.lft')
			    ->where('a.id != 1' )
			    ->from('#__rsg2_galleries AS a');
    
		    /**
		    // Filter by the extension type
		    if ($this->element['parent'] == true || $jinput->get('option') == 'com_rsgallery2')
		    {
			    $query->where('(a.extension = ' . $db->quote($extension) . ' OR a.parent_id = 0)');
		    }
		    else
		    {
			    $query->where('(a.extension = ' . $db->quote($extension) . ')');
		    }
		    /**/
    
		    /**
		    // Filter language
		    if (!empty($this->element['language']))
		    {
			    if (strpos($this->element['language'], ',') !== false)
			    {
				    $language = implode(',', $db->quote(explode(',', $this->element['language'])));
			    }
			    else
			    {
				    $language = $db->quote($this->element['language']);
			    }
    
			    $query->where($db->quoteName('a.language') . ' IN (' . $language . ')');
		    }
		    /**/
    
		    // Filter on the published state
		    $query->where('a.published IN (' . implode(',', ArrayHelper::toInteger($published)) . ')');
    
		    /**
		    // Filter galleries on User Access Level
		    // Filter by access level on galleries.
		    if (!$user->authorise('core.admin'))
		    {
			    $groups = implode(',', $user->getAuthorisedViewLevels());
			    $query->where('a.access IN (' . $groups . ')');
		    }
		    /**/
    
		    $query->order('a.lft ASC');
    
		    /**
		    // If parent isn't explicitly stated but we are in com_rsgallery2 assume we want parents
		    if ($oldCat != 0 && ($this->element['parent'] == true || $jinput->get('option') == 'com_rsgallery2'))
		    {
			    // Prevent parenting to children of this item.
			    // To rearrange parents and children move the children up, not the parents down.
			    $query->join('LEFT', $db->quoteName('#__rsg2_galleries') . ' AS p ON p.id = ' . (int) $oldCat)
				    ->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');
    
			    $rowQuery = $db->getQuery(true);
			    $rowQuery->select('a.id AS value, a.name AS text, a.level, a.parent_id')
				    ->from('#__rsg2_galleries AS a')
				    ->where('a.id = ' . (int) $oldCat);
			    $db->setQuery($rowQuery);
			    $row = $db->loadObject();
		    }
		    /**/
    
		    // Get the options.
		    $db->setQuery($query);

			$options = $db->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			// Translate ROOT
			if ($this->element['parent'] == true || $jinput->get('option') == 'com_rsgallery2')
			{
				if ($options[$i]->level == 0)
				{
					$options[$i]->text = Text::_('JGLOBAL_ROOT_PARENT');
				}
			}

			if ($options[$i]->published == 1)
			{
				$options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . $options[$i]->text;
			}
			else
			{
				$options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . '[' . $options[$i]->text . ']';
			}

			/**
			// Displays language code if not set to All
			if ($options[$i]->language !== '*')
			{
				$options[$i]->text = $options[$i]->text . ' (' . $options[$i]->language . ')';
			}
			/**/
		}

		foreach ($options as $i => $option)
		{
			/*
			 * To take save or create in a gallery you need to have create rights for that gallery unless the item is already in that gallery.
			 * Unset the option if the user isn't authorised for it. In this field assets are always galleries.
			 */
			if ($option->level != 0 && !$user->authorise('core.create', 'com_rsgallery2' . '.gallery.' . $option->value))
			{
				unset($options[$i]);
			}
		}


		if (($this->element['parent'] == true || $jinput->get('option') == 'com_rsgallery2')
			&& (isset($row) && !isset($options[0]))
			&& isset($this->element['show_root']))
		{
			if ($row->parent_id == '1')
			{
				$parent = new \stdClass;
				$parent->text = Text::_('JGLOBAL_ROOT_PARENT');
				array_unshift($options, $parent);
			}

			array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('JGLOBAL_ROOT')));
		}

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
