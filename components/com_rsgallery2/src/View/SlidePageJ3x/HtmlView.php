<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\View\SlidePageJ3x;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Filesystem\Path;
use Joomla\Registry\Registry;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ImageExif;

/**
 * Single image with pagination
 * HTML Rsgallery2 View class for the Rsgallery2 component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The model state
     *
     * @var    \JObject
     * @since  3.1
     */
    protected $state;

    /**
     * The list of images
     *
     * @var    array|false
     * @since  3.1
     */
    protected $items;

    /**
     * index in image list matching the user selected image id
     */
    protected $imageIdx;

    /**
     * The pagination object
     *
     * @var    \Joomla\CMS\Pagination\Pagination
     * @since  3.1
     */
    protected $pagination;

    /**
     * The page parameters
     *
     * @var    \Joomla\Registry\Registry|null
     * @since  3.1
     */
    protected $params = null;

    /**
     * The page class suffix
     *
     * @var    string
     * @since  4.0.0
     */
    protected $pageclass_sfx = '';

    /**
     * The logged in user
     *
     * @var    \JUser|null
     * @since  4.0.0
     */
    protected $user = null;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed   A string if successful, otherwise an Error object.
     */
	public function display($tpl = null)
	{
        $app = Factory::getApplication();
        $input  = Factory::getApplication()->input;
        $this->galleryId = $input->get('gid', 0, 'INT');
        $imageId = $input->get('img_id', 0, 'INT');
        $limitstart = $input->get('start', -1, 'INT');

        /* wrong call but not expected. Happens but why ? */
        if ($this->galleryId < 2)
        {
            Factory::getApplication()->enqueueMessage("gallery id is zero or not allowed -> why", 'error');
        }

        $this->mergeMenuOptions();

        // Get some data from the models
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');

        $params =
        $this->params     = $this->state->get('params');
        $this->user       = // $user = Factory::getContainer()->get(UserFactoryInterface::class);
	    $user = $app->getIdentity();

        $this->isShowPagination = $params->get('show_pagination', 2);
        $this->isShowDescription = $params->get('isSlpShowImgDescriptionJ3x', false);
        $this->isShowDownload = $params->get('isSlpShowDownloadJ3x', false);
        $this->isShowVoting = $params->get('isSlpShowVotingJ3x', false);
        $this->isShowComments = $params->get('isSlpShowCommentsJ3x', false);
        $this->isShowExif = $params->get('isSlpShowExifJ3x', false);

        $this->isDebugSite   = $params->get('isDebugSite');
        $this->isDevelopSite = $params->get('isDevelop');

        //--- pagination ------------------------------------

        // Entry by click on gallery image ?
        if ($limitstart <0) {
            $this->imageIdx = $this->imageIdxInList ($imageId, $this->items);
            //$this->state->set('list.limitstart', $this->imageIdx);
            $this->state->set('list.start', $this->imageIdx);
        }

        // one image shown
        $this->state->set('list.limit', 1);
        // images of gallery
        $this->state->set('list.total', count ($this->items));

        $this->pagination = $this->get('Pagination');

        // Flag indicates to not add limitstart=0 to URL
        $this->pagination->hideEmptyLimitstart = true;

        //--- select start image --------------------------------------------------------------------

        // from pagination
        $this->imageIdx = $this->pagination->limitstart;
        $this->image = null;
        if (count ($this->items) >= $this->imageIdx) {

            $this->image = $this->items [$this->imageIdx];
        }

        //---   --------------------------------------------------------------------

        $model = $this->getModel();

        $gallery =
        $this->gallery = $model->galleryData($this->galleryId);

        // add slideshow url
        if (! empty ($gallery)) {
            $model->assignSlideshowUrl ($gallery);
        }

        if ( ! empty($this->items)) {
            // Add image paths, image params ...
            //$data = $model->AddLayoutData ($this->items);
        }

        //--- exif data --------------------------------------------------------

        if ( ! empty ($this->image )) {

            if($this->isShowExif){

                // image to display exits
                if ( ! empty ($this->image)) {

                    //--- load additional language file --------------------------------

                    $lang = Factory::getApplication()->getLanguage();
                    $lang->load('com_rsg2_exif',
                        Path::clean(JPATH_ADMINISTRATOR . '/components/' . 'com_rsgallery2'), null, false, true);

                    //--- selected tags from original file --------------------------------

                    // tags from config
                    $userExifTags = ImageExif::userExifTagsJ3x();
                    // tags in file
                    $ImageExifFileTags = $model->exifDataUserSelected($this->image->OriginalFile, $userExifTags);

                    // tags in second item of array
                    $ImageExifTags = [];
                    if (! empty ($ImageExifFileTags[1])) {

                        $ImageExifTags = $ImageExifFileTags[1];
                    }

                    $this->image->exifTags = $ImageExifTags;
                }
            }
        }

        if (count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

//   		$state = $this->state = $this->get('State');
//		$params = $this->params = $state->get('params');
//		$itemparams = new Registry(json_decode($item->params));
//
//		$temp = clone $params;
//		$temp->merge($itemparams);
//		$item->params = $temp;
//
//		Factory::getApplication()->triggerEvent('onContentPrepare', array ('com_rsgallery2.rsgallery2', &$item));
//
//		// Store the events for later
//		$item->event = new \stdClass;
//		$results = Factory::getApplication()->triggerEvent('onContentAfterTitle', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->afterDisplayTitle = trim(implode("\n", $results));
//






//		$results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->beforeDisplayContent = trim(implode("\n", $results));
//
//		$results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', array('com_rsgallery2.rsgallery2', &$item, &$item->params));
//		$item->event->afterDisplayContent = trim(implode("\n", $results));
//
		return parent::display($tpl);
	}



//  ToDo: movoe to model
    public function mergeMenuOptions()
    {
        /**
        $app = Factory::getApplication();

        if ($menu = $app->getMenu()->getActive())
        {
        $menuParams = $menu->getParams();
        }
        else
        {
        $menuParams = new Registry;
        }

        $mergedParams = clone $this->params;
        $mergedParams->merge($menuParams);

        $this->params = $mergedParams;
        /**/

        // gid should be zero ToDo: is this really needed *?
        $input = Factory::getApplication()->input;
        //$this->galleryId = $input->get('gid', 0, 'INT');

        // $this->menuParams = new \stdClass();
        $this->menuParams = (object)[];
//        $this->menuParams->gallery_show_title = $input->getBool('gallery_show_title', true);
//        $this->menuParams->gallery_show_description = $input->getBool('gallery_show_description', true);
//        $this->menuParams->gallery_show_slideshow = $input->getBool('gallery_show_slideshow', true);
//        $this->menuParams->displaySearch = $input->getBool('displaySearch', true);

//        $this->menuParams->images_column_arrangement = $input->getInt('images_column_arrangement', '');
//        $this->menuParams->max_columns_in_images_view= $input->getInt('max_columns_in_images_view', '');
//        $this->menuParams->images_row_arrangement = $input->getInt('images_row_arrangement', '');
//        $this->menuParams->max_rows_in_images_view = $input->getInt('max_rows_in_images_view', '');
//        $this->menuParams->max_thumbs_in_images_view = $input->getInt('max_thumbs_in_images_view', '');
//
//        $this->menuParams->images_show_title = $input->getBool('images_show_title', true);
//        $this->menuParams->images_show_description = $input->getBool('images_show_description', true);

    }

    /**
     * Detect matching image by ID in image list
     * @param $imageId
     * @param $images
     *
     * @return int
     *
     * @since version
     *
     *  ToDo: move to model
     */
    public function imageIdxInList ($imageId, $images)
    {
        /**/
        $imageIdx = -1;

        if (!empty ($images)) {

            // Not given use first
            $imageIdx = 0;

            $count = count($images);
            for ($idx = 0; $idx < $count ; $idx++) {
                if ($images[$idx]->id == $imageId) {
                    $imageIdx = $idx;
                    break;
                }
            }
        }

        return $imageIdx;
    }


}
