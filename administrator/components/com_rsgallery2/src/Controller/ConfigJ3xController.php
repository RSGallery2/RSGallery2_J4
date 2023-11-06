<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright (c) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Response\JsonResponse;
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

class ConfigJ3xController extends AdminController // FormController
{
	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
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
	 * Proxy for getModel.
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since __BUMP_VERSION__
	 */
	/**
    public function getModel($name = 'Config', $prefix = 'Administrator', $config = array('ignore_request' => true))
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
     * On cancel raw view goto maintenance
     *
     * @param null $key (not used)
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
	public function cancel_rawView($key = null)
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}

	/**
	 * On cancel raw exit goto maintenance
	 * @param null $key
	 *
	 * @return bool
	 *
	 * @since __BUMP_VERSION__
	 */
	public function cancel_rawEdit($key = null)
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}

	/**
	 * Standard cancel (may not be used)
	 *
	 * @param null $key
	 *
	 * @return bool
	 *
	 * @since __BUMP_VERSION__
	 */
	public function cancel($key = null)
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link);

		return true;
	}

	/**
     * Save changes in raw edit view value by value
     *
     * @since __BUMP_VERSION__
     */
	public function apply_rawEdit()
    {
	    $msg     = null;
	    $msgType = 'notice';

	    Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

	    $msg     = "apply_rawEdit: " . '<br>';

	    // Access check
	    $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_rsgallery2');

        if (!$canAdmin)
	    {
		    $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
		    $msgType = 'warning';
		    // replace newlines with html line breaks.
		    str_replace('\n', '<br>', $msg);
	    }
	    else
	    {
		    $model   = $this->getModel('ConfigJ3xRaw');

		    $isSaved = $model->saveFromForm();
            if ($isSaved) {
                $msg .= Text::_('Saved configuration J3x parameters successfully');
            } else {
                $msg .= Text::_('Error on saving configuration J3x parameters: ');
            }
        }

	    $link = 'index.php?option=com_rsgallery2&view=configJ3x&layout=RawEdit';
        $this->setRedirect($link, $msg, $msgType);
    }
    
    /**
     * Save changes in raw edit view value by value
     *
     * @since __BUMP_VERSION__
     */
	public function save_rawEdit()
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		$msg     = "save_rawEdit: " . '<br>';
		$msgType = 'notice';

        // Access check
        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_rsgallery2');

        if (!$canAdmin) {
            $msg = $msg . Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            $model = $this->getModel('ConfigRaw');

	        $isSaved = $model->saveFromForm();
            if ($isSaved) {
                $msg .= Text::_('Saved configuration J3x parameters successfully');
            } else {
                $msg .= Text::_('Error on saving configuration J3x parameters: ');
            }
        }

		$link ='index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Save changes in raw edit view value by value
	 *
	 * @since version 4.3
	 *
	public function reset2default()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg     = "reset2default: " . '<br>';
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		} else {
			$model = $this->getModel('ConfigRaw');

			$isSaved = $model->reset2default();

			$msg  = "Reset configuration to default values ";
			if ($isSaved)
			{
				$msg .= "successful";
			}
			else
			{
				$msg .= "failed !!!";
			}
		}

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}
	/**/

    /**
     * Standard save of configuration
     * @param null $key
     * @param null $urlVar
     *
     * @since version 4.3
     */
	function save($key = null, $urlVar = null)
	{
		parent::save($key, $urlVar);

		$inTask = $this->getTask();

		if ($inTask != "apply")
		{
			// Don't go to default ...
			$this->setredirect('index.php?option=com_rsgallery2');
		}
	}

}

