<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Model;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Joomla\CMS\MVC\Controller\Exception\Save;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\Component\Media\Administrator\Exception\FileExistsException;
use Joomla\Component\Media\Administrator\Exception\FileNotFoundException;
use Joomla\Component\Media\Administrator\Exception\InvalidPathException;
use Joomla\Component\Media\Administrator\Model\ApiModel;
use Joomla\Component\Media\Administrator\Provider\ProviderManagerHelperTrait;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 *
 *
 * @since  4.2.0
 */
class UploadModel extends BaseModel
{
    use ProviderManagerHelperTrait;

    /**
     * Instance of com_media's ApiModel
     *
     * @var ApiModel
     * @since  4.1.0
     */
//    private $versionApiModel;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->uploadApiModel = new ApiModel();
    }

    /**
     * Method to get a single files or folder.
     *
     * @return  \stdClass  A file or folder object.
     *
     * @throws  ResourceNotFound
     * @since   4.1.0
     */
    public function getItem()
    {
        // ToDo; fill out later
        $componentName = 'com_rsgallery2';

        $oVersion = new \stdClass();

        return $oVersion;
    }


    /**
     * Method to save a file or folder.
     *
     * @param   string  $path  The primary key of the item (if exists)
     *
     * @return  string   The path
     *
     * @throws  Save
     * @since   4.1.0
     *
     */
    public function save($path = null): string
    {
        $image_name = $this->getState('image_name', null);
        $gallery_id = $this->getState('gallery_id', false);
        $content    = $this->getState('content', null);
        $override   = $this->getState('override', false);

        //--- create path ----------------------------------

        // ToDo: use file class to retrieve original by gallery id
        // $path = 'local-image' . ':/' . implode('/', $paths);
//        $path = 'local-image:/' . 'Rsgallery2/' . $gallery_id . '/' . $image_name;
        $path        = '/rsgallery2/' . $gallery_id . '/original/' . $image_name;
        $adapterName = 'local-images';

        //--- ToDo: validate path ------------------------------

        try {
            //--- write file ------------------------------

            if ($path && $content) {
                // com_media expects separate directory and file name.
                $basename = basename($path);
                $dirname  = \dirname($path);

                $name = $this->uploadApiModel->createFile(
                    $adapterName,
                    $basename,
                    $dirname,
                    $content,
                    $override,
                );

                $resultPath = $dirname . '/' . $name;
            }
        } catch (FileNotFoundException) {
            throw new Save(
                Text::sprintf(
                    'WEBSERVICE_COM_MEDIA_FILE_NOT_FOUND',
                    $dirname . '/' . $basename,
                ),
                404,
            );
        } catch
        (FileExistsException) {
            throw new Save(
                Text::sprintf(
                    'WEBSERVICE_COM_MEDIA_FILE_EXISTS',
                    $dirname . '/' . $basename,
                ),
                400,
            );
        } catch
        (InvalidPathException) {
            throw new Save(
                Text::sprintf(
                    'WEBSERVICE_COM_MEDIA_BAD_FILE_TYPE',
                    $dirname . '/' . $basename,
                ),
                400,
            );
        }

        // If we still have no result path, something fishy is going on.
        if (!$resultPath) {
            throw new Save(
                Text::_(
                    'WEBSERVICE_COM_MEDIA_UNSUPPORTED_PARAMETER_COMBINATION'
                ),
                400
            );
        }

        // Return resulting path with the requested adapter in it
        return $adapterName . ':/' . $resultPath;
    }
}
