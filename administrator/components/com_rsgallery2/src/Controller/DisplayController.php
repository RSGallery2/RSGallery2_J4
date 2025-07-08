<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Input\Input;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
/**
 * Rsgallery2 master display controller.
 *
 * @since __BUMP_VERSION__
 */
class DisplayController extends BaseController
{
    /**
     * The default view.
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
    protected $default_view = 'rsgallery2';

    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     *                                         Recognized key values include 'name', 'default_task', 'model_path', and
     *                                         'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input              $input    Input
     *
     * @since __BUMP_VERSION__
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable  If true, the view output will be cached
     *
     * @return  BaseController|bool  This object to support chaining.
     *
     * @license    GNU General Public License version 2 or later
     *
     * @since      __BUMP_VERSION__
     */
    public function display($cachable = false, $urlparams = [])
    {
        return parent::display();
    }
}
