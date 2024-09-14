<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\Component\Categories\Administrator\Helper\CategoryAssociationHelper;

use function defined;

/**
 * Rsgallery2 Component Association Helper
 *
 * @since  __BUMP_VERSION__
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
     * @since  __BUMP_VERSION__
     */
    public static function getAssociations($id = 0, $view = null)
    {
        $jinput = Factory::getApplication()->input;
        $view   = $view ?? $jinput->get('view');
        $id     = empty($id) ? $jinput->getInt('id') : $id;

        if ($view === 'rsgallery2') {
            if ($id) {
                $associations = Associations::getAssociations(
                    'com_rsgallery2',
                    '#__rsgallery2_details',
                    'com_rsgallery2.item',
                    $id,
                );

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
