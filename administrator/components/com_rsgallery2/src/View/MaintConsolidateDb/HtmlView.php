<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\MaintConsolidateDb;

\defined('_JEXEC') or die;

//use \Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageReferences;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel;


/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    protected $form;
    /**
     * The sidebar markup
     *
     * @var  string
     */
    protected $sidebar;

    // ToDo: Use other rights instead of core.admin -> IsRoot ?
    // core.admin is the permission used to control access to
    // the global config
    protected $UserIsRoot;

    /**
     * @var ImageReferences
     */
    protected $oImgRefs;

    protected $IsAnyDbRefMissing; // header

    protected $isDebugBackend;
	protected $isDevelop;
	protected $hasJ3xFile;
	protected $hasJ4xFile; // J4x ++

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
        $this->isDevelop      = $rsgConfig->get('isDevelop');

	    //------------------------------------------
	    // image file data
	    //------------------------------------------

		// use Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('MaintConsolidateDB', 'rsgallery2Model');
        $ConsolidateModel = $this->getModel();

        // contains lost and found items
        $this->oImgRefs = $ConsolidateModel->GetImageReferences();

	    $this->hasJ3xFile = $this->oImgRefs->hasJ3xFile();
	    $this->hasJ4xFile = $this->oImgRefs->hasJ4xFile();

	    //--- form ------------------------------------------

	    // Factory::getContainer()->get(FormFactoryInterface::class)->createForm($name, $options);
		$xmlFile    = JPATH_BASE . '/components/com_rsgallery2' . '/forms/maintConsolidateDB.xml';

		$this->form = Form::getInstance('maintConsolidateDB', $xmlFile);

        $this->isJ3xRsg2DataExisting = J3xExistModel::J3xConfigTableExist();

        //--- Check user rights ---------------------------------------------

        // ToDo: More detailed for rsgallery admin
        $app = Factory::getApplication();

        //$user = $app->getIdentity();
        $user             = $this->getCurrentUser();
        $canAdmin         = $user->authorise('core.admin');
        $this->UserIsRoot = $canAdmin;

        //--- display settings ----------------------------------------------

        HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=maintenance');
        Rsgallery2Helper::addSubmenu('maintenance');
        $this->sidebar = Sidebar::render();

        $Layout = Factory::getApplication()->input->get('layout');

        $this->addToolbar($Layout);

        parent::display($tpl);
        return;
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
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . 'Tasks: <br>'
                . '* No Function for: createImageDbItems<br>'
                . '* No Function for: createMissingImages<br>'
                . '* No Function for: createWatermarkImages<br>'
                . '* No Function for: assignParentGallery<br>'
                . '* No Function for: deleteRowItems<br>'
                . '* No Function for: repairAllIssuesItems<br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
                . '</span><br><br>';
        }

//		switch ($Layout)
//		{
//			case 'MaintConsolidateDb':

        ToolBarHelper::title(
            Text::_('COM_RSGALLERY2_MAINT_CONSOLIDATE_IMAGE_DATABASE'),
            'icon-database icon-checkbox-checked',
        );

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
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2')) {
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

