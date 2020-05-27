<?php
/**
 * Hall of Fame in the RSGallery2 project
 * Credits: Historical list of people participating in the project
 *
 * @version       $Id: installMessage.php  2012-07-09 18:52:20Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003-2019 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

namespace Joomla\Component\Rsgallery2\Administrator\Helper;

use Joomla\CMS\Language\Text;
use JUri;

defined('_JEXEC') or die();

class InstallMessage
{

    protected $linksHtml = '';
    public $newRelease =  '-1.0.0.1';

/**
	public const installMessageText = <<<EOT
    !!! Da tut was !!!!
EOT;
/**/

    public function __construct()
    {

        $this->linksHtml = $this->createLinksHtml();

        $this->newRelease = '-1.0.0.2';

    }


    public function installMessageText () {

        $instMessage = "";

        $instMessage .= $this->linksHtml;


        return $instMessage;
    }

    /**
     *
     * @param $type 'install' / 'update'
     *
     *
     * @since version
     */
    private function createLinksHtml ($type='')
    {
        $logoLink = JURI::root() . '/media/com_rsgallery2/images/RSG2_logoText.svg';

        $rsg2ControlPanelLink = JURI::root() . '/administrator/index.php?option=com_rsgallery2';
        $controlPanelText = Text::_('COM_RSGALLERY2_MENU_CONTROL_PANEL');
        $controlPanelTitle = Text::_('COM_RSGALLERY2_INSTALL_GOTO_CONTROL_PANEL_TITLE');

        $rsg2ConfigurationLink = JURI::root() . '/administrator/index.php?option=com_config&view=component&component=com_rsgallery2';
        $configurationText = Text::_('COM_RSGALLERY2_MENU_CONFIG');
        $configurationTitle = Text::_('COM_RSGALLERY2_INSTALL_GOTO_CONFIGURATION_TITLE');

        $rsg2GalleriesLink = JURI::root() . '/administrator/index.php?option=com_rsgallery2&view=galleries';
        $galleriesText = Text::_('COM_RSGALLERY2_MENU_GALLERIES');
        $galleriesTitle = Text::_('COM_RSGALLERY2_INSTALL_GOTO_GALLERIES_TITLE');

        // height="100"  active
        $html =<<<EOT
            <div class="hero-unit">
				<div class="text-center">
	                <img src="$logoLink" alt="RSGallery2 Logo" height="150px" />
	            </div>
                <div class="alert alert-success">
                    <h3>RSGallery2 $this->newRelease was installed successfully.</h3>
                </div>
                <p></p>
				<div class="text-center">
					<div class="Xbtn-group">
						<a title="$controlPanelTitle" class="btn btn-warning" href="$rsg2ControlPanelLink">
							<div class="fa fa-home fa-fw" aria-hidden="true"></div>
							$controlPanelText
						</a>
						<a title="$configurationTitle" class="btn btn-info" href="$rsg2ConfigurationLink">
							<div class="fa fa-cog fa-fw" aria-hidden="true"></div>
							$configurationText
						</a>
						<a title="$galleriesTitle" class="btn btn-success" href="$rsg2GalleriesLink">
							<div class="fa fa-th fa-fw" aria-hidden="true"></div>
							$galleriesText
						</a>
					</div>
                </div>
            </div>
EOT;

        /**
        //--- changelog on -----------------------------------------------
        if ($type == 'update')
        {
            if (!empty ($this->oldRelease))
            {
                $jsonChangelogs = ChangeLogModel::changeLogElements($this->oldRelease);
                $changelogs = ChangeLogModel::changeLogsData2Html ($jsonChangelogs);

                $upgradeText = '';
                foreach ($changelogs as $htmlElements) {
                    $upgradeText .= $htmlElements;
                }
            }

            $html .= $upgradeText;
        }
        /**/

        return $html;

    }



}
