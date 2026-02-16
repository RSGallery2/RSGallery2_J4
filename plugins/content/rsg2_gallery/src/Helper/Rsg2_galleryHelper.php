<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Plugin\Content\Rsg2_gallery\Helper;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;
use Joomla\Component\Config\Administrator\Controller\RequestController;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Helper for mod_rsg2_galleries
 *
     * @since      5.1.0
 */
class Rsg2_galleryHelper implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    public $pagination;
    protected $galleriesModel;
    protected $rsgComponent;
    protected $galleryModel;

    protected $app;
    public function __construct()
    {
        // boot component only once Model('Gallery', 'Site')
        $this->app = Factory::getApplication();
        $this->rsgComponent = $this->app->bootComponent('com_rsgallery2')->getMVCFactory();
        $this->galleryModel = $this->rsgComponent->createModel('GalleryJ3x', 'Site', ['ignore_request' => true]);
    }

    public function galleryImagesHtml($rsgComponent, registry $params) : string
    {
        $this->rsgComponent = $rsgComponent;

        //--- collect images -----------------------------------------------

        $gid = $params->get('gid');
        $images = $this->getImagesOfGallery($gid, $params);


//        $app = $this->app;
//        $rsgView = $this->rsgComponent->createView('galleryj3x', 'Site', 'html', );
//        $rsgView->setModel($this->rsgComponent->createModel('Gallery', 'Site', ['ignore_request' => true]));
        // $rsgView->setLanguage($app->getLanguage());

        // $rsgView->document = $this->getDocument();

//        $text = $rsgView->display();

//
//        // Execute backend controller
//        $serviceData = json_decode($json, true);
//
//        // Access backend com_config
//        $requestController = new RequestController();
//

        $layoutName = 'ImagesAreaJ3x.default';

        $layout = new FileLayout($layoutName);

        $displayData['isDebugSite']   = $params->get('isDebugSite');
        $displayData['isDevelopSite'] = $this->isDevelopSite;

        $displayData['images'] = $this->items;
//        $displayData['params'] = $this->params->toObject();
        $displayData['params'] = $params->toObject();
        //$displayData['menuParams'] = $this->menuParams;
        $displayData['pagination'] = $this->pagination;

        $displayData['gallery']   = $this->gallery;
        $displayData['galleryId'] = $this->galleryId;

        //$displaySearch = $this->params->get('displaySearch', false);
        $displaySearch = $params->get('displaySearch', false);
        if ($displaySearch) {
            $searchLayout = new FileLayout('Search.search');
            // $searchData['options'] = $searchOptions ...; // gallery
        }



        return '<h4>--- Replaced: However, it still needs to be coded. ---</h4>';

    }


    /**
     * Get a list of the gallery images from the gallery model.     *
     *
     * @param   Registry        $params  The module parameters
     * @param   CMSApplication  $app     The application
     *
     * @return  array
     *
     * @since  5.1.0
     */
    public function getImagesOfGallery(int $gid, Registry $params) // , SiteApplication $app)
    {
        $images = [];

        try {
            $model = $this->galleryModel;

            //--- state -------------------------------------------------

            $state = $model->getState();

            // Set application parameters in model
            // $appParams = $app->getParams();

            $model->setState('params', $params);

            $model->setState('list.start', 0);
            $model->setState('filter.published', 1);

            $limit = $params->get('max_thumbs_in_images_view_j3x');
            $model->setState('list.limit', $limit);

            // This module does not use tags data
            $model->setState('load_tags', false);

            //--- state gid -------------------------------------------------

            $model->setState('gallery.id', $gid);
            // ToDo: remove ?
            $model->setState('gid', $gid);

            //--- images -----------------------------------------------------------------------

//             $this->galleryModel->populateState();

            // $images= $this->galleryModel->get('Items');
            $images = $this->galleryModel->getItems();

            if (!empty($images)) {
                // Add image paths, image params ...
                $data = $this->galleryModel->AddLayoutData($images);
            }

            //--- pagination -------------------------------------------------

            $this->pagination = $model->getPagination();

            // Flag indicates to not add limitstart=0 to URL
            $this->pagination->hideEmptyLimitstart = true;

        } catch (\RuntimeException $e) {
            // ToDO: Message more explicit
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $images;
    }


    /**
     *
     * @return string
     *
     * @since  5.1.0
     */
    public function getText()
    {
        $msg = "    --- Rsg2_gallery module ----- ";

        return $msg;
    }
}
