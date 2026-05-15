<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Content api helper.
 *
 * @since  4.0.0
 */
class ManifestHelper
{

	/**
	 * Method to get the manifest from the Databas as json
	 *
	 * @return  null|string json representation
	 *
	 * @throws  ResourceNotFound
	 * @since   4.1.0
	 */
	public static function getDbManifestJson($extension = 'com_rsgallery2'): null|string
	{
		$manifest = null;

		try
		{
			$db = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db->createQuery()
				->select($db->quoteName('manifest_cache'))
				->from($db->quoteName('#__extensions'))
				->where($db->quoteName('element') . ' = ' . $db->quote($extension));
			$db->setQuery($query);

			$manifest = $db->loadResult();
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
		}

		return $manifest;
	}

	/**
	 * Method to get the manifest from the Databas as assoc array
	 *
	 * @return  null|array
	 *
	 * @throws  ResourceNotFound
	 * @since   4.1.0
	 */
	public static function getDbManifest($extension = 'com_rsgallery2'): null|array
	{
		$manifest = null;

		try
		{
			$manifestJson = self::getDbManifestJson($extension);

			if (!empty($manifestJson))
			{
				$manifest = json_decode($manifestJson, true);
			}
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
			// throw new \Exception("Could not perform copy operation.", 0, $e);
		}

		return $manifest;
	}

	/**
	 * Method to save the manifest from an assoc array
	 *
	 * @return
	 *
	 * @throws  ResourceNotFound
	 * @since   4.1.0
	 */
	public static function saveDbManifestJson(string $manifestJson, $extension = 'com_rsgallery2'): null|string
	{
		$isSaved = false;

		try
		{
			$db = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db->createQuery()
				->update($db->quoteName('#__extensions'))
				->set($db->quoteName('manifest_cache') . ' = ' . $db->quote($manifestJson)  )
				->where($db->quoteName('element') . ' = ' . $db->quote($extension));
			$db->setQuery($query)->execute();

			$isSaved = true;
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
		}

		return $isSaved;
	}

	/**
	 * Method to save the manifest from a assoc object
	 *
	 * @param $oManifest
	 * @param $extension
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public static function saveDbManifest($oManifest, $extension = 'com_rsgallery2')
	{
		$isSaved = false;

		try
		{
			$manifestJson = json_encode($oManifest); // flags

			if (!empty($oManifest))
			{
				$isSaved = self::saveDbManifestJson($manifestJson, $extension);
			}
			else
			{
				throw new \Exception ("Can not json_encode given manifest data ");
			}
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
			// throw new \Exception("Could not perform copy operation.", 0, $e);
		}

		return $isSaved;
	}


// Just for inspection
//	/**
//	 * Save RSG2 configuration to db
//	 * @param   Registry  $params
//	 *
//	 * @return bool
//	 *
//	 * @since  5.1.0
//	 */
//	public function saveParams(Registry $params)
//	{
//		$db = self::getDatabase();
//
//		return $db->setQuery(
//			'UPDATE #__extensions'
//			. ' SET params = ' . $db->quote((string) $params)
//			. ' WHERE element = ' . $db->quote('com_rsgallery2')
//		)->execute();
//	}
//
//$params = json_encode($params);
//
//$query = $db->createQuery()
//->update($db->quoteName('#__extensions'))
//->set($db->quoteName('params') . ' = ' . $db->quote($params))
//->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
//->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
//->where($db->quoteName('element') . ' = ' . $db->quote('stats'));
//
//try {
//$db->setQuery($query)->execute();
//} catch (Exception $e) {
//	self::collectError(__METHOD__, $e);
//
//	return;
//}
//    }

}
