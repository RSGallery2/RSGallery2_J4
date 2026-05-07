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
use Tobscure\JsonApi\Exception\InvalidParameterException;

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
	public function getItems()
	{
		$componentName = 'com_rsgallery2';

		$oConfig = new \stdClass();

		try
		{

			$db = Factory::getContainer()->get(DatabaseInterface::class);

			$query = $db->createQuery()->select($db->quoteName('params'))->from($db->quoteName('#__extensions'))->where($db->quoteName('element') . ' = ' . $db->quote($componentName));

			$db->setQuery($query);

			$jsonStr = $db->loadResult();
			//$jsonStr = $db->loadObject();

			if (!empty($jsonStr))
			{
				$params = json_decode($jsonStr, true);

				foreach ($params as $key => $value)
				{
					$oConfig->$key = $value;
				}
			}

		}
		catch (\Exception $e)
		{
			throw new \RuntimeException($e->getMessage());
		}

		if (empty($params))
		{
			throw new \RuntimeException("Error: The RSG2 configuration may not have been saved yet. Please save the configuration first.");
		}

		return $oConfig;
	}

	/**
	 * Depending on task (displayItems,displayItem) a set or subset
	 * from all config parameters is returned
	 *
	 * @return \stdClass
	 *
	 * @since version
	 */
	public function getItem()
	{
		//--- prepare complete list ------------------------------------------

		$oItemSet = $oConfig = $this->getItems();

		//--- on single parameter ---------------------------------------------

		$task = $this->state->get('task');
		if ($task == 'displayItem')
		{
			//--- given in route -------------------------------

			$param = $this->state->get('param');

			$oItemSet         = new \stdClass();
			$oItemSet->$param = $oConfig->$param;

			//--- given as json parameter -------------------------------

			$data = $this->state->get('data');
			if (!empty($data)) {
				foreach($data as $param => $value){
					$oItemSet->$param = $oConfig->$param;
				}
			}
		}

		return $oItemSet;
	}

	// ToDo: edit delete -> check how it is done in Media

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

			// All items
			$oConfig = $this->getItem();

			$isChanged = false;
			foreach ($data as $param => $value)
			{
				// parameter exists or must be set
				if (isset($oConfig->$param) || $isForce)
				{
					if ($isForce)
					{
						$oConfig->$param = $value;
						$isChanged       = true;
					}
					else
					{
						if ($value != $oConfig->$param)
						{
							$oConfig->$param = $value;
							$isChanged       = true;
						}
					}
				}
				else
				{
					// Send the error response
					$error = Text::sprintf('Parameter "%s" does not exist in component configuration. ', $param);
					throw new InvalidParameterException($error, 403, null, $param);

					// $isSaved = false;
				}
			}

			if ($isChanged)
			{
				$isSaved = $this->saveParams($oConfig);
			}
		}

		return $isSaved;
	}

	/**
	 *
	 * @param   mixed  $data
	 *
	 *
	 * @since version
	 */
	public function delete(mixed $data = [])
	{
		$isDeleted = true;

		// may be used when accepting multiple parameter
		if (!empty ($data))
		{

			// All items
			$oConfig = $this->getItems();

			$isChanged = false;
			foreach ($data as $param => $value)
			{
				// parameter exists or must be set
				if (isset($oConfig->$param))
				{
					unset($oConfig->$param);
					$isChanged = true;
				}
				else
				{
//					// Send the error response
//					$error = Text::sprintf('Delete: Parameter "%s" does not exist in component configuration. ', $param);
//					throw new InvalidParameterException($error, 403, null, $param);

					 $isDeleted = false;
				}
			}

			if ($isChanged)
			{
				$isDeleted = $this->saveParams($oConfig);
			}
		}

		return $isDeleted;
	}

	public static function saveParams($params)
	{
		$componentName = 'com_rsgallery2';

		$paramsString = json_encode($params);

		// Save params in DB
		$db = Factory::getContainer()->get(DatabaseInterface::class);

		$query = $db->createQuery()->update($db->quoteName('#__extensions'))->set($db->quoteName('params') . ' = :params')->where($db->quoteName('element') . ' = :element')->bind(':params', $paramsString)->bind(':element', $componentName);;
		$db->setQuery($query);

		try
		{
			$result = $db->execute();
		}
		catch (\Exception  $exception)
		{
			Factory::getApplication()->enqueueMessage(Text::_($exception->errorMessage()), 'warning');

			return false;
		};

		return true;
	}

}