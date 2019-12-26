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
	public function copySelectedOldItems2New ()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
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
					$msg     = $msg . Text::_('COM_RSGALLERY2_NO_ITEM_SELECTED');
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
					
					//$isOk = $cfg3xModel->copyOldItemsList2New ($configNames);
					$isOk = $configModel->copyOldItemsList2New ($configNames);

					if ($isOk)
					{
						$msg .= "Successful copied items:" . count ($selected);
					}
					else
					{
						$msg .= "Error at copyOldItemsList2New items. Expected: " . count ($selected);
						$msgType = 'warning';					
					}
				}
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing saveOrdering: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbCopyOldConfig';
		$this->setRedirect($link, $msg, $msgType);
	} 

	/**
     * Copies list of selected old configuration items to new configuration
     *
     * @since 5.0.0
	 */
	public function copyOldItems2New ()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . Text::_('JERROR_ALERTNOAUTHOR');
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
//				$isOk = $configModel->copyOldItems2New ($oldConfigItems);
//				$isOk = $configModel->copyOldItemsList2New ($oldConfigItems);

				if (count($oldConfigItems))
				{
					$isOk = $configModel->copyOldItemsList2New ($oldConfigItems);
					if ($isOk)
					{
						$msg .= "Successful copied old configuration items";
					}
					else
					{
						$msg .= "Error at copyOldItems2New items";
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
				$OutTxt .= 'Error executing copyOldItems2New: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		$link = 'index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbCopyOldConfig';
		$this->setRedirect($link, $msg, $msgType);
	}

} // class

