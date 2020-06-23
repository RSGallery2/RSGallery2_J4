<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

namespace Joomla\Component\Rsgallery2\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Response\JsonResponse;
use Joomla\Input\Input;
use Joomla\Utilities\ArrayHelper;


/**
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
//	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.config.php ');
}
/**/

class MaintenanceJ3xController extends AdminController
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
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);

	}

	/**
     * Copies list of selected old configuration items to new configuration
     *
     * @since 5.0.0
	 */
	public function copySelectedOldIJ3xConfig2J4xOptions ()
	{
		$msg     = "MaintenanceJ3xController.copySelectedOldIJ3xConfig2J4xOptions: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				$cfg3xModel = $this->getModel('MaintenanceJ3x');
				$oldConfigItems = $cfg3xModel->OldConfigItems();

				$configModel = $this->getModel('ConfigRaw');

//				$IsAllCreated = false;
				$selected = $this->input->get('cid', array(), 'array');

				if (empty ($selected))
				{
					$msg     .= Text::_('COM_RSGALLERY2_NO_ITEM_SELECTED');
					$msgType = 'warning';
				} 
				else 
				{
					// Collect config names to copy
					$configNames = [];
					
					foreach ($selected as $name)
					{
						$configNames[$name] = $oldConfigItems[$name];
					}
					
					//$isOk = $cfg3xModel->copyJ3xConfigItems2J4xOptions ($configNames);
					$isOk = $configModel->copyJ3xConfigItems2J4xOptions ($configNames);

					if ($isOk)
					{
						$msg .= "Successful copied items:" . count ($selected);
					}
					else
					{
						$msg .= "Error at copyJ3xConfigItems2J4xOptions items. Expected: " . count ($selected);
						$msgType = 'warning';					
					}
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing copySelectedOldIJ3xConfig2J4xOptions: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbCopyOld3xConfig';
		$this->setRedirect($link, $msg, $msgType);
	} 

	/**
     * Copies all  old configuration items to new configuration
     *
     * @since 5.0.0
	 */
	public function copyOldIJ3xConfig2J4xOptions ()
	{
		$msg     = "MaintenanceJ3xController.copyOldIJ3xConfig2J4xOptions: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				$cfg3xModel = $this->getModel('MaintenanceJ3x');
				$configModel = $this->getModel('ConfigRaw');

				$oldConfigItems = $cfg3xModel->OldConfigItems();
//				$isOk = $configModel->copyOldIJ3xConfig2J4xOptions ($oldConfigItems);
//				$isOk = $configModel->copyJ3xConfigItems2J4xOptions ($oldConfigItems);

				if (count($oldConfigItems))
				{
					// J3x config state: 0:not upgraded, 1:upgraded,  -1:upgraded and deleted
					// Smuggle the J3x config state "upgraded:1" into the list
					$oldConfigItems ['j3x_config_upgrade'] = "1";

					$isOk = $configModel->copyJ3xConfigItems2J4xOptions ($oldConfigItems);
					if ($isOk)
					{
						$msg .= "Successful copied old configuration items";
					}
					else
					{
						$msg .= "Error at copyOldIJ3xConfig2J4xOptions items";
						$msgType = 'error';
					}
				}
				else
				{
					$msg .= "No old configuration items";
					$msgType = 'warning';
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing copyOldIJ3xConfig2J4xOptions: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbCopyOld3xConfig';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
     * Copies all old J3x gallery items to J4 galleries
     *
     * @since 5.0.0
	 */
	public function copyOldIJ3xGalleries2J4x ()
	{
		$msg     = "MaintenanceJ3xController.copyOldIJ3xGalleries2J4x: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				$cfg3xModel = $this->getModel('MaintenanceJ3x');
				$galleryModel = $this->getModel('Gallery');

				$J3xGalleryItemsSorted = $cfg3xModel->j3x_galleriesListSorted();

				if (count($J3xGalleryItemsSorted))
				{

//					$J3xGalleryItemsSorted ['j3x_config_upgrade'] = "1";

                    $J4Galleries = $cfg3xModel->convertJ3xGalleriesToJ4x ($J3xGalleryItemsSorted);

                    $isOk = $galleryModel->saveItems($J4Galleries);
					if ($isOk)
					{
						$msg .= "Successful copied old gallery items items";
					}
					else
					{
						$msg .= "Error at copyOldIJ3xGalleries2J4x items";
						$msgType = 'error';
					}
				}
				else
				{
					$msg .= "No old configuration items";
					$msgType = 'warning';
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing copyOldIJ3xGalleries2J4x: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

        //$link = 'index.php?option=com_rsgallery2&view=galleries';
        $link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DBTransferOldJ3xGalleries';
		$this->setRedirect($link, $msg, $msgType);
	}

} // class

