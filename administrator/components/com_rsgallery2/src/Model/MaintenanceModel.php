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


/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
     * @since   5.1.0
 */
class MaintenanceModel extends BaseDatabaseModel
{

    public function CheckImagePaths()
    {
        $isPathsExisting = false;

        try {
            $j4xImagePath = new ImagePathsModel ();  // ToDo: J3x
            $galleryIds   = $this->j4x_galleryIds();

            $isPathsExisting = true;
            $notExisitnPaths = [];
            foreach ($galleryIds as $galleryId) {
                // galleryJ4x path is depending on gallery id
                $j4xImagePath->setPaths_URIs_byGalleryId($galleryId);
                $isGalleryPathsExisting = $j4xImagePath->isPathsExisting();
                if (!$isGalleryPathsExisting) {
                    $notExisitnPaths [] = $j4xImagePath->galleryRoot . '/...';
                }

                $isPathsExisting &= $isGalleryPathsExisting;
            }

            if (count($notExisitnPaths)) {
                $notPathList = implode('<br>', $notExisitnPaths);
                Factory::getApplication()->enqueueMessage('No paths found for <br>' . $notPathList);
            }
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isPathsExisting;
    }

	/**
	 * Recreates all paths for j4x images and tells if at least one was different (repaired))
	 *
	 * @return bool|int
	 *
	 * @throws \Exception
	 * @since  5.1.0
	 */
    public function RepairImagePaths()
    {
        $isPathsRepaired = false;

        try {
            $j4xImagePath = new ImagePathsModel ();  // ToDo: J3x
            $galleryIds   = $this->j4x_galleryIds();

            $isPathsRepaired = true;
            foreach ($galleryIds as $galleryId) {
                // galleryJ4x path is depending on gallery id
                $j4xImagePath->setPaths_URIs_byGalleryId($galleryId);
                $isPathsRepaired &= $j4xImagePath->createAllPaths();
            }
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isPathsRepaired;
    }

	/**
	 * Retrieve gallery ids from database (j4x) style
	 *
	 * @return array|mixed
	 *
	 * @throws \Exception
	 * @since  5.1.0
	 */
    public function j4x_galleryIds()
    {
        $galleryIds = [];

        try {
            // $name = (string) $this->element['name'];
            // $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible galleryIds
            $db = $this->getDatabase();

	        $query = $db->createQuery()
                //->select('id AS value, name AS text, level, published, lft, language')
                ->select('id')
                ->from($db->quoteName('#__rsg2_galleries'))
                ->where($db->quoteName('id') . ' != 1')
                // Filter on the published state
                // ->where('published IN (' . implode(',', ArrayHelper::toInteger($published)) . ')');
                // ToDo: Use option in XML to select ASC/DESC
                ->order('lft ASC');

            // Get the options.
            //$galleryIds = $db->setQuery($query)->loadObjectList();
            $galleryIds = $db->setQuery($query)->loadColumn();
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleryIds;
    }

}

