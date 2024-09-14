/**
 * @package    RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage com_rsgallery2
 * @copyright  (c) 2016-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * @author      finnern
 * @since       5.0.0.4
 */

/*----------------------------------------------------------------
   Joomla interface created by ericfernance on 27/11/2015
   (with rsg2 additions found in internet
----------------------------------------------------------------*/

/**/
interface Joomla {
    JText: {
        _(String)
    }
    submitbutton: any;
    submitform: any;
}

/**/

//declare var joomla: Joomla;

//const joomla = window.Joomla || {};
const joomla: Joomla = window.Joomla || {};

// Joomla form token
var Token: string;

//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function (event) {


    joomla.submitbutton = function (buttonName) {

        let confirmMessage: string = '';
        // ToDo: switch for several pressbutton s -> change text, on not empty text let confirm

        switch (buttonName) {
            case '':
                // eslint-disable-next-line no-restricted-globals
                confirmMessage = joomla.JText._('COM_ASSOCIATIONS_PURGE_CONFIRM_PROMPT');
                break;

            default:
                break;
        }

        // Task possible without further attention (confirmation)
        if (empty(confirmMessage)) {
            joomla.submitform(pressbutton);
        } else {
            // confirmation requested
            if (confirm(confirmMessage)) {
                joomla.submitform(pressbutton);
            }
        }

        return true;
    };


});
