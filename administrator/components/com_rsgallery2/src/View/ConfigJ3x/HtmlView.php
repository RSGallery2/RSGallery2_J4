<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\ConfigJ3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	protected $configVars;

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
		$Layout = Factory::getApplication()->input->get('layout');
		//echo '$Layout: ' . $Layout . '<br>';

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDevelop = $rsgConfig->get('isDevelop');

		// "needs configj3x model using table ConfigJ3x ....";
		// CopyConfigJ3xModel j3xConfigItems
		// $this->items = $this->get('Items');

		// $this->configVars = $this->items;

		$this->configVars = [];

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=config&layout=RawView');
		/**
		$Layout = Factory::getApplication()->input->get('layout');
		Rsgallery2Helper::addSubmenu('config');
		$this->sidebar = \JHtmlSidebar::render();
		/**/

		$this->addToolbar($Layout);
		/**/

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function addToolbar($Layout)
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// on develop show open tasks if existing
		if (!empty ($this->isDevelop))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
				. '* !!! Read j3x config NOT ready !!! needs configj3x model using table ConfigJ3x .... !!!"<br>'
				. '* !!! Save not ready !!!<br>'
				. '* Secure user input <br>'
				. '* copy to file <br>'
				. '* copy to clipboard <br>'
				. '* RawView: dt dl dd definition on small width will interleap <br>'
				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
				. '</span><br><br>';
		} else {

			echo '<span style="color:red">'
				. '* !!! NOT ready !!! needs configj3x model using table ConfigJ3x .... !!!"<br>'
//				. '* <br>'
//				. '* <br>'
				. '</span><br><br>';
		}

		switch ($Layout)
		{
			case 'RawView':
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . Text::_('COM_RSGALLERY2_CONFIG_J3X_RAW_VIEW'), 'screwdriver');
				ToolBarHelper::cancel('configJ3x.cancel_rawView', 'JTOOLBAR_CLOSE');

				break;

			case 'RawEdit':
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . Text::_('COM_RSGALLERY2_CONFIG_J3X_RAW_EDIT'), 'screwdriver');
				ToolBarHelper::apply('configJ3x.apply_rawEdit');
				ToolBarHelper::save('configJ3x.save_rawEdit');
				ToolBarHelper::cancel('configJ3x.cancel_rawEdit', 'JTOOLBAR_CLOSE');
				break;
			default:
				ToolBarHelper::cancel('configJ3x.cancel', 'JTOOLBAR_CLOSE');
				break;
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}
	}



}

