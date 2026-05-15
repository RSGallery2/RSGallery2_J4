<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
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
     * @since  3.0
     */
    protected $default_view = 'images';

    /**
     * Adds some parameters for file name
     * then uses parent:add to save
     *
     * @since version
     */
    public function db_reserve_image_id (){

	    $title  = $this->input->json->getString('title');
	    $name  = $this->input->json->getString('name');
	    $gallery_id  = $this->input->json->getString('gallery_id', '');

	    $missingParameters = [];

	    if(empty($title))
	    {
		    $missingParameters[] = 'title';
	    }

	    if(empty($name))
	    {
		    $missingParameters[] = 'name';
	    }

	    if(\count($missingParameters))
	    {
		    // throw new InvalidParameterException(Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)));
		    throw new InvalidParameterException(Text::sprintf('Missing required parameter(s): %s', implode(' & ', $missingParameters)));
	    }

		//--- gallery_id ------------------------------------------------

	    if ((int)$gallery_id == 1) {
		    // throw new InvalidParameterException(Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)));
		    throw new InvalidParameterException(Text::_('Invalid parameter value "1" for gallery_id. Id 1 is reserved for the internal empty root item' ));
	    }

	    $isGalleryExisting = $this->isGalleryExisting($gallery_id);

	    if(! $isGalleryExisting)
	    {
		    // throw new InvalidParameterException(Text::sprintf('WEBSERVICE_COM_MEDIA_MISSING_REQUIRED_PARAMETERS', implode(' & ', $missingParameters)));
		    throw new InvalidParameterException(Text::sprintf('Gallery does not exist for parameter gallery_id: %s', $gallery_id));
	    }

		//--- prevent double names ------------------------------------------------------

		// ToDo: ? rsg2 model -> ....
	    // May need save on original rsg2 model
	    // Write to $title  = $this->input->json->getString('title'); setString not supported

		//--- prevent double titles ------------------------------------------------------

	    // ToDo: ? rsg2 model -> ....
	    // May need save on original rsg2 model
	    // Write to $name  = $this->input->json->setString('name', ); setString not supported

	    parent::add();
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
