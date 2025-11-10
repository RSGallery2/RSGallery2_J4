<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Helper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\Component\Categories\Administrator\Helper\CategoryAssociationHelper;

/**
 * Rsgallery2 Component Association Helper
 *
     * @since      5.1.0
 */
abstract class AssociationHelper extends CategoryAssociationHelper
{
    /**
     * Method to get the associations for a given item
     *
     * @param   integer  $id    Id of the item
     * @param   string   $view  Name of the view
     *
     * @return  array   Array of associations for the item
     *
     * @since   5.1.0     */
    public static function getAssociations($id = 0, $view = null)
    {
        $jinput = Factory::getApplication()->input;
        $view   = $view ?? $jinput->get('view');
        $id     = empty($id) ? $jinput->getInt('id') : $id;

        if ($view === 'rsgallery2') {
            if ($id) {
                $associations = Associations::getAssociations('com_rsgallery2', '#__rsgallery2_details', 'com_rsgallery2.item', $id);

                $return = [];

                foreach ($associations as $tag => $item) {
                    $return[$tag] = RouteHelper::getRsgallery2Route($item->id, (int)$item->catid, $item->language);
                }

                return $return;
            }
        }

        if ($view === 'category' || $view === 'categories') {
            return self::getCategoryAssociations($id, 'com_rsgallery2');
        }

        return [];
    }
}
