<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Input\Input;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Utilities\ArrayHelper;

/**
 * Rsgallery2 master display controller.
 *
 * @since __BUMP_VERSION__
 */
class MaintenanceController extends BaseController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     *                                         Recognized key values include 'name', 'default_task', 'model_path', and
     *                                         'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input              $input    Input
     *
     * @since   5.1.0     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * Extract configuration variables from RSG2 config file to reset to original values
     *
     * @throws \Exception
     *
     * @since  5.1.0     */
    public function CheckImagePaths()
    {
        $isOk = false;

        $msg     = "MaintenanceCleanUp.CheckImagePaths: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $MaintModel      = $this->getModel('Maintenance');
                $isPathsExisting = $MaintModel->CheckImagePaths();
                if ($isPathsExisting) {
                    // config saved message
                    $msg .= Text::_('All paths to images exist', true);
                } else {
                    $msg     .= "Missing pathes for images found (dependend on gallery id or size)'";
                    $msgType = 'warning';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing CheckImagePaths: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Extract configuration variables from RSG2 config file to reset to original values
     *
     * @throws \Exception
     *
     * @since  5.1.0     */
    public function RepairImagePaths()
    {
        $isOk = false;

        $msg     = "MaintenanceCleanUp.RepairImagePaths: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $MaintModel = $this->getModel('Maintenance');
                $isSaved    = $MaintModel->RepairImagePaths();

                if ($isSaved) {
                    // config saved message
                    $msg .= Text::_('Image paths are created', true);
                } else {
                    $msg     .= "Error at repair image paths'";
                    $msgType = 'warning';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing RepairImagePaths: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        $this->setRedirect($link, $msg, $msgType);
    }


    /**
     * The default view.
     *
     * @var    string
     * @since  5.1.0     */
//	protected $default_view = 'rsgallery2';

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable  If true, the view output will be cached
     *
     * @return  BaseController|bool  This object to support chaining.
     *
     * @license    GNU General Public License version 2 or later
     *
     * @since      5.1.0     *
	public function display($cachable = false, $urlparams = [])
	{

		// $model = $this->getModel('');




		return parent::display();
	}
    /**/

    /**
     * Proxy for getModel.
     *
     * @param   string  $name
     * @param   string  $prefix
     * @param   array   $config
     *
     * @return mixed
     *
     * @since  5.1.0     */
    /**
    public function getModel($name = 'Maintenance', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
	/**/



    /**
     *
     *
     * @return bool
     *
     * @since  5.1.0     */
    public function checkImageExifData()
    {
        $this->checkToken();

        $msgType = 'notice';
        $link    = 'index.php?option=com_rsgallery2&view=maintenance&layout=checkimageexif';

        try {
            // Access check
            $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
            if (!$canAdmin) {
                $msg     = Text::_('JERROR_ALERTNOAUTHOR');
                $msgType = 'warning';
                // replace newlines with html line breaks.
                $msg = nl2br($msg);
                $this->setRedirect($link, $msg, $msgType);
            } else {
//                //--- input: collect selected gallery id, and file names -----------------------------------
//
//                // ToDo: collect selected gallery id, and filenames
////                $id = $this->input->get('id', 0, 'int');
//                $imageNames = $this->input->get('jform', [], 'array');
//
//                $cids = $this->input->get('cid', [], 'ARRAY');
//                ArrayHelper::toInteger($cids);
//
//                // ToDo: Determine filenames with real paths
//
////                // simulate
////                $filenames = [];
////
////                foreach ($data as $Idx => $fileName) {
////
////                    $filenames [] = $fileName;
////                }
//
//                $fileNames = [];
//
//                foreach ($imageNames as $idx => $fileName) {
//                    if (in_array($idx, $cids)) {
//                        $fileNames [] = $fileName;
//                    }
//                }
//
//                if (count($fileNames) > 0) {
//                    //--- collect EXIF data of files -----------------------------------
//
//                    $modelImage      = $this->getModel('image');
//                    $exifDataOfFiles = $modelImage->exifDataAllOfFiles($fileNames);
//
//                    //--- prepare send to form ---------------------------------
//
//                    $exifDataJsonified = json_encode($exifDataOfFiles);
//
////                $link = $link . '&amp;exifData=' . $exifDataJsonified;
//                    $link = $link
//                        . '&' . http_build_query(array('cid' => $cids))
//                        . '&exifData=' . $exifDataJsonified;
//                }

                $input = $this->input;

                $data  = $input->post->get('jform', [], 'array');
                $test1 = json_encode($data);

                $cids = $input->get('cid', [], 'ARRAY');
//                $galleryIds = $input->get('galIds', [], 'array');
//                $imageNames  = $input->get('imgNames', [], 'array');
                $galleryIds = ArrayHelper::toInteger($data ['galIds']);
//                $imageNames  = ArrayHelper::toString ($data ['imgNames']);
                $imageNames = $data ['imgNames'];

//                $link .= ''
//                    . '&' . http_build_query(array('cids' => $cids))
//                    . '&' . http_build_query(array('galIds' => $galleryIds))
//                    . '&' . http_build_query(array('imgNames' => $imageNames))
//                    ;
                $link .= ''
                    . '&' . http_build_query(['cids' => $cids])
                    . '&' . http_build_query(['galIds' => $galleryIds])
                    . '&' . http_build_query(['imgNames' => $imageNames]);
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing checkImageExifData: ' . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        $this->setRedirect($link);

        return true;
    }

    /**
     * On cancel goto maintenance
     *
     * @return bool
     *
     * @since  5.1.0     */
    public function cancel()
    {
        $this->checkToken();

        $link = 'index.php?option=com_rsgallery2&view=maintenance';
        $this->setRedirect($link);

        return true;
    }

    /**
     * On cancel goto com_rsgallery2
     *
     * @return bool
     *
     * @since  5.1.0     */
    public function cancel_rsg2()
    {
        $this->checkToken();

        $link = 'index.php?option=com_rsgallery2';
        $this->setRedirect($link);

        return true;
    }

//    /**
//     * Proxy for getModel
//     *
//     * @param   string  $name    The model name. Optional.
//     * @param   string  $prefix  The class prefix. Optional.
//     * @param   array   $config  The array of possible config values. Optional.
//     *
//     * @return  BaseDatabaseModel  The model.
//     *
//     * @since __BUMP_VERSION__
//     */
//    public function getModel($name = 'maintenance', $prefix = 'Administrator', $config = array('ignore_request' => true))
//    {
//        return parent::getModel($name, $prefix, $config);
//    }
//

}
