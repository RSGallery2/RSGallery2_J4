/**
 * @package     RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       5.0.0.4
 */
/**/
//declare var joomla: Joomla;
//const joomla = window.Joomla || {};
const joomla = window.Joomla || {};
// Joomla form token
var Token;
//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (event) {
    joomla.submitbutton = function (buttonName) {
        let confirmMessage = '';
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
        }
        else {
            // confirmation requested
            if (confirm(confirmMessage)) {
                joomla.submitform(pressbutton);
            }
        }
        return true;
    };
});
