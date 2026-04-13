<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2008-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Controller;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\String\Inflector;
use Rsgallery2\Component\Rsgallery2\Api\Model\VersionModel;
use Rsgallery2\Component\Rsgallery2\Api\View\Version\JsonapiView;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The version controller
 *
 * @since  5.0.10
 */
class VersionController extends ApiController
{
    /**
     * The content type of the item.
     *
     * @var    string
     * @since  5.0.10
     */
    protected $contentType = 'version';

    /**
     * The default view for the display method.
     *
     * @var    string
     * @since  5.0.10
     */
    protected $default_view = 'version';

    /**
     * Generic method to prepare the view
     *
     * @return JsonApiView  The prepared view
     *
     * @since  5.0.10
     */
    protected function prepareView()
    {
        $viewType   = $this->app->getDocument()->getType();
        $viewName   = $this->input->get('view', $this->default_view);
        $viewLayout = $this->input->get('layout', 'default', 'string');

        try {
            /** @var JsonApiView $view */
            $view = $this->getView(
                $viewName,
                $viewType,
                '',
                ['base_path' => $this->basePath, 'layout' => $viewLayout, 'contentType' => $this->contentType]
            );
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        $modelName = $this->input->get('model', Inflector::singularize($this->contentType));

        // Create the model, ignoring request data so we can safely set the state in the request from the controller
        /** @var VersionModel $model */
        $model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);

        // test if model is valid
//        try {
//            $modelName = $model->getName();
//        } catch (\Exception $e) {
//            throw new \RuntimeException($e->getMessage());
//        }
        if (!$model) {
            throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_MODEL_CREATE'));
        }

        // Push the model into the view (as default)
        $view->setModel($model, true);

        $view->setDocument($this->app->getDocument());
        $view->displayItem();

        return $view;
    }
}
