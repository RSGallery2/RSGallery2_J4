<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use DateTime;
use Joomla\CMS\Input\Input;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\Rsg2ExtensionModel;




// use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;

/**
 * Rsgallery2 master display controller.
 *
     * @since      5.1.0
 */
class DevelopController extends BaseController
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
     * @since   5.1.0     *
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

    }
    /**/

    /**
     * The default view.
     *
     * @var    string
     * @since  5.1.0     */
    //  protected $default_view = 'rsgallery2';

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable  If true, the view output will be cached
     *
     * @return  BaseController|bool  This object to support chaining.
     *
     * @license    GNU General Public License version 2 or later
     *
     * @since      5.1.0
     */
    public function display($cachable = false, $urlparams = [])
    {
        // $model = $this->getModel('');

        return parent::display();
    }

    /**
     * Proxy for getModel.
     *
     * @param   string  $name
     * @param   string  $prefix
     * @param   array   $config
     *
     * @return mixed
     *
     * @since  5.1.0     *

    public function getModel($name = 'Develop', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
    /**/

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    public function createGalleries_001()
    {
        $msg     = "DevelopController.createGalleries_001: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $count = 1;

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createGalleries(1);

                $isOk = $this->createGalleries($count);

                if ($isOk) {
                    $msg .= "Successful created 1 items";
                } else {
                    $msg     .= "Error at createGalleries_001 1 items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createGalleries_001: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createGalleries';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    public function createGalleries_010()
    {
        $msg     = "DevelopController.createGalleries_010: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $count = 10;

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createGalleries(10);

                $isOk = $this->createGalleries($count);

                if ($isOk) {
                    $msg .= "Successful created 10 items";
                } else {
                    $msg     .= "Error at createGalleries_010 10 items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createGalleries_010: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createGalleries';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    public function createGalleries_100()
    {
        $msg     = "DevelopController.createGalleries_100: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $count = 100;

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createGalleries(100);

                $isOk = $this->createGalleries($count);

                if ($isOk) {
                    $msg .= "Successful created 100 items";
                } else {
                    $msg     .= "Error at createGalleries_100 100 items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createGalleries_100: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createGalleries';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    public function createGalleries_random()
    {
        $msg     = "DevelopController.createGalleries_random: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $count = random_int(10, 40) + 10; // 10-50

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createGalleries($count);

                $isOk = $this->createGalleries($count);

                if ($isOk) {
                    $msg .= "Successful created 1 items";
                } else {
                    $msg     .= "Error at createGalleries_random 1 items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createGalleries_random: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createGalleries';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    public function createImages_001()
    {
        $msg     = "DevelopController.createImages_001: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;
                $count     = 1;

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages(1, $galleryId);
//

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created 1 items";
                } else {
                    $msg     .= "Error at createImages_001 1 items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createImages_001: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createImages';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    public function createImages_010()
    {
        $msg     = "DevelopController.createImages_010: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;
                $count     = 10;

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages(10, $galleryId);

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created 10 items";
                } else {
                    $msg     .= "Error at createImages_010 10 items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createImages_010: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createImages';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    public function createImages_100()
    {
        $msg     = "DevelopController.createImages_100: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;
                $count     = 100;

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages(100, $galleryId);
//

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created 100 items";
                } else {
                    $msg     .= "Error at createImages_100 100 items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createImages_100: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createImages';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.1.0     */
    private function createImages_random()
    {
        $msg     = "DevelopController.createImages_random: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;

                $count = random_int(10, 40) + 10; // 10-50

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages($count, $galleryId);
//

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created ' . $count . ' items";
                } else {
                    $msg     .= "Error at createImages_random ' . $count . ' items";
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createImages_random: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=createImages';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Create count galleries in DB
     * The title will contain the actual date and seconds
     *
     * @param $count
     * @param $parentId
     *
     * @return bool
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function createGalleries($count, $parentId = 1)
    {
        $isCreated = false;

        try {
            $allCreated = true;

            for ($idx = 0; $idx < $count; $idx++) {
                $dateTime = $this->stdDateTime();
                $title    = $dateTime . ' (' . $idx . ')';

                $description = 'dev created';

                // Factory::getApplication()->enqueueMessage($useFileName, 'notice');

                // gallery db handle
                // @var GalleryModel $modelDB
                $modelDb = $this->getModel('Gallery');

                //$j4xImagePath = new ImagePathsModel (); ? J3x
                //$modelDb = new Rsgallery2\Component\Rsgallery2\Administrator\Model\eGallery();
                //$modelDb = new eGallery();
                // $modelDb = new eGallery();

                $isCreated = $modelDb->createGallery($title, $parentId, $description);

                if (empty($isCreated)) {
                    $allCreated = false;

                    // actual give an error
                    //$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
                    $msg = 'Create gallery DB item for "' . $title . '" failed. Use maintenance -> Consolidate image database to check it ';
                    Factory::getApplication()->enqueueMessage($msg, 'error');
                }
            }

            $isCreated = $allCreated;
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isCreated;
    }

    /**
     * Create count images with given parent gallery in DB
     * The title will contain the actual date and seconds
     *
     * @param $count
     * @param $galleryId
     *
     * @return bool
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function createImages($count, $galleryId)
    {
        $isCreated = false;

        try {
            $allCreated = true;

            for ($idx = 0; $idx < $count; $idx++) {
                $dateTime    = $this->stdDateTime();
                $useFileName = $dateTime . '.jpg';
                $title       = $dateTime . ' (' . $idx . ')';

                $description = 'dev created';

                // Factory::getApplication()->enqueueMessage($useFileName, 'notice');

                // image db handle
                // @var ImageModel $modelDB
                $modelDb = $this->getModel('Image');

                // $j4xImagePath = new ImagePathsModel (); ? j3x
                //$modelDb = new Rsgallery2\Component\Rsgallery2\Administrator\Model\Image();
                //$modelDb = new Image();
                // $modelDb = new image();

                $imageId = $modelDb->createImageDbItem($useFileName, $title, $galleryId, $description);

                if (empty($imageId)) {
                    $allCreated = false;

                    // actual give an error
                    //$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
                    $msg = 'Create image DB item for "' . $useFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
                    Factory::getApplication()->enqueueMessage($msg, 'error');
                }

                /* ???? model
                header("Content-type: image/png");
                $im = @imagecreate(50, 100)
                or die("Kann keinen neuen GD-Bild-Stream erzeugen");
                $background_color = imagecolorallocate($im, 0, 0, 0);
                $text_color = imagecolorallocate($im, 233, 14, 91);
                imagestring($im, 1, 5, 5, "Ein Test-String", $text_color);
                imagepng($im);
// PHP 8.5 deprecated, needs PHP 8.0
//                imagedestroy($im);
                /**/
                /**
                // Erzeut ein leeres Bild und fügt ein wenig Text hinzu
                $im = imagecreatetruecolor(120, 20);
                $text_color = imagecolorallocate($im, 233, 14, 91);
                imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);

                // Die Content-Type-Kopfzeile senden, in diesem Fall image/jpeg
                header('Content-Type: image/jpeg');

                //// Das Bild ausgeben
                //imagejpeg($im);

                // Das Bild als 'simpletext.jpg' speichern
                imagejpeg($im, 'simpletext.jpg');

                // sets background to red
                $red = imagecolorallocate($im, 255, 0, 0);
                imagefill($im, 0, 0, $red);

                // Den Speicher freigeben
// PHP 8.5 deprecated, needs PHP 8.0
//                imagedestroy($im);

                /**/
            }

            $isCreated = $allCreated;
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isCreated;
    }

    // ToDo: Move to own helper class

    /**
     * Standard date for auto generate galleries and images
     *
     * @return string
     *
     * @throws \Exception
     * @since  5.1.0
     */
    private function stdDateTime()
    {
        $now = '2020_error_stdDateTime';

        try {
            $datetime = new DateTime();
//            $now = $datetime->format('Y.m.d_H.i.s.v');
            $now = $datetime->format('Y.m.d_H.i.s.u');
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $now;
    }

    /**
     * Standard cancel (may not be used)
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
     * On develop check installation message the version number may be
     * patched for the changelog display from / to version
     *
     * @since 5.1.0     */
    public function useOldVersion()
    {
        $msg     = "DevelopController.useOldVersion: ";
        $msgType = 'notice';
        echo "test";
        $link = 'index.php?option=com_rsgallery2&view=develop&layout=InstallMessage';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $input        = Factory::getApplication()->input;
                $lowerVersion = $input->get('PreviousVersion', '', 'STRING');
                if (empty($lowerVersion)) {
                    $lowerVersion = '5.0.0.1';
                }

                $msg .= ' Successful assigned RSG2 version: ' . $lowerVersion;

                // toDO: secure by check for numbers and points
                // split ('.')

                //$link = 'index.php?option=com_rsgallery2&view=develop&layout=InstallMessage'
                //    . '&lowerVersion=' . $lowerVersion;
                $link .= '&lowerVersion=' . $lowerVersion;
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing useOldVersion: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect($link);
    }

    /**
     * On installation of RSG2 the message for the changelog display depends on the
     * from / to version number. The RSG2 "extension" number in the db can be
     * set here (from maintenace -> Test Install/Update message (form)
     *
     * @since 5.1.0     */
    public function assignVersion()
    {
        // ??? maintenance / manifest ....
        $msg     = "DevelopController.assignVersion: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $input        = Factory::getApplication()->input;
                $lowerVersion = $input->get('PreviousVersion', '', 'STRING');
                if (empty($lowerVersion)) {
                    $lowerVersion = '5.0.0.1';
                }

                // secure by check for numbers and points
                // split ('.')

                $isOk = false;

                if ($isOk) {
                    $msg .= ' Successful assigned RSG2 version: ' . $lowerVersion;
                } else {
                    $msg     .= 'Error at assignment of RSG2 version: ' . $lowerVersion;
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing assignVersion: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=develop&layout=InstallMessage';
        $link = Route::_('index.php?option=com_rsgallery2&view=develop&layout=InstallMessage');
        $this->setRedirect($link);
    }

    /**
     * Merge actual config with standard values and .....
     * @return mixed
     *
     * @throws \Exception
     * @since  5.1.0
     */
    public function mergeParams()
    {
        $actualParams  = Rsg2ExtensionModel::readRsg2ExtensionConfiguration();
        $defaultParams = Rsg2ExtensionModel::readRsg2ExtensionDefaultConfiguration();
        //$mergedParams  = Rsg2ExtensionModel::mergeDefaultAndActualParams($this->defaultParams, $this->actualParams);
        $mergedParams  = Rsg2ExtensionModel::mergeDefaultAndActualParams($defaultParams, $actualParams);
        $isWritten     = Rsg2ExtensionModel::replaceRsg2ExtensionConfiguration($mergedParams);

        return $mergedParams;
    }
}
