<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Registry\Registry;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class GalleryJ3xModel extends GalleryModel
{



	/**
	 * Add "asInline" Url to each image
	 * @param $images
	 *
	 *
	 * @since 4.5.0.0
	 */
	public function AddLayoutData($images)
	{
		// ToDo: check for J3x style of gallery (? all in construct ?)
		parent::AddLayoutData($images);

		try
		{
			foreach ($images as $image)
			{

				$this->AssignUrl_AsInline($image);
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'GalleriesModel: AddLayoutData: Error executing query: "' . "" . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $images;
	}

	/**
	 * @param $images
	 *
	 *
	 * @since 4.5.0.0
	 */
	public function AssignUrl_AsInline($image)
	{

		try {

			$image->UrlLayout_AsInline = ''; // fall back

			//  Factory::getApplication()->getMenu()
			$app = Factory::getApplication();
			$urlMenu  = $app->getMenu()->getActive()->link;
//
//			echo Route::_($urlMenu);
//			echo '<br>';

			/**/
			{
				//$image->UrlLayout_AsInline = Route::_(URI::root() . 'option=com_rsgallery2&view=galleryJ3x'
				$image->UrlLayout_AsInline = Route::_($urlMenu
					. '&gid=' . $image->gallery_id
					. '&iid=' . $image->id
					. '&layout=imagesJ3xAsInline',true,0,true);
			}
			/**/
			// ToDo: watermarked file
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'GalleriesModelJ3x: AssignUrl_AsInline: Error executing query: "' . "" . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

	}





}

