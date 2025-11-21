<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Filesystem\Path;

/**
 * Galleries helper.
 *
     * @since      5.1.0
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
     * @since   5.1.0     */
//    public static function addSubmenu($extension)
//    {
//        // is this used ?
//    }

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
        $associations     = [];
        $user             = Factory::getApplication()->getIdentity();
        $groups           = implode(',', $user->getAuthorisedViewLevels());

        foreach ($langAssociations as $langAssociation) {
            // Include only published galleries with user access
            $arrId   = explode(':', $langAssociation->id);
            $assocId = $arrId[0];
            $db      = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db->createQuery()
                ->select($db->quoteName('published'))
                ->from($db->quoteName('#__galleries'))
                ->where('access IN (' . $groups . ')')
                ->where($db->quoteName('id') . ' = ' . (int)$assocId);

            $result = (int)$db->setQuery($query)->loadResult();

            if ($result === 1) {
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

        $data              = [];
        $data['id']        = $catid;
        $data['extension'] = $extension;

        if (!$galleryTable->load($data)) {
            $catid = 0;
        }

        return (int)$catid;
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
