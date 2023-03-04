<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_rsg2_gallery
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Module\Rsgallery2\Site\Helper;

\defined('_JEXEC') or die;

/**
 * Helper for mod_rsg2_gallery
 *
 * @since  __BUMP_VERSION__
 */
class Rsg2_galleryHelper
{
	/**
	 * Retrieve rsg2_gallery test
	 *
	 * @param   Registry        $params  The module parameters
	 * @param   CMSApplication  $app     The application
	 *
	 * @return  array
	 */
	public static function getText()
	{
		return 'Rsg2_galleryHelpertest';
	}

    public static function getImageNamesOfUrl ($folderUrl)
    {
        $Images = [];

        $html = file_get_contents($folderUrl);
////        $data = file_get_contents(JPATH_ROOT . '/' . $path);
//        $data = json_decode($html, true);
//        $data = json_decode($html);
//        $data = $html ? json_decode($html, true) : null;
//

        // ToDo: first element is wrong: check regex
//        // toDo: Only allowed extensions
//        $count = preg_match_all("((http|https|ftp|ftps)://?([a-zA-Z0-9\\\./]*.jpg))", $html, $files);
        $count = preg_match_all('/<a href="([^"]+)(png|jpg|webp\/)">[^<]*<\/a>/i', $html, $files);
        for ($i = 0; $i < $count; ++$i) {
            $fileName = $files[1][$i] . $files[2][$i];
////            echo "File: " . $fileName . "<br />\n";
//
            $Images[] = $folderUrl . '/' . $fileName;
        }
//
////        var_dump($files);

        return $Images;
    }


    public static function getImageNamesOfFolder ($folder)
    {
        $Images = [];

        // toDo: Only allowed extensions
        foreach(glob($folder . '*.{jpg,JPG,jpeg,JPEG,png,PNG}',GLOB_BRACE) as $fileName) {
            // echo "File: " . $fileName . "<br />\n";
            $Images[] =  $fileName;
        }

        return $Images;
    }



}

