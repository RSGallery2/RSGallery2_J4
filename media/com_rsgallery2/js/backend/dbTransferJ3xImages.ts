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

// https://stackoverflow.com/questions/12709074/how-do-you-explicitly-set-a-new-property-on-window-in-typescript


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

Window.Joomla = Window.Joomla || {};
//

/*----------------------------------------------------------------

----------------------------------------------------------------*/

// Required gallery ID
function markImages_nGalleryTimes(maxGalleries: number) {

    // needs ref gallery refstate=active/deactivate)
    // j3x_rows: HTMLTableRowElement []; // = [];

    let j3x_rows: HTMLElement [];
    let checkbox: HTMLInputElement;
    let galleryId: string;
    const doCheck: boolean = true;

    let galleries: string[] = [];

    // let j3x_rows: HTMLElement [] = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row")));
    j3x_rows = <HTMLElement []>Array.from(document.getElementsByName("j3x_gal_row[]"));

    //j3x_images
    j3x_rows.forEach ((j3x_row) => {
        let isMerged = j3x_row.getAttribute("isMerged");

        // count not merged galleries
        if (!isMerged) {

            galleryId = j3x_row.getAttribute("galleryId");
            checkbox = <HTMLInputElement> j3x_row.querySelector('input[type="checkbox"]');

            // Assign if necessary
            if (checkbox.checked != doCheck) {

                // within range ? add to enabled list
                if (galleries.length < maxGalleries) {
                    if (!galleries.includes(galleryId)) {
                        galleries.push(galleryId);
                    }
                }

                // Mark if gallery is in range
                if (galleries.includes(galleryId)) {
                    checkbox.checked = doCheck;
                }
            }
        }
    });

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
