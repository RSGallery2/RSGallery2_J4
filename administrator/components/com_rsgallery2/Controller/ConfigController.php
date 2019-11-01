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

class ConfigController extends FormController
{

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
    public function getModel($name = 'Config', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
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
     * @since version 4.3
     */
	public function cancel_rawView($key = null)
	{
		Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));

		$link = Route::_('index.php?option=com_rsgallery2&view=maintenance');
		$this->setRedirect($link);

		return true;
	}

    /**
     * Save changes in raw edit view value by value
     *
     * @since version 4.3
     */
	public function apply_rawEdit()
    {
	    Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));

	    $msg = "apply_rawEdit: " . '<br>';
        $msgType = 'notice';

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            $model = $this->getModel('ConfigRaw');
	        $isSaved = $model->save();

        }
        $link = Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEdit');
        $this->setRedirect($link, $msg, $msgType);
    }
    /**
     * Save changes in raw edit view value by value
     *
     * @since version 4.3
     */
	public function save_rawEdit()
	{
		Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));

		$msg     = "save_rawEdit: " . '<br>';
		$msgType = 'notice';

        // Access check
        $canAdmin = Factory::getUser()->authorise('core.edit', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            $model = $this->getModel('ConfigRaw');
	        $isSaved = $model->save();
        }

		$link = Route::_('index.php?option=com_rsgallery2&view=maintenance');
		$this->setRedirect($link, $msg, $msgType);
	}

	/*-------------------------------------------------------------------------------------*/
	/**
	 * removes all entries fromm old
	 *
	 * @since version 4.3
	 */
	/**
	public function remove_OldConfigData()
	{
	Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));


	$msg     = "remove_OldConfigData: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			$model     = $this->getModel('ConfigRaw');
			$isRemoved = $model->removeOldConfigData();

			if ($isRemoved)
			{
				$msg .= 'Successfully removed J2.5 configuration data';
			}
			else
			{
				$msg .= '!!! Failed at removing J2.5 configuration data !!! ';
				$msgType = 'error';
			}
		}

	$link = Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEditOld');
		// $link = Route::_('index.php?option=com_rsgallery2&view=maintenance');
		$this->setRedirect($link, $msg, $msgType);
	}
	/**/

	/**
     * On cancel raw exit goto maintenance
     * @param null $key
     *
     * @return bool
     *
     * @since version 4.3
     */
	public function cancel_rawEdit($key = null)
	{
		Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));

		$link = Route::_('index.php?option=com_rsgallery2&view=maintenance');
		$this->setRedirect($link);

		return true;
	}

	/**
	 * Save changes in raw edit view value by value and copy items to the
	 * old configuration  data
	 *
	 * @since version 4.3
	 *
	public function save_rawEditAndCopyOld()
	{
		Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));


	$msg     = "save_rawEdit: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->save();
			$isSaved = $model->copyNew2Old();
		}

	$link = Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEditOld');
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Save changes in raw edit view value by value and copy items to the
	 * old configuration  data
	 *
	 * @since version 4.3
	 */
	/**
	public function copy_rawEditFromOld()
	{
	Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));


	$msg     = "copy_rawEditFromOld: " . '<br>';
		$msgType = 'notice';
		$isSaved = false;

			// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg .= JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->copyOld2New();
		}

		if ($isSaved)
		{
			$msg .= " done";
		}
		else
		{
			$msg .= " !!! not done !!!";
		}

	$link = Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEdit');
		$this->setRedirect($link, $msg, $msgType);
	}

	/*  */

	/**
	 * Save changes in raw edit view value by value and copy items to the
	 * old configuration  data
	 *
	 * @since version 4.3
	 */
	/**
	public function save_rawEdit2Text()
	{
	Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));


	$msg     = "save_rawEdit: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$ConfigParameter = JComponentHelper::getParams('com_rsgallery2');
			$ConfigParameter = $ConfigParameter->toArray();

			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->save();
			$isSaved = $model->createConfigTextFile($ConfigParameter);
		}

	$link = Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEdit');
		$this->setRedirect($link, $msg, $msgType);
	}

	/**
	 * Read text file and copy items to the configuration  data
	 *
	 * @since version 4.3
	 */
	/**
	public function read_rawEdit2Text()
	{
	Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));


	$msg     = "write_rawEdit2Text: " . '<br>';
		$msgType = 'notice';

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.edit', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			$model = $this->getModel('ConfigRaw');
			$isSaved = $model->readConfigTextFile();
		}

	$link = Route::_('index.php?option=com_rsgallery2&view=config&layout=RawEdit');
		$this->setRedirect($link, $msg, $msgType);
	}
	/**/
	/**
	 * Standard cancel (may not be used)
     *
	 * @param null $key
	 *
	 * @return bool
     *
     * @since version 4.3
	 */
	public function cancel($key = null)
	{
		// Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));
		// parent::cancel($key);

		$link = Route::_('index.php?option=com_rsgallery2');
		$this->setRedirect($link);

		return true;
	}

    /**
     * Standard save of configuration
     * @param null $key
     * @param null $urlVar
     *
     * @since version 4.3
     */
    /**
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
	/**/
}
