<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;

/**
 * Rsgallery2 Component Controller
 *
 * @since  __BUMP_VERSION__
 */
class DisplayController extends BaseController
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
//     * Constructor.
//     *
//     * @param   array                $config   An optional associative array of configuration settings.
//     * Recognized key values include 'name', 'default_task', 'model_path', and
//     * 'view_path' (this list is not meant to be comprehensive).
//     * @param   MVCFactoryInterface  $factory  The factory.
//     * @param   CMSApplication       $app      The JApplication for the dispatcher
//     * @param   \JInput              $input    Input
//     *
//     * @since __BUMP_VERSION__
//     */
//    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
//    {
//        // Contact frontpage Editor contacts proxying:
//        $input = Factory::getApplication()->input;
//
//        if ($input->get('view') === 'contacts' && $input->get('layout') === 'modal')
//        {
//            $config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
//        }
//
//        parent::__construct($config, $factory, $app, $input);
//    }
//    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
//    {
//        $config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
//
//        parent::__construct($config, $factory, $app, $input);
//    }

    /**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link \JFilterInput::clean()}.
	 *
	 * @return  static  This object to support chaining.
	 *
	 * @since __BUMP_VERSION__
     */
	public function display($cachable = false, $urlparams = array())
	{
		parent::display($cachable);

		return $this;



    }
}
