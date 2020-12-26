<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;


class PathHelper
{
	public static function join(): string
	{
		$paths = func_get_args();
		$paths = array_map(fn($path) => str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $path), $paths);
		$paths = array_map(fn($path) => self::trimPath($path), $paths);

		return implode(DIRECTORY_SEPARATOR, $paths);
	}

	private static function trimPath(string $path): string
	{
		$path  = trim($path);
		$start = $path[0] === DIRECTORY_SEPARATOR ? 1 : 0;
		$end   = $path[strlen($path) - 1] === DIRECTORY_SEPARATOR ? -1 : strlen($path);

		return substr($path, $start, $end);
	}
}

//Path::combine("C:\Program Files", "/Repository", "sub-repository/folder/", "file.txt");
////return "C:\Program Files\Repository\sub-repository\folder\file.txt"
//
//Path::combine("C:\Program Files", "/Repository/", "\\sub-repository\\folder\\", "sub-folder", "file.txt");
////return "C:\Program Files\Repository\sub-repository\folder\sub-folder\file.txt"
//
//Path::combine("C:\file.txt");
////return "C:\file.txt"
//
//Path::combine();
////return ""
///
///
///

/**
 * Merge several parts of URL or filesystem path in one path
 * Examples:
 *  echo merge_paths('stackoverflow.com', 'questions');           // 'stackoverflow.com/questions' (slash added between parts)
 *  echo merge_paths('usr/bin/', '/perl/');                       // 'usr/bin/perl/' (double slashes are removed)
 *  echo merge_paths('en.wikipedia.org/', '/wiki', ' Sega_32X');  // 'en.wikipedia.org/wiki/Sega_32X' (accidental space fixed)
 *  echo merge_paths('etc/apache/', '', '/php');                  // 'etc/apache/php' (empty path element is removed)
 *  echo merge_paths('/', '/webapp/api');                         // '/webapp/api' slash is preserved at the beginnnig
 *  echo merge_paths('http://google.com', '/', '/');              // 'http://google.com/' slash is preserved at the end
/**/

/**
 * Joins paths for files or url
 * Attention: may not be perfect so check once in a while
 * @return string|string[]|null
 *
 * @since __BUMP_VERSION__
 */
//function PathHelper::join() {
//
//$paths = array();
//
//	foreach (func_get_args() as $arg) {
//		if ($arg !== '') { $paths[] = $arg; }
//	}
//
//	return preg_replace('#/+#','/',join('/', $paths));
//}
//

//    /**
//     * Joins paths for files or url
//     * Attention: may not be perfect so check once in a while
//     * @return string|string[]|null
//     *
//     * @since __BUMP_VERSION__
//     */
//    public static function path_join() {
//
//        $paths = array();
//
//        foreach (func_get_args() as $arg) {
//            if ($arg !== '') { $paths[] = $arg; }
//        }
//
//        return preg_replace('#/+#','/',join('/', $paths));
//    }

//// PHP 7.+
//public static function paths_join(string ...$parts): string {
//	$parts = array_map('trim', $parts);
//	$path = [];
//
//	foreach ($parts as $part) {
//		if ($part !== '') {
//			$path[] = $part;
//		}
//	}
//
//	$path = implode(DIRECTORY_SEPARATOR, $path);
//
//	return preg_replace(
//		'#' . preg_quote(DIRECTORY_SEPARATOR) . '{2,}#',
//		DIRECTORY_SEPARATOR,
//		$path
//	);
//}


/**
 * Merge several parts of URL or filesystem path in one path
 * Examples:
 *  echo merge_paths('stackoverflow.com', 'questions');           // 'stackoverflow.com/questions' (slash added between parts)
 *  echo merge_paths('usr/bin/', '/perl/');                       // 'usr/bin/perl/' (double slashes are removed)
 *  echo merge_paths('en.wikipedia.org/', '/wiki', ' Sega_32X');  // 'en.wikipedia.org/wiki/Sega_32X' (accidental space fixed)
 *  echo merge_paths('etc/apache/', '', '/php');                  // 'etc/apache/php' (empty path element is removed)
 *  echo merge_paths('/', '/webapp/api');                         // '/webapp/api' slash is preserved at the beginnnig
 *  echo merge_paths('http://google.com', '/', '/');              // 'http://google.com/' slash is preserved at the end
/**/



