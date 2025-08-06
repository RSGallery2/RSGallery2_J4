/**
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 * @copyright  (c) 2024-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * @author      finnern
 */

/*
 * bs_set2dark.ts
 * supports maintenance user confirm messages
 * @since       5.0.21
*/

//======================================================================================
// On dark mode exchange class '...-light' with '...-dark'
//======================================================================================

// On start:  DOM is loaded and ready
//======================================================================================

document.addEventListener("DOMContentLoaded", function (event) {

    // On dark mode exchange class '...-light' with '...-dark'
    bootstrapSet2DarkMode();
});

/**
 * On dark mode exchange class '...-light' with '...-dark'
 */
 function bootstrapSet2DarkMode() {

    // alert ("Starts bootstrapSet2DarkMode");

    // dark mode ?
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {

        // alert("Dark");

        //--- table -------------------------------------------

        // $(ele).toggleClass('bg-light bg-dar')
        const lightTableElements = document.querySelectorAll('.table-light');

        // Change the text of multiple elements with a loop
        lightTableElements.forEach(element => {
            element.classList.remove('table-light');
            element.classList.add('table-dark');
        });

        //--- text -------------------------------------------

        // $(ele).toggleClass('text-light text-dark')
        const lightBgElements = document.querySelectorAll('.bg-light');

        // Change the text of multiple elements with a loop
        lightBgElements.forEach(element => {
            element.classList.remove('bg-light');
            element.classList.add('bg-dark');
        });

        //--- navbar -------------------------------------------

        // // $(ele).toggleClass('navbar-light navbar-dark')
        // const lightNavbarElements = document.querySelectorAll('.navbar-light');
        //
        // // Change the text of multiple elements with a loop
        // lightNavbarElements.forEach(element => {
        //     element.classList.remove('.navbar-light');
        //     element.classList.add('.navbar-dark');
        // });

    }

} // bootstrapSet2DarkMode