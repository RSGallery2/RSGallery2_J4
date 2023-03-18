<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Gallery;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * HTML View class for gallery edit
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  \JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  \JObject
	 */
	protected $state;

	/**
	 * Flag if an association exists
	 *
	 * @var  boolean
	 */
	protected $assoc;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  \JObject
	 */
	protected $canDo;

	/**
	 * Is there a content type associated with this gallery aias
	 *
	 * @var    boolean
	 * @since __BUMP_VERSION__
	 */
	protected $checkTags = false;

	protected $isDebugBackend;
	protected $isDevelop;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		//--- config --------------------------------------------------------------------

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDebugBackend = $rsgConfig->get('isDebugBackend');
		$this->isDevelop = $rsgConfig->get('isDevelop');

		//--- Form --------------------------------------------------------------------

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		//$section = $this->state->get('gallery.section') ? $this->state->get('gallery.section') . '.' : '';
		//$this->canDo = ContentHelper::getActions($this->state->get('gallery.component'), $section . 'gallery', $this->item->id);
		$this->canDo = ContentHelper::getActions('com_rsgallery2', 'gallery', $this->item->id);
		$this->assoc = $this->get('Assoc');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		// Check if we have a content type for this alias
		if (!empty(TagsHelper::getTypes('objectList', array($this->state->get('gallery.extension') . '.gallery'), true)))
		{
			$this->checkTags = true;
		}

		Factory::getApplication()->input->set('hidemainmenu', true);

		
		/**
		// If we are forcing a language in modal (used for associations).
		if ($this->getLayout() === 'modal' && $forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', '', 'cmd'))
		{
			// Set the language field to the forcedLanguage and disable changing it.
			$this->form->setValue('language', null, $forcedLanguage);
			$this->form->setFieldAttribute('language', 'readonly', 'true');

			// Only allow to select galleries with All language or with the forced language.
			$this->form->setFieldAttribute('parent_id', 'language', '*,' . $forcedLanguage);

			// Only allow to select tags with All language or with the forced language.
			$this->form->setFieldAttribute('tags', 'language', '*,' . $forcedLanguage);
		}
		/**/

		// different toolbar on different layouts
		$Layout = Factory::getApplication()->input->get('layout');
		$this->addToolbar($Layout);

		Factory::getApplication()->input->set('hidemainmenu', true);

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function addToolbar($Layout = 'default')
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// on develop show open tasks if existing
		if (!empty ($this->isDevelop))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
                . '* Fix: Save as copy with parent set does not save <br>'
                . '* Fix: Save as copy with published does not retunr published <br>'
                . '* Fix: cancel in batch<br>'
                . '* batch: add set parent<br>'
                . '* published_up, published_down: preset on first save with published <br>'
                . '* Thumb nails as images<br>'
                . '* Cancel ?or save ? -> Close Image edit -> Fehlöer Null ...<br>'
                . '* description to each input parameter "_DESC"<br>'
				. '* start as published ? external parameter<br>'
				. '* options: Values not defined and add params<br>'
                . '* options: show user, layout ... see article<br>'
                . '* test published_up, published_down: on Web page <br>'
