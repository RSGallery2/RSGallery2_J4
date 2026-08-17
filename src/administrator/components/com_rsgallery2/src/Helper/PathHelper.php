<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

use Joomla\Filesystem\Path;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @package Rsgallery2\Component\Rsgallery2\Administrator\Helper
 *
 * @since   5.1.0
 */
class PathHelper
{
    /**
     * Does create a path with join of given arguments and cleans the path afterward
     *
     * @return string
     *
     * @since  5.1.0     */
    public static function join(): string
    {
        $paths = implode(DIRECTORY_SEPARATOR, func_get_args());

        // path inside actual joomla
        return Path::check($paths, JPATH_ROOT);
    }

}
