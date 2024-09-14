<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Session\Session;
use Joomla\CMS\User\UserFactoryInterface;

/**
 * The image properties Controller
 *
 * @since __BUMP_VERSION__
 */
class ImagesPropertiesController extends AdminController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     *                                         Recognized key values include 'name', 'default_task', 'model_path', and
     *                                         'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   \JInput              $input    Input
     *
     * @since __BUMP_VERSION__
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);
    }

//    /**
//	 * Proxy for getModel
//	 *
//	 * @param   string  $name    The model name. Optional.
//	 * @param   string  $prefix  The class prefix. Optional.
//	 * @param   array   $config  The array of possible config values. Optional.
//	 *
//	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	public function getModel($name = 'Gallery', $prefix = 'Administrator', $config = array('ignore_request' => true))
//	{
//		return parent::getModel($name, $prefix, $config);
//	}

    /**
     * Redirect to standard image properties tile view
     * Called from upload
     *
     * @since 4.3.0
     */
    public function PropertiesView()
    {
        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     = "ImagesProperties.PropertiesView: ";
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            // toDo: find " str_replace('\n', '<br>', $msg);" nad replace in complete project
            $msg = nl2br($msg);

            $link = 'index.php?option=com_rsgallery2';
            $this->setRedirect($link, $msg, $msgType);
        } else {
            // &ID[]=2&ID[]=3&ID[]=4&ID[]=12
            //127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=imagesProperties&cid[]=1&cid[]=2&cid[]=3&cid[]=4
            $cids = $this->input->get('cid', 0, 'int');
            //$this->setRedirect('index.php?option=' . $this->option . '&view=' . $this->view_list . '&' . http_build_query(array('cid' => $cids)));
            $this->setRedirect(
                'index.php?option=' . $this->option . '&view=imagesProperties' . '&' . http_build_query(['cid' => $cids],
                ),
            );

            parent::display();
        }
    }


    /**
     * Save user changes from imagesPropertiesView
     *
     * @since version 4.3
     */
    public function save_imagesProperties()
    {
        $this->checkToken();

        $ImgCount  = 0;
        $ImgFailed = 0;


        $msg     = "save_imagesProperties: " . '<br>';
        $msgType = 'notice';

        try {
            $this->checkToken();

            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                $ImagesProperties = $this->ImagesPropertiesFromInput();

                $imgModel = $this->getModel('image');

                foreach ($ImagesProperties as $ImagesProperty) {
                    $IsSaved = $imgModel->save_imageProperties($ImagesProperty);

                    if ($IsSaved) {
                        $ImgCount++;
                    } else {
                        $ImgFailed++;
                    }
                }

                // $msg '... successful assigned .... images ...
                if ($ImgCount) {
                    $msg_ok = ' Successful saved ' . $ImgCount . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_ok, 'notice');
                }
                if ($ImgFailed) {
                    $msg_bad = ' Failed on save of ' . $ImgFailed . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_bad, 'error');
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing save_imagesProperties: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        $link = 'index.php?option=com_rsgallery2&view=images';
        $this->setRedirect($link, $msg, $msgType);
    }


    /**
     * Apply changes from imagesPropertiesView
     * Is like save_imagesProperties but redirects to calling view
     *
     * @since version 4.3
     */
    public function apply_imagesProperties()
    {
        $this->checkToken();

        $msg     = "apply_imagesProperties: " . '<br>';
        $msgType = 'notice';

        try {
            $this->checkToken();

            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                $ImagesProperties = $this->ImagesPropertiesFromInput();

                $imgModel = $this->getModel('image');

                foreach ($ImagesProperties as $ImagesProperty) {
                    $IsSaved = $imgModel->save_imageProperties($ImagesProperty);

                    if ($IsSaved) {
                        $ImgCount++;
                    } else {
                        $ImgFailed++;
                    }
                }

                // $msg '... successful assigned .... images ...
                if ($ImgCount) {
                    $msg_ok = ' Successful saved ' . $ImgCount . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_ok, 'notice');
                }
                if ($ImgFailed) {
                    $msg_bad = ' Failed on save of ' . $ImgFailed . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_bad, 'error');
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing apply_imagesProperties: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // Create list of CIDS and append to link URL like in PropertiesView above
        // &ID[]=2&ID[]=3&ID[]=4&ID[]=12
        $cids = $this->input->get('cid', 0, 'int');
        $link = 'index.php?option=' . $this->option . '&view=' . $this->view_list . '&' . http_build_query(
                ['cid' => $cids],
            );
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Exit without saving
     *
     * @since version 4.3
     */
    public function cancel_imagesProperties()
    {
        $this->checkToken();

        $link = 'index.php?option=com_rsgallery2&view=images';
        $this->setRedirect($link);
    }


    /**
     * Delete selected images
     *
     * @since version 4.3
     */
    public function delete_imagesProperties()
    {
        $this->checkToken();

        $msg     = "delete_imagesProperties: " . '<br>';
        $msgType = 'notice';

        try {
            // selected ids
            $sids = $this->input->get('sid', 0, 'int');
            $cids = $this->input->get('cid', 0, 'int');

            // unset($ids[$i]);

            $this->checkToken();

            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                // delete them all
                $model = $this->getModel('image');
                $model->delete($sids);

                // Remove from display list
                foreach ($sids as $sid) {
                    $key = array_search($sid, $cids);
                    if ($key !== false) {
                        unset($cids[$key]);
                    }
                }

                // success
                $msg = 'Deleted ' . count($sids) . ' images';
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing delete_imagesProperties: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // $link = 'index.php?option=com_rsgallery2&view=imagesProperties' .....;
        $link = 'index.php?option=' . $this->option . '&view=' . $this->view_list . '&' . http_build_query(
                ['cid' => $cids],
            );

        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * rotate_images_left directs selected master images and all dependent images to be turned left against the clock
     *
     *
     * @since version 4.3
     */
    public function rotate_images_left()
    {
        // Done later: $this->checkToken();

        $msg = "rotate_left: " . '<br>';

        $direction = 90.000;
        $this->rotate_images($direction, $msg);
    }

    /**
     * rotate_images_right directs selected master images and all dependent images to be turned right with the clock
     *
     *
     * @since version 4.3
     */
    public function rotate_images_right()
    {
        // Done later: $this->checkToken();

        $msg = "rotate_right: " . '<br>';

        $direction = -90.000;
        $this->rotate_images($direction, $msg);
    }

    /**
     * rotate_images_180 directs selected master image and all dependent images to be turned 180 degrees (upside down)
     *
     *
     * @since version 4.3
     */
    public function rotate_images_180()
    {
        // Done later: $this->checkToken();

        $msg = "rotate_180: " . '<br>';

        $direction = 180.000;
        $this->rotate_images($direction, $msg);
    }

    /**
     * rotate_images directs the master image and all dependent images to be turned by given degrees
     *
     * @param   double  $direction  angle to turn the image
     * @param   string  $msg        start of message to be given to the user on setRedirect
     *
     *
     * @throws Exception
     * @since version 4.3
     */
    public function rotate_images($direction = -90.000, $msg)
    {
        $this->checkToken();

        $msgType   = 'notice';
        $ImgCount  = 0;
        $ImgFailed = 0;

        try {
            $this->checkToken();

            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                // selected ids
                $sids = $this->input->get('sid', 0, 'int');

                // toDo: create imageDb model
                $modelImages = $this->getModel('images');

                // Needed filename and gallery id
                //$fileNames = $modelImages->fileNamesFromIds($sids);
                $imgFileDatas = $modelImages->ids2FileData($sids);

                $modelFile = $this->getModel('imageFile');

                foreach ($imgFileDatas as $imgFileData) {
                    //$fileName = $imgFileData ['name'];
                    //$galleryId =  $imgFileData ['gallery_id'];
                    $fileName  = $imgFileData->name;
                    $galleryId = $imgFileData->gallery_id;
                    $id        = $imgFileData->id;

                    $IsSaved = $modelFile->rotate_image($id, $fileName, $galleryId, $direction);

                    if ($IsSaved) {
                        $ImgCount++;
                    } else {
                        $ImgFailed++;
                    }
                }

                // $msg '... successful assigned .... images ...
                if ($ImgCount) {
                    $msg_ok = ' Successful rotated ' . $ImgCount . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_ok, 'notice');
                }
                if ($ImgFailed) {
                    $msg_bad = ' Failed on rotation of ' . $ImgFailed . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_bad, 'error');
                }

                // not all images were rotated
                if ($ImgCount < count($imgFileDatas)) {
                    $msgType = 'warning';
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing rotate_images: ""' . $direction . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // Create list of CIDS and append to link URL like in PropertiesView above
        // &ID[]=2&ID[]=3&ID[]=4&ID[]=12
        $cids = $this->input->get('cid', 0, 'int');
        $link = 'index.php?option=' . $this->option . '&view=' . $this->view_list . '&' . http_build_query(
                ['cid' => $cids],
            );
        $link = 'index.php?option=' . $this->option . '&view=imagesProperties' . '&' . http_build_query(['cid' => $cids],
            );
        //$this->setRedirect($link, $msg, $msgType);
        $this->setRedirect($link);
    }

    /**
     * flip_images_horizontal directs selected master images and all dependent images to be flipped horizontal (left <-> right)
     *
     *
     * @since version 4.3
     */
    public function flip_images_horizontal()
    {
        $this->checkToken();

        $msg = "flip_images_horizontal: " . '<br>';

        $flipMode = IMG_FLIP_HORIZONTAL; //  IMG_FLIP_VERTICAL,  IMG_FLIP_BOTH
        $this->flip_images($flipMode, $msg);
    }

    /**
     * flip_images_vertical directs selected master image and all dependent images to be flipped horizontal (top <-> bottom)
     *
     *
     * @since version 4.3
     */
    public function flip_images_vertical()
    {
        // Done later: $this->checkToken();

        $msg = "flip_images_vertical: " . '<br>';

        $flipMode = IMG_FLIP_VERTICAL;
        $this->flip_images($flipMode, $msg);
    }

    /**
     * flip_images_both directs the master image and all dependent images to be flipped horizontal and vertical
     *
     *
     * @since version 4.3
     */
    public function flip_images_both()
    {
        // Done later: $this->checkToken();

        $msg = "flip_images_both: " . '<br>';

        $flipMode = IMG_FLIP_BOTH;
        $this->flip_images($flipMode, $msg);
    }

    /**
     * flip_images directs the master image and all dependent images to be flipped
     * according to mode horizontal, vertical or both
     *
     * @param   string  $msg  start of message to be given to the user on setRedirect
     *
     * @since version 4.3
     */
    public function flip_images($flipMode, $msg)
    {
        $msgType = 'notice';

        try {
            $this->checkToken();

            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
            } else {
                // selected ids
                $sids = $this->input->get('sid', 0, 'int');

                // toDo: create imageDb model
                $modelImages = $this->getModel('images');
                // Needed filename and gallery id
                //$fileNames = $modelImages->fileNamesFromIds($sids);
                $imgFileDatas = $modelImages->ids2FileData($sids);

                $modelFile = $this->getModel('imageFile');

                foreach ($imgFileDatas as $imgFileData) {
                    //$fileName = $imgFileData ['name'];
                    //$galleryId =  $imgFileData ['gallery_id'];
                    $fileName  = $imgFileData->name;
                    $galleryId = $imgFileData->gallery_id;
                    $id        = $imgFileData->id;

                    $IsSaved = $modelFile->flip_image($id, $fileName, $galleryId, $flipMode);

                    if ($IsSaved) {
                        $ImgCount++;
                    } else {
                        $ImgFailed++;
                    }
                }

                // $msg '... successful assigned .... images ...
                if ($ImgCount) {
                    $msg_ok = ' Successful flipped ' . $ImgCount . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_ok, 'notice');
                }
                if ($ImgFailed) {
                    $msg_bad = ' Failed on flipping of ' . $ImgFailed . ' image properties';
                    Factory::getApplication()->enqueueMessage($msg_bad, 'error');
                }

                // not all images were rotated
                if ($ImgCount < count($imgFileDatas)) {
                    $msgType = 'warning';
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing flip_images: ""' . $flipMode . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // Create list of CIDS and append to link URL like in PropertiesView above
        // &ID[]=2&ID[]=3&ID[]=4&ID[]=12
        $cids = $this->input->get('cid', 0, 'int');
        $link = 'index.php?option=' . $this->option . '&view=' . $this->view_list . '&' . http_build_query(
                ['cid' => $cids],
            );
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Collects from user input the parameter of each image into one object per image
     *
     * @return array of images with input properties each
     *
     * @since 4.3.2
     */
    public function ImagesPropertiesFromInput()
    {
        $ImagesProperties = [];

        try {
            $input = Factory::getApplication()->input;

            $cids         = $input->get('cid', 0, 'int');
            $titles       = $input->get('title', 0, 'string');
            $descriptions = $input->get('description', 0, 'string');

            $idx = 0;
            foreach ($cids as $Idx => $cid) {
                $ImagesProperty = new \stdClass();

                $ImagesProperty->cid = $cids [$Idx];
                // ToDo: Check for not HTML input
                $ImagesProperty->title       = $titles [$Idx];
                $ImagesProperty->description = $descriptions [$Idx];

                $ImagesProperties [] = $ImagesProperty;
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing ImagesPropertiesFromInput: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $ImagesProperties;
    }


}
