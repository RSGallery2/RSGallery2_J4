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

class UploadController extends AdminController
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
	 * Proxy for getModel.
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
	 */
    public function getModel($name = 'Upload', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
	/**/

	/*
		$params = ComponentHelper::getParams('com_rsgallery2');

		if ($params->get('', '0'))
		{
			$options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
			$options['text_file'] = 'indexer.php';
			Log::addLogger($options);
		}

		// Log the start
		try
		{
			Log::add('Starting the indexer', Log::INFO);
		}
		catch (\RuntimeException $exception)
		{
			// Informational log only
		}
	*/

	/**
     * Copies list of selected old configuration items to new configuration
     *
     * @since 5.0.0
	 */
	/**
	public function copySelectedOldItems2New ()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				$cfg3xModel = $this->getModel('Upload');

				$IsAllCreated = false;
//				$input     = JFactory::getApplication()->input;
//				$GalleryId = $input->get('ParentGalleryId', 0, 'INT');
				$selected = $this->input->get('cfgId', array(), 'array');
				$allNames = $this->input->get('cfgName', array(), 'array');

				if (empty ($selected))
				{
					$msg     = $msg . Text::_('COM_RSGALLERY2_NO_ITEM_SELECTED');
					$msgType = 'warning';
				} 
				else 
				{
					// Collect config names to copy
					$configNames = [];
					
					foreach ($selected as $idx => $name)
					{
						$configNames[] = $allNames[(int)$idx];
					}
					
					$isOk = $cfg3xModel->copyOldItemsList2New ($configNames);

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

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		// Message to user ...

		// Create list of CIDS and append to link URL like in PropertiesView above
		// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
		$cids = $this->input->get('cid', 0, 'int');
		$link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));
		$this->setRedirect($link, $msg, $msgType);
		
//		$this->setRedirect(Route::_('index.php?option=com_content&view=featured', false), $message);
	} 
	/**/


	/**
     * Copies list of selected old configuration items to new configuration
     *
     * @since 5.0.0
	 */
	/**
	public function copyOldItems2New ()
	{
		$msg     = "controller.createImageDbItems: ";
		$msgType = 'notice';

		Session::checkToken();

		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				$cfg3xModel = $this->getModel('Upload');

				$isOk = $cfg3xModel->copyOldItems2New ();

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
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing copyOldItems2New: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}

		// ToDo: Message to user ...

		// Create list of CIDS and append to link URL like in PropertiesView above
		// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
		$cids = $this->input->get('cid', 0, 'int');
		$link = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids));
		$this->setRedirect($link, $msg, $msgType);
		
//		$this->setRedirect(Route::_('index.php?option=com_content&view=featured', false), $message);
	} 
	/**/

} // class

