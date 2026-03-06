<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Controller;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Media\Administrator\Provider\ProviderManagerHelperTrait;
use Joomla\Component\Media\Api\Model\MediumModel;
use Joomla\String\Inflector;
use Tobscure\JsonApi\Exception\InvalidParameterException;

// use function dirname;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

// phpcs:enable PSR1.Files.SideEffects


class UploadController extends ApiController
{
    use ProviderManagerHelperTrait;

    /**
     * The content type of the item.
     *
     * @var    string
     * @since  4.1.0
     */
    protected $contentType = 'upload';


    /**
     * The default view for the display method.
     *
     * @var    string
     *
     * @since  4.1.0
     */
    protected $default_view = 'upload';


    public function upload(): void
    {
        // $image_name = $this->input->json->get('image_name', '', 'PATH');
        $image_name = $this->input->json->get('image_name', '', 'STRING');
        $gallery_id = $this->input->json->get('gallery_id', '', 'INTEGER');
        $content    = $this->input->json->get('content', '', 'RAW');

        $missingParameters = [];

        if (empty($image_name)) {
            $missingParameters[] = 'image_name';
        }

        if (empty($gallery_id)) {
            $missingParameters[] = 'gallery_id';
        }

        // Content is only required when it is a file
        if (empty($content)) {
            $missingParameters[] = 'content';
        }

        if (\count($missingParameters)) {
            throw new InvalidParameterException(
                Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)),
            );
        }

        //--- secure path and image name ----------------------------

        // ToDo: secure path and image name
        // see input above ?
        // if (!\file_exists($path)) {}

        $this->modelState->set('image_name', $image_name);
        $this->modelState->set('gallery_id', $gallery_id);

        // Check if an existing file may be overwritten. Defaults to false.
        $this->modelState->set('override', $this->input->json->get('override', false));

        parent::add();
    }

    /**
     * Method to create or modify a file or folder.
     *
     * @param   integer  $recordKey  The primary key of the item (if exists)
     *
     * @return  string   The path
     *
     * @since   4.1.0
     */
    protected function save($recordKey = null)
    {
        // Explicitly get the single item model name.
        $modelName = $this->input->get('model', Inflector::singularize($this->contentType));

        /** @var MediumModel $model */
        $model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);

        $json = $this->input->json;

        // Decode content, if any
        if ($content = base64_decode($json->get('content', '', 'raw'))) {
            $this->checkContent();
        }

        // If there is no content, com_media assumes the path refers to a folder.
        $this->modelState->set('content', $content);

        return $model->save();
    }



    /**
     * Performs various checks to see if it is allowed to save the content.
     *
     * @return  void
     *
     * @since   4.1.0
     *
     * @throws  \RuntimeException
     */
    private function checkContent(): void
    {
        $params       = ComponentHelper::getParams('com_media');
        $helper       = new \Joomla\CMS\Helper\MediaHelper();
        $serverlength = $this->input->server->getInt('CONTENT_LENGTH');

        // Check if the size of the request body does not exceed various server imposed limits.
        if (
            ($params->get('upload_maxsize', 0) > 0 && $serverlength > ($params->get('upload_maxsize', 0) * 1024 * 1024))
            || $serverlength > $helper->toBytes(\ini_get('upload_max_filesize'))
            || $serverlength > $helper->toBytes(\ini_get('post_max_size'))
            || $serverlength > $helper->toBytes(\ini_get('memory_limit'))
        ) {
            throw new \RuntimeException(Text::_('COM_MEDIA_ERROR_WARNFILETOOLARGE'), 400);
        }
    }




}
