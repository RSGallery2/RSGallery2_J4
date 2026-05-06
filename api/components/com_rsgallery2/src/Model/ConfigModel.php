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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\Database\DatabaseInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 *
 *
 * @since  4.2.0
 */
class ConfigModel extends BaseModel
{
	public function __construct($config = [])
	{
		parent::__construct($config);
	}

	/**
	 * Method to get all configuration parameters
	 *
	 * @return  \stdClass  A file or folder object.
	 *
	 * @throws  ResourceNotFound
	 * @since   4.1.0
	 */
	public function getItem()
	{
		$componentName = 'com_rsgallery2';

		$oConfig = new \stdClass();

		try
		{

			$db = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db->createQuery()->select($db->quoteName('params'))->from($db->quoteName('#__extensions'))->where($db->quoteName('element') . ' = ' . $db->quote($componentName));

			$db->setQuery($query);

			$jsonStr = $db->loadResult();

			if (!empty($jsonStr))
			{
				$params = json_decode($jsonStr, true);

				//--- just one parameter ---------------------------------------------

				$singlePara = $this->state->get('param');
				if (!empty ($singlePara))
				{
					$value                = $params[$singlePara];
					$params               = [];
					$params [$singlePara] = $value;
				}

				$oConfig = (object) $params;
			}

		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
		}

		if (empty($params))
		{
			if (!empty ($singlePara))
			{
				throw new \RuntimeException("Error: The RSG2 configuration may not have been saved yet. Please save the configuration first.");
			}
		}

		return $oConfig;
	}

	// ToDo: edit delete -> check how it is done in Media

	/**
	 *
	 * @param   mixed  $data
	 *
	 *
	 * @since version
	 */
	public function save(mixed $data = [])
	{
		$isSaved = false;

		// may be used when accepting multiple parameter
		if (!empty ($data))
		{

			// All items
			$oConfig = $this->getItem();

			$isChanged = false;
			foreach ($data as $param => $value)
			{

				if ($value != $oConfig->$param)
				{
					$oConfig->$param = $value;
					$isChanged       = true;
				}
				else
				{

					// ToDo: Tell not found element -> test
					// enqueue
					Factory::getApplication()->enqueueMessage(Text::sprintf('Parameter %s does not exist in component parameter. ', $param), 'warning');
					// Factory::getApplication()->enqueueMessage(Text::sprintf('Parameter %s does not exist in component parameter. ', $param), 'notice');
				}
			}

			if ($isChanged)
			{
				$isSaved = $this->saveParams($oConfig);
			}

		}
		return $isSaved;
	}

	public static function saveParams($params)
	{
		$componentName = 'com_rsgallery2';

		$paramsString = json_encode($params);

		// Save params in DB
		$db = Factory::getContainer()->get(DatabaseInterface::class);

		$query = $db->createQuery()->update($db->quoteName('#__extensions'))->set($db->quoteName('params') . ' = :params')->where($db->quoteName('element') . ' = :element')->bind(':params', $paramsString)->bind(':element', $componentName);;
		$db->setQuery($query);

		$result = $db->execute();

		try
		{
			$db->execute();
		}
		catch (\ExecutionFailureException  $exception)
		{
			Factory::getApplication()->enqueueMessage(Text::_($exception->errorMessage()), 'warning');

			return false;
		};

		return true;
	}

}