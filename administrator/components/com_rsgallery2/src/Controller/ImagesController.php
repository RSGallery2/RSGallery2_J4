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

use Joomla\CMS\Input\Input;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;




/**
 * The Images List Controller
 *
     * @since      5.1.0
 */
class ImagesController extends AdminController
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
     * Proxy for getModel
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  The array of possible config values. Optional.
     *
     * @return  BaseDatabaseModel  The model.
     *
     * @since   5.1.0     */
    public function getModel($name = 'Image', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     *
     * @return bool
     *
     * @since  5.1.0     */
    public function reinitImagesTable()
    {
        $isOk = false;

        $msg     = "ImagesController.reinitImagesTable: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            // toDo: find " str_replace('\n', '<br>', $msg);" nad replace in complete project
            $msg = nl2br($msg);
        } else {
            try {
                // Get the model.
                $model = $this->getModel('images');

                // Remove the items.
                $isOk = $model->reinitImagesTable();
                if ($isOk) {
                    $msg .= Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET_SUCCESS');
                } else {
                    $msg .= Text::_('COM_RSGALLERY2_IMAGES_TABLE_RESET_ERROR') . ': ' . $model->getError();
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing reinitImagesTable: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        //$link = 'index.php?option=com_rsgallery2&view=images&layout=images_raw';
        $link = 'index.php?option=com_rsgallery2&view=maintenance';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     * Moves one or more items (images) to another gallery, ordering each item as the last one.
     *
     * @throws \Exception
     * @since 4.3.0
     */
    public function moveImagesToGallery()
    {
        //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "Control:moveTo: ";
        $msgType = 'notice';

        $this->checkToken();

        // Access check
        $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br($msg);
        } else {
            try {
                // Model tells if successful
                $model = $this->getModel('image');

                $IsMoved = $model->moveImagesToGallery();
                if ($IsMoved) {
                    $msg .= 'Moved images successfully';
                } else {
                    $msg     .= 'Move of images ... failed';
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing moveTo: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=images';
        $this->setRedirect($link, $msg, $msgType);
    }

    /**
     * Saves changed manual ordering of galleries
     *
     * @throws \Exception
     * @since 4.3.0
     */
    public function copyImagesToGallery()
    {
        //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "Control:copyTo: ";
        $msgType = 'notice';

        $this->checkToken();

        // Access check
        $canAdmin = $this->app->getIdentity()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br($msg);
        } else {
            try {
                // Model tells if successful
                $model = $this->getModel('image');

                $IsCopied = $model->copyImagesToGallery();
                if ($IsCopied) {
                    $msg .= 'Copied mages successfully';
                } else {
                    $msg     .= 'Copy of images ... failed';
                    $msgType = 'error';
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing copyTo: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=images', $msg, $msgType);
    }

}
