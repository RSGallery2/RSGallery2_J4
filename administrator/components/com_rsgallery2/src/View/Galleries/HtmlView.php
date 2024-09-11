<?php
/**
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 *
 * @copyright  (c) 2005 - 2022 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Galleries;

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
use Joomla\CMS\User\UserFactoryInterface;

use Joomla\Component\Content\Administrator\Extension\ContentComponent;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to
	// the global config

	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  \JObject
	 */
	protected $state;

	/**
	 * The pagination object
	 *
	 * @var    Pagination
	 * @since __BUMP_VERSION__
	 */
	protected $pagination;
	/**
	 * Form object for search filters
	 *
	 * @var  \JForm
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  \JObject
	 */
	protected $canDo;

	/**
	 * Is there a content type associated with this gallery alias
	 *
	 * @var    boolean
	 * @since __BUMP_VERSION__
	 */
	protected $checkTags = false;

	protected $isDebugBackend;
	protected $isDevelop;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise an \Exception object.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->filterForm    = $this->get('FilterForm');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item)
		{
			$this->ordering[$item->parent_id][] = $item->id;
		}

		//$section = $this->state->get('gallery.section') ? $this->state->get('gallery.section') . '.' : '';
		//$this->canDo = ContentHelper::getActions($this->state->get('gallery.component'), $section . 'gallery', $this->item->id);
		//$this->canDo = ContentHelper::getActions('com_rsgallery2', 'category', $this->state->get('filter.category_id'));
		$this->canDo = ContentHelper::getActions('com_rsgallery2');
		//$this->assoc = $this->get('Assoc');

		//--- config --------------------------------------------------------------------

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDebugBackend = $rsgConfig->get('isDebugBackend');
		$this->isDevelop = $rsgConfig->get('isDevelop');

		//// Check if we have a content type for this alias
		//if (!empty(TagsHelper::getTypes('objectList', array($this->state->get('gallery.extension') . '.gallery'), true)))
		//{
		//	$this->checkTags = true;
		//}

		/**
		// Prepare a mapping from parent id to the ids of its children
		$this->ordering = array();
		foreach ($this->items as $item)
		{
			$this->ordering[$item->parent_id][] = $item->id;
		}
		/**/

        $Layout = $this->getLayout();

        switch ($Layout)
        {
            case 'galleries_raw':
                $galleriesModel      = $this->getModel();
                $this->items = $galleriesModel->allGalleries ();

                break;

            case 'galleries_tree':
                $galleriesModel      = $this->getModel();
                $this->items = $galleriesModel->allGalleries ();

                break;

            default:


                break;

        }

        //--- sidebar --------------------------------------------------------------------

		if ($Layout !== 'modal')
		{
			HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=Upload');
			Rsgallery2Helper::addSubmenu('galleries');
			$this->sidebar =  \Joomla\CMS\HTML\Helpers\Sidebar::render();

			// $Layout = Factory::getApplication()->input->get('layout');
			$this->addToolbar($Layout);
		}
		else
		{
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
		}

		//--- display --------------------------------------------------------------------

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
        $canDo = \Joomla\Component\Content\Administrator\Helper\ContentHelper::getActions('com_content', 'category', $this->state->get('filter.category_id'));
        // $user  = Factory::getContainer()->get(UserFactoryInterface::class);
		$user  = $this->getCurrentUser();

        // Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		switch ($Layout)
		{
			case 'galleries_raw':
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
						. '* Raw edit form<br>'
						. '* Can do ...<br>'
		                . '* Add pagination<br>'
						. '* mark element width id 1 <br>'
						//	. '* <br>'
						//	. '* <br>'
						//	. '* <br>'
						//	. '* <br>'
						. '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_GALLERIES_VIEW_RAW_DATA'), 'images');

				ToolBarHelper::editList('gallery.raw_edit');
				ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'gallery.delete', 'JTOOLBAR_EMPTY_TRASH'); 
				break;

            case 'galleries_tree':
                // on develop show open tasks if existing
                if (!empty ($this->isDevelop))
                {
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
                        . '* Improve tree design<br>'
                        //	. '* <br>'
                        //	. '* <br>'
                        //	. '* <br>'
                        . '</span><br><br>';
                }

                ToolBarHelper::title(Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE'), 'images');

                break;


            default:
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
						. '* Ordering: Mouse move not working<br>'
                        . '* ? Batch: move ...? <br>'
                        . '* params test write, read back -> json_encode registry<br>'
                        . '* <br>'
                        . '* include workflow<br>'
                        . '* Add Modified (+ by) hide creation when small <br>'
                        . '* column width by css instead in html<br>'
						. '* Can do ...<br>'
						. '* __associations <br>'
                    	. '* Badges array like in categories for images: Published, unpublished, trashed, archieved ... <br>'
                        . '* On develop show order left right level<br>'
				    	. '* Link to images should restrict to gallery in link<br>'
				    //	. '* <br>'
				    //	. '* <br>'
				    //	. '* <br>'
				    //	. '* <br>'
						. '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MANAGE_GALLERIES'), 'images');

				ToolBarHelper::addNew('gallery.add');

                if ($canDo->get('core.edit.state') || count($this->transitions))
                {
                    $dropdown = $toolbar->dropdownButton('status-group')
                        ->text('JTOOLBAR_CHANGE_STATUS')
                        ->toggleSplit(false)
                        ->icon('fa fa-ellipsis-h')
                        ->buttonClass('btn btn-action')
                        ->listCheck(true);

                    $childBar = $dropdown->getChildToolbar();

                    if ($canDo->get('core.edit.state'))
                    {
                        $childBar->publish('galleries.publish')->listCheck(true);

                        $childBar->unpublish('galleries.unpublish')->listCheck(true);

                        $childBar->archive('galleries.archive')->listCheck(true);

                        $childBar->checkin('galleries.checkin')->listCheck(true);

                        $childBar->trash('galleries.trash')->listCheck(true);

                        //				$toolbar->standardButton('refresh')
                        //					->text('JTOOLBAR_REBUILD')
                        //					->task('gallery.rebuild');

                    }

                    // Add a batch button
                    if ($user->authorise('core.create', 'com_content')
                        && $user->authorise('core.edit', 'com_content')
                        && $user->authorise('core.execute.transition', 'com_content'))
                    {
                        $childBar->popupButton('batch')
                            ->text('JTOOLBAR_BATCH')
                            ->selector('collapseModal')
                            ->listCheck(true);
                    }

                    if ($this->state->get('filter.published') == ContentComponent::CONDITION_TRASHED
                        && $canDo->get('core.delete'))
                    {
                        $toolbar->delete('galleries.delete')
                            ->text('JTOOLBAR_EMPTY_TRASH')
                            ->message('JGLOBAL_CONFIRM_DELETE')
                            ->listCheck(true);
                    }

                    // ToolBarHelper::editList('gallery.edit');
                }

				break;
			
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}

		/** ? joomla media .... ?
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
		$lang = Factory::getApplication()->getLanguage();
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
        $this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.images');

		// Prepare the toolbar.
		ToolbarHelper::title(
			$title,
			'folder gallery-' . ($isNew ? 'add' : 'edit')
				. ' ' . substr($component, 4) . ($section ? "-$section" : '') . '-gallery-' . ($isNew ? 'add' : 'edit')
		);

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories($component, 'core.create')) > 0))
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

			if (ComponentHelper::isEnabled('com_history') && $componentParams->get('save_history', 0) && $itemEditable)
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

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering'     => Text::_('JGRID_HEADING_ORDERING'),
			'a.published'    => Text::_('JSTATUS'),
			'a.name'         => Text::_('JGLOBAL_TITLE'),
			'category_title' => Text::_('JCATEGORY'),
			'a.access'       => Text::_('JGRID_HEADING_ACCESS'),
			'a.language'     => Text::_('JGRID_HEADING_LANGUAGE'),
			'a.id'           => Text::_('JGRID_HEADING_ID'),
		);
	}

}
