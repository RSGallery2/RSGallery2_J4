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
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/**
 * Rsgallery2 master display controller.
 *
 * @since  1.0
 */
class MaintenanceController extends BaseController
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
	 *
	public function display($cachable = false, $urlparams = array())
	{
		
		// $model = $this->getModel('');
		
		
		
		
		return parent::display();
	}
    /**/

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
	/**
    public function getModel($name = 'Maintenance', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }  	
	/**/



    /**
     * On cancel goto maintenance
     *
     * @return bool
     *
     * @since version 4.3
     */
    public function cancel()
    {
        Session::checkToken();

        $link = Route::_('index.php?option=com_rsgallery2&view=maintenance');
        $this->setRedirect($link);

        return true;
    }

    /**
     * Change file uninstall.mysql.utf8.sql so it does remove the RSG2 Tables
     *
     * @throws \Exception
     *
     * @since 5.0.0
     */
    public function importConfigFile()
    {
        $msg = "Maintenance.importConfigFile: ";
        $msgType = 'notice';

        Session::checkToken();

        $canAdmin = Factory::getUser()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            //Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {

                $input = Factory::getApplication()->input;
                $oFile = $input->files->get('config_file', array(), 'raw');

                $srcTempPathFileName = $oFile['tmp_name'];
                $fileType            = $oFile['type'];
                $fileError           = $oFile['error'];
                $fileSize            = $oFile['size'];

                // Changed name of existing file name
//                $safeFileName   = File::makeSafe($oFile['name']);
                $fContent = file_get_contents($srcTempPathFileName);
                $configJson = json_decode($fContent, true);

                $configData = $configJson [RSG2_configuration];

                $model   = $this->getModel('ConfigRaw');
                $isSaved = $model->SaveItems($configData);

                if ($isSaved) {
                    $msg .= "Successful uploaded and inserted configuration file data";
                } else {
                    $msg .= "Error at uploading and inserting configuration file data'";
                    $msgType = 'error';
                }

            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing prepareRemoveTables: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }

        }

        $link = 'index.php?option=com_rsgallery2&view=Maintenance';
        $this->setRedirect($link, $msg, $msgType);
    }







}
