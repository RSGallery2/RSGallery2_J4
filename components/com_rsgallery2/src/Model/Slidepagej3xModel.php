<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Database\QueryInterface;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageExif;
use RuntimeException;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class SlidePageJ3XModel extends Imagesj3xModel
{


    /**
     * In slide page view a single item is shown.
     * Pagination parameters are changed to match it
     * It can not be added to populatian as it needs ...
     *
	 * @throws \Exception
     * @since version
     */

    public function setState2SingleItem(array $items)
    {
        $app = Factory::getApplication();

        //$limitstart = $app->input->get('start', -1, 'INT');
        $limitstart = $app->input->get('limitstart', -1, 'INT');

        //--- pagination ------------------------------------

        // Entry by click on gallery image ?
        if ($limitstart < 0) {
            $imageId = $app->input->get('img_id', 0, 'INT');

            // May create list
            //$items = $this->getItems();
            $imageIdx = $this->imageIdxInList($imageId, $items);
            //$this->state->set('list.limitstart', $this->imageIdx);
            $this->state->set('list.start', $imageIdx);
        }

        // one image shown
        $this->state->set('list.limit', 1);
        // images of gallery
        $total = count($items);
        $this->state->set('list.total', $total);

        return;
    }

    /**
     * Detect matching image by ID in image list
     *
     * @param $imageId
     * @param $images
     *
     * @return int
     *
     * @since version
     *
     *  ToDo: move to model
     */
    public function imageIdxInList($imageId, $images)
    {
        /**/
        $imageIdx = -1;

        if (!empty ($images)) {
            // Not given use first
            $imageIdx = 0;

            $count = count($images);
            for ($idx = 0; $idx < $count; $idx++) {
                if ($images[$idx]->id == $imageId) {
                    $imageIdx = $idx;
                    break;
                }
            }
        }

        return $imageIdx;
    }

    /**
     * @param $filename
     * @param $userExifTags
     *
     * @return array Return exif item list of 'translation Id' => value
     *
     * @since version
     */
    public function exifDataUserSelected($filename, $userExifTags)
    {
        $exifDataOfFile = [$filename];

        try {
            //--- collect by exif names --------------------------------------

            $oImageExif = new ImageExif ($filename);

            $exifItems = $oImageExif->readExifDataUserSelected($userExifTags);

            //--- translate ID for names -------------------------------------

            $exifTranslated = [];
            foreach ($exifItems as $exifTag => $value) {
                [$type, $name] = ImageExif::tag2TypeAndName($exifTag);
                $transId                  = $oImageExif::exifTranslationId($name);
                $exifTranslated[$transId] = $value;
            }
            //---  -----------------------------------------------------------

            if (!empty ($exifTranslated)) {
                $exifDataOfFile = [$filename, $exifTranslated];
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing exifDataUserSelected: "' . $filename . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $exifDataOfFile;
    }

    /**
     * Method to get a database query to list images.
     *
     * @return  QueryInterface object.
     *
     * @since __BUMP_VERSION__
     */
    protected function getListQuery()
    {
        $query = parent::getListQuery();

        $input     = Factory::getApplication()->input;
        $galleryId = $input->get('id', 0, 'INT');

        // If gallery ID is given
        if ($galleryId) {
            $query->where('a.gallery_id = ' . (int)$galleryId);
        }

        return $query;
    }

}

