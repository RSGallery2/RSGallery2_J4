<?php
/**
 * @package     ${YYY}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

\defined('_JEXEC') or die;

use JModelLegacy;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\Image;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


class DevelopModel extends BaseDatabaseModel
{

//    public function createGalleries($count) // , $parentGalleryId=0
//    {
//        $isCreated = false;
//
//        try {
//
//            for ($idx = 0; $idx < $count; $idx++) {
//
//                $dateTime = $this->stdDateTime();
//                $imgName = $dateTime . ' (' . $idx . ')';
//                Factory::getApplication()->enqueueMessage($imgName, 'notice');
//
//            }
//
//
//
////
////
////
////
////            $db = Factory::getDbo();
////            $query = $db->getQuery(true)
//////                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
////                ->select('*')
////                ->from('#__rsgallery2_galleries')
////                ->order('ordering ASC');
////
////            // Get the options.
////            $db->setQuery($query);
////
////            $isCreated = $db->loadObjectList();
//
//
//            $isCreated = true;
//
//        }
//        catch (\RuntimeException $e)
//        {
//            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
//        }
//
//
//        return $isCreated;
//    }
//
//
//    public function createImages($count, $galleryId)
//    {
//        $isCreated = false;
//
//        try {
//
//            $allCreated = true;
//
//            for ($idx = 0; $idx < $count; $idx++) {
//
//                $dateTime = $this->stdDateTime();
//                $useFileName = $dateTime . ' (' . $idx . ')';
//                $description = 'dev created';
//
//                Factory::getApplication()->enqueueMessage($useFileName, 'notice');
//
//                // image db handle
//                $modelDb = $this->getModel('Image');
//
//                $j4xImagePath = new ImagePaths (); ? J3x
//                //$modelDb = new Rsgallery2\Component\Rsgallery2\Administrator\Model\Image();
//                //$modelDb = new Image();
//                $modelDb = new image();
//
//                $imageId = $modelDb->createImageDbItem($useFileName, '', $galleryId, $description);
//                if (empty($imageId)) {
//                    // actual give an error
//                    //$msg     .= Text::_('JERROR_ALERTNOAUTHOR');
//                    $msg = 'Create DB item for "' . $useFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
//                    Factory::getApplication()->enqueueMessage($msg, 'error');
//                }
//                else{
//                    $allCreated = false;
//                }
//            }
//
//            $isCreated = $allCreated;
//
//        }
//        catch (\RuntimeException $e)
//        {
//            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
//        }
//
//
//        return $isCreated;
//    }
//
//
//    // ToDo: Move to own helper class
//    function stdDateTime () {
//        $now = '2020_error_stdDateTime';
//
//        try
//        {
//            $datetime = new \DateTime();
////            $now = $datetime->format('Y.m.d_H.i.s.v');
//            $now = $datetime->format('Y.m.d_H.i.s.u');
//
//        }
//        catch (\RuntimeException $e)
//        {
//            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
//        }
//
//        return $now;
//    }





}

