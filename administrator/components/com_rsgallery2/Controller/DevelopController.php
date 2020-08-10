<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Image\Image;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Component\Rsgallery2\Administrator\Model\ImagePaths;

/**
 * Rsgallery2 master display controller.
 *
 * @since  1.0
 */
class DevelopController extends BaseController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * Recognized key values include 'name', 'default_task', 'model_path', and
     * 'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   \JInput              $input    Input
     *
     * @since   1.0
     *
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

    }
    /**/

    /**
	 * The default view.
	 *
	 * @var    string
	 * @since  1.0
	 */
//	protected $default_view = 'rsgallery2';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link \JFilterInput::clean()}.
	 *
	 * @return  BaseController|bool  This object to support chaining.
	 *
	 * @since   1.0
	 */
	public function display($cachable = false, $urlparams = array())
	{
		
		// $model = $this->getModel('');
		
		return parent::display();
	}

	/**
	 * Proxy for getModel.
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
	 *

    public function getModel($name = 'Develop', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
    /**/

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.0.0
     */
    public function createGalleries_001()
    {
        $msg = "DevelopController.createGalleries_001: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
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
                    $msg .= "Error at createGalleries_001 1 items";
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
     * @since 5.0.0
     */
    public function createGalleries_010()
    {
        $msg = "DevelopController.createGalleries_010: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
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
                    $msg .= "Error at createGalleries_010 10 items";
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
     * @since 5.0.0
     */
    public function createGalleries_100()
    {
        $msg = "DevelopController.createGalleries_100: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
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
                    $msg .= "Error at createGalleries_100 100 items";
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
     * @since 5.0.0
     */
    public function createGalleries_random()
    {
        $msg = "DevelopController.createGalleries_random: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $count = random_int (10, 40) + 10; // 10-50

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createGalleries($count);

                $isOk = $this->createGalleries($count);

                if ($isOk) {
                    $msg .= "Successful created 1 items";
                } else {
                    $msg .= "Error at createGalleries_random 1 items";
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



    /**/

    /**
     * Copies all old configuration items to new configuration
     *
     * @since 5.0.0
     */
    public function createImages_001()
    {
        $msg = "DevelopController.createImages_001: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;
                $count = 1;

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages(1, $galleryId);
//

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created 1 items";
                } else {
                    $msg .= "Error at createImages_001 1 items";
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
     * @since 5.0.0
     */
    public function createImages_010()
    {
        $msg = "DevelopController.createImages_010: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;
                $count = 10;


//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages(10, $galleryId);

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created 10 items";
                } else {
                    $msg .= "Error at createImages_010 10 items";
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
     * @since 5.0.0
     */
    public function createImages_100()
    {
        $msg = "DevelopController.createImages_100: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;
                $count = 100;


//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages(100, $galleryId);
//

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created 100 items";
                } else {
                    $msg .= "Error at createImages_100 100 items";
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
     * @since 5.0.0
     */
    private function createImages_random()
    {
        $msg = "DevelopController.createImages_random: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // ToDo: determine gallery from input
                $galleryId = 2;

                $count = random_int (10, 40) + 10; // 10-50

//                $j3xModel = $this->getModel('Develop');
//
//                $isOk = $j3xModel->createImages($count, $galleryId);
//

                $isOk = $this->createImages($count, $galleryId);

                if ($isOk) {
                    $msg .= "Successful created ' . $count . ' items";
                } else {
                    $msg .= "Error at createImages_random ' . $count . ' items";
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


    public function createGalleries($count, $parentId=1)
    {
        $isCreated = false;

        try {

            $allCreated = true;

            for ($idx = 0; $idx < $count; $idx++) {

                $dateTime = $this->stdDateTime();
                $title = $dateTime . ' (' . $idx . ')';

                $description = 'dev created';

                // Factory::getApplication()->enqueueMessage($useFileName, 'notice');

                // gallery db handle
                $modelDb = $this->getModel('Gallery');

                $j4xImagePath = new ImagePaths ();
                //$modelDb = new Joomla\Component\Rsgallery2\Administrator\Model\eGallery();
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

    public function createImages($count, $galleryId)
    {
        $isCreated = false;

        try {

            $allCreated = true;

            for ($idx = 0; $idx < $count; $idx++) {

                $dateTime = $this->stdDateTime();
                $useFileName = $dateTime . '.jpg';
                $title = $dateTime . ' (' . $idx . ')';

                $description = 'dev created';

                // Factory::getApplication()->enqueueMessage($useFileName, 'notice');

                // image db handle
                $modelDb = $this->getModel('Image');

                $j4xImagePath = new ImagePaths ();
                //$modelDb = new Joomla\Component\Rsgallery2\Administrator\Model\Image();
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

            }

            $isCreated = $allCreated;

        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isCreated;
    }

    // ToDo: Move to own helper class
    private function stdDateTime () {
        $now = '2020_error_stdDateTime';

        try
        {
            $datetime = new \DateTime();
//            $now = $datetime->format('Y.m.d_H.i.s.v');
            $now = $datetime->format('Y.m.d_H.i.s.u');

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $now;
    }



    /**
     * Standard cancel (may not be used)
     *
     * @return bool
     *
     * @since version 4.3
     */
    public function cancel()
    {
        Session::checkToken();

        $link = 'index.php?option=com_rsgallery2&view=maintenance';
        $this->setRedirect($link);

        return true;
    }

}
