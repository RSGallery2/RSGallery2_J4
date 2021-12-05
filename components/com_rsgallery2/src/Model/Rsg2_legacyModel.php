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
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Registry\Registry;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagesModel;
use Rsgallery2\Component\Rsgallery2\Site\Model;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class Rsg2_legacyModel extends GalleriesModel
{
    /**
    protected $layoutParams = null; // col/row count
	/**/

	/**
	 * This function will retrieve the data of the n last uploaded images
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, images name, date, and user name as rows
	 *
	 * @since __BUMP_VERSION__
	 * @throws Exception
	 */
	public static function latestImages($limit)
	{
		$latest = array();

		try
		{
			// Create a new query object.
			$db    = Factory::getDBO();
			$query = $db->getQuery(true);

			$query
				->select('*')
				->from($db->quoteName('#__rsg2_images'))
				->order($db->quoteName('id') . ' DESC');

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			foreach ($rows as $row)
			{
				$ImgInfo            = array();
				$ImgInfo['name']    = $row->name;
				$ImgInfo['images'] = ImagesModel::GalleryName($row->gallery_id);
				$ImgInfo['date']    = $row->created;

				//$ImgInfo['user'] = rsgallery2ModelImages::getUsernameFromId($row->userid);
				$user            = Factory::getUser($row->created_by);
				$ImgInfo['user'] = $user->get('username');

				$latest[] = $ImgInfo;
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'latestImages: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $latest;
	}


	/**
	 * This function will retrieve the data of n random uploaded images
	 *
	 * @param int $limit > 0 will limit the number of lines returned
	 *
	 * @return array rows with image name, images name, date, and user name as rows
	 *
	 * @since __BUMP_VERSION__
	 * @throws Exception
	 */
	public static function randomImages($limit)
	{
		$latest = array();

		try
		{
			// Create a new query object.
			$db    = Factory::getDBO();
			$query = $db->getQuery(true);

			$query
				->select('*')
				->from($db->quoteName('#__rsg2_images'))
				->order('RAND()');

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			foreach ($rows as $row)
			{
				$ImgInfo            = array();
				$ImgInfo['name']    = $row->name;
				$ImgInfo['images'] = ImagesModel::GalleryName($row->gallery_id);
				$ImgInfo['date']    = $row->created;

				//$ImgInfo['user'] = rsgallery2ModelImages::getUsernameFromId($row->userid);
				$user            = Factory::getUser($row->created_by);
				$ImgInfo['user'] = $user->get('username');

				$latest[] = $ImgInfo;
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'latestImages: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $latest;
	}


}
