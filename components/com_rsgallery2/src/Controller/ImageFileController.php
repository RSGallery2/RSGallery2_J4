<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2022-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Session\Session;
use Joomla\Input\Input;
use Joomla\Registry\Registry;

/**
 * The Controller
 *
 * @since __BUMP_VERSION__
 */
class ImageFileController extends BaseController
{
    /**
     * The extension for which the galleries apply.
     *
     * @var    string
     * @since  5.1.0     */
    protected $extension;

    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input              $input    Input
     *
     * @since   5.1.0     * @see    \JControllerLegacy
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        if (empty($this->extension)) {
            $this->extension = $this->input->get('extension', 'com_rsgallery2');
        }
    }

    /**
     * Download image file to user via Browser.
     *
     * @return  boolean
     *
     * @since   5.1.0     */
    public function downloadfile()
    {
        $isDownloaded = false;

        // $msg     = '<strong>' . 'Save2Upload ' . ':</strong><br>';
        $msg = 'Download image file: ';
        $app = Factory::getApplication();

        // Not needed : everyone may download the original image
        //        // Check for request forgeries.
        //        $this->checkToken();

        $input   = Factory::getApplication()->input;
        $imageId = $input->get('id', 0, 'INT');

        try {
            /** @yyyvar \Rsgallery2\Component\Rsgallery2\Administrator\Model\ImageFileModel $model */
            $model = $this->getModel();

            // query database for needed attributes
            [$fileName, $galleryId, $use_j3x_location] = $model->imageFileAttrib($imageId);

            // not successful
            if (empty($fileName) || empty($galleryId)) {
                $msg     .= ' failed. Filename or gallery could not be determnined. ID: ' . $imageId;
                $msgType = 'error';
                $app->enqueueMessage($msg, $msgType);
            } else {
                [$OriginalFilePath, $OriginalFileUri] = $model->getOriginalPaths($fileName, $galleryId, $use_j3x_location);

                $isDownloaded = $model->downloadImageFile($OriginalFilePath, $OriginalFileUri);

                if ($isDownloaded) {
                    // ToDo: Prepare gallery ID and pre select it in upload form

                    $msg     .= ' successful';
                    $msgType = 'notice';
                    $app->enqueueMessage($msg, $msgType);
                } else {
                    $msg     .= ' failed. Download could not be completed for ID: ' . $imageId;
                    $msgType = 'error';
                    $app->enqueueMessage($msg, $msgType);
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing rebuild: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isDownloaded;
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
     * @since   5.1.0     */
    public function getModel($name = 'ImageFile', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }
    /**/

//	/**
//	 *
//	 *
//	 * @param   array  $data  An array of input data.
//	 *
//	 * @return  boolean
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	protected function yyyy($data = array())
//	{
//        $app  = Factory::getApplication();
//        $user = $app->getIdentity();
//
//		return ($user->authorise('core.create', $this->extension) || count($user->getAuthorisedGalleries($this->extension, 'core.create')));
//	}

}
