<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

/**
 * The Images List Controller
 *
 * @since __BUMP_VERSION__
 */
class ImagesController extends AdminController
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
     * @since __BUMP_VERSION__
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
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
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function getModel($name = 'Image', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}



	/**
	 * Check in of one or more records.
	 *
	 * Overrides \JControllerAdmin::checkin to redirect to URL with extension.
	 *
	 * @return  boolean  True on success
	 *
	 * @since __BUMP_VERSION__
	 */
	/* ToDo try to remove */
	public function checkin()
	{
		// Process parent checkin method.
		$result = parent::checkin();

		// Override the redirect Uri.
//		$redirectUri = 'index.php?option=' . $this->option . '&view=' . $this->view_list . '&extension=' . $this->input->get('extension', '', 'CMD');
//		$this->setRedirect(Route::_($redirectUri, false), $this->message, $this->messageType);
		$this->setRedirect(Route::_('index.php?option=com_rsgallery2&view=Images'));

		return $result;
	}
	/**/


    /**
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public function reinitImagesTable()
    {
        $isOk = false;

        $msg = "ImagesController.reinitImagesTable: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            // toDo: find " str_replace('\n', '<br>', $msg);" nad replace in complete project
            $msg = nl2br ($msg);
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
     * @throws Exception
     * @since 4.3.0
     */
    public function moveImagesTo()
    {
        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "Control:moveTo: ";
        $msgType = 'notice';

        Session::checkToken();

        // Access check
        $canAdmin = Factory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin)
        {
            $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br ($msg);
        }
        else
        {
            try
            {
                // Model tells if successful
                $model = $this->getModel('image');

                $IsMoved = $model->moveImagesTo();
                if ($IsMoved)
                {
                    $msg .= 'Move of images ... successfull';
                }
                else
                {
                    $msg .= 'Move of images ... failed';
                    $msgType = 'error';
                }
            }
            catch (RuntimeException $e)
            {
                $OutTxt = '';
                $OutTxt .= 'Error executing moveTo: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=images';
        $this->setRedirect($link, $msg, $msgType);

    }

    /**
     * Saves changed manual ordering of galleries
     *
     * @throws Exception
     * @since 4.3.0
     */
    public function copyImagesTo()
    {
        //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
        $msg     = "Control:copyTo: ";
        $msgType = 'notice';

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin)
        {
            $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br ($msg);
        }
        else
        {
            try
            {
                // Model tells if successful
                $model = $this->getModel('image');

                $IsCopied = $model->copyImagesTo();
                if ($IsCopied)
                {
                    $msg .= 'Copy of images ... successfully';
                }
                else
                {
                    $msg .= 'Copy of images ... failed';
                    $msgType = 'error';
                }
            }
            catch (RuntimeException $e)
            {
                $OutTxt = '';
                $OutTxt .= 'Error executing copyTo: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $this->setRedirect('index.php?option=com_rsgallery2&view=images', $msg, $msgType);
    }



}
