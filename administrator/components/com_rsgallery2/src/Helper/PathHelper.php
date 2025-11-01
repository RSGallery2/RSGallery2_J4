<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

use Joomla\Filesystem\Path;

/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Helper
 *
     * @since   5.1.0
 */
class PathHelper
{
    /**
     * Does create a path with join of rgiven arguments and cleans the path afterwards
     *
     * @return string
     *
     * @since  5.1.0     */
    public static function join(): string
    {
        $paths = implode(DIRECTORY_SEPARATOR, func_get_args());

        return Path::clean($paths);
    }


//	public static function join(): string
//	{
//		$paths = func_get_args();
//		$paths = array_map(fn($path) => str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $path), $paths);
//		$paths = array_map(fn($path) => self::trimPath($path), $paths);
//
//		return implode(DIRECTORY_SEPARATOR, $paths);
//	}
//
//	// use
//	private static function trimPath(string $path): string
//	{
//		$path  = trim($path);
//		$start = $path[0] === DIRECTORY_SEPARATOR ? 1 : 0;
//		$end   = $path[strlen($path) - 1] === DIRECTORY_SEPARATOR ? -1 : strlen($path);
//
//		return substr($path, $start, $end);
//	}
}

