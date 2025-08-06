/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

/*----------------------------------------------------------------
   Joomla interface created by ericfernance on 27/11/2015
   (with rsg2 additions found in internet)
 *
 * supports maintenance user confirm messages
 *
   @since       5.0.0.4
----------------------------------------------------------------*/

/**/
interface Joomla {
    JText: {
        _(String)
    }
    // submitbutton: any;
    // submitform: any;
}

/**/

//declare var joomla: Joomla;

// //const joomla = window.Joomla || {};
// const joomla:Joomla = window.Joomla || {};

// Joomla form token
var Token: string;

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
