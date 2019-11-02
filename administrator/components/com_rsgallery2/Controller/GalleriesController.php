<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

/**
 * The Galleries List Controller
 *
 * @since  1.6
 */
class GalleriesController extends AdminController
{
	/**
	 * Proxy for getModel
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Gallery', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Rebuild the nested set tree.
	 *
	 * @return  boolean  False on failure or error, true on success.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$extension = $this->input->get('extension');
		$this->setRedirect(Route::_('index.php?option=com_rsgallery2&view=galleries&extension=' . $extension, false));

		/** @var \Joomla\Component\Rsgallery2\Administrator\Model\GalleryModel $model */
		$model = $this->getModel();

		if ($model->rebuild())
		{
			// Rebuild succeeded.
			$this->setMessage(Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_SUCCESS'));

			return true;
		}

		// Rebuild failed.
		$this->setMessage(Text::_('COM_RSGALLERY2_GALLERIES_REBUILD_FAILURE'));

		return false;
	}

	/**
	 * Deletes and returns correctly.
	 *
	 * @return  void
	 *
	 * @since   3.1.2
	 */
	public function delete()
	{
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = $this->input->get('cid', array(), 'array');
		$extension = $this->input->getCmd('extension', null);

		if (!is_array($cid) || count($cid) < 1)
		{
			$this->app->enqueueMessage(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'warning');
		}
		else
		{
			// Get the model.
			/** @var \Joomla\Component\Rsgallery2\Administrator\Model\GalleryModel $model */
			$model = $this->getModel();

			// Make sure the item ids are integers
			$cid = ArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->delete($cid))
			{
				$this->setMessage(Text::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&extension=' . $extension, false));
	}

	/**
	 * Check in of one or more records.
	 *
	 * Overrides \JControllerAdmin::checkin to redirect to URL with extension.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.6.0
	 */
	public function checkin()
	{
		// Process parent checkin method.
		$result = parent::checkin();

		// Override the redirect Uri.
		$redirectUri = 'index.php?option=' . $this->option . '&view=' . $this->view_list . '&extension=' . $this->input->get('extension', '', 'CMD');
		$this->setRedirect(Route::_($redirectUri, false), $this->message, $this->messageType);

		return $result;
	}
}
