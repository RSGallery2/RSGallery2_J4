/**
 * @package     RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage  com_rsgallery2
 * @copyright (c) 2022-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       5.0.0.4
 */


// ToDo: check https://stackoverflow.com/questions/21247278/about-d-ts-in-typescript

/*----------------------------------------------------------------
   Joomla interface created by ericfernance on 27/11/2015
   (with rsg2 additions found in internet)
----------------------------------------------------------------*/

/**/
interface Joomla {
    JText: {
        _(String)
    }

    // submitbutton: any;
    submitbutton (task: string, formSelector: string, validate: boolean|undefined|null) : void;
    submitform (task, form: HTMLElement|undefined|null, validate: boolean|undefined|null) : void;

    isChecked (isitchecked: boolean, form: string | undefined): boolean;
    checkAll (elem: HTMLElement): void ;
}

/**/

//declare var joomla: Joomla;

//const joomla = window.Joomla || {};
//const joomla: Joomla = window.Joomla || {};
//const joomla: Joomla = ((Joomla) window.Joomla) || {};

// declare global {
//     interface Window { Joomla: any; }
// }
//
// window.Joomla = window.Joomla || {};
//


// ToDo: fill out for joomla
