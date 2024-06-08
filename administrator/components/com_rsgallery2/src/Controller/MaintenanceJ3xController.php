<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright (c) 2016-2024 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;


/**
 * global $Rsg2DebugActive;
 *
 * if ($Rsg2DebugActive)
 * {
 * // Include the JLog class.
 * //    jimport('joomla.log.log');
 *
 * // identify active file
 * JLog::add('==> ctrl.config.php ');
 * }
 * /**/
class MaintenanceJ3xController extends AdminController
{

    /**
     * Constructor
     *
     * @param array $config An optional associative array of configuration settings.
     * Recognized key values include 'name', 'default_task', 'model_path', and
     * 'view_path' (this list is not meant to be comprehensive).
     * @param MVCFactoryInterface $factory The factory.
     * @param CMSApplication $app The JApplication for the dispatcher
     * @param \JInput $input Input
     *
     * @since __BUMP_VERSION__
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

    }

    // /**
     // * applyExistingJ3xData
     // * J3x Configuration-, galleries, images and more data will be adjusted to RSG2 J4x form
     // *
     // * @since __BUMP_VERSION__
     // */
    // public function applyExistingJ3xData()
    // {
        // $msg = "MaintenanceJ3xController.applyExistingJ3xData: ";
        // $msgType = 'notice';

        // $this->checkToken();

        // $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        // if (!$canAdmin) {
            // //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            // $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            // $msgType = 'warning';
            // // replace newlines with html line breaks.
            // str_replace('\n', '<br>', $msg);
        // } else {
            // try {
                // $j3xModel = $this->getModel('MaintenanceJ3x');

                // $isOk = $j3xModel->applyExistingJ3xData();

                // if ($isOk) {
                    // $msg .= "Successful copied J3x DB galleries, J3x DB images and J3x configuration items";
                // } else {
                    // $msg .= "Error at applyExistingJ3xData items";
                    // $msgType = 'error';
                // }

            // } catch (\RuntimeException $e) {
                // $OutTxt = '';
                // $OutTxt .= 'Error executing applyExistingJ3xData: "' . '<br>';
                // $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                // $app = Factory::getApplication();
                // $app->enqueueMessage($OutTxt, 'error');
            // }

        // }

        // $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        // $this->setRedirect($link, $msg, $msgType);
    // }

    /**
     * Copies all old configuration items to new configuration
     * ...User: different return page
     * @since __BUMP_VERSION__
     */
    public function copyJ3xConfig2J4xOptionsUser()
    {
		$this->copyJ3xConfig2J4xOptions ();

	    $link = 'index.php?option=com_rsgallery2';
	    $this->setRedirect($link);
    }

