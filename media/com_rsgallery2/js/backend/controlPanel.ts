/**
 * @package    RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage com_rsgallery2
 * @copyright  (c) 2024-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * @author      finnern
 * @since       5.0.21
 */

// Only define the Joomla namespace if not defined.
//window.Joomla = window.Joomla || {};
//Window.Joomla = Window.Joomla || {};
// https://stackoverflow.com/questions/12709074/how-do-you-explicitly-set-a-new-property-on-window-in-typescript
// (<any>Window).Joomla = (<any>Window).Joomla || {}

function exchangeCssOnDarkMode(): void {
    // console.log('Hello!');

    alert ("Starts");
}






//======================================================================================
// On start:  DOM is loaded and ready
//======================================================================================

document.addEventListener("DOMContentLoaded", function (event) {

    exchangeCssOnDarkMode ();

});
