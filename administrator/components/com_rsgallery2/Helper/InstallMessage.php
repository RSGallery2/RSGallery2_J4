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
use Joomla\Component\Rsgallery2\Administrator\Model\ChangeLogModel;
use JUri;

defined('_JEXEC') or die();

class InstallMessage
{

    protected $linksHtml = '';
    public $newRelease =  '-1.0.0.1';
    public $oldRelease =  '-1.0.0.1';

    /**
     * InstallMessage constructor.
     * @param string $newRelease
     * @param string $oldRelease
     *
     * @since version
     */
    public function __construct($newRelease, $oldRelease = '-1.0.0.2')
    {
        $this->newRelease = $newRelease;
        $this->oldRelease = $oldRelease;

        $this->linksHtml = $this->createLinksHtml();
    }

    /**
     * @param string $upgradeId
     *
     * @return string
     *
     * @since version
     */
    public function installMessageText ($upgradeId = '') {

        $instMessage = "";

        $instMessage .= $this->linksHtml;

        if ($upgradeId == 'update') {

            $instMessage .= $this->changelogCss ();
            $instMessage .= $this->ChangeLogHtml();
        }

        return $instMessage;
    }

    /**
     * Base construct containing logo and links to config-, control-, galleries page
     *      *
     * @param $type 'install' / 'update'
     *
     *
     * @since version
     */
    private function createLinksHtml ($type='')
    {
        //--- preprae lings and text in variables --------------------------------------------

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

        //--- html outpu --------------------------------------------

        $html =<<<EOT
                <div class="alert alert-success" style="text-align:center;">
                    <strong>RSGallery2 $this->newRelease was installed successfully</strong>
                </div>
            <div class="hero-unit">
				<div class="text-center">
	                <img src="$logoLink" alt="RSGallery2 Logo" height="150px" />
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
            <br />
            <br />
EOT;

        return $html;
    }

    /**
     * Fetch changelog and provide a table in collapsible
     *
     * @return string
     *
     * @since version
     */
    private function ChangeLogHtml () {

        $changeLogText = '';

        try {
            if (!empty ($this->oldRelease))
            {
                //--- fetch changelog and create a table each -----------------------------

                $jsonChangelogs = ChangeLogModel::changeLogElements($this->oldRelease);
                // Array: Html table each log item
                $changelogTables= ChangeLogModel::changeLogsData2Html ($jsonChangelogs);

                //--- enclose by collapsible ----------------------------------------------

                $title = Text::_('COM_RSGALLERY2_CHANGELOG');
                $id = 'rsg2_changelog';
                $collapsed = false;
                // Cord display collapser or not
                $changeLogText = ChangeLogModel::collapseContent ($title, $changelogTables, $id, $collapsed);
            }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error in InstallMessage view: "' . 'ChangeLogHtml' . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $changeLogText;
    }

    /**
     * style for installation message
     *
     * @return string
     *
     * @since version
     */
    private function changelogCss () {

        $html =<<<EOT
            <style>
            /* ToDo: More specific add dictionaries with class= gallery/images ... */
            .table caption {
                caption-side: top;
              white-space: nowrap;
            }
            
            .changelog_area {
                        display: flex;
                        flex-direction: row;
              justify-content: flex-start;
            }
            
            .changelog_key {
                        min-width: 100px;
              border-right: 2px solid red;
            }
            
            .changelog_value_area {
                        display: flex;
                        flex-direction: column;
              flex-wrap: wrap;
            }
            
            .change-log-caption {
                        color: black;
                    }
            
            .change-log-table {
                        border-bottom: 2px solid black;
            }
            
            .card-header .fa {
                        transition: 0.3s transform ease-in-out;
            }
            
            .card-header .collapsed .fa {
                        transform: rotate(-90deg);
            }
            </style>
EOT;

        return $html;
    }

} // class