	/**
     * Copies all old configuration items to new configuration
     *
     * @since __BUMP_VERSION__
     */
    public function copyJ3xConfig2J4xOptions()
    {
        $msg = "MaintenanceJ3xController.copyJ3xConfig2J4xOptions: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {
                $j3xModel = $this->getModel('MaintenanceJ3x');

                $isOk = $j3xModel->collectAndCopyJ3xConfig2J4xOptions();

                if ($isOk) {
                    $msg .= "Successful applied J3x configuration items";

	                $isOk = ConfigRawModel::writeConfigParam ('j3x_db_config_copied', true);
	                if ($isOk) {
		                $msg .= " and assigned copied flag";

	                } else {
		                $msg .= "!!! but error at writeConfigParam !!!";
		                $msgType = 'error';
	                }


                } else {
                    $msg .= "Error at collectAndCopyJ3xConfig2J4xOptions items";
                    $msgType = 'error';
                }

            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing collectAndCopyJ3xConfig2J4xOptions: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

        }

        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbcopyj3xconfig';
        $this->setRedirect($link, $msg, $msgType);
    }


	/**
	 * Copies all old J3x gallery items to J4 galleries in database
	 * ...User: different return page
	 * @since __BUMP_VERSION__
	 */
	public function copyDbJ3xGalleries2J4xUser()
	{
		$this->copyDbJ3xGalleries2J4x ();

		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link);
	}

	/**
     * Copies all old J3x gallery items to J4 galleries in database
     *
     * @since __BUMP_VERSION__
     */
    public function copyDbJ3xGalleries2J4x()
    {
        $msg = "MaintenanceJ3xController.copyDbJ3xGalleries2J4x: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {
                $j3xModel = $this->getModel('MaintenanceJ3x');

                $isOk = $j3xModel->copyDbAllJ3xGalleries2J4x();

                if ($isOk) {

	                $msg .= "Successful applied J3x gallery items ";

                    $isOk = ConfigRawModel::writeConfigParam ('j3x_db_galleries_copied', true);
	                if ($isOk) {
		                $msg .= " and assigned copied flag";

	                } else {
		                $msg .= "!!! but error at writeConfigParam !!!";
		                $msgType = 'error';
	                }

                } else {
	                $msg .= "Error at copyDbJ3xGalleries2J4x items";
	                $msgType = 'error';
                }

            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing copyDbJ3xGalleries2J4x: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

        }

        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleries';
        $this->setRedirect($link, $msg, $msgType);
    }

	/**
     * Copies all old J3x gallery items to J4 galleries
     *
     * @since __BUMP_VERSION__
     */
    public function copyDbSelectedJ3xGalleries2J4x()
    {
        $msg = "MaintenanceJ3xController.copyDbSelectedJ3xGalleries2J4x: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $cids = $this->input->get('cid', array(), 'array');

                if (!is_array($cids) || count($cids) < 1) {
                    //$this->app->enqueueMessage(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'warning');
                    $msg .= Text::_($this->text_prefix . '_NO_ITEM_SELECTED');
                    $msgType = 'warning';
                } else {

	                $j3xModel = $this->getModel('MaintenanceJ3x');

	                $isOk = $j3xModel->copyDbSelectedJ3xGalleries2J4x($cids);
	                if ($isOk)
	                {
		                $msg .= "Successful applied J3x gallery items ";

		                $isOk = ConfigRawModel::writeConfigParam('j3x_db_galleries_copied', true);

		                if ($isOk)
		                {
			                $msg .= "and assigned configuration parameters";

		                }
		                else
		                {
			                $msg     .= "!!! but error at writeConfigParam !!!";
			                $msgType = 'error';
		                }

	                }
	                else
	                {
		                $msg     .= "Error at copyDbJ3xGalleries2J4x items";
		                $msgType = 'error';
	                }
                }

            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing copyDbSelectedJ3xGalleries2J4x: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

        }

        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleries';
        $this->setRedirect($link, $msg, $msgType);
    }

	/**
	 * Reset image table to empty state (No images in RSG J4x
	 * ? used in mantenance ?
	 * @return bool
	 *
	 * @since __BUMP_VERSION__
	 */
    public function resetImagesTable()
    {
        $isOk = false;

        $msg = "ImagesController.resetImagesTable: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {
                // Get the model.
                /** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel */
                $j3xModel = $this->getModel('MaintenanceJ3x');

                // Remove the items.
                $isOk = $j3xModel->resetImagesTable();
                if ($isOk) {
                    $msg .= Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET_SUCCESS');
                } else {
                    $msg .= Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET_ERROR') ;
                }

            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing resetImagesTable: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

        }

        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

