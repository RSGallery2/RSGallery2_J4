<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

\defined('_JEXEC') or die;

/**
 * Gallery Component Association Helper
 *
     * @since      5.1.0
 */
abstract class GalleryAssociationHelper
{
    public static $gallery_association = true;

    /**
     * Method to get the associations for a given gallery
     *
     * @param   integer  $id         Id of the item
     * @param   string   $extension  Name of the component
     *
     * @return  array    Array of associations for the component galleries
     *
     * @since   5.1.0     */
    public static function getGalleryAssociations($id = 0, $extension = 'com_rsgallery2')
    {
        $return = [];

        if ($id) {
            $helperClassname = ucfirst(substr($extension, 4)) . 'HelperRoute';

            $associations = GalleriesHelper::getAssociations($id, $extension);

            foreach ($associations as $tag => $item) {
	            // ToDo: getGalleryRoute not defined as such
                if (class_exists($helperClassname) && is_callable([$helperClassname, 'getGalleryRoute'])) {
	                // ToDo: getGalleryRoute not defined as such
                    $return[$tag] = $helperClassname::getGalleryRoute($item, $tag);
                } else {
                    $return[$tag] = 'index.php?option=' . $extension . '&view=gallery&id=' . $item;
                }
            }
        }

        return $return;
    }
}
