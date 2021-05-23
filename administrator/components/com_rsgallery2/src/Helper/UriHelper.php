<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

use Joomla\CMS\Uri\Uri;

class UriHelper
{
    /**
     * Does create a path with join of rgiven arguments and cleans the path afterwards
     * @return string
     *
     * @since version
     */
	public static function join(): string
	{
		$uri = implode('/', func_get_args());

        $uri = str_replace('\\', "/", $uri);
        // needed ? $uri = str_replace('//', "/", $uri);

		return $uri;
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

