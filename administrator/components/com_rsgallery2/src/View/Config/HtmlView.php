<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\View\Config;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\Rsgallery2Helper;

/**
 * View class for a list of rsgallery2.
 *
     * @since      5.1.0
 */
class HtmlView extends BaseHtmlView
{
    protected $configVars;
	protected bool $isDevelop;
	protected $sidebar;

    /**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  mixed  A string if successful, otherwise an \Exception object.
     *
     * @since   5.1.0     */
    public function display($tpl = null)
    {
        $Layout = Factory::getApplication()->input->get('layout');
        //echo '$Layout: ' . $Layout . '<br>';

        $rsgConfig       = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $this->isDevelop = $rsgConfig->get('isDevelop');

        $this->configVars = $rsgConfig;

        //---  --------------------------------------------------------------

        HTMLHelper::_('sidebar.setAction', 'index.php?option=com_rsgallery2&view=config&layout=RawView');
        /**/
        $Layout = Factory::getApplication()->input->get('layout');
        Rsgallery2Helper::addSubmenu('config');
        $this->sidebar = Sidebar::render();

        $this->addToolbar($Layout);

        parent::display($tpl);

        return;
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   5.1.0     */
    protected function addToolbar($Layout)
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . 'Tasks: <br>'
                . '* Secure user input <br>'
                . '* copy to file <br>'
                . '* copy to clipboard <br>'
                . '* RawView: dt dl dd definition on small width will overlap<br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
                . '</span><br><br>';
        }

        switch ($Layout) {
            case 'RawView':
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . Text::_('COM_RSGALLERY2_CONFIGURATION_RAW_VIEW'), 'screwdriver');
                ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');

                break;

            case 'RawEdit':
				ToolBarHelper::title(Text::_('COM_RSGALLERY2_MAINTENANCE')
					. ': ' . Text::_('COM_RSGALLERY2_CONFIGURATION_RAW_EDIT'), 'screwdriver');
                ToolBarHelper::apply('config.apply_rawEdit');
                ToolBarHelper::save('config.save_rawEdit');
                ToolBarHelper::cancel('config.cancel_rawEdit', 'JTOOLBAR_CLOSE');
                break;
            default:
                ToolBarHelper::cancel('config.cancel', 'JTOOLBAR_CLOSE');
                break;
        }

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2')) {
            $toolbar->preferences('com_rsgallery2');
        }
    }

}

