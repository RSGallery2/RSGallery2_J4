<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel;



/**
 * The Galleries List Controller
 *
 * @since __BUMP_VERSION__
 */
class MaintConsolidateDbController extends AdminController
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @since 4.3.0
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
     * @since 4.3.0
     */
    public function getModel(
        $name = 'maintConsolidateDB',
        $prefix = 'rsgallery2Model',
        $config = ['ignore_request' => true],
    ) {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Creates a database entry (row) for all mismatched items
     *
     * @since 4.3.0
     */
    public function createImageDbItems()
    {
        $isOk = false;

        $msg     = "MaintConsolidateDb.createImageDbItems: ";
        $msgType = 'notice';

        $this->checkToken();

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            $msg = nl2br($msg);
        } else {
            try {
				/**
				/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model *
				$model = $this->getModel();

				$isOk = $model->rebuild();
				if ($isOk) {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS');
				} else {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
				}
				/**/

//				$msg .= ' started';
//				Factory::getApplication()->enqueueMessage($msg, 'notice');

                $msg .= ' no code, not done';
                Factory::getApplication()->enqueueMessage($msg, 'error');
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createImageDbItems: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=maintConsolidateDb';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     * Rebuild the nested set tree.
     *
     * @return  boolean  False on failure or error, true on success.
     *
     * @since __BUMP_VERSION__
     */
    public function createMissingImages()
    {
        $isOk = false;

        $msg     = "MaintConsolidateDb.createMissingImages: ";
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
				/**
				/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model *
				$model = $this->getModel();

				$isOk = $model->rebuild();
				if ($isOk) {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS');
				} else {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
				}
				/**/

//				$msg .= ' started';
//				Factory::getApplication()->enqueueMessage($msg, 'notice');

                $msg .= ' no code, not done';
                Factory::getApplication()->enqueueMessage($msg, 'error');
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createMissingImages: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=maintConsolidateDb';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     * Rebuild the nested set tree.
     *
     * @return  boolean  False on failure or error, true on success.
     *
     * @since __BUMP_VERSION__
     */
    public function createWatermarkImages()
    {
        $isOk = false;

        $msg     = "MaintConsolidateDb.createWatermarkImages: ";
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
				/**
				/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model *
				$model = $this->getModel();

				$isOk = $model->rebuild();
				if ($isOk) {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS');
				} else {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
				}
				/**/

//				$msg .= ' started';
//				Factory::getApplication()->enqueueMessage($msg, 'notice');

                $msg .= ' no code, not done';
                Factory::getApplication()->enqueueMessage($msg, 'error');
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing createWatermarkImages: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=maintConsolidateDb';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     * Rebuild the nested set tree.
     *
     * @return  boolean  False on failure or error, true on success.
     *
     * @since __BUMP_VERSION__
     */
    public function assignParentGallery()
    {
        $isOk = false;

        $msg     = "MaintConsolidateDb.assignParentGallery: ";
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
				/**
				/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model *
				$model = $this->getModel();

				$isOk = $model->rebuild();
				if ($isOk) {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS');
				} else {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
				}
				/**/

//				$msg .= ' started';
//				Factory::getApplication()->enqueueMessage($msg, 'notice');

                $msg .= ' no code, not done';
                Factory::getApplication()->enqueueMessage($msg, 'error');
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing assignParentGallery: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=maintConsolidateDb';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     * Rebuild the nested set tree.
     *
     * @return  boolean  False on failure or error, true on success.
     *
     * @since __BUMP_VERSION__
     */
    public function deleteRowItems()
    {
        $isOk = false;

        $msg     = "MaintConsolidateDb.deleteRowItems: ";
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
				/**
				/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model *
				$model = $this->getModel();

				$isOk = $model->rebuild();
				if ($isOk) {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS');
				} else {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
				}
				/**/

//				$msg .= ' started';
//				Factory::getApplication()->enqueueMessage($msg, 'notice');

                $msg .= ' no code, not done';
                Factory::getApplication()->enqueueMessage($msg, 'error');
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing deleteRowItems: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=maintConsolidateDb';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     * Creates a database entry (row) for all mismatched items
     *
     * @since 4.3.0
     */
    public function repairAllIssuesItems()
    {
        $isOk = false;

        $msg     = "MaintConsolidateDb.repairAllIssuesItems: ";
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
				/**
				/** @var \Rsgallery2\Component\Rsgallery2\Administrator\Model\GalleryModel $model *
				$model = $this->getModel();

				$isOk = $model->rebuild();
				if ($isOk) {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS');
				} else {
				$msg .= Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
				}
				/**/

//				$msg .= ' started';
//				Factory::getApplication()->enqueueMessage($msg, 'notice');

                $msg .= ' no code, not done';
                Factory::getApplication()->enqueueMessage($msg, 'error');
            } catch (\RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing repairAllIssuesItems: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_rsgallery2&view=maintConsolidateDb';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

}
