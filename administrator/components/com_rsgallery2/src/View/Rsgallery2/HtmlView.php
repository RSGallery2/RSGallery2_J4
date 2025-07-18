<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Rsgallery2;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\CreditsEnumeration;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\CreditsExternal;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Version;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ChangeLogModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleriesModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagesModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\J3xExistModel;


//use Joomla\CMS\Helper\ContentHelper;
//use Joomla\CMS\Factory;

/**
 * View class for a list of rsgallery2.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    protected $buttons = [];

    /**
     * The sidebar markup
     *
     * @var  string
     */
    protected $sidebar;

    protected $Rsg2Version;

    protected $lastGalleries;
    protected $lastImages;

    protected $changelogs;

    protected $credits;

    protected $externalLicenses;

    protected $isDebugBackend;
    protected $isDevelop;
    protected $isConfigSavedOnce;

    protected $isJ3xDataExisting;

    protected $isDoCopyJ3xDbConfig;
    protected $isDoCopyJ3xDbGalleries;
    protected $isDoCopyJ3xDbImages;

	protected $isDoChangeJ3xMenuLinks;
	protected $isDoChangeGidMenuLinks;
    protected $isDoCopyJ3xImages;

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
        $this->buttons = $this->getRsg2ControlButtons();

        //--- config --------------------------------------------------------------------

        // $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $rsgConfig = ComponentHelper::getParams('com_rsgallery2');

        /*-------------------------------------------------------------------------------
        first run checks
        -------------------------------------------------------------------------------*/

        //--- auto save config after install ------------------------------

        // Is configuration not initialized ?
        if (empty ($rsgConfig->get('image_size'))) {
            // configuration must be saved once to be initialized
            $configRawModel = new ConfigRawModel ();
            $isSaved        = $configRawModel->ResetConfigToDefault();

            // attention: configuration is not updated for this run
            // $rsgConfig = ComponentHelper::getParams('com_rsgallery2');
        }

        //--- Check for J3x parts ------------------------------

        $this->isJ3xDataExisting = J3xExistModel::J3xConfigTableExist();

        // activate flags for debug
	    // $this->isJ3xDataExisting = true;

        if ($this->isJ3xDataExisting) {

			// ToDo: use form data see $form->setValue('dbcopyj3xconfiguser', null, $rsgConfig->get('j3x_db_config_copied'));

			// which transfer actions are needed ?
            $this->isDoCopyJ3xDbConfig    = !$rsgConfig->get('j3x_db_config_copied');
            $this->isDoCopyJ3xDbGalleries = !$rsgConfig->get('j3x_db_galleries_copied');
            $this->isDoCopyJ3xDbImages    = !$rsgConfig->get('j3x_db_images_copied');
            $this->isDoChangeJ3xMenuLinks = !$rsgConfig->get('j3x_menu_gid_increased');
            $this->isDoCopyJ3xImages      = !$rsgConfig->get('j3x_images_copied');

        }

	    // ToDo: use form data see $form->setValue('dbcopyj3xconfiguser', null, $rsgConfig->get('j3x_db_config_copied'));
	    $this->isDoChangeGidMenuLinks = !$rsgConfig->get('j3x_menu_gid_moved_to_id');

        /*-------------------------------------------------------------------------------
        Standard
        -------------------------------------------------------------------------------*/

        //$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $this->isDebugBackend = $rsgConfig->get('isDebugBackend');
        $this->isDevelop      = $rsgConfig->get('isDevelop');

        //---  --------------------------------------------------------------------

        $this->lastGalleries = GalleriesModel::latestGalleries(5);
        $this->lastImages    = ImagesModel::latestImages(5);

        //---  --------------------------------------------------------------------

        // Check for errors.
        /* Must load form before
        if (count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode("\n", $errors), 500);
        }
        /**/

        $oRsg2Version      = new rsgallery2Version();
        $this->Rsg2Version = $oRsg2Version->getShortVersion(); // getLongVersion, getVersion

        $ChangeLogModel = new ChangeLogModel ();
        // ToDo: add previous version
        $jsonChangelogs = $ChangeLogModel->changeLogElements();

        // echo "\$jsonChangelogs: " . json_encode($jsonChangelogs);
        
        // Array: Html table each log item
        $this->changelogs = $ChangeLogModel->changeLogsData2Html($jsonChangelogs);

        $this->credits = CreditsEnumeration::CreditsEnumerationText;

        $this->externalLicenses = CreditsExternal::CreditsExternalText;

        HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2');
        Rsgallery2Helper::addSubmenu('control');
        $this->sidebar = Sidebar::render();

        $this->addToolbar();

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
    protected function addToolbar()
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . '* Install: documentation<br>'
                . '* Use _CFG_ in ?variable? names<br>'
                . '* General: ts: separate script (library) for "easy" ajax calls<br>'
                . '* include workflow<br>'
                . '* Save CFG on deinstall / remove (? Query complete delete) ? maintenace save restore ?<br>'
                . '* Install: Fix autosave standard config <br>'
                . '* Table header width -> use class(es) <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
// May have to be checked again ?:	. '*  deprecated Factory::getApplication()->getIdentity() ==> $app->getIdentity()<br>'
//        Factory::getApplication()->getIdentity()
//	      $app  = Factory::getApplication();
//        $user = $app->getIdentity();
//		$user  = $this->getCurrentUser();

//				. '* <br>'
//				. '* <br>'
                . '</span><br><br>';
        }

        // Set the title
        ToolBarHelper::title(Text::_('COM_RSGALLERY2_SUBMENU_CONTROL_PANEL'), 'home-2');

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2')) {
            $toolbar->preferences('com_rsgallery2');
        }
    }

    private function getRsg2ControlButtons()
    {
        $buttons = [
            [

                //'link'   => Route::_('index.php?option=com_rsgallery2&view=config'),
                'link'   => Route::_('index.php?option=com_config&view=component&component=com_rsgallery2'),
                'image'  => 'fa fa-cog',
                'text'   => Text::_('COM_RSGALLERY2_MAIN_CONFIGURATION'),
                'access' => ['core.manage', 'com_rsgallery2', 'core.create', 'com_rsgallery2'],
                'group'  => '',
            ],
            [
                'link'   => Route::_('index.php?option=com_rsgallery2&view=galleries'),
                'image'  => 'fa fa-th', // fa fa-th
                'text'   => Text::_('COM_RSGALLERY2_MAIN_MANAGE_GALLERIES'),
                'access' => ['core.manage', 'com_media'],
                'group'  => '',
            ],
            [
                'link'   => Route::_('index.php?option=com_rsgallery2&view=upload'),
                'image'  => 'fa fa-upload',
                'text'   => Text::_('COM_RSGALLERY2_MAIN_UPLOAD'),
                'access' => ['core.manage', 'com_config', 'core.admin', 'com_config'],
                'group'  => '',
            ],
            [
                'link'   => Route::_('index.php?option=com_rsgallery2&view=images'),
                'image'  => 'fa fa-image',
                'text'   => Text::_('COM_RSGALLERY2_MAIN_MANAGE_IMAGES'),
                'access' => ['core.manage', 'com_config', 'core.admin', 'com_config'],
                'group'  => '',
            ],
            [
                'link'   => Route::_('index.php?option=com_rsgallery2&view=maintenance'),
                'image'  => 'fa fa-cogs', // gears
                'text'   => Text::_('COM_RSGALLERY2_MAIN_MAINTENANCE'),
                'access' => ['core.manage', 'com_modules'],
                'group'  => '',
            ],
        ];

        return $buttons;
    }

}
