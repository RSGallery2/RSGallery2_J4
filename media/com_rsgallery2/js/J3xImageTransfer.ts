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



// function onSelectionChange(target: EventTarget) {
//     let selection = <HTMLInputElement>target;
//     this.markImagesBySelection (selection.value);
//}

function markbyGallerySelection(doCheck: boolean) {

    let selectGallery = <HTMLInputElement> document.getElementById('SelectGallery');
    let galleryId = selectGallery.value;

    this.markImagesBySelection (galleryId, doCheck);

}



// Required gallery ID
function markImagesBySelection (reqGalleryId: string, doCheck: boolean) {

    // needs ref gallery refstate=active/deactive)
    // j3x_rows: HTMLTableRowElement []; // = [];

    let j3x_rows: HTMLElement [];
    let checkbox: HTMLInputElement;

    // let j3x_rows: HTMLElement [] = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row")));
    j3x_rows = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row[]"));

    //j3x_images
    j3x_rows.forEach ((j3x_row) => {
        let isMerged = j3x_row.getAttribute("isMerged");

        if (!isMerged) {

            let galleryId = j3x_row.getAttribute("galleryId");

            checkbox = <HTMLInputElement>j3x_row.querySelector('input[type="checkbox"]');

            if (reqGalleryId == galleryId) {

                // Assign if necessary
                if (checkbox.checked != doCheck) {
                    checkbox.checked = doCheck;
                }
            }
        }
    });

}


// Required gallery ID
function markImages_nGalleryTimes (maxGalleries: number) {

    // needs ref gallery refstate=active/deactive)
    // j3x_rows: HTMLTableRowElement []; // = [];

    let j3x_rows: HTMLElement [];
    let checkbox: HTMLInputElement;
    let galleryId: string;
    let doCheck: boolean = true;

    let galleries: string[] = [];

    // let j3x_rows: HTMLElement [] = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row")));
    j3x_rows = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row[]"));

    //j3x_images
    j3x_rows.forEach ((j3x_row) => {
        let isMerged = j3x_row.getAttribute("isMerged");

        // count not merged galleries
        if (!isMerged) {

            galleryId = j3x_row.getAttribute("galleryId");
            checkbox = <HTMLInputElement>j3x_row.querySelector('input[type="checkbox"]');

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



//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function(event) {

//    selectGallery : HTMLInputElement;

    // buttonPresetNextGallery : HTMLAnchorElement;
    // fileInput : HTMLButtonElement;

    // let selectGallery = <HTMLInputElement> document.getElementById('SelectGallery');
    // selectGallery.onclick = (ev) => onSelectionChange (ev.target);

    //
    let btnSelectGalleryFiles = <HTMLButtonElement> document.getElementById('selectGallery');
    btnSelectGalleryFiles.onclick = (ev) => markbyGallerySelection (true);
    let btnDeSelectGalleryFiles = <HTMLButtonElement> document.getElementById('deSelectGallery');
    btnDeSelectGalleryFiles.onclick = (ev) => markbyGallerySelection (false);

    let btnSelectNextGalleryFiles = <HTMLButtonElement> document.getElementById('selectNextGallery');
    btnSelectNextGalleryFiles.onclick = (ev) => markImages_nGalleryTimes (1);
    let btnSelectNext10GalleryFiles = <HTMLButtonElement> document.getElementById('selectNextGalleries10');
    btnSelectNext10GalleryFiles.onclick = (ev) => markImages_nGalleryTimes (2);
    let btnSelectNext100GalleryFiles = <HTMLButtonElement> document.getElementById('selectNextGalleries100');
    btnSelectNext100GalleryFiles.onclick = (ev) => markImages_nGalleryTimes (100);



//    buttonSetNextGalleryFiles.onclick = (ev) => onSelectionChange (ev.target);
    //let fileInput = <HTMLInputElement> document.querySelector('#config_file');

    // buttonManualFiles.onclick = () => {
    //     alert ("buttonManualFiles.onclick href:" + buttonManualFiles.getAttribute('href'));
    //     //fileInput.click();
    //     //joomla.submitbutton(buttonManualFiles.getAttribute('href'));
    //
    // }

//    selectGallery.onchange = (ev) => this.onSelectionChange(ev.target);


});
