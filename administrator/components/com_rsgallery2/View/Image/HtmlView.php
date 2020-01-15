<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\View\Image;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

//use Joomla\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * View class for a list of rsgallery2.
 *
 * @since  1.0
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
	 * @since  4.0.0
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
	 * @since   1.0
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
				. '*  ? Table header COM_RSGALLERY2_FIELDSET_RULES ? permissions ?<br>'
				. '*  published_up, published_down<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br><br>';
		}

		switch ($Layout)
		{
			case 'edit':
			default:
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_EDIT_IMAGE', 'image'));

				ToolBarHelper::apply('image.apply');
				ToolBarHelper::save('image.save');
				ToolBarHelper::save2new('image.save2new');
				if (empty($this->item->id))
				{
					ToolBarHelper::cancel('image.cancel');
				}
				else
				{
					ToolBarHelper::cancel('image.cancel');
				}

//				ToolBarHelper::custom ('gallery.save2upload','upload','','COM_RSGALLERY2_SAVE_AND_GOTO_UPLOAD', false);

				break;
		}

		$toolbar->preferences('com_rsgallery2');
	}




}

