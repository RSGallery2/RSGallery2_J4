<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\MaintConsolidateDb;

\defined('_JEXEC') or die;

//use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel;

/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	protected $buttons = [];

	protected $isDebugBackend;
	protected $isDevelop;

	protected $isDangerActive;
	protected $isRawDbActive;
	protected $isUpgradeActive;
	protected $isTestActive;
	protected $isJ3xRsg2DataExisting;
	protected $developActive;

	protected $intended;

	// ToDo: Use other rights instead of core.admin -> IsRoot ?
	// core.admin is the permission used to control access to
	// the global config
	protected $UserIsRoot;

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

		//--- config --------------------------------------------------------------------

		$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		//$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
		$this->isDebugBackend = $rsgConfig->get('isDebugBackend');
		$this->isDevelop = $rsgConfig->get('isDevelop');

		//--- get needed data ------------------------------------------


		$ConsolidateModel      = $this->getModel(); // JModelLegacy::getInstance('MaintConsolidateDB', 'rsgallery2Model');
		$this->ImageReferences = $ConsolidateModel->GetImageReferences();

		// for prepared but not ready views
		// $input = Factory::getApplication()->input;


		// Check for errors.
		/* Must load form before
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}
		/**/

        $this->isJ3xRsg2DataExisting = J3xExistModel::J3xConfigTableExist();

		$xmlFile    = JPATH_COMPONENT . '/models/forms/maintConsolidateDB.xml';
		$this->form = Form::getInstance('maintConsolidateDB', $xmlFile);






        //--- Check user rights ---------------------------------------------

		// toDo: More detailed for rsgallery admin
		$app       = Factory::getApplication();

		$user = $app->getIdentity();
		$canAdmin = $user->authorise('core.admin');
		$this->UserIsRoot = $canAdmin;

		//--- begin to display ----------------------------------------------

//		Factory::getApplication()->input->set('hidemainmenu', true);

		//---  --------------------------------------------------------------

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=maintenance');
		Rsgallery2Helper::addSubmenu('maintenance');
		$this->sidebar = \JHtmlSidebar::render();

		$Layout = Factory::getApplication()->input->get('layout');

		$this->addToolbar($Layout);

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
				. '* <br>'
				. '* YYYYY<br>'
				. '* <br>'
				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
				. '</span><br><br>';
		}

//		switch ($Layout)
//		{
//			case 'MaintConsolidateDb':

				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'), 'icon-database icon-checkbox-checked');

				ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');

                ToolBarHelper::custom('MaintConsolidateDb.createImageDbItems', 'database', '', 'COM_RSGALLERY2_CREATE_DATABASE_ENTRIES', true);
        		ToolBarHelper::custom('MaintConsolidateDb.createMissingImages', 'image', '', 'COM_RSGALLERY2_CREATE_MISSING_IMAGES', true);
        		ToolBarHelper::custom('MaintConsolidateDb.createWatermarkImages', 'scissors', '', 'COM_RSGALLERY2_CREATE_MISSING_WATERMARKS', true);
                ToolBarHelper::custom('MaintConsolidateDb.assignParentGallery', 'images', '', 'COM_RSGALLERY2_ASSIGN_SELECTED_GALLERY', true);
                ToolBarHelper::custom('MaintConsolidateDb.deleteRowItems', 'delete', '', 'COM_RSGALLERY2_DELETE_SUPERFLOUS_ITEMS', true);
                ToolBarHelper::custom('MaintConsolidateDb.repairAllIssuesItems', 'refresh', '', 'COM_RSGALLERY2_REPAIR_ALL_ISSUES', true);

//				break;
//
//			default:
//				// Set the title
//				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MANAGE_MAINTENANCE'), 'cogs'); // 'maintenance');
//				ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');
//				// ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');
//				break;
//		}
//

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2'))
		{
			$toolbar->preferences('com_rsgallery2');
		}
	}

	/**
	public function getModel($name = '', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
	/**/

}

