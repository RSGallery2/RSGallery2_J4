<?php
/**
 * @package    com_rsgallery2
 *
 * @author     RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2020-2020 RSGallery2 Team
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://www.rsgallery2.org
 */

// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

// required is used as classes may not be loaded on  fresh install
// !!! needed by install

// require_once(JPATH_ADMINISTRATOR . 'com_rsgallery2/helper/installMessage.php');
//require_once(JPATH_ADMINISTRATOR . 'com_rsgallery2/Model/Rsg2ExtensionModel.php');

/**
 * Tasks needed on new/update installation of RSG2
 *
 * @since version
 *
 */
class Rsg2InstallTasks
{
    // ToDo: logs

//    public $newRelease = '-1.0.0.1';
//    public $oldRelease = '';
    /**
     *  Read manifest data , insert config write back

     *
     * @since version
     */
	 // prepared, may not be used later
    static function initConfigFromXmlFile ()
    {

        // Read extension manifest data,

        // insert config

		echo '<br>!!! Rsg2InstallTasks !!!<br>';

        // write back Read extension manifest data

		// insert configuration standard values
		/**
		//$configModel = $this->getModel('ConfigRaw');
		$configModel = $this->getModel('ConfigRaw', 'Rsgallery2Model', array('ignore_request' => true))
		$isSaved = $configModel->ResetConfigToDefault();

		if ($isSaved) {
		// config saved message
		$msg .= '<br><br>' . Text::_('Configuration parameters resetted to default', true);
		}
		else
		{
		$msg .= "Error at resetting configuration to default'";
		$msgType = 'warning';
		}
		echo $msg;
		/**/

    }

} // class