//	public function copyDbSelectedJ3xImages2J4x()
//	{
//		$msg = "MaintenanceJ3xController.copyDbSelectedJ3xImages2J4x: ";
//		$msgType = 'notice';
//
//		$this->checkToken();
//
//		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
//		if (!$canAdmin) {
//			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
//			$msg .= Text::_('JERROR_ALERTNOAUTHOR');
//			$msgType = 'warning';
//			// replace newlines with html line breaks.
//			str_replace('\n', '<br>', $msg);
//		} else {
//			try {
//				$cids = $this->input->get('cid', array(), 'array');
//
//				if (!is_array($cids) || count($cids) < 1) {
//					//$this->app->enqueueMessage(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'warning');
//					$msg .= Text::_($this->text_prefix . '_NO_ITEM_SELECTED');
//					$msgType = 'warning';
//				} else {
//
//					$j3xModel = $this->getModel('MaintenanceJ3x');
//
//					$isOk = $j3xModel->copyDbSelectedJ3xImages2J4x($cids);
//					if ($isOk)
//					{
//						$msg .= "Successful applied J3x image items";
//					}
//					else
//					{
//						$msg     .= "Error at copyDbSelectedJ3xImages2J4x items";
//						$msgType = 'error';
//					}
//				}
//			} catch (\RuntimeException $e) {
//				$OutTxt = '';
//				$OutTxt .= 'Error executing copyDbSelectedJ3xImages2J4x: "' . '<br>';
//				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//				$app = Factory::getApplication();
//				$app->enqueueMessage($OutTxt, 'error');
//			}
//
//		}
//
//		//$link = 'index.php?option=com_rsgallery2&view=galleries';
//		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages';
//		$this->setRedirect($link, $msg, $msgType);
//	}

	public function copyDbImagesOfSelectedGalleries()
	{
		$msg = "MaintenanceJ3xController.copyDbImagesOfSelectedGalleries: ";
		$msgType = 'notice';

		$this->checkToken();

		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin) {
			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			try {
				$cids = $this->input->get('cid', array(), 'array');

				if (!is_array($cids) || count($cids) < 1) {
					//$this->app->enqueueMessage(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'warning');
					$msg .= Text::_($this->text_prefix . '_NO_ITEM_SELECTED');
					$msgType = 'warning';
				} else {

					$j3xModel = $this->getModel('MaintenanceJ3x');

					//$isOk = $j3xModel->copyDbSelectedJ3xImages2J4x($cids);
					$isOk = $j3xModel->copyDbImagesOfSelectedGalleries($cids);
					if ($isOk)
					{
						$msg .= "Successful applied J3x image items";
					}
					else
					{
						$msg     .= "Error at copyDbSelectedJ3xImages2J4x items";
						$msgType = 'error';
					}
				}
			} catch (\RuntimeException $e) {
				$OutTxt = '';
				$OutTxt .= 'Error executing copyDbImagesOfSelectedGalleries: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		//$link = 'index.php?option=com_rsgallery2&view=galleries';
		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Copies all old configuration items to new configuration
	 *
	 * @since __BUMP_VERSION__
	 */
	public function copyDbJ3xImages2J4xUser()
	{
		$this->copyDbJ3xImages2J4x ();

		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link);
	}

	/**
     * Copies all old J3x image items to J4 images
     *
     * @since __BUMP_VERSION__
     */
    public function copyDbJ3xImages2J4x()
    {
        $msg = "MaintenanceJ3xController.copyDbJ3xImages2J4x: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $j3xModel = $this->getModel('MaintenanceJ3x');

                $isOk = $j3xModel->copyDbAllJ3xImages2J4x();
                if ($isOk) {
	                $msg .= "Successful applied J3x image items";

                    $isOk = ConfigRawModel::writeConfigParam ('j3x_db_images_copied', true);
	                if ($isOk) {
		                $msg .= " and assigned copied flag";

	                } else {
		                $msg .= "!!! but error at writeConfigParam !!!";
		                $msgType = 'error';
	                }

                } else {
                    $msg .= "Error at copyDbJ3xImages2J4x items";
                    $msgType = 'error';
                }

            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing copyDbJ3xImages2J4x: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        //$link = 'index.php?option=com_rsgallery2&view=galleries';
        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3ximages';
        $this->setRedirect($link, $msg, $msgType);
    }

//    /**
//     * Moves all old J3x image to J4 images path
//     *
//     * @since __BUMP_VERSION__
//     */
//    public function moveAllJ3xImages2J4x()
//    {
//        $msg = "MaintenanceJ3xController.moveAllJ3xImages2J4x: ";
//        $msgType = 'notice';
//
//        $this->checkToken();
//
//        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
//        if (!$canAdmin) {
//            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
//            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
//            $msgType = 'warning';
//            // replace newlines with html line breaks.
//            str_replace('\n', '<br>', $msg);
//        } else {
//            try {
//                $j3xModel = $this->getModel('MaintenanceJ3x');
//
//                // Collect image Ids  (ToDo: collect ids by db query in $j3xModel)
//                $j3x_images = $j3xModel->j3x_imagesMergeList();
//                $j3x_imageIds = [];
//                foreach ($j3x_images as $j3x_image) {
//
//                    $j3x_imageIds [] = $j3x_image->id;
//                }
//
//                $isOk = $j3xModel->moveImagesJ3x2J4xById($j3x_imageIds);
//                if ($isOk) {
//                    $isOk = ConfigRawModel::writeConfigParam ('j3x_images_copied', true);
//
//                    $msg .= "Successful moved all J3x image files";
//                    $msgType = 'success'; // ToDo: use in all controllers
//                } else {
//                    $msg .= "Error at moveAllJ3xImages2J4x images";
//                    $msgType = 'error';
//                }
//
//            } catch (\RuntimeException $e) {
//                $OutTxt = '';
//                $OutTxt .= 'Error executing moveAllJ3xImages2J4x: "' . '<br>';
//                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//                $app = Factory::getApplication();
//                $app->enqueueMessage($OutTxt, 'error');
//            }
//
//        }
//
//        //$link = 'index.php?option=com_rsgallery2&view=galleries';
//        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=movej3ximages';
//        $this->setRedirect($link, $msg, $msgType);
//    }

//    /**
//     * Copies selected old J3x image items to J4 images path
//     *
//     * @since __BUMP_VERSION__
//     */
//    public function moveSelectedJ3xImages2J4x()
//    {
//        $msg = "MaintenanceJ3xController.moveSelectedJ3xImages2J4x: ";
//        $msgType = 'notice';
//
//        $this->checkToken();
//
//        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
//        if (!$canAdmin) {
//            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
//            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
//            $msgType = 'warning';
//            // replace newlines with html line breaks.
//            str_replace('\n', '<br>', $msg);
//        } else {
//            try {
//                // Get items to remove from the request.
//                $cids = $this->input->get('cid', array(), 'array');
//                $extension = $this->input->getCmd('extension', null);
//
//                if (!is_array($cids) || count($cids) < 1) {
//                    //$this->app->enqueueMessage(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'warning');
//                    $msg .= Text::_($this->text_prefix . '_NO_ITEM_SELECTED');
//                    $msgType = 'warning';
//                } else {
//
//                    $j3xModel = $this->getModel('MaintenanceJ3x');
//
//                    $isOk = $j3xModel->moveImagesJ3x2J4xById($cids);
//                    if ($isOk) {
//                        $msg .= "Successful moved J3x image files";
//                        $msgType = 'success'; // ToDo: use in all controllers
//                    } else {
//                        $msg .= "Error at moveSelectedJ3xImages2J4x images";
//                        $msgType = 'error';
//                    }
//                }
//
//            } catch (\RuntimeException $e) {
//                $OutTxt = '';
//                $OutTxt .= 'Error executing moveSelectedJ3xImages2J4x: "' . '<br>';
//                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//                $app = Factory::getApplication();
//                $app->enqueueMessage($OutTxt, 'error');
//            }
//
//        }
//
//        //$link = 'index.php?option=com_rsgallery2&view=galleries';
//        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=movej3ximages';
//        $this->setRedirect($link, $msg, $msgType);
//    }

    /**
     * Copies all old J3x image to J4 images path
     *
     * @since __BUMP_VERSION__
     */
    public function updateMovedJ3xImages2J4x()
    {
        $msg = "MaintenanceJ3xController.updateMovedJ3xImages2J4x: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                $j3xModel = $this->getModel('MaintenanceJ3x');

                // Collect image Ids (ToDo: collect ids by db query in $j3xModel)
                $j3x_images = $j3xModel->j3x_imagesMergeList();
                $j3x_imageIds = [];
                foreach ($j3x_images as $j3x_image) {

                    $j3x_imageIds [] = $j3x_image->id;
                }

                $isOk = $j3xModel->updateMovedJ3xImages2J4x($j3x_imageIds);
                if ($isOk) {
                    $msg .= "Successful updated database for J3x -> J4x image files";
                } else {
                    $msg .= "Error at updateMovedJ3xImages2J4x: Update database for J3x -> J4x image files ";
                    $msgType = 'error';
                }

            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing updateMovedJ3xImages2J4x: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

        }

        //$link = 'index.php?option=com_rsgallery2&view=galleries';
        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=movej3ximages';
        $this->setRedirect($link, $msg, $msgType);
    }


    /**
     * Extract configuration variables from RSG2 config file to reset to original values
     *
     * @throws \Exception
     *
     * @since __BUMP_VERSION__
     */
    public function CheckImagePathsJ3x()
    {
        $isOk = false;

        $msg = "MaintenanceCleanUp.CheckImagePaths: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {

                $MaintModel = $this->getModel('MaintenanceJ3x');

                $isPathsExisting = $MaintModel->CheckImagePaths();
                if ($isPathsExisting) {
                    // config saved message
                    $msg .= Text::_('All paths to images j3x exist', true);
                }
                else
                {
                    $msg .= "Missing pathes for images j3x found '";
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
     * @since __BUMP_VERSION__
     */
    public function RepairImagePathsJ3x()
    {
        $isOk = false;

        $msg = "MaintenanceCleanUp.RepairImagePaths: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {

                $MaintModel = $this->getModel('MaintenanceJ3x');
                $isSaved = $MaintModel->RepairImagePaths();

                if ($isSaved) {
                    // config saved message
                    $msg .= Text::_('Image paths are created', true);
                }
                else
                {
                    $msg .= "Error at repair image paths'";
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
     * From given gallery id all matching images are collected
     * Returns id and names of all found images
     *
     * @throws Exception
     * @since __BUMP_VERSION__
     */
    function ajaxRequestImageIds()
    {
        global $rsgConfig, $Rsg2DebugActive;

        $msg = 'ajaxRequestImageIds::';
        $app = Factory::getApplication();

        // do check token
        $this->checkToken();
//        if (!Session::checkToken())
//        {
//            $errMsg   = Text::_('JINVALID_TOKEN') . " (02)";
//            $hasError = 1;
//            echo new JsonResponse($msg, $errMsg, $hasError);
//            $app->close();
//
//            return; // ToDo Check on all pre exits
//        }

        /* ToDo: // Access check
        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin)
        {
            $errMsg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
            $hasError = 1;
            echo new JsonResponse($msg, $errMsg, $hasError);
            $app->close();
        }
        /**/

        // for debug ajax response errors / notice
        $errorType = 0; //  1: error, 2: notice, 3: enqueueMessage types error, 4: enqueue. warning 5: exception
        if ($errorType) { issueError  ($errorType);}

        try
        {
            if ($Rsg2DebugActive)
            {
                // identify active file
                Log::add('==> ajaxRequestImageIds');
            }

            $input = Factory::getApplication()->input;

            //--- gallery name  --------------------------------------------

            $galleryName = $input->get('gallery_name', '', 'string');
            $ajaxImgDbObject['gallery_name'] = $galleryName;


            //--- gallery ID --------------------------------------------

            $galleryId = $input->get('gallery_id', 0, 'INT');
            // wrong id ? ToDo: test is number ...
            if ($galleryId < 1)
            {
                $msg .= ': Invalid gallery ID at request image ids';

                if ($Rsg2DebugActive)
                {
                    Log::add($msg);
                }

                echo new JsonResponse($ajaxImgDbObject, $msg, true);

                $app->close();
                return;
            }

            $ajaxImgDbObject['gallery_id']     = $galleryId;

            //----------------------------------------------------
            // Collect image names from db
            //----------------------------------------------------

            // Get the model.
            /** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel */
            $j3xModel = $this->getModel('MaintenanceJ3x');


            // Collect image Ids
            $j3x_images = $j3xModel->j3x_imagesToBeMovedByGallery([$galleryId]);

//            $j3x_imageIds = [];
//            foreach ($j3x_images as $j3x_image) {
//
//                $j3x_imageIds [] = $j3x_image->id;
//            }

            if ($Rsg2DebugActive)
            {
                Log::add('<== uploadAjax: After ajaxRequestImageIds: ' . count($j3x_images));
            }

            // $this->ajaxDummyAnswerOK (); return; // 05

            $ajaxImgDbObject['image_ids'] = $j3x_images;
            $isCreated                  = count($j3x_images) > 0;

            //----------------------------------------------------
            // return result
            //----------------------------------------------------

            if ($Rsg2DebugActive)
            {
                Log::add('    $ajaxImgDbObject: ' . json_encode($ajaxImgDbObject));
                Log::add('    $msg: "' . $msg . '"');
                Log::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
            }

            // No message as otherwise it would be displayed in form
            echo new JsonResponse($ajaxImgDbObject, "", !$isCreated, false); // true);

            if ($Rsg2DebugActive)
            {
                Log::add('<== Exit ajaxRequestImageIds');
            }

        }
        catch (\Exception $e)
        {
            echo new JsonResponse($e);
        }

        $app->close();
    }

    /** enum ==>
    abstract class DaysOfWeek
    {
        const Sunday = 0;
        const Monday = 1;
        // etc.
    }
    /**/


    /**
     * Moves siblings of given image to new rsgallery2 folder structure
     * single J3x image
     *
     *
     * @throws Exception
     * @since __BUMP_VERSION__
     */
    function ajaxMoveJ3xImage()
    {
        // Todo: Check Authorisation, Jupload , check mime type ...

        global $rsgConfig, $isDebugBackend, $isDevelop;

        $msg = '::';
        $app = Factory::getApplication();

	    //--- config --------------------------------------------------------------------

	    $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
	    //$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
	    $isDebugBackend = $rsgConfig->get('isDebugBackend');
	    $isDevelop = $rsgConfig->get('isDevelop');

	    if ($isDebugBackend)
	    {
		    Log::add('ajaxMoveJ3xImage ==>');
	    }

	    //--- access check --------------------------------------------------------------------

	    // do check token
        // $this->checkToken();
        if (!Session::checkToken())
        {
            $errMsg   = Text::_('JINVALID_TOKEN') . " (02)";
            $hasError = 1;
            echo new JsonResponse($msg, $errMsg, $hasError);
            $app->close();
            return; // ToDo Check on all pre exits
        }

        /* ToDo: // Access check
        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin)
        {
            $errMsg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
            $hasError = 1;
            echo new JsonResponse($msg, $errMsg, $hasError);
            $app->close();
        }
        /**/

        // for debug ajax response errors / notice
        $errorType = 0; //  1: error, 2: notice, 3: enqueueMessage types error, 4: enqueue. warning 5: exception
        if ($errorType) { issueError  ($errorType); }

        try
        {
            $input = Factory::getApplication()->input;

            //--- image data  --------------------------------------------

            $galleryId = $input->get('gallery_id', '', 'string');
            $imageId = $input->get('image_id', '', 'string');
            $imageName = $input->get('image_name', '', 'string');

//            $ajaxImgDbObject['gallery_name'] = $galleryName;
//
//
//            //--- gallery ID --------------------------------------------
//
//            $galleryId = $input->get('gallery_id', 0, 'INT');
//            // wrong id ? ToDo: test is number ...
//            if ($galleryId < 1)
//            {
//                $msg .= ': Invalid gallery ID at drag and drop upload';
//
//                if ($Rsg2DebugActive)
//                {
//                    Log::add($msg);
//                }
//
//                echo new JsonResponse($ajaxImgDbObject, $msg, true);
//
//                $app->close();
//                return;
//            }

            $ajaxImgDbObject['image_id']   = $imageId;
            $ajaxImgDbObject['image_name'] = $imageName;
            $ajaxImgDbObject['gallery_id'] = $galleryId;

            //----------------------------------------------------
            // move j3x image(s)
            //----------------------------------------------------

            $isMovedDb = false;

            // Get the model.
            /** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel */
            $j3xModel = $this->getModel('MaintenanceJ3x');

            //  = self::J3X_IMG_NOT_FOUND;
            [$stateOriginal, $stateDisplay, $stateThumb, $stateWatermarked, $stateImageDb] =
                $j3xModel->j3x_moveImage ($imageId, $imageName, $galleryId);

            $hasError = false;
            $isMovedDb = true;


//            // Get the model.
//            /** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\MaintenanceJ3xModel */
//            $j3xModel = $this->getModel('MaintenanceJ3x');
//
//
//            // Collect image Ids (ToDo: collect ids by db query in $j3xModel)
//            $j3x_images = $j3xModel->j3x_imagesToBeMovedByGallery([$galleryId]);
//
////            $j3x_imageIds = [];
////            foreach ($j3x_images as $j3x_image) {
////
////                $j3x_imageIds [] = $j3x_image->id;
////            }
//

            if ($isDebugBackend)
            {
//                Log::add('<== uploadAjax: After : ' . count($j3x_images));
            }

            // $this->ajaxDummyAnswerOK (); return; // 05

            $ajaxImgDbObject['state_original']    = $stateOriginal;
            $ajaxImgDbObject['state_display']     = $stateDisplay;
            $ajaxImgDbObject['state_thumb']       = $stateThumb;
            $ajaxImgDbObject['state_watermarked'] = $stateWatermarked;
            $ajaxImgDbObject['state_image_db']    = $stateImageDb;

            //----------------------------------------------------
            // return result
            //----------------------------------------------------

            if ($isDebugBackend)
            {
//                Log::add('    $ajaxImgDbObject: ' . json_encode($ajaxImgDbObject));
//                Log::add('    $msg: "' . $msg . '"');
//                Log::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
            }

            // No message as otherwise it would be displayed in form
            //echo new JsonResponse($ajaxImgDbObject, "", !$isMovedDb, false); // true);
            echo new JsonResponse($ajaxImgDbObject, "", $hasError, false); // true);

	        if ($isDebugBackend)
	        {
		        Log::add('ajaxMoveJ3xImage <== ' . $hasError);
	        }

        }
        catch (\Exception $e)
        {
            echo new JsonResponse($e);
        }

        $app->close();
    }

	/**
	 *
	 *
	 * @since __BUMP_VERSION__
	 */
	public function j3xUpgradeJ3xMenuLinksUser()
	{
		$this->j3xUpgradeJ3xMenuLinks ();

		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link);
	}

	/**
	 *
	 *
	 * @since __BUMP_VERSION__
	 */
	public function j3xUpgradeJ3xMenuLinks()
	{
		$msg = "MaintenanceJ3xController.j3xUpgradeJ3xMenuLinks: ";
		$msgType = 'notice';

		$this->checkToken();

		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin) {
			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {

			try {
				$j3xModel = $this->getModel('MaintenanceJ3x');

				$isOk = $j3xModel->j3xUpgradeJ3xMenuLinks();

				if ($isOk) {

					$msg .= "Successful changed j3x menu inks and increased menu J3x gallery gids ";

					$isOk = ConfigRawModel::writeConfigParam ('j3x_menu_gid_increased', true);
					if ($isOk) {
						$msg .= " and assigned copied flag";

					} else {
						$msg .= "!!! but error at writeConfigParam !!!";
						$msgType = 'error';
					}

				} else {
					$msg .= "Error at j3xUpgradeJ3xMenuLinks items";
					$msgType = 'error';
				}

			} catch (\RuntimeException $e) {
				$OutTxt = '';
				$OutTxt .= 'Error executing j3xUpgradeJ3xMenuLinks: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 *
	 *
	 * @since __BUMP_VERSION__
	 */
	public function j3xLowerJ4xMenuLinks()
	{
		$msg = "MaintenanceJ3xController.j3xUpgradeJ3xMenuLinks: ";
		$msgType = 'notice';

		$this->checkToken();

		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin) {
			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {

			try {
				$j3xModel = $this->getModel('MaintenanceJ3x');

				$isOk = $j3xModel->j3xLowerJ4xMenuLinks();

				if ($isOk) {

					$msg .= "Successful lower j3x menu inks";

				} else {
					$msg .= "Error at j3xLowerJ4xMenuLinks items";
					$msgType = 'error';
				}

			} catch (\RuntimeException $e) {
				$OutTxt = '';
				$OutTxt .= 'Error executing j3xLowerJ4xMenuLinks: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2&view=Maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 *
	 *
	 * @since __BUMP_VERSION__
	 */
	public function j3xUpperJ4xMenuLinks()
	{
		$msg = "MaintenanceJ3xController.j3xUpgradeJ3xMenuLinks: ";
		$msgType = 'notice';

		$this->checkToken();

		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin) {
			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {

			try {
				$j3xModel = $this->getModel('MaintenanceJ3x');

				$isOk = $j3xModel->j3xUpperJ4xMenuLinks();

				if ($isOk) {

					$msg .= "Successful lower j3x menu inks";

				} else {
					$msg .= "Error at j3xUpperJ4xMenuLinks items";
					$msgType = 'error';
				}

			} catch (\RuntimeException $e) {
				$OutTxt = '';
				$OutTxt .= 'Error executing j3xUpperJ4xMenuLinks: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=lowerJ4xMenuLinks';
		$this->setRedirect($link, $msg, $msgType);
	}


	/**
	 *
	 *
	 * @since __BUMP_VERSION__
	 */
	public function j3xDegradeUpgradedJ3xMenuLinks()
	{
		$msg = "MaintenanceJ3xController.j3xDegradeUpgradedJ3xMenuLinks: ";
		$msgType = 'notice';

		$this->checkToken();

		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin) {
			//Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {

			try {
				$j3xModel = $this->getModel('MaintenanceJ3x');

				$isOk = $j3xModel->j3xDegradeUpgradedJ4xMenuLinks();

				if ($isOk) {

					$msg .= "Successful decreased menu J3x gallery gids ";

//					$isOk = ConfigRawModel::writeConfigParam ('j3x_menu_gid_increased', true);
//					if ($isOk) {
//						$msg .= " and assigned copied flag";
//
//					} else {
//						$msg .= "!!! but error at writeConfigParam !!!";
//						$msgType = 'error';
//					}

				} else {
					$msg .= "Error at j3xDegradeUpgradedJ3xMenuLinks items";
					$msgType = 'error';
				}

			} catch (\RuntimeException $e) {
				$OutTxt = '';
				$OutTxt .= 'Error executing j3xDegradeUpgradedJ3xMenuLinks: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=changeJ3xMenuLinks';
		$this->setRedirect($link, $msg, $msgType);
	}

} // class

/**
 * ajax response error tests
 * function may be included in all ajax calls for tests of errors
 *
 * @param $errorType integer
 *     1: error
 *     2: notice
 *     3: enqueueMessage types with error set
 *     4: enqueueMessage types with NO error set
 *     5: enqueueMessage types with thrown exception
 *
 * @throws \Exception
 * @since __BUMP_VERSION__
 */
function issueError  ($errorType)
{
    $app = Factory::getApplication();

    //  0: nothing, 1:error, 2:notice, .... see above
    if ($errorType)
    {
        $result = "Resulting data (simulated)";
        switch ($errorType)
        {
            case 1:
                echo new JsonResponse($result, Text::_('COM_COMPONENT_MY_TASK_ERROR'), true);
                break;

            case 2:
                echo new JsonResponse($result, 'Main response message');
                break;

            case 3:
                $app->enqueueMessage('This part has error 1');
                $app->enqueueMessage('This part has error 2');
                $app->enqueueMessage("Enqueued notice 1", "notice");
                $app->enqueueMessage("Enqueued notice 2", "notice");
                $app->enqueueMessage('Here was a small warning 1', 'warning');
                $app->enqueueMessage('Here was a small warning 2', 'warning');
                $app->enqueueMessage('Here was a small error 1', 'error');
                $app->enqueueMessage('Here was a small error 2', 'error');
                echo new JsonResponse($result, Text::_('!!! Response message with error set !!!'), true);
                break;

            case 4:
                $app->enqueueMessage('This part was successful 1');
                $app->enqueueMessage('This part was successful 2');
                $app->enqueueMessage("Enqueued notice 1", "notice");
                $app->enqueueMessage("Enqueued notice 2", "notice");
                $app->enqueueMessage('Here was a small warning 1', 'warning');
                $app->enqueueMessage('Here was a small warning 2', 'warning');
                $app->enqueueMessage('Here was a small error 1', 'error');
                $app->enqueueMessage('Here was a small error 2', 'error');
                echo new JsonResponse($result, 'Response message with !!! no !!! error set');
                break;
            case 5:
                $app->enqueueMessage('This part was successful 1');
                $app->enqueueMessage('This part was successful 2');
                $app->enqueueMessage("Enqueued notice 1", "notice");
                $app->enqueueMessage("Enqueued notice 2", "notice");
                $app->enqueueMessage('Here was a small warning 1', 'warning');
                $app->enqueueMessage('Here was a small warning 2', 'warning');
                $app->enqueueMessage('Here was a small error 1', 'error');
                $app->enqueueMessage('Here was a small error 2', 'error');

                throw new \Exception('Attention: raised exception ');

                echo new JsonResponse($result, 'Response message with !!! no !!! error set');
                break;
        }

        $app->close();
    }
    /**/
}

// class is above