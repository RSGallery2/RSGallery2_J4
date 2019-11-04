<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Rsgallery2\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Table\Table;

/**
 * Galleries helper.
 *
 * @since  1.6
 */
class GalleriesHelper
{
	/**
	 * Configure the Submenu links.
	 *
	 * @param   string  $extension  The extension being used for the galleries.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($extension)
	{
		// Avoid nonsense situation.
		if ($extension == 'com_rsgallery2')
		{
			return;
		}

		$parts = explode('.', $extension);
		$component = $parts[0];

		if (count($parts) > 1)
		{
			$section = $parts[1];
		}

		// Try to find the component helper.
		$eName = str_replace('com_', '', $component);
		$file = Path::clean(JPATH_ADMINISTRATOR . '/components/' . $component . '/helpers/' . $eName . '.php');

		if (file_exists($file))
		{
			$prefix = ucfirst(str_replace('com_', '', $component));
			$cName = $prefix . 'Helper';

			\JLoader::register($cName, $file);

			if (class_exists($cName))
			{
				if (is_callable(array($cName, 'addSubmenu')))
				{
					$lang = Factory::getLanguage();

					// Loading language file from the administrator/language directory then
					// loading language file from the administrator/components/*extension*/language directory
					$lang->load($component, JPATH_BASE, null, false, true)
					|| $lang->load($component, Path::clean(JPATH_ADMINISTRATOR . '/components/' . $component), null, false, true);

					call_user_func(array($cName, 'addSubmenu'), 'galleries' . (isset($section) ? '.' . $section : ''));
				}
			}
		}
	}

	/**
	 * Gets a list of associations for a given item.
	 *
	 * @param   integer  $pk         Content item key.
	 * @param   string   $extension  Optional extension name.
	 *
	 * @return  array of associations.
	 */
	public static function getAssociations($pk, $extension = 'com_rsgallery2')
	{
		$langAssociations = Associations::getAssociations($extension, '#__galleries', 'com_rsgallery2.item', $pk, 'id', 'alias', '');
		$associations     = array();
		$user             = Factory::getUser();
		$groups           = implode(',', $user->getAuthorisedViewLevels());

		foreach ($langAssociations as $langAssociation)
		{
			// Include only published galleries with user access
			$arrId    = explode(':', $langAssociation->id);
			$assocId  = $arrId[0];
			$db       = \JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->quoteName('published'))
				->from($db->quoteName('#__galleries'))
				->where('access IN (' . $groups . ')')
				->where($db->quoteName('id') . ' = ' . (int) $assocId);

			$result = (int) $db->setQuery($query)->loadResult();

			if ($result === 1)
			{
				$associations[$langAssociation->language] = $langAssociation->id;
			}
		}

		return $associations;
	}

	/**
	 * Check if Gallery ID exists otherwise assign to ROOT gallery.
	 *
	 * @param   mixed   $catid      Name or ID of gallery.
	 * @param   string  $extension  Extension that triggers this function
	 *
	 * @return  integer  $catid  Gallery ID.
	 */
	public static function validateGalleryId($catid, $extension)
	{
		$galleryTable = Table::getInstance('GalleryTable', '\\Joomla\\Component\\Galleries\\Administrator\\Table\\');

		$data = array();
		$data['id'] = $catid;
		$data['extension'] = $extension;

		if (!$galleryTable->load($data))
		{
			$catid = 0;
		}

		return (int) $catid;
	}

	/**
	 * Create new Gallery from within item view.
	 *
	 * @param   array  $data  Array of data for new gallery.
	 *
	 * @return  integer
	 */
	public static function createGallery($data)
	{
		$galleryModel = Factory::getApplication()->bootComponent('com_rsgallery2')
			->getMVCFactory()->createModel('Gallery', 'Administrator', ['ignore_request' => true]);
		$galleryModel->save($data);

		$catid = $galleryModel->getState('gallery.id');

		return $catid;
	}
}
