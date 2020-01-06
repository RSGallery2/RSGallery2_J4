<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\View\MaintenanceJ3x;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Joomla\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * View class for a list of rsgallery2.
 *
 * @since  1.0
 */
class HtmlView extends BaseHtmlView
{
	protected $configVars;

	protected $configVarsOld;

	protected $form;


	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise an \Exception object.
	 *
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		$Layout = Factory::getApplication()->input->get('layout');
		//echo '$Layout: ' . $Layout . '<br>';

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDevelop = $rsgConfig->get('isDevelop');

		$this->configVars = $rsgConfig;
		$this->configVarsOld = array ();

//		$this->form = $this->get('Form');

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=config&layout=RawView');
		/**/
		$Layout = Factory::getApplication()->input->get('layout');

		switch ($Layout)
		{
			case 'DbCopyOldConfig':

				try
				{
					$j3xModel      = $this->getModel();
					$this->configVarsOld = $j3xModel->OldConfigItems();


					// iterate over all values
					$this->configVarsMerged = $j3xModel->MergeOldAndNew($this->configVarsOld, $this->configVars);

				}
				catch (RuntimeException $e)
				{
					$OutTxt = '';
					$OutTxt .= 'Error collecting config data for: "' . $Layout . '"<br>';
					$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

					$app = JFactory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
				}


				break;
		}


		Rsgallery2Helper::addSubmenu('maintenance');
		$this->sidebar = \JHtmlSidebar::render();

		$this->addToolbar($Layout);
		/**/

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0
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
				. '* Make old config column smaller <br>'
				. '* Secure user input <br>'
				. '* List of special transfer old1 -> new2 ....<br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
//				. '*  <br>'
				. '</span><br>';
		}

		switch ($Layout)
		{
			case 'DbCopyOldConfig':
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_COPY_OLD_CONFIG'), 'screwdriver');
				ToolBarHelper::custom ('MaintenanceJ3x.copyOldItems2New','copy','','COM_RSGALLERY2_COPY_COMPLETE_OLD_CONFIGURATION', false);
				ToolBarHelper::custom ('MaintenanceJ3x.copySelectedOldItems2New','copy','','COM_RSGALLERY2_COPY_SELECTED_OLD_CONFIGURATION', true);
				//ToolBarHelper::custom ('copyoldconfig.recompare','upload','','COM_RSGALLERY2_OLD_CONFIGURATION_RECOMPARE', true);

				ToolBarHelper::cancel('config.cancel_rawView');
				break;

			case 'DBTransferOldGalleries':
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_TRANSFER_GALLERIES'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView');
				break;
			case 'DBTransferOldImages':
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_TRANSFER_IMAGES'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView');
				break;

				/**
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . Text::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				ToolBarHelper::apply('config.apply_rawEdit');
				ToolBarHelper::save('config.save_rawEdit');
				ToolBarHelper::cancel('config.cancel_rawEdit');
				break;
				 * */
			default:
				ToolBarHelper::cancel('config.cancel');
				break;
		}

		// direct to config
		$toolbar->preferences('com_rsgallery2');
	}

	/**
	public function getModel($name = 'Associations', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	/**/

}

