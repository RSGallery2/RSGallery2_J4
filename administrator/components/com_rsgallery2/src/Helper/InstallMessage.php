<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ChangeLogModel;

\defined('_JEXEC') or die;

/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Helper
 *
 * @since       __BUMP_VERSION__
 */
class InstallMessage
{
    /**
     * @var string
     * @since 5.1.0     */
    protected $linksHtml = '';
    public $newRelease = '-1.0.0.1';
    public $oldRelease = '';

    /**
     * InstallMessage constructor.
     *
     * @param   string  $newRelease
     * @param   string  $oldRelease
     *
     * @since   5.1.0     */
    public function __construct($newRelease, $oldRelease = '')
    {
        $this->newRelease = $newRelease;
        $this->oldRelease = $oldRelease;

        $this->linksHtml = $this->createLinksHtml($this->newRelease);
    }

    /**
     * Add part with standard icon and buttons together with changelog information
     * On a 'second' installation the changelog will be displayed
     *
     * @param   string  $updateId  tells with 'upgrade' that it is not the first installation
     *
     * @return string
     *
     * @since  5.1.0     */
    public function installMessageText($updateId = '')
    {
        $instMessage = "";

        $instMessage .= $this->linksHtml;

        if ($updateId == 'update') {
            $instMessage .= $this->changeLogHtml();
        } else {
            //
            // $instMessage .= "ToDo: Welcome to first use of RSGallery2 ....."; // ? state , possible functions
        }

        return $instMessage;
    }

    /**
     * Base construct containing logo and links to config-, control-, galleries page
     *
     * @return string html of loo and buttons
     *
     * @since  5.1.0     */
    public static function createLinksHtml($newRelease)
    {
        //--- prepare links and text in variables --------------------------------------------

        $logoLink = URI::root() . '/media/com_rsgallery2/images/RSG2_logoText.svg';

        $rsg2ControlPanelLink = URI::root() . '/administrator/index.php?option=com_rsgallery2';
        $controlPanelText     = Text::_('COM_RSGALLERY2_MENU_CONTROL_PANEL');
        $controlPanelTitle    = Text::_('COM_RSGALLERY2_INSTALL_GOTO_CONTROL_PANEL_TITLE');

        $rsg2ConfigurationLink = URI::root() 
			. '/administrator/index.php?option=com_config&view=component&component=com_rsgallery2';
        $configurationText     = Text::_('COM_RSGALLERY2_MENU_CONFIG');
        $configurationTitle    = Text::_('COM_RSGALLERY2_INSTALL_GOTO_CONFIGURATION_TITLE');

        $rsg2GalleriesLink = URI::root() . '/administrator/index.php?option=com_rsgallery2&view=galleries';
        $galleriesText     = Text::_('COM_RSGALLERY2_MENU_GALLERIES');
        $galleriesTitle    = Text::_('COM_RSGALLERY2_INSTALL_GOTO_GALLERIES_TITLE');

        //--- html output --------------------------------------------

        $html = <<<EOT
                        <div class="alert alert-success" style="text-align:center;">
                            <strong>RSGallery2 $newRelease was installed successfully</strong>
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
     * Fetch changelog and provide version information in collapsible cards
     *
     * @return string html card containing tables for each version
     *
     * @throws \Exception
     * @since  5.1.0     */
    private function changeLogHtml()
    {
        $changeLogText = '';

        try {
            // ToDo: Save old when not same and use in new class ...

            // fallback: Since J!4
            if (empty ($this->oldRelease)) {
                $this->oldRelease = '5.0.0.1';
            }

            // Installed same a second time show all
            // ToDo: fetch previous installed version
            if ($this->oldRelease == $this->newRelease) {
                $this->oldRelease = '5.0.0.1';
            }

            //--- fetch changelog and create html tables each -----------------------------

            $ChangeLogModel = new ChangeLogModel ();
            $jsonChangelogs = $ChangeLogModel->changeLogElements($this->oldRelease, $this->newRelease);
            // Array: Html table each log item
            $changelogTables = $ChangeLogModel->changeLogsData2Html($jsonChangelogs);

            //--- enclose by collapsible ----------------------------------------------

            $id        = 'rsg2_changelog';
            $collapsed = false;
            // Cord display collapsed or not
            $changeLogText = ChangeLogModel::collapseContent($changelogTables, $id, $collapsed);
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error in InstallMessage view: "' . 'ChangeLogHtml' . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } catch (\Exception $e) {
        }

        return $changeLogText;
    }

} // class
