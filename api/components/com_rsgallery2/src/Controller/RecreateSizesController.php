<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Controller;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Media\Administrator\Provider\ProviderManagerHelperTrait;
use Joomla\Component\Media\Api\Model\MediumModel;
use Joomla\Filesystem\File;
use Joomla\String\Inflector;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\PathHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImageFileModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;
use Tobscure\JsonApi\Exception\InvalidParameterException;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

// phpcs:enable PSR1.Files.SideEffects


class RecreateSizesController extends ApiController
{
    use ProviderManagerHelperTrait;

    /**
     * The content type of the item.
     *
     * @var    string
     * @since  4.1.0
     */
    protected $contentType = 'recreatesizes';


    /**
     * The default view for the display method.
     *
     * @var    string
     *
     * @since  4.1.0
     */
    protected $default_view = 'recreatesizes';

    public function recreate_sizes()
    {

        // $image_name = $this->input->json->get('image_name', '', 'PATH');
        $image_name = $this->input->json->get('image_name', '', 'STRING');
        $gallery_id = $this->input->json->get('gallery_id', '', 'INTEGER');

        $missingParameters = [];

        if (empty($image_name))
        {
            $missingParameters[] = 'image_name';
        }

        if (empty($gallery_id))
        {
            $missingParameters[] = 'gallery_id';
        }

        if (\count($missingParameters))
        {
//      throw new InvalidParameterException(Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)));
            throw new InvalidParameterException(Text::sprintf('Missing required parameter(s): %s', implode(' & ', $missingParameters)));
        }

        $targetFileName = File::makeSafe($image_name);

        $rsgConfig        = ComponentHelper::getParams('com_rsgallery2');
        $thumbSize        = $rsgConfig->get('thumb_size');
        $use_j3x_location = $rsgConfig->get('useJ3xOldPaths');

        $srcTempPathFileName = '';

        if (!$use_j3x_location)
        {
            $imagePaths = new ImagePathsModel($gallery_id);  // ToDo: J3x
            $imagePaths->createAllPaths();

            $originalFileName = PathHelper::join($imagePaths->originalBasePath, $targetFileName);
        }
        else
        {
            $imagePathJ3x = new ImagePathsJ3xModel($gallery_id);  // ToDo: J3x
            $imagePathJ3x->createAllPaths();

            $originalFileName = PathHelper::join($imagePathJ3x->originalBasePath, $targetFileName);
        }

        //----------------------------------------------------
        // Move file and create display, thumbs and watermarked images
        //----------------------------------------------------

        try
        {
            $origin = 'server'; // Do not move original file

            /* @var ImageFileModel $modelFile */
            $modelFile = $this->getModel('imageFile');
            [$isCreated, $urlThumbFile, $msg] = $modelFile->MoveImageAndCreateRSG2Images($originalFileName, $targetFileName, $gallery_id, $origin, $use_j3x_location);
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'moveFile2OrignalDir: "' . $srcTempPathFileName . '" -> "' . $targetFileName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

//            if ($Rsg2DebugActive) {
//                Log::add($OutTxt);
//            }
        }

        if (!$isCreated)
        {
            // ToDo: remove $imageId fom image database

            //...


        }

        return;
    }

}
