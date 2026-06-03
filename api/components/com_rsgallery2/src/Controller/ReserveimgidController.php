<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2026-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\CMS\Response\JsonResponse;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImageModel;
use Tobscure\JsonApi\Exception\InvalidParameterException;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The images controller
 *
 * @since  4.0.0
 */
class ReserveimgidController extends ImagesController // ApiController
{
    /**
     * The content type of the item.
     *
     * @var    string
     * @since  4.0.0
     */
    protected $contentType = 'images';

    /**
     * The default view for the display method.
     *
     * @var    string
     *
     * @since  5.0
     */
    protected $default_view = 'images';

    /**
     * Adds some parameters for file name
     * then uses parent:add to save
     *
     * @since  5.0
     */
    public function db_reserve_image_id (){

	    $title  = $this->input->json->getString('title');
	    $description  = $this->input->json->getString('description', '');
        // Image filename
	    $name  = $this->input->json->getString('name');
	    $gallery_id  = $this->input->json->getInt('gallery_id', 0);

	    $missingParameters = [];

	    if(empty($title))
	    {
		    $missingParameters[] = 'title';
	    }

	    if(empty($name))
	    {
		    $missingParameters[] = 'name';
	    }

	    if(empty($gallery_id))
	    {
		    $missingParameters[] = 'gallery_id';
	    }

	    if(\count($missingParameters))
	    {
		    // throw new InvalidParameterException(Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)));
		    throw new InvalidParameterException(Text::sprintf('Missing required parameter(s): %s', implode(' & ', $missingParameters)));
	    }

		//--- gallery_id ------------------------------------------------

	    if ((int)$gallery_id < 2) {
		    // throw new InvalidParameterException(Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)));
		    throw new InvalidParameterException(Text::_('Invalid parameter value "1" for gallery_id. Id "1" is reserved for the internal empty root tree item' ));
	    }

	    $isGalleryExisting = $this->isGalleryExisting($gallery_id);

	    if(! $isGalleryExisting)
	    {
		    // throw new InvalidParameterException(Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)));
		    throw new InvalidParameterException(Text::sprintf('Gallery does not exist for parameter gallery_id: %s', $gallery_id));
	    }

		//--- prevent double names ------------------------------------------------------

		// ToDo: ? done in RSG2 Admin model ?
	    // May need save on original rsg2 model
	    // Write to $title  = $this->input->json->getString('title'); setString not supported

		//--- prevent double titles ------------------------------------------------------

        // ToDo: ? done in RSG2 Admin model ?
	    // May need save on original rsg2 model
	    // Write to $name  = $this->input->json->setString('name', ); setString not supported

	    try
        {
            // ToDo: try parent::add() like in JoomGallery

            //--- create model ----------------------------------------------

            /* @var ImageModel $modelDb */
            $modelDb = $this->getModel('image', 'administrator');

            //--- Create Destination file name -----------------------

            // see original administrator\components\com_rsgallery2\src\Controller\UploadController.php
            // ToDo: use sub folder for each gallery and check within gallery
            // Each filename is only allowed once so create a new one if file already exist
            $useFileName                    = $modelDb->generateNewImageName($name, $gallery_id);
            $ajaxImgDbObject['dstFileName'] = $useFileName;

            $imageId = $modelDb->createImageDbItem($useFileName, '', $gallery_id, $description);
            if (empty($imageId)) {
                // actual give an error
                //$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
                $msg = 'db_reserve_image_id: Create DB item for "' . $name . '"->"' . $useFileName
                    . '" failed. Use maintenance -> Consolidate image database to check it ';
                echo new JsonResponse($ajaxImgDbObject, $msg, true);

                return;
            }

        }
        catch (\RuntimeException $e)
        {
            $errorTxt = '';
//            $errorTxt = 'moveFile2OrignalDir: "' . $srcTempPathFileName . '" -> "' . $targetFileName . '"<br>';
            $errorTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            throw new \RuntimeException($errorTxt);
        }

        return parent::displayItem($imageId);

//		    parent::add();
    }

    // Implement other methods like read, update, delete as needed

	/**
	 * @param   string  $gallery_id
	 *
	 *
	 * @since version
	 */
	private function isGalleryExisting(string $gallery_id)
	{
		$imgCount = 0;

		try {
			$db = Factory::getContainer()->get(DatabaseInterface::class);

			if ($gallery_id > 1)
			{
				$query = $db->createQuery()
					->select('COUNT(*)')
					->from('#__rsg2_galleries')
					->where($db->quoteName('id') . ' = :id')
					->bind(':id', $gallery_id, ParameterType::INTEGER);

				$db->setQuery($query);
				$imgCount = $db->loadResult();
			}
		} catch (\Exception $e) {
			throw new \RuntimeException($e->getMessage());
		}

		return ! empty ($imgCount);
	}
}
