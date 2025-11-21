<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2022-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Input\Input;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;



/**
 * The Controller
 *
     * @since      5.1.0
 */
class ImageFileController extends BaseController
{
    /**
     * The extension for which the galleries apply.
     *
     * @var    string
     * @since  5.1.0     */
    // 2025.01.10 ToDo: followin is not working do we need  'extension' ?
    // protected $extension;

    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input              $input    Input
     *
     * @since   5.1.0
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        // 2025.01.10 ToDo: followin is not working do we need  'extension' ?
        /**
        if (empty($this->extension)) {
            //
            $this->extension = $this->input->get('extension', 'com_rsgallery2');
        }
        /**/
    }
    /**/
}
