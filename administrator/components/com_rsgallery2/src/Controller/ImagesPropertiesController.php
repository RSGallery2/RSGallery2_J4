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
use Joomla\CMS\Session\Session;

/**
 * The Galleries List Controller
 *
 * @since __BUMP_VERSION__
 */
class ImagesPropertiesController extends AdminController
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

//    /**
//	 * Proxy for getModel
//	 *
//	 * @param   string  $name    The model name. Optional.
//	 * @param   string  $prefix  The class prefix. Optional.
//	 * @param   array   $config  The array of possible config values. Optional.
//	 *
//	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	public function getModel($name = 'Gallery', $prefix = 'Administrator', $config = array('ignore_request' => true))
//	{
//		return parent::getModel($name, $prefix, $config);
//	}

	/**
	 * Redirect to standard image properties tile view
	 * Called from upload
	 *
	 * @since 4.3.0
	 */
	public function PropertiesView ()
	{
		Session::checkToken();

		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg = "ImagesProperties.PropertiesView: ";
			$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			// toDo: find " str_replace('\n', '<br>', $msg);" nad replace in complete project
			$msg = nl2br($msg);

			$link = 'index.php?option=com_rsgallery2';
			$this->setRedirect($link, $msg, $msgType);
		}
		else
		{
			// &ID[]=2&ID[]=3&ID[]=4&ID[]=12
			//127.0.0.1/Joomla3x/administrator/index.php?option=com_rsgallery2&view=imagesProperties&cid[]=1&cid[]=2&cid[]=3&cid[]=4
			$cids = $this->input->get('cid', 0, 'int');
			//$this->setRedirect('index.php?option=' . $this->option . '&view=' . $this->view_item . '&' . http_build_query(array('cid' => $cids)));
			$this->setRedirect('index.php?option=' . $this->option . '&view=imagesProperties' . '&' . http_build_query(array('cid' => $cids)));

			parent::display();
		}
	}
}
