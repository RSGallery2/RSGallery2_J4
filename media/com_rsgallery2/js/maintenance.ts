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
const joomla:Joomla = window.Joomla || {};

// Joomla form token
var Token:string;

//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function(event) {


    joomla.submitbutton = function (pressbutton) {

        let confirmMessage:string = '';
        // ToDo: switch for sveral pressbutton s _> change text, on not empty text let confirm

        if (pressbutton === 'associations.purge') {
            // eslint-disable-next-line no-restricted-globals
            if (confirm(joomla.JText._('COM_ASSOCIATIONS_PURGE_CONFIRM_PROMPT'))) {
                joomla.submitform(pressbutton);
            } else {
                return false;
            }
        } else {
            joomla.submitform(pressbutton);
        }

        return true;
    };

    buttonManualFiles : HTMLAnchorElement;
    fileInput : HTMLButtonElement;

    let buttonManualFiles = <HTMLButtonElement> document.querySelector('.ConfigRawReadFromFile');
    let fileInput = <HTMLInputElement> document.querySelector('#config_file');

    buttonManualFiles.onclick = () => {fileInput.click(); joomla.submitbutton(buttonManualFiles.getAttribute('href'));}

});
