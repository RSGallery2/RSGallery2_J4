<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2014-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\DatabaseInterface;

/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
     * @since   5.1.0
 */
class UploadModel extends BaseDatabaseModel
{

    /**
     * Check if at least one gallery exists
     * Regards the nested structure (ID=1 is only root of tree and no gallery)
     *
     * @return true on galleries found
     *
     * @since  5.1.0     */
    public static function is1GalleryExisting()
    {
        $is1GalleryExisting = false;

        try {
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->createQuery();

            // count gallery items
            $query->select('COUNT(*)')
                // ignore root item  where id is "1"
                ->where($db->quoteName('id') . ' != 1')
                ->from('#__rsg2_galleries');

            $db->setQuery($query, 0, 1);
            $IdGallery = $db->loadResult();

            // > 0 galleries exist
            $is1GalleryExisting = !empty ($IdGallery);
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error count for galleries in "__rsg2_galleries" table' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $is1GalleryExisting;
    }

    /**
     * Query for ID of latest gallery
     *
     * @return string ID of latest gallery
     *
     * @since 4.3.0
     */
    public static function IdLatestGallery()
    {
        $IdLatestGallery = 0;

        try {
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->createQuery();

            $test = $db->quoteName('created') . ', ' . $db->quoteName('id') . ' DESC' . "";

            $query->select($db->quoteName('id'))
                ->from('#__rsg2_galleries')
                ->where($db->quoteName('id') . ' != 1')
                ->setLimit(1)
//			->order($db->quoteName('created') . ' DESC');
//			->order( $db->quoteName('id') . ' DESC')
                ->order($db->quoteName('created') . ' DESC' . ', ' . $db->quoteName('id') . ' DESC');

            $db->setQuery($query, 0, 1);
            $IdLatestGallery = $db->loadResult();
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'IdLatestGallery: Error executing query: "' . $query . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $IdLatestGallery;
    }

}

