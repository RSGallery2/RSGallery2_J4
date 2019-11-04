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

/**
 * Gallery Component Association Helper
 *
 * @since  3.0
 */
abstract class GalleryAssociationHelper
{
	public static $gallery_association = true;

	/**
	 * Method to get the associations for a given gallery
	 *
	 * @param   integer  $id         Id of the item
	 * @param   string   $extension  Name of the component
	 *
	 * @return  array    Array of associations for the component galleries
	 *
	 * @since  3.0
	 */
	public static function getGalleryAssociations($id = 0, $extension = 'com_rsgallery2')
	{
		$return = array();

		if ($id)
		{
			$helperClassname = ucfirst(substr($extension, 4)) . 'HelperRoute';

			$associations = GalleriesHelper::getAssociations($id, $extension);

			foreach ($associations as $tag => $item)
			{
				if (class_exists($helperClassname) && is_callable(array($helperClassname, 'getGalleryRoute')))
				{
					$return[$tag] = $helperClassname::getGalleryRoute($item, $tag);
				}
				else
				{
					$return[$tag] = 'index.php?option=' . $extension . '&view=gallery&id=' . $item;
				}
			}
		}

		return $return;
	}
}
