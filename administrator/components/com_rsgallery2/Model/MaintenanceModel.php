<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Rsgallery2\Administrator\Model;

defined('_JEXEC') or die;

use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Component\Rsgallery2\Administrator\Model\ImagePaths;

class MaintenanceModel extends BaseDatabaseModel
{

    public function CheckImagePaths ()
    {
        $isPathsExisting = false;

        try {

            $j4xImagePath = new ImagePaths ();
            $galleryIds = $this->j4x_galleryIds();

            $isPathsExisting = true;
            $notExisitnPaths = [];
            foreach ($galleryIds as $galleryId) {

                // galleryJ4x path is depending on gallery id
                $j4xImagePath->setPathsURIs_byGalleryId($galleryId);
                $isGalleryPathsExisting = $j4xImagePath->isPathsExisting ();
                if ( ! $isGalleryPathsExisting) {
                    $notExisitnPaths [] = $j4xImagePath->galleryRoot . '/...';
                }

                $isPathsExisting &= $isGalleryPathsExisting;
            }

            if (count($notExisitnPaths)) {
                $notPathList = implode ($notExisitnPaths, '<br>');
                Factory::getApplication()->enqueueMessage('No paths found for <br>' . $notPathList);
            }

        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isPathsExisting;
    }

    public function RepairImagePaths ()
    {
        $isPathsRepaired = false;

        try {

            $j4xImagePath = new ImagePaths ();
            $galleryIds = $this->j4x_galleryIds();

            $isPathsRepaired = true;
            foreach ($galleryIds as $galleryId) {

                // galleryJ4x path is depending on gallery id
                $j4xImagePath->setPathsURIs_byGalleryId($galleryId);
                $isPathsRepaired &= $j4xImagePath->createAllPaths ();
            }

        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isPathsRepaired;
    }

    public function j4x_galleryIds()
    {
        $galleryIds = [];

        try {
        // $name = (string) $this->element['name'];
        // $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible galleryIds
        $db   = Factory::getDbo();

        $query = $db->getQuery(true)
            //->select('id AS value, name AS text, level, published, lft, language')
            ->select('id')
            ->from($db->quoteName('#__rsg2_galleries'))
            ->where($db->quoteName('id') . ' != 1' )
            // Filter on the published state
            // ->where('published IN (' . implode(',', ArrayHelper::toInteger($published)) . ')');
            // ToDo: Use option in XML to select ASC/DESC
            ->order('lft ASC');

            // Get the options.
            //$galleryIds = $db->setQuery($query)->loadObjectList();
            $galleryIds = $db->setQuery($query)->loadColumn();

        }
        catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleryIds;
    }



}

