<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Images;

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

use Joomla\CMS\Uri\Uri;
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

	protected $UserIsRoot;

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

	protected $HtmlPathThumb;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		//--- get needed form data ------------------------------------------

		// Check rights of user
//		$this->UserIsRoot = $this->CheckUserIsRoot();

		$this->items         = $this->get('Items');
        $errors = $this->get('Errors');
        $this->filterForm    = $this->get('FilterForm');
        $errors = $this->get('Errors');
        $this->pagination    = $this->get('Pagination');
        $errors = $this->get('Errors');
        $this->state         = $this->get('State');
        $errors = $this->get('Errors');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
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

		//--- thumb --------------------------------------------------------------------

		// ToDo: HtmlPathThumb path must be taken from model (? file model ?)
		$this->HtmlPathThumb = URI::base() . $rsgConfig->get('???imgPath_thumb') . '/';
		////echo 'ThumbPath: ' . JPATH_THUMB . '<br>';
		////echo 'ImagePathThumb: ' . $rsgConfig->imgPath_thumb . '<br>';
		////echo 'ImagePathThumb: ' . JURI_SITE . $rsgConfig->get('imgPath_thumb') . '<br>';
		//echo $this->HtmlPathThumb . '<br>';

		//--- sidebar --------------------------------------------------------------------

		$Layout = $this->getLayout();


        switch ($Layout)
        {
            case 'images_raw':
                $imageModel = $this->getModel();
                $dummyItems = $imageModel->allImages();

                break;

            default:


                break;

        }

        if ($Layout !== 'modal')
		{
			HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=Upload');
			Rsgallery2Helper::addSubmenu('images');
			$this->sidebar = \JHtmlSidebar::render();

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
        $user  = Factory::getUser();

        // Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		switch ($Layout)
		{
			case 'images_raw':
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
					echo '<span style="color:red">'
						. 'Tasks: <br>'
						. '* Can do ...<br>'
						. '* Add pagination<br>'
						. '* Test: archived, trashed, (delete)<br>'
						. '* Add delete function<br>'
						//	. '* <br>'
						//	. '* <br>'
						//	. '* <br>'
						. '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_IMAGES_VIEW_RAW_DATA'), 'image');

				ToolBarHelper::editList('image.edit');
				ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'image.delete', 'JTOOLBAR_EMPTY_TRASH');
				break;


			default:
				// on develop show open tasks if existing
				if (!empty ($this->isDevelop))
				{
                    echo '<span style="color:red">'
                        . 'Tasks: <br>'
	                    . '* Ordering: Mouse move not working<br>'
                        . '* Delete images for real <br>'
                        . '* Test: archived, trashed, (delete)<br>'
                        . '* Batch : turn images, move .... <br>'
                        . '* params test write, read back -> json_enmcode registry<br>'
	                    . '* <br>'
                        . '* Can do ...<br>'
                        . '* __associations <br>'
                        . '* HtmlPathThumb path must be taken from model (? file model ?) <br>'
                        . '* display thumb'
                        . '* column width by css<br>'
                        . '* Status (title and side text like article <br>'
                        . '* Delete function needs to delete watermarked too !<br>'
                        . '* Image not shown above title (data-original-title?)<br>'
                        . '* Vote and others only when user enabled<br>'
                        . '* Put gallery name beside alias. Only when sorting by galleris is otherwise possible<br>'
                        . '* On develop show order<br>'
                        //	. '* <br>'
                        //	. '* <br>'
                        //	. '* <br>'
                        . '</span><br><br>';
				}

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MANAGE_IMAGES'), 'image');

				//ToolBarHelper::addNew('image.add');

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
                        $childBar->publish('images.publish')->listCheck(true);

                        $childBar->unpublish('images.unpublish')->listCheck(true);

                        $childBar->archive('images.archive')->listCheck(true);

                        $childBar->checkin('images.checkin')->listCheck(true);

                        $childBar->trash('images.trash')->listCheck(true);

                        // $toolbar->standardButton('refresh')
                        // 	->text('JTOOLBAR_REBUILD')
                        // 	->task('image.rebuild');

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
                        $toolbar->delete('images.delete')
                            ->text('JTOOLBAR_EMPTY_TRASH')
                            ->message('JGLOBAL_CONFIRM_DELETE')
                            ->listCheck(true);
                    }

                    // ToolBarHelper::editList('image.edit');
                }

				break;
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}

        // $toolbar->help('JHELP_CONTENT_ARTICLE_MANAGER');
	}



}

