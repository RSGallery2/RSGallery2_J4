<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Plugin\Content\Rsg2_galleries\Helper;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;
use RuntimeException;

\defined('_JEXEC') or die;

/**
 * Helper for mod_rsg2_galleries
 *
 * @since  __BUMP_VERSION__
 */
class Rsg2_galleriesHelper //implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    public $pagination;
    protected $galleriesModel;

    public function __construct(array $data)
    {
        // boot component only once Model('Gallery', 'Site')

        $app = $data['app'];

        // ToDo: add params, app to local vars

        // SiteApplication $app
        $this->galleriesModel = $app
            ->bootComponent('com_rsgallery2')
            ->getMVCFactory()
            // ->createModel('Gallery', 'Site', ['ignore_request' => true]);
            ->createModel('Galleries', 'Site', ['ignore_request' => true]);
    }

	/**
	 * @param   int  $gid
	 *
	 * @return mixed
	 *
	 * @since version
	 */
    public function getGalleryData(int $gid)
    {
        return $this->galleriesModel->getParentGallery($gid);
    }

    /**
     * Get a list of the gallery galleries from the slideshow model.     *
     *
     * @param   Registry        $params  The module parameters
     * @param   CMSApplication  $app     The application
     *
     * @return  array
     */
    public function getGalleriesOfGallery(int $gid, Registry $params, SiteApplication $app)
    {
        $galleries = [];

        try {
            $model = $this->galleriesModel;

            //--- state -------------------------------------------------

            $state = $model->getState();

            // Set application parameters in model
            $appParams = $app->getParams();

            $model->setState('params', $params);

            $model->setState('list.start', 0);
            $model->setState('filter.published', 1);

            // Set the filters based on the module params
            // $model->setState('list.limit', (int) $params->get('count', 5));
            $model->setState('list.limit', 99);

            // This module does not use tags data
            $model->setState('load_tags', false);

            $model->setState('gallery.id', $gid);
            $model->setState('gid', $gid);

            //--- galleries -----------------------------------------------------------------------

//             $this->galleriesModel->populateState();

            // $galleries= $this->galleriesModel->get('Items');
            $galleries = $this->galleriesModel->getItems();

            if (!empty($galleries)) {
                // Add image paths, image params ...
                $data = $this->galleriesModel->AddLayoutData($galleries);
            }

		} catch (\RuntimeException $e) {
            // ToDo: Message more explicit
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleries;
    }

//	public static function getImageNamesOfUrl ($folderUrl)
//    {
//        $galleries = [];
//
//        $html = file_get_contents($folderUrl);
//////        $data = file_get_contents(JPATH_ROOT . '/' . $path);
////        $data = json_decode($html, true);
////        $data = json_decode($html);
////        $data = $html ? json_decode($html, true) : null;
////
//
//        // ToDo: first element is wrong: check regex
////        // toDo: Only allowed extensions
////        $count = preg_match_all("((http|https|ftp|ftps)://?([a-zA-Z0-9\\\./]*.jpg))", $html, $files);
//        $count = preg_match_all('/<a href="([^"]+)(png|jpg|webp\/)">[^<]*<\/a>/i', $html, $files);
//        for ($i = 0; $i < $count; ++$i) {
//            $fileName = $files[1][$i] . $files[2][$i];
//////            echo "File: " . $fileName . "<br />\n";
////
//            $galleries[] = $folderUrl . '/' . $fileName;
//        }
////
//////        var_dump($files);
//
//        return $galleries;
//    }
//
//
//    public static function getImageNamesOfFolder ($folder)
//    {
//        $galleries = [];
//
//        // toDo: Only allowed extensions
//        foreach(glob($folder . '*.{jpg,JPG,jpeg,JPEG,png,PNG}',GLOB_BRACE) as $fileName) {
//            // echo "File: " . $fileName . "<br />\n";
//            $galleries[] =  $fileName;
//        }
//
//        return $galleries;
//    }

	/**
	 *
	 * @return string
	 *
	 * @since version
	 */
    public function getText()
    {
        $msg = "    --- Rsg2_galleries module ----- ";

        return $msg;
    }

}

