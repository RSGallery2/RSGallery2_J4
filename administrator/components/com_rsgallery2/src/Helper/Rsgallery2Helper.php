<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;

use function defined;

/**
 * Rsgallery2 component helper.
 *
 * @since __BUMP_VERSION__
 */
class Rsgallery2Helper extends ContentHelper
{
    /**
     * Configure the Linkbar.
     *
     * @param   string  $vName  The name of the active view.
     *
     * @return  void
     *
     * @since __BUMP_VERSION__
     */
    public static function addSubmenu($vName)
    {
        /**
         * if (ComponentHelper::isEnabled('com_fields') && ComponentHelper::getParams('com_rsgallery2')->get('custom_fields_enable', '1'))
         * {
         * \Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
         * Text::_('JGLOBAL_FIELDS'),
         * 'index.php?option=com_fields&context=com_rsgallery2.rsgallery2',
         * $vName == 'fields.fields'
         * );
         * \Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
         * Text::_('JGLOBAL_FIELD_GROUPS'),
         * 'index.php?option=com_fields&view=groups&context=com_rsgallery2.rsgallery2',
         * $vName == 'fields.groups'
         * );
         * }
         * /**/

//		echo "\$vname: $vName <br>";

//        //--- toggle element ------------------------------------------------
//
////        <span id="menu-collapse-icon" class="fas fa-fw fa-toggle-on" aria-hidden="true"></span>
//         \Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
//            '<span Id="rsg2_toggle_sidebar" class="fas fa-fw fa-toggle-on" ></span>&nbsp;',
//            "#",
//            false);
//
////        echo "<br>humpf<br>";
//
//        HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/sidebar.css', array('version' => 'auto', 'relative' => true));

        //--- standard form elements ----------------------------------------

        // '<span class="sidebar-item-title">' . Home Dashboard . '</span>'

        $link = 'index.php?option=com_rsgallery2&view=rsgallery2';
        Sidebar::addEntry(
            '<span class="icon-home-2" ></span>&nbsp;' .
            '<span class="sidebar-item-title">' . Text::_('COM_RSGALLERY2_MENU_CONTROL_PANEL') . '</span>',
            $link,
            $vName == 'control',
        );

        $link = 'index.php?option=com_rsgallery2&view=galleries';
        Sidebar::addEntry(
            '<span class="icon-images" ></span>&nbsp;' .
            '<span class="sidebar-item-title">' . Text::_('COM_RSGALLERY2_MENU_GALLERIES') . '</span>',
            $link,
            $vName == 'galleries',
        );

        $link = 'index.php?option=com_rsgallery2&view=upload';
        Sidebar::addEntry(
            '<span class="icon-upload" ></span>&nbsp;' .
            '<span class="sidebar-item-title">' . Text::_('COM_RSGALLERY2_MENU_UPLOAD') . '</span>',
            $link,
            $vName == 'upload',
        );

        //--- Add images view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2&view=images';
        Sidebar::addEntry(
            '<span class="icon-image" ></span>&nbsp;' .
            '<span class="sidebar-item-title">' . Text::_('COM_RSGALLERY2_MENU_IMAGES') . '</span>',
            // 'index.php?option=com_rsgallery2&rsgOption=images',
            $link,
            $vName == 'images' || $vName == 'images_raw',
        );
        //false);

        //--- Add maintenance view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2&view=maintenance';
        // In config add maintenance
        Sidebar::addEntry(
            '<span class="icon-cogs" ></span>&nbsp;' .
            '<span class="sidebar-item-title">' . Text::_('COM_RSGALLERY2_MENU_MAINTENANCE') . '</span>',
            $link,
            $vName == 'maintenance',
        );

        //--- Add develop view link ------------------------------------

        $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $isDevelop = $rsgConfig->get('isDevelop');

        if ($isDevelop) {
            $link = 'index.php?option=com_rsgallery2&view=develop';
            // In config add maintenance
            Sidebar::addEntry(
                '<span class="icon-cube" ></span>&nbsp;' . // cube'
                '<span class="sidebar-item-title">' . Text::_('COM_RSGALLERY2_MENU_DEVELOP') . '</span>',
                $link,
                $vName == 'develop',
            );
        }

        //--- config ------------------------------------
        /**
         * $link = 'index.php?option=com_rsgallery2&view=config&task=config.edit';
         * // In maintenance add config
         * \Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
         * '<span class="icon-equalizer" >  </span>' .
         * Text::_('COM_RSGALLERY2_MENU_CONFIG'),
         * $link,
         * false);
         * /**/
    }
}
