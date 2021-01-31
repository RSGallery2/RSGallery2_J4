<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Rsgallery2 Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 * @since       __DEPLOY_VERSION__
 */
abstract class RouteHelper
{
	/**
	 * Get the URL route for a rsgallery2 from a foo ID, rsgallery2 category ID and language
	 *
     * @param   integer  $id        The route of the content item.
     * @param   integer  $catid     The category ID.
     * @param   integer  $language  The language code.
     * @param   string   $layout    The layout value.
	 *
	 * @return  string  The link to the rsgallery2
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getRsgallery2Route($id, $catid, $language = 0, $layout = null)
	{
		// Create the link
		$link = 'index.php?option=com_rsgallery2&view=rsgallery2&id=' . $id;

		if ($catid > 1)
		{
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

        if ($layout)
        {
            $link .= '&layout=' . $layout;
        }

        return $link;
	}

	/**
	 * Get the URL route for a foo from a foo ID, rsgallery2 category ID and language
	 *
	 * @param   integer  $id        The id of the rsgallery2
	 * @param   integer  $catid     The id of the rsgallery2's category
	 * @param   mixed    $language  The id of the language being used.
     * @param   string   $layout    The layout value.
	 *
	 * @return  string  The link to the rsgallery2
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getYYYYRoute($id, $catid, $language = 0, $layout = null)
	{
	    // for further routes (category ?) see com_content article routehelper

		// Create the link
		$link = 'index.php?option=com_rsgallery2&view=foo&id=' . $id;

		if ($catid > 1)
		{
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

        if ($layout)
        {
            $link .= '&layout=' . $layout;
        }

		return $link;
	}

	/**
	 * Get the URL route for a rsgallery2 category from a rsgallery2 category ID and language
	 *
	 * @param   mixed  $catid     The id of the rsgallery2's category either an integer id or an instance of CategoryNode
	 * @param   mixed  $language  The id of the language being used.
	 *
	 * @return  string  The link to the rsgallery2
	 *
	 * @since   __DEPLOY_VERSION__
	 */
    /**
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof CategoryNode)
		{
			$id = $catid->id;
		}
		else
		{
			$id = (int) $catid;
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			// Create the link
			$link = 'index.php?option=com_rsgallery2&view=category&id=' . $id;

			if ($language && $language !== '*' && Multilanguage::isEnabled())
			{
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}
    /**/


    /**
     * Get the form route.
     *
     * @param   integer  $id  The form ID.
     *
     * @return  string  The article route.
     *
     * @since   1.5
     */
    /**
    public static function getFormRoute($id)
    {
        // toDo for edit ...
        return 'index.php?option=com_content&task=article.edit&a_id=' . (int) $id;
    }
    /**/

}
