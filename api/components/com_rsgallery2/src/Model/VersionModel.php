<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2019-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\DatabaseInterface;
//use Rsgallery2\Component\Rsgallery2\Api\Helper\ManifestHelper;
use Rsgallery2\Component\Rsgallery2\Administrator\Helper\ManifestHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since 5.0.10
 */
class VersionModel extends BaseDatabaseModel
{
	protected string $componentName = 'com_rsgallery2';

	/**
	 * @param $config
	 *
	 * @throws \Exception
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);

//        $this->versionApiModel = new ApiModel();
	}

	/**
	 * Method to get a single result
	 *
	 * @return  \stdClass  A file or folder object.
	 *
	 * @throws  ResourceNotFound
	 * @since   4.1.0
	 */
	public function getItem()
	{
		// Dummy default
		$oVersion               = new \stdClass();
		$oVersion->version      = "xx.xx.xx";
		$oVersion->creationDate = "2025.xx.xx";

		try
		{
			$oManifest = ManifestHelper::getDbManifest($this->componentName);

			if (!empty($oManifest))
			{
				$oVersion->version      = $oManifest['version'];
				$oVersion->creationDate = $oManifest['creationDate'];

			}
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
		}

		return $oVersion;
	}

	/**
	 *
	 * @param   mixed  $data
	 *
	 *
	 * @since version
	 */
	public function save(mixed $data = [], $isForce = false)
	{
		$isSaved = true;

		// may be used when accepting multiple parameter
		if (!empty ($data))
		{
			$isChanged = false;
			$isSaved = false;

			try
			{
				$oManifest = ManifestHelper::getDbManifest($this->componentName);

				if (!empty($oManifest))
				{
					//--- version ------------------------------------

					if (!empty ($data['version']))
					{
						$version = $data['version'];
						if ($oManifest['version'] != $version)
						{
							$oManifest['version'] = $data['version'];
							$isChanged = true;
						}
					}

					//--- creation date ------------------------------------

					if (!empty ($data['creationDate']))
					{
						$creationDate = $data['creationDate'];
						if ($oManifest['creationDate'] != $creationDate)
						{
							$oManifest['creationDate'] = $creationDate;
							$isChanged = true;
						}
					}

					//--- save changes ----------------------------------------

					if ($isChanged) {
						$isSaved = ManifestHelper::saveDbManifest($oManifest, $this->componentName);
					}
				}

			}
			catch (\Exception $e)
			{
				throw new \RuntimeException($e->getMessage());
			}
		}

		return $isSaved;
	}

}
