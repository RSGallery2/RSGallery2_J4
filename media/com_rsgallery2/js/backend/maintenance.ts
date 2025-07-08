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
    Text: {
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


    joomla.submitbutton = function (task) {

        // alert('task: ' + JSON.stringify(task));

        //--- confirmation message --------------------------------

        let confirmMessage: string;

        // assign text to confirm
        switch (task) {
            case 'MaintenanceCleanUp.purgeImagesAndData':
                // eslint-disable-next-line no-restricted-globals
                // confirmMessage = joomla.JText._('COM_ASSOCIATIONS_PURGE_CONFIRM_PROMPT');
                confirmMessage = joomla.Text._('COM_RSGALLERY2_CONFIRM_PURGE_TABLES_DEL_IMAGES');
                break;
            case 'MaintenanceCleanUp.prepareRemoveTables':
            case 'Galleries.reinitNestedGalleryTable':
                confirmMessage = joomla.Text._('COM_RSGALLERY2_CONFIRM_CONSIDER_BACKUP_OR_CONTINUE');
                break;

            case 'MaintenanceCleanUp.ResetConfigToDefault':
                confirmMessage = joomla.Text._('COM_RSGALLERY2_CONFIRM_RESET_CONFIG_DEFAULT');
                break;

            case 'config.importConfigFile':
                confirmMessage = joomla.Text._('COM_RSGALLERY2_CONFIRM_IMPORT_CONFIG_FILE');
                break;

            case 'Galleries.reinitNestedGalleryTable':
                confirmMessage = joomla.Text._('COM_RSGALLERY2_CONFIRM_REINIT_GALLERIES');
                break;

            case 'Images.reinitImagesTable':
                confirmMessage = joomla.Text._('COM_RSGALLERY2_CONFIRM_REINIT_IMAGES');
                break;

            default:
                confirmMessage = "";
                // // test
                // confirmMessage = joomla.Text._('COM_ASSOCIATIONS_PURGE_CONFIRM_PROMPT');
                // alert('confirmMessage: ' + JSON.stringify(confirmMessage));
                break;
        }

        //--- issue task --------------------------------

        // Task without further confirmation
        if (confirmMessage == '') {
            joomla.submitform(task);
        } else {
            // confirmation requested
            if (confirm(confirmMessage)) {
                alert('submitform: ');
                joomla.submitform(task);
            } else {
                // user cancel
                return false;
            }
        }

        return true;
    }

    // Joomla.submitbutton = function (task) {
    //     if (task == 'item.cancel') {
    //         Joomla.submitform(task, document.getElementById('save'));
    //     } else {
    //         if (task != 'item.cancel' && document.formvalidator.isValid(document.id('save'))) {
    //             Joomla.submitform(task, document.getElementById('save'));
    //         } else {
    //             alert('<?php echo $this->escape(JText::_('
    //             JGLOBAL_VALIDATION_FORM_FAILED
    //             ')); ?>'
    //         )
    //             ;
    //         }
    //     }
    // }

}) // addEventListener

