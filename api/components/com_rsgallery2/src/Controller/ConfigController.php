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
        $viewType = $this->app->getDocument()->getType();
        $viewName = $this->input->get('view', $this->default_view);
        $viewLayout = $this->input->get('layout', 'default', 'string');

        try {
            /** @var \Joomla\CMS\MVC\View\JsonApiView $view */
            $view = $this->getView(
                $viewName,
                $viewType,
                '',
                ['base_path' => $this->basePath, 'layout' => $viewLayout, 'contentType' => $this->contentType],
            );
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        //--- create model -------------------------------------

        $modelName = $this->input->get('model', Inflector::singularize($this->contentType));

        // Create the model, ignoring request data so we can safely set the state in the request from the controller
        $model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);

        // test if model is valid
        if (!$model) {
            throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_MODEL_CREATE'));
        }

        // Push the model into the view (as default)
        $view->setModel($model, true);

        //--- filter -------------------------------------

        $apiFilterInfo = $this->input->get('filter', [], 'array');
        $filter        = InputFilter::getInstance();

        if (\array_key_exists('search', $apiFilterInfo)) {
            $model->setState('filter.search', $filter->clean($apiFilterInfo['search'], 'STRING'));
            //$this->modelState->set('filter.search', $filter->clean($apiFilterInfo['search'], 'STRING'));
        }

        //--- display result -------------------------------------

        $view->setDocument($this->app->getDocument());
        $view->displayItem();

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
		$param = $this->input->get('para', '', 'string');
		$this->modelState->set('param', $param);

//		// Display files in specific path.
//		$this->modelState->set('path', $path ?: $this->input->get('path', '', 'STRING'));
//
//		// Return files (not folders) as urls.
//		if ($this->input->exists('url')) {
//			$this->modelState->set('url', $this->input->get('url', true, 'BOOLEAN'));
//		}

		return parent::displayItem(0);
	}

	public function edit()
	{
		// Access check.
		if (!$this->allowEdit()) {
			throw new NotAllowed('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED', 403);
		}

		// all variables
		$data = $this->input->json->getArray();

		if (empty($data))
		{
			throw new InvalidParameterException(Text::sprintf('No parameter given for patch config'));		//	Text::sprintf('Missing required parameter(s): %s', implode(' & ', $missingParameters))
		}

		// ToDo: Handle array
		// $param = key($data[0]);
		$param = min(array_keys($data));
		$value = $data[$param];

//		// attention param used for get single item ToDo: revisit for handlinge get single different
//		$this->modelState->set('parameter', $param);
//		$this->modelState->set('value', $value);

		//--- Create the model -----------------------------------------------------------------

		/** @var ConfigModel $model */
		$model = $this->getModel('Config', '', ['ignore_request' => true, 'state' => $this->modelState]);

		$is = $model->save($data);
		$this->modelState->set('parameter', $param);
//		throw new \Exception(Text::_('edit ...'));

		return parent::displayItem('test');
	}

//	/**
//	 * Method to create or modify a file or folder.
//	 *
//	 * @param   integer  $recordKey  The primary key of the item (if exists)
//	 *
//	 * @return  string   The path
//	 *
//	 * @since   4.1.0
//	 */
//	protected function save($recordKey = null)
//	{
//		// Explicitly get the single item model name.
//		$inflector = InflectorFactory::create()->build();
//		$modelName = $this->input->get('model', $inflector->singularize($this->contentType));
//
//		/** @var MediumModel $model */
//		$model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);
//
////		$json = $this->input->json;
////
////		// Decode content, if any
////		if ($content = base64_decode($json->get('content', '', 'raw'))) {
////			$this->checkContent();
////		}
////
////		// If there is no content, com_media assumes the path refers to a folder.
////		$this->modelState->set('content', $content);
//
//		return $model->save();
//	}

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

	// Implement other methods like read, update, delete as needed
}
