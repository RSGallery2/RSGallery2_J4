<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2023-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;


use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\DatabaseInterface;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ConfigRawModel;

/**
 * Model to handle J3x config items for transfer to j4x config
 *
 * @since __BUMP_VERSION__
 */
class CopyConfigJ3xModel extends BaseDatabaseModel
{
	/**
	 * @return array|mixed
	 * @throws \Exception
	 */
	static function j3xConfigItems()
	{
		$oldItems = array();

		try {
			// Create a new query object.
			$db = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true);

			$query
				//->select('*')
				->select($db->quoteName(array('name', 'value')))
				->from($db->quoteName('#__rsgallery2_config'))
				->order($db->quoteName('name') . ' ASC');
			$db->setQuery($query);

			$oldItems = $db->loadAssocList('name', 'value');
		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'j3xConfigItems: Error executing query: "' . $query . '"' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $oldItems;
	}

	/**
	 * Merge J3x configuration into J4x
	 * @param $J3xConfigItems
	 * @param $configVars
	 * @return array
	 * @throws \Exception
	 */
	// Configuration test lists of variables:
	//      a) untouchedRsg2Config, b) untouchedJ3xConfig, c) 1:1 merged, d) assisted merges
	static function MergeJ3xConfigTestLists($j3xConfigItems, $j4xConfigItems)
	{
		// component parameters to array
		$assistedJ3xItems = [];  // j3x to j4x when names different
		$assistedJ4xItems = [];  // j4x presetting on transfer j3x setup
		$mergedItems = [];
		$untouchedJ3xItems = [];
		$untouchedJ4xItems = [];

		try {

			//--- Manual list of assisted merges --------------------------------------------
			// items which need special handling for merge j3x to j4x

			//--- transform J3x to J4x (New names) ------------------------------------------

			// galcountNrs  <=> galDisplayCountJ3x
			$assistedJ3xItems ['galcountNrs'] = array('max_thumbs_in_root_galleries_view_j3x',
				$j3xConfigItems['galcountNrs']);

			// ??? images_column_arrangement_j3x ???
			//$assistedJ3xItems ['display_thumbs_maxPerPage'] = array('images_column_arrangement_j3x',
			//	$j3xConfigItems['display_thumbs_maxPerPage']);

			//
			$assistedJ3xItems ['display_thumbs_colsPerPage'] = array('max_columns_in_images_view_j3x',
				$j3xConfigItems['display_thumbs_colsPerPage']);

			// yyy
			$assistedJ3xItems ['display_thumbs_maxPerPage'] = array('max_thumbs_in_images_view_j3x',
				$j3xConfigItems['display_thumbs_maxPerPage']);

			//--- transform J4x to match J3x setting (preset new variable) --------------------------

			// example $assistedJ4xItems ['images_column_arrangement'] = 1;

			//--- transform 1:1 J3x to J4x ---------------------------------------------------

			foreach ($j3xConfigItems as $name => $value) {
				// Not handled manually
				if (!array_key_exists($name, $assistedJ3xItems)) {
					// 1:1 copy
					if (array_key_exists($name, $j4xConfigItems)) {
						$mergedItems [$name] = $value; // array ($value, $j4xConfigItems[$name]);
					} else {
						$untouchedJ3xItems [$name] = $value;
					}
				}
			}

			//--- Not used J4x items ---------------------------------------------------

			// untouched J4x item ?
			foreach ($j4xConfigItems as $name => $value) {
				// Not handled manually
				if (!array_key_exists($name, $assistedJ4xItems)) {
					if (!array_key_exists($name, $mergedItems)) {
						$untouchedJ4xItems [$name] = $value;
					}
				}
			}

			ksort($assistedJ3xItems);
			ksort($assistedJ4xItems);
			ksort($mergedItems);
			ksort($untouchedJ3xItems);
			ksort($untouchedJ4xItems);

		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'OldConfigItems: Error executing MergeJ3xConfiguration: <br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return array(
			$assistedJ3xItems,
			$assistedJ4xItems,
			$mergedItems,
			$untouchedJ3xItems,
			$untouchedJ4xItems
		);
	}

	public function j3x_galleriesList()
	{
		$galleries = array();

		try {
			$db = $this->getDatabase();
			$query = $db->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
				->select('*')
				->from('#__rsgallery2_galleries')
				->order('ordering ASC');

			// Get the options.
			$db->setQuery($query);

			$galleries = $db->loadObjectList();

		} catch (\RuntimeException $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}


		return $galleries;
	}

	/**
	 * collectAndCopyJ3xConfig2J4xOptions
	 * Collects copy lists of type:
	 *     merged: 1:1 tranferable items
	 *     assisted: J3x old name -> j4x new name
	 * @return bool
	 *
	 * @throws \Exception
	 * @since __BUMP_VERSION__
	 */
	public function collectAndCopyJ3xConfig2J4xOptions()
	{

		$isOk = false;

		try {

			$j3xConfigItems = $this->j3xConfigItems();
			$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
			$j4xConfigItems = $rsgConfig->toArray();

			// Configuration test lists: untouchedRsg2Config, untouchedJ3xConfig, 1:1 merged, assisted merges
			list(
				$assistedJ3xItems,
				$assistedJ4xItems,
				$mergedItems,
				$untouchedJ3xItems,
				$untouchedJ4xItems
				) = $this->MergeJ3xConfigTestLists($j3xConfigItems, $j4xConfigItems);

			if (count($mergedItems)) {
				// ToDo: write later
				// J3x config state: 0:not upgraded, 1:upgraded,  -1:upgraded and deleted
				// Smuggle the J3x config state "upgraded:1" into the list
				//$oldConfigItems ['j3x_config_upgrade'] = "1";

				$isOk = $this->copyJ3xConfigItems2J4xOptions(
					$j4xConfigItems,
					$assistedJ3xItems,
					$assistedJ4xItems,
					$mergedItems);

			} else {
				Factory::getApplication()->enqueueMessage(Text::_('No old configuration items'), 'warning');
			}

		} catch (\RuntimeException $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $isOk;
	}

	/**
	 * copyJ3xConfigItems2J4xOptions
	 * Bundles the merged items and the assisted items to a list and saves it
	 * as the new j4x configuration parameters
	 * @param $j4xConfigItems
	 * @param $assistedJ3xItems
	 * @param $mergedItems
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 * @since __BUMP_VERSION__
	 */
	public function copyJ3xConfigItems2J4xOptions($j4xConfigItems,
	                                              $assistedJ3xItems,
	                                              $assistedJ4xItems,
	                                              $mergedItems)
	{
		$isSaved = false;

		try {

			// copy 1:1 items
			foreach ($mergedItems as $name => $value) {
				$j4xConfigItems [$name] = $value;
			}

			// assisted copying new names
			foreach ($assistedJ3xItems as $j3xName => $var) {
				list($j4xName, $j4xNewValue) = $var;
				$j4xConfigItems [$j4xName] = $j4xNewValue;
			}

			// assisted presetting on transfer j3x setup
			foreach ($assistedJ4xItems as $j4xName => $value) {
				$j4xConfigItems [$j4xName] = $value;
			}

			// Save parameter
			$configModel = new ConfigRawModel ();
			$isSaved = $configModel->saveItems($j4xConfigItems);

		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'MaintenanceJ3xModel: Error in copyJ3xConfigItems2J4xOptions: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}




}
