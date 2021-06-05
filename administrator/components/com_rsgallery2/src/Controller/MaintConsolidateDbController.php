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
class MaintConsolidateDbController extends AdminController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     *
     * @since 4.3.0
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
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
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
     * @since 4.3.0
	 */
	public function getModel($name = 'maintConsolidateDB', 
        $prefix = 'rsgallery2Model', 
        $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
     * Creates a database entry (row) for all mismatched items
     *
     * @since 4.3.0
	 */
	public function createImageDbItems ()
	{
		$isOk = false;

		$msg = "MaintConsolidateDb.createImageDbItems: ";
		$msgType = 'notice';

		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		$canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin) {
			$msg .= Text::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
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

				$msg .= ' started';
				Factory::getApplication()->enqueueMessage($msg, 'notice');

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
	public function createMissingImages ()
	{
		$isOk = false;

		$msg = "MaintConsolidateDb.createMissingImages: ";
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

				$msg .= ' started';
				Factory::getApplication()->enqueueMessage($msg, 'notice');

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
	public function createWatermarkImages ()
	{
		$isOk = false;

		$msg = "MaintConsolidateDb.createWatermarkImages: ";
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

				$msg .= ' started';
				Factory::getApplication()->enqueueMessage($msg, 'notice');

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
	public function assignParentGallery ()
	{
		$isOk = false;

		$msg = "MaintConsolidateDb.assignParentGallery: ";
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

				$msg .= ' started';
				Factory::getApplication()->enqueueMessage($msg, 'notice');

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
	public function deleteRowItems ()
	{
		$isOk = false;

		$msg = "MaintConsolidateDb.deleteRowItems: ";
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

				$msg .= ' started';
				Factory::getApplication()->enqueueMessage($msg, 'notice');

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
	public function repairAllIssuesItems ()
	{
		$isOk = false;

		$msg = "MaintConsolidateDb.repairAllIssuesItems: ";
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

				$msg .= ' started';
				Factory::getApplication()->enqueueMessage($msg, 'notice');

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
