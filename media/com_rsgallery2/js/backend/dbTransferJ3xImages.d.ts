/**
 * @package    RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage com_rsgallery2
 * @copyright  (c) 2022-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * @author      finnern
 * @since       5.0.0.4
 */

export {};

// ToDo: check https://stackoverflow.com/questions/21247278/about-d-ts-in-typescript

/* eslint-disable no-var */

// interface customWindow extends Window {
//   customProperty?: any;
// }
//
// declare const window: customWindow;
//
// /* ... */
//
// window.customProperty


/*----------------------------------------------------------------
   Joomla interface created by ericfernance on 27/11/2015
   (with rsg2 additions found in internet)
----------------------------------------------------------------*/

/**/
export interface Joomla {
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
/**
declare global {
    interface Window { MyNamespace: any; }
}

window.MyNamespace = window.MyNamespace || {};
/**/

declare global {
    interface Window {
        Joomla: any;
    }
}

