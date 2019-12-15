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

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

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

class MaintenanceJ3xController extends FormController
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
    public function getModel($name = 'MaintenanceJ3x', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
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

}

