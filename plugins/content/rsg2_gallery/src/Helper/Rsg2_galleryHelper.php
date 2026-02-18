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
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\Factory\MVCFactory;
use Joomla\CMS\Pagination\Pagination;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;
use RSGallery2\Component\Rsgallery2\Site\Model\Galleryj3xModel;


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

    /* @var Pagination */
    protected $pagination;

    /* @var MVCFactory */
    protected $rsgComponent;

    /* @var Galleryj3xModel */
    protected $galleryModel;

    /* @var SiteApplication */
    protected $app;

    /* @var SiteApplication */
    protected $rsgConfig;

    /**
     * Prepare all local variable which are used only once
     *
     * @throws \Exception
     */
    public function __construct()
    {
        //--- boot rsg2 component -----------------

        $this->app          = Factory::getApplication();
        $this->rsgComponent = $this->app->bootComponent('com_rsgallery2')->getMVCFactory();

        //--- rsg2 site gallery model  -----------------

        $this->galleryModel = $this->rsgComponent->createModel('GalleryJ3x', 'Site', ['ignore_request' => true]);

        //--- rsg2 component config -----------------

        $this->rsgConfig = ComponentHelper::getParams('com_rsgallery2');
    }

    /**
     * create HTML for image thumbs of gallery
     * use rsg2 config, plg parameter and user parameter from article text
     *
     * @param   Registry  $usrParams
     * @param   Registry  $plgParams
     *
     * @return string
     *
     * @since version
     */
    public function galleryImagesHtml(registry $usrParams, registry $plgParams): string
    {
        //--- parameter ----------------------------------

        // The original registry should not be changed as it is still needed
        // $appParams = $app->getConfig();  //
        $params = new Registry($this->rsgConfig, true);
        $params->merge($plgParams, true);
        $params->merge($usrParams, true);

        // ToDo: check $params = $params->toObject(); Remove get ....

        //--- debugOnlyTitle: only tell about the replacement -----------------------------

        $debugOnlyTitle = $usrParams->get('debugOnlyTitle', 0);
        if (!empty($debugOnlyTitle)) {
            $content_output = '<h4>"--- Rsg2_gallery replacement ---"</h4>';
        } else {
            //--- load css/js -----------------------------------------------

            $wa = $this->app->getDocument()->getWebAssetManager();
            $wa->getRegistry()->addExtensionRegistryFile('com_rsgallery2');
            $wa->usePreset('com_rsgallery2.site.galleryJ3x');

            //--- collect images -----------------------------------------------

            $gid = $params->get('gid', '-1');
            // check if gid is missing or wrong (no real check of DB)
            if ($gid < 2) {
                $html[] = '{Plg RSG2 gallery: Gid is missing in marker "gid:xx,.." (' . $gid . ')}';
            }

            //--- collect images and gallery data-----------------------------------------------

            $images  = $this->getImagesOfGallery($gid, $params);
            $gallery = $this->galleryModel->galleryData($gid);

            //--- selected layout -----------------------------------------------

            $layoutName = $params->get('images_layout');
            if ($layoutName == 'default') {
                $layoutName = 'ImagesAreaJ3x.default';
            }
            $layoutFolder = JPATH_SITE . '/components/com_rsgallery2/layouts';

            $layout = new FileLayout($layoutName, $layoutFolder);

            //--- layout data -----------------------------------------------

            $displayData['images'] = $images;
            $test                  = $params->toObject();
            $displayData['params'] = $params->toObject();

            $displayData['isDebugSite']   = $params->get('isDebugSite');
            $displayData['isDevelopSite'] = $params->get('isDevelopSite');

            $displayData['gallery']   = $gallery;
            $displayData['galleryId'] = $gid;

            //--- search -----------------------------------------------

            $displaySearch = $params->get('displaySearch', false);
            if ($displaySearch) {
                $searchLayout = new FileLayout('Search.search', JPATH_ROOT . '/components/com_rsgallery2/layouts');
                // $searchData['options'] = $searchOptions ...; // gallery
            }

            //-------------------------------------------------------------------
            // create html data
            //-------------------------------------------------------------------

            $html[] = '    <div class="rsg2__form rsg2__images_area">';
            $html[] = '';

            if (!empty($params->get('isDebugSite'))) {
                $html[] = '            <h2>' . Text::_('RSGallery2 "gallery j3x legacy"') . ' view </h2>';
                $html[] = '            <hr>';
            }
            $html[] = '';

            //--- display search ----------
            $html[] = '';
            if ($displaySearch) {
                $html[] = '            ' . $searchLayout->render();
            }
            $html[] = '';

            //--- display gallery images ----------
            $html[] = '';
            $html[] = '        ' . $layout->render($displayData);
            $html[] = '';

            //--- display pagination ----------

            $html[] = '        ' . $this->pagination->getListFooter();

            $html[] = '    </div>';

            $content_output = implode($html);
        }

        return $content_output;
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
