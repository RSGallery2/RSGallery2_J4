<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (c) 2016-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * @author         finnern
 * RSGallery is Free Software
 */

// ToDo: 2024.10: namespace helper
// namespace \Rsgallery2\Component\Rsgallery2\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * @package     ${yyy}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
class CascadedParam
{

    /**
     * lastSet
     * The last defined parameter variable given is used if it is not set to 'global'
     * Use: A configuration parameter may be overwritten be menu setting which
     * may be overwritten by item setting which may be ...
     * If any setting is 'global' it is expected that the previous setting is still valid
     *
     * @param   mixed  ...$paramValues
     *
     * @return mixed|stdClass last found 'valid' value
     *
     * @since version
     */
    public static function lastSet(...$paramValues)
    {
        $paramValue = new stdClass();

        try {
            //foreach ($vars as &$value)
            foreach ($paramValues as $value) {
                if (isset ($value)) {
                    if ($value != 'global') {
                        $paramValue = $value;
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $paramValue;
    }

}