//				. '* <br>'
//				. '* <br>'
				. '</span><br><br>';
		}

		switch ($Layout)
		{
			case 'edit':
			default:
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_EDIT_GALLERY', 'images'));

				ToolBarHelper::apply('gallery.apply');
				ToolBarHelper::save('gallery.save');
				ToolBarHelper::save2new('gallery.save2new');
                ToolBarHelper::save2copy('gallery.save2copy');

                if (empty($this->item->id))
				{
					ToolBarHelper::cancel('gallery.cancel', 'JTOOLBAR_CLOSE');
				}
				else
				{
					ToolBarHelper::cancel('gallery.cancel', 'JTOOLBAR_CLOSE');
				}

				// Goto upload with selected gallery id
                ToolBarHelper::custom ('gallery.save2upload','upload','','COM_RSGALLERY2_SAVE_AND_GOTO_UPLOAD', false);

//                $link = 'index.php?option=com_rsgallery2&view=upload' . '&id=' . $this->item->id;
//                $toolbar->appendButton( 'Link', 'upload', 'COM_RSGALLERY2_SAVE_AND_GOTO_UPLOAD', $link);

				break;
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}

		/**
		$extension = Factory::getApplication()->input->get('extension');
		$user = Factory::getApplication()->getIdentity();
		$userId = $user->id;

		$isNew = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Avoid nonsense situation.
		if ($extension == 'com_rsgallery2')
		{
			return;
		}

		// The extension can be in the form com_foo.section
		$parts = explode('.', $extension);
		$component = $parts[0];
		$section = (count($parts) > 1) ? $parts[1] : null;
		$componentParams = ComponentHelper::getParams($component);

		// Need to load the menu language file as mod_menu hasn't been loaded yet.
		$lang = Factory::getLanguage();
		$lang->load($component, JPATH_BASE, null, false, true)
		|| $lang->load($component, JPATH_ADMINISTRATOR . '/components/' . $component, null, false, true);

		// Get the results for each action.
		$canDo = $this->canDo;

		// If a component galleries title string is present, let's use it.
		if ($lang->hasKey($component_title_key = $component . ($section ? "_$section" : '') . '_GALLERY_' . ($isNew ? 'ADD' : 'EDIT') . '_TITLE'))
		{
			$title = Text::_($component_title_key);
		}
		// Else if the component section string exits, let's use it
		elseif ($lang->hasKey($component_section_key = $component . ($section ? "_$section" : '')))
		{
			$title = Text::sprintf('COM_RSGALLERY2_GALLERY_' . ($isNew ? 'ADD' : 'EDIT')
					. '_TITLE', $this->escape(Text::_($component_section_key))
					);
		}
		// Else use the base title
		else
		{
			$title = Text::_('COM_RSGALLERY2_GALLERY_BASE_' . ($isNew ? 'ADD' : 'EDIT') . '_TITLE');
		}

		// Load specific css component
		// HTMLHelper::_('stylesheet', $component . '/administrator/ ??? galleries.css', array('version' => 'auto', 'relative' => true));
        $this->document->getWebAssetManager()->usePreset('com_rsallery2.backend.images');

		// Prepare the toolbar.
		ToolbarHelper::title(
			$title,
			'folder gallery-' . ($isNew ? 'add' : 'edit')
				. ' ' . substr($component, 4) . ($section ? "-$section" : '') . '-gallery-' . ($isNew ? 'add' : 'edit')
		);

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedGalleries($component, 'core.create')) > 0))
		{
			ToolbarHelper::saveGroup(
				[
					['apply', 'gallery.apply'],
					['save', 'gallery.save'],
					['save2new', 'gallery.save2new']
				],
				'btn-success'
			);

			ToolbarHelper::cancel('gallery.cancel', 'JTOOLBAR_CLOSE');
		}

		// If not checked out, can save the item.
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_user_id == $userId);

			$toolbarButtons = [];

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				$toolbarButtons[] = ['apply', 'gallery.apply'];
				$toolbarButtons[] = ['save', 'gallery.save'];

				if ($canDo->get('core.create'))
				{
					$toolbarButtons[] = ['save2new', 'gallery.save2new'];
				}
			}

			// If an existing item, can save to a copy.
			if ($canDo->get('core.create'))
			{
				$toolbarButtons[] = ['save2copy', 'gallery.save2copy'];
			}

			ToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			if (ComponentHelper::isEnabled('com_contenthistory') && $componentParams->get('save_history', 0) && $itemEditable)
			{
				$typeAlias = $extension . '.gallery';
				ToolbarHelper::versions($typeAlias, $this->item->id);
			}

			ToolbarHelper::cancel('gallery.cancel', 'JTOOLBAR_CLOSE');
		}

		ToolbarHelper::divider();

		// Compute the ref_key
		$ref_key = strtoupper($component . ($section ? "_$section" : '')) . '_GALLERY_' . ($isNew ? 'ADD' : 'EDIT') . '_HELP_KEY';

		// Check if thr computed ref_key does exist in the component
		if (!$lang->hasKey($ref_key))
		{
			$ref_key = 'JHELP_COMPONENTS_'
						. strtoupper(substr($component, 4) . ($section ? "_$section" : ''))
						. '_GALLERY_' . ($isNew ? 'ADD' : 'EDIT');
		}

		/*
		 * Get help for the gallery/section view for the component by
		 * -remotely searching in a language defined dedicated URL: *component*_HELP_URL
		 * -locally  searching in a component help file if helpURL param exists in the component and is set to ''
		 * -remotely searching in a component URL if helpURL param exists in the component and is NOT set to ''
		 *
		if ($lang->hasKey($lang_help_url = strtoupper($component) . '_HELP_URL'))
		{
			$debug = $lang->setDebug(false);
			$url = Text::_($lang_help_url);
			$lang->setDebug($debug);
		}
		else
		{
			$url = null;
		}

		ToolbarHelper::help($ref_key, $componentParams->exists('helpURL'), $url, $component);
		/**/
	}
}
