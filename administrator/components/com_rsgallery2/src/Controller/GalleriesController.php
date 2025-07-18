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
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryTreeModel;



/**
 * The Galleries List Controller
 *
 * @since __BUMP_VERSION__
 */
class GalleriesController extends AdminController
{
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
     * Proxy for getModel
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  The array of possible config values. Optional.
     *
     * @return  BaseDatabaseModel  The model.
     *
     * @since __BUMP_VERSION__
     */
    public function getModel($name = 'Gallery', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Rebuild the nested set tree.
     *
     * @return  boolean  False on failure or error, true on success.
     *
     * @since __BUMP_VERSION__
     */
    public function rebuild()
    {
        $isOk = false;

        $msg     = "GalleriesController.rebuild: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                /** @var GalleryModel $model */
                $model = $this->getModel();

                $isOk = $model->rebuild();
                if ($isOk) {
                    $msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS');
                } else {
                    $msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing rebuild: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=galleries&layout=galleries_tree';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public function reinitNestedGalleryTable()
    {
        $isOk = false;

        $msg     = "GalleriesController.reinitNestedGalleryTable: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // Get the model.
                $model = $this->getModel('GalleryTree');

                // Remove the items.
                $isOk = $model->reinitNestedGalleryTable();
                if ($isOk) {
                    $msg .= Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET_SUCCESS');
                } else {
                    $msg .= Text::_('COM_RSGALLERY2_GALLERIES_TABLE_RESET_ERROR') . ': ' . $model->getError();
                }
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing reinitNestedGalleryTable: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=galleries&layout=galleries_tree';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /** @var GalleryTreeModel $model */

}
