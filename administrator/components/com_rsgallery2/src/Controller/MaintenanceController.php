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
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/**
 * Rsgallery2 master display controller.
 *
 * @since __BUMP_VERSION__
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
     * @since __BUMP_VERSION__
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

    }


    /**
     * Extract configuration variables from RSG2 config file to reset to original values
     *
     * @throws \Exception
     *
     * @since __BUMP_VERSION__
     */
    public function CheckImagePaths()
    {
        $isOk = false;

        $msg = "MaintenanceCleanUp.CheckImagePaths: ";
        $msgType = 'notice';

        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {

                $MaintModel = $this->getModel('Maintenance');
                $isPathsExisting = $MaintModel->CheckImagePaths();
                if ($isPathsExisting) {
                    // config saved message
                    $msg .= Text::_('All paths to images exist', true);
                }
                else
                {
                    $msg .= "Missing pathes for images found (dependend on gallery id or size)'";
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
    public function RepairImagePaths()
    {
        $isOk = false;

        $msg = "MaintenanceCleanUp.RepairImagePaths: ";
        $msgType = 'notice';

        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {

            try {

                $MaintModel = $this->getModel('Maintenance');
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
	 * The default view.
	 *
	 * @var    string
	 * @since __BUMP_VERSION__
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
	 * @since __BUMP_VERSION__
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
	 * @since __BUMP_VERSION__
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
     * @since __BUMP_VERSION__
     */
    public function cancel()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $link = 'index.php?option=com_rsgallery2&view=maintenance';
        $this->setRedirect($link);

        return true;
    }






}
