<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;

/**
 * Rsgallery2 component helper.
 *
     * @since      5.1.0
 */
class Rsgallery2Helper extends ContentHelper
{
    /**
     * Configure the Linkbar. Only icons no text
     *
     * @param   string  $vName  The name of the active view.
     *
     * @return  void
     *
     * @since   5.1.0     */
    public static function addSubmenu($vName)
    {

        //--- standard form elements ----------------------------------------

        // '<span class="sidebar-item-title">' . Home Dashboard . '</span>'

        $link = 'index.php?option=com_rsgallery2&view=rsgallery2';
        Sidebar::addEntry(
            '<span class="icon-home-2" ></span>',
            $link,
            $vName == 'control',
        );

        $link = 'index.php?option=com_rsgallery2&view=galleries';
        Sidebar::addEntry(
            '<span class="icon-images" ></span>',
            $link,
            $vName == 'galleries',
        );

        $link = 'index.php?option=com_rsgallery2&view=upload';
        Sidebar::addEntry(
            '<span class="icon-upload" ></span>',
            $link,
            $vName == 'upload',
        );

        //--- Add images view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2&view=images';
        Sidebar::addEntry(
            '<span class="icon-image" ></span>',
            // 'index.php?option=com_rsgallery2&rsgOption=images',
            $link,
            $vName == 'images' || $vName == 'images_raw',
        );

	    //--- config ------------------------------------

	    $link = 'index.php?option=com_config&view=component&component=com_rsgallery2"';
	    Sidebar::addEntry(
		    '<span class="icon-equalizer" ></span>',
		    // 'index.php?option=com_rsgallery2&rsgOption=images',
		    $link,
		    $vName == 'config' || $vName == 'config_raw',
	    );

        //--- Add maintenance view link ------------------------------------

        $link = 'index.php?option=com_rsgallery2&view=maintenance';
        // In config add maintenance
        Sidebar::addEntry(
            '<span class="icon-cogs" ></span>',
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
                '<span class="icon-cube" ></span>',
                $link,
                $vName == 'develop',
            );
        }

    }
}
