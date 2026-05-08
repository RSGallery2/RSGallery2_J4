<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Controller;

use Doctrine\Inflector\InflectorFactory;
use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Language\Text;
use Joomla\Component\Media\Api\Model\MediumModel;
use Joomla\Input\Json;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\String\Inflector;
use Rsgallery2\Component\Rsgallery2\Api\Model\ConfigModel;
use Tobscure\JsonApi\Exception\InvalidParameterException;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The images controller
 *
 * @since  4.0.0
 */
class ConfigController extends ApiController
{
	/**
	 * The content type of the item.
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $contentType = 'config';

	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $default_view = 'config';

	/**
	 * Call version model
	 *
	 * @param $cachable
	 * @param $urlparams
	 *
	 * @return $this|\Joomgallery\Component\Joomgallery\Api\Controller\VersionController
	 *
	 * @since  5.1.0
	 */
	public function displayList($cachable = false, $urlparams = [])
	{
		$viewType   = $this->app->getDocument()->getType();
		$viewName   = $this->input->get('view', $this->default_view);
		$viewLayout = $this->input->get('layout', 'default', 'string');

		try
		{
			/** @var \Joomla\CMS\MVC\View\JsonApiView $view */
			$view = $this->getView($viewName, $viewType, '', ['base_path' => $this->basePath, 'layout' => $viewLayout, 'contentType' => $this->contentType],);
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
		}

		//--- set state from given parameter  ---------------------------------------------

		// 		$this->modelState->set('param', $param);

		//--- create model -------------------------------------

		$modelName = $this->input->get('model', Inflector::singularize($this->contentType));

		// Create the model, ignoring request data so we can safely set the state in the request from the controller
		/** @var ConfigModel $model */
		$model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);

		// test if model is valid
		if (!$model)
		{
			throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_MODEL_CREATE'));
		}

		// Push the model into the view (as default)
		$view->setModel($model, true);

		//--- filter -------------------------------------

		$apiFilterInfo = $this->input->get('filter', [], 'array');
		$filter        = InputFilter::getInstance();

		if (\array_key_exists('search', $apiFilterInfo))
		{
			$model->setState('filter.search', $filter->clean($apiFilterInfo['search'], 'STRING'));
			//$this->modelState->set('filter.search', $filter->clean($apiFilterInfo['search'], 'STRING'));
		}

		//--- display result -------------------------------------

		$view->setDocument($this->app->getDocument());
		$view->displayItem();

		// $view->displayList();

		return $this;
	}


	/**
	 * @param $param
	 *
	 * @return ConfigController
	 *
	 * @since version
	 */
	public function displayItem($param = '')
	{
		//--- set state from given parameter  -------------------------------------

		$param = $this->input->get('para', '', 'string');
		$this->modelState->set('param', $param);

		// all variables
		$data = $this->input->json->getArray();
		if (!empty($data))
		{
			$this->modelState->set('data', $data);
		}

		//--- display parameters --------------------------------------------------

		return parent::displayItem(0);
	}

	/**
	 *
	 * @return ConfigController
	 *
	 * @throws InvalidParameterException
	 * @since version
	 */
	public function edit()
	{
		// Access check.
		if (!$this->allowEdit())
		{
			throw new NotAllowed('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED', 403);
		}

		// all variables
		$data = $this->input->json->getArray();

		if (empty($data))
		{
			throw new InvalidParameterException(Text::_('No parameter given for patch config'), 403);        //	Text::sprintf('Missing required parameter(s): %s', implode(' & ', $missingParameters))
		}

		//--- Create the model -----------------------------------------------------------------

		/** @var ConfigModel $model */
		$model = $this->getModel('Config', '', ['ignore_request' => true, 'state' => $this->modelState]);

		$isSaved = $model->save($data);

		return parent::displayItem('0');
	}

	/**
	 * Method to check if it's allowed to modify an existing file or folder.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   4.1.0
	 */
	protected function allowEdit($data = [], $key = 'id'): bool
	{
		$user = $this->app->getIdentity();

		// com_media's access rules contains no specific update rule.
		return $user->authorise('core.edit', 'com_media');
	}

	public function add(): void
	{
		// Access check.
		if (!$this->allowEdit())
		{
			throw new NotAllowed('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED', 403);
		}

		parent::add();
	}

	/**
	 * Method to check if it's allowed to add a new file or folder
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   4.1.0
	 */
	protected function allowAdd($data = []): bool
	{
		$user = $this->app->getIdentity();

		return $user->authorise('core.create', 'com_media');
	}

	protected function save($recordKey = null)
	{
		// Explicitly get the single item model name.
		//$inflector = InflectorFactory::create()->build();
		// $modelName = $this->input->get('model', $inflector->singularize($this->contentType));

		// all variables
		$data = $this->input->json->getArray();

		if (empty($data))
		{
			throw new InvalidParameterException(Text::_('No parameter given for post config'), 403);        //	Text::sprintf('Missing required parameter(s): %s', implode(' & ', $missingParameters))
		}

		//--- Create the model -----------------------------------------------------------------

		/** @var ConfigModel $model */
		$model = $this->getModel('Config', '', ['ignore_request' => true, 'state' => $this->modelState]);

		return $model->save($data, true);
	}

	public function delete($id = null): void
	{
		if (!$this->allowDelete()) {
			throw new NotAllowed('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED', 403);
		}

		$param = $this->input->get('para', '', 'string');
		$this->modelState->set('param', $param);

		// all variables  => not working as json not given (when not in editor)
		$json = $this->input->json;
//		$raw = $this->input->json->getRaw();
		$data = $this->input->json->getArray();

		$param = $this->input->get('para', '', 'string');
		if (!empty($param)) {
//			$key = key((array)$param);
//			$data->$key = $param;
			$data[$param] = 0;
		}

		if (empty($data))
		{
			$errText = Text::sprintf('No parameter given for delete config ==> json: "%s"', $this->input->json->getRaw());
			throw new InvalidParameterException($errText, 403);        //	Text::sprintf('Missing required parameter(s): %s', implode(' & ', $missingParameters))
		}

		//--- Create the model -----------------------------------------------------------------

		/** @var ConfigModel $model */
		$model = $this->getModel('Config', '', ['ignore_request' => true, 'state' => $this->modelState]);

		$model->delete($data);

//		$this->app->setHeader('status', 204);
		parent::displayItem('0');
	}

	/**
	 * Method to check if it's allowed to delete an existing file or folder.
	 *
	 * @return  boolean
	 *
	 * @since   4.1.0
	 */
	protected function allowDelete(): bool
	{
		$user = $this->app->getIdentity();

		return $user->authorise('core.delete', 'com_media');
	}



}
