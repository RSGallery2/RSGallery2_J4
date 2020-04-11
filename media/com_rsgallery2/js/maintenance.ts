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

//    buttonManualFiles : HTMLButtonElement;
//    buttonZipFile : HTMLButtonElement;
//    buttonFolderImport : HTMLButtonElement;

//    this.buttonManualFiles = <HTMLButtonElement> document.querySelector('#select-file-button-drop');
//    this.buttonZipFile = <HTMLButtonElement> document.querySelector('#select-zip-file-button-drop');
//    this.buttonFolderImport = <HTMLButtonElement> document.querySelector('#ftp-upload-folder-button-drop');


    this.buttonManualFiles.onclick = () => joomla.submitbutton('yyyy');;
    this.buttonZipFile.onclick = () =>  fileZip.click();
    this.buttonFolderImport.onclick = (ev: DragEvent) => this.onImportFolder(ev);

//    <button id="applyBtn" type="button" class="hidden" onclick="Joomla.submitbutton('plugin.apply');"></button>


});
