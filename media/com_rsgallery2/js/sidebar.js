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
/**/
//declare var joomla: Joomla;
// //const joomla = window.Joomla || {};
// const joomla:Joomla = window.Joomla || {};
// Joomla form token
var Token;
//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (event) {
    /**/
    function onToggleSidebar(ev) {
        let element = ev.target;
        // ToDo: In Rsgallery2Helper->addSubmenu exchange '<span class="sidebar-item-title">' with different class
        //       Find by class, change class to hide  or ...
        let liElements = element.querySelector('li');
        alert('liElements: ' + liElements.length);
        for (let idx = 0; idx < liElements.length; idx++) {
            // ToDO: Change class to hide  or ... ==> sidebar.ts
        }
    }
    //--- sidebar toggle element -------------------------------------------
    let toggle_sidebar = document.getElementById('rsg2_toggle_sidebar');
    toggle_sidebar.onclick = (ev) => onToggleSidebar(ev);
});
