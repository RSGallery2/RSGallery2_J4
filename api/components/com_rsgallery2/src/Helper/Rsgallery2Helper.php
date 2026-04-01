<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Helper;

use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Content api helper.
 *
 * @since  4.0.0
 */
class Rsgallery2Helper
{
    /**
     * Fully Qualified Domain name for the image url
     *
     * @param   string  $uri      The uri to resolve
     *
     * @return  string
     */
    public static function resolve(string $uri): string
    {
        // Check if external URL.
        if (stripos($uri, 'http') !== 0) {
            return Uri::root() . $uri;
        }

        return $uri;
    }
}
