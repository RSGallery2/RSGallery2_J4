/**
 * @package     RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage  com_rsgallery2
 * @copyright (c) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       5.0.0.4
 */

// ToDo: check https://stackoverflow.com/questions/21247278/about-d-ts-in-typescript

/*----------------------------------------------------------------
   Joomla interface created by ericfernance on 27/11/2015
   (with rsg2 additions found in internet)
----------------------------------------------------------------*/

/**
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

// import {Joomla} from "./dbTransferJ3xImages";


// Only define the Joomla namespace if not defined.
//window.Joomla = window.Joomla || {};
//Window.Joomla = Window.Joomla || {};
// https://stackoverflow.com/questions/12709074/how-do-you-explicitly-set-a-new-property-on-window-in-typescript
(<any>Window).Joomla = (<any>Window).Joomla || {}


//declare var joomla: Joomla;
//
// //const joomla = window.Joomla || {};
// //const joomla: Joomla = window.Joomla || {};
// //const joomla: Joomla = ((Joomla) window.Joomla) || {};
//
// declare global {
//     interface Window { Joomla: any; }
// }
//

// Window.Joomla = Window.Joomla || {};

/*----------------------------------------------------------------

----------------------------------------------------------------*/

// Required gallery ID
function markImages_nGalleryTimes(maxGalleries: number) {

    // needs ref gallery refstate=active/deactivate)
    // j3x_rows: HTMLTableRowElement []; // = [];

    let j3x_rows: HTMLElement [];
    let j3x_form: HTMLFormElement;
    let gallery_checkbox: HTMLInputElement;
    let boxchecked: HTMLInputElement;
    let galleryId: string;
    const doCheck: boolean = true;

    let galleries: string[] = [];

    // let j3x_rows: HTMLElement [] = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row")));
    //j3x_rows = <HTMLElement []>Array.from(document.getElementsByName("j3x_gal_row[]"));
    j3x_rows = <HTMLElement []>Array.from(document.getElementsByName("j3x_gal_row"));

    // all gallery rows

    j3x_form = <HTMLFormElement> document.getElementById('adminForm');
    // boxchecked = <HTMLInputElement>j3x_form.querySelector('input[name="boxchecked"]');
    boxchecked = <HTMLInputElement> document.getElementsByName('boxchecked')[0];

    j3x_rows.forEach ((j3x_row) => {

        // within range ?
        if (galleries.length < maxGalleries) {

            // enable not merged galleries

            let isMerged = j3x_row.hasAttribute("is_merged");
            if ( ! isMerged) {

                galleryId = j3x_row.getAttribute("gallery_id");
                gallery_checkbox = <HTMLInputElement>j3x_row.querySelector('input[type="checkbox"]');

                // Assign checked if not already done
                if (gallery_checkbox.checked != doCheck) {

//                    alert ("01");

                    if (!galleries.includes(galleryId)) {

                        gallery_checkbox.checked = doCheck;

                        boxchecked.value = doCheck ?
                            (parseInt(boxchecked.value, 10) + 1).toString() :
                            (parseInt(boxchecked.value, 10) - 1).toString();
                        boxchecked.dispatchEvent(new CustomEvent('change', {
                            bubbles: true,
                            cancelable: true
                        }));


                        // if (typeof gallery_checkbox.onclick == "function") {
                        //
                        //     alert ("02");
                        //
                        //     // return $options.onClick && $options.onClick.apply($options, arguments);
                        //     // Joomla.isChecked(isChecked, this.tableEl.id);
                        //
                        //     // gallery_checkbox.onclick.apply(gallery_checkbox);
                        //     // gallery_checkbox.click ();
                        //     var event: MouseEvent = new (<any>MouseEvent)('click',
                        //         { 'view': window,
                        //             'bubbles': true,
                        //             'cancelable': true,
                        //             'target': gallery_checkbox});
                        //     gallery_checkbox.onclick (event);
                        //
                        //     // alert ("03");
                        //     //
                        //     // // onclick="Joomla.isChecked(this.checked);
                        //     // Window.Joomla.isChecked(gallery_checkbox.checked);
                        // }

                        // add to enabled list
                        galleries.push(galleryId);
                    }

                }
            }
        }

    }); // foreach function

}

/*----------------------------------------------------------------
   Pointer to used html elements on form
----------------------------------------------------------------*/


class FormElements {
    btnSelectNextGallery: HTMLButtonElement;
    btnSelectNextGalleries10: HTMLButtonElement;
    btnSelectNextGalleries100: HTMLButtonElement;

    // : HTMLElement;
    // select eElements on form
    constructor() {
        //--- Select next buttons ----------------------------------------------------

        this.btnSelectNextGallery = <HTMLButtonElement>document.getElementById('selectNextGallery');
        this.btnSelectNextGalleries10 = <HTMLButtonElement>document.getElementById('selectNextGalleries10');
        this.btnSelectNextGalleries100 = <HTMLButtonElement>document.getElementById('selectNextGalleries100');

        this.btnSelectNextGallery.onclick = () => markImages_nGalleryTimes(1);
        this.btnSelectNextGalleries10.onclick = () => markImages_nGalleryTimes(10);
        this.btnSelectNextGalleries100.onclick = () => markImages_nGalleryTimes(100);

    }

} // Form Elements

//---  -----------------------------------------------------------------------------------


//======================================================================================
// On start:  DOM is loaded and ready
//======================================================================================

document.addEventListener("DOMContentLoaded", function (event) {

    // collect html elements
    let elements = new FormElements();

});
