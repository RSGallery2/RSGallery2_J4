<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (C) 2021-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * RSGallery is Free Software
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\Database\DatabaseInterface;
use Joomla\Utilities\ArrayHelper;
use RuntimeException;
use stdClass;

use function defined;

/**
 * Class MaintenanceJ3xModel
 *
 * @package Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
 * Handles old J3x RSG23 data structures. Especially for transferring the config data
 *
 *
 */

// removed for install: BaseDatabaseModel
//class MaintenanceJ3xModel extends BaseModel

/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Model
 *
 * @since       version
 */
class MaintenanceJ3xModel extends CopyConfigJ3xModel
{

//	// ToDo: May not copy dbimages (too many ???)
//    public function applyExistingJ3xData()
//    {
//        $isOk = true;
//
//        //--- DB configuration ---------------------------------------------
//
//        try {
//
//            $isOkConfig = $this->collectAndCopyJ3xConfig2J4xOptions();
//            $isOk &= $isOkConfig;
//
//            if (!$isOkConfig) {
//                Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x configuration failed'), 'error');
//            }
//        } catch (\RuntimeException $e) {
//            Factory::getApplication()->enqueueMessage($e->getMessage() . ' Copy J3x DB config', 'error');
//        }
//
//        //--- DB galleries ---------------------------------------------
//
//        try {
//            $isOkGalleries = $this->copyDbAllJ3xGalleries2J4x();
//            $isOk &= $isOkGalleries;
//
//            if (!$isOkGalleries) {
//                Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x galleries failed'), 'error');
//            }
//        } catch (\RuntimeException $e) {
//            Factory::getApplication()->enqueueMessage($e->getMessage() . '  Copy J3x DB galleries', 'error');
//        }
//
//        //--- DB images ---------------------------------------------
//
//        try {
//
//            $isOkImages = $this->copyDbAllJ3xImages2J4x();
//            $isOk &= $isOkImages;
//
//            if (!$isOkImages) {
//                Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x images failed'), 'error');
//            }
//
//        } catch (\RuntimeException $e) {
//            Factory::getApplication()->enqueueMessage($e->getMessage() . '  Copy j3x DB images', 'error');
//        }
//
//        // ....
//        // ? ToDo: ACL, assets ...
//
//
//        // Left out: copy of images as would be exceeding allkowed execution times
//
//        return $isOk;
//    }

    /**
     * !!! ToDo: Count j3x child images query wrong
     * List of J3x galleries that are also present in J4x supplemented by image count
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_galleries4DbImagesList()
    {
        $galleries = [];

        try {
            $db    = $this->getDatabase();
            $query = $db
                ->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->select($db->quoteName(['j3x.id', 'j3x.alias', 'j3x.name', 'j3x.description']))
                ->from($db->quoteName('#__rsgallery2_galleries', 'j3x'))
                ->order('id ASC')
                // !!! ToDo: Count j3x child images query wrong
                ->select('COUNT(img.gallery_id) as j3x_img_count')
                ->join(
                    'LEFT',
                    $db->quoteName('#__rsgallery2_files', 'img')
                    . ' ON ('
                    . $db->quoteName('img.gallery_id') . ' = ' . $db->quoteName('j3x.id')
                    . ')',
                )
                ->group('j3x.id')
                // !!! ToDo: Count j4x child images query wrong
                ->select('COUNT(j4x.gallery_id) as j4x_img_count')
                ->join(
                    'LEFT',
                    $db->quoteName('#__rsg2_images', 'j4x')
                    . ' ON ( '
//		            . '(' . $db->quoteName('j3x.id') . '+1) = ' . $db->quoteName('j4x.gallery_id')
                    . $db->quoteName('j4x.gallery_id') . ' = ( ' . $db->quoteName('j3x.id') . ' +1 )'
                    . ')',
                )//	             ->group('j4x.gallery_id')
            ;

            // Get the options.
            $db->setQuery($query);

            $galleries = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleries;
    }

    /**
     * @param $aGallery
     * @param $bGallery
     *
     * @return int
     *
     * @since version
     */
    private static function cmpJ4xGalleries($aGallery, $bGallery)
    {
        $a = $aGallery->ordering;
        $b = $bGallery->ordering;

        if ($a == $b) {
            return 0;
        }

        return ($a < $b) ? -1 : 1;
    }

    /**
     * @param $j3xItems
     * @param $j4xItems
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function MergedJ3xIdsDbImages($j3xItems, $j4xItems)
    {
        $mergedId = [];

        try {
            foreach ($j3xItems as $j3xItem) {
                // fetch from db
                foreach ($j4xItems as $j4xItem) {
//                    if ($j3xItem->title == $j4xitem->title)
                    if ($j3xItem->title == $j4xItem->title) {
                        $mergedId [] = $j3xItem->id;
                        break;
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $mergedId;
    }

    /**
     * @param $j3xItems
     * @param $j4xItems
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function MergedJ3xImageIds($j3xItems, $j4xItems)
    {
        $mergedId = [];

        try {
            // All J3x db items
            foreach ($j3xItems as $j3xItem) {
                // against all j4x db itemsAll
                foreach ($j4xItems as $j4xItem) {
                    // Same name / title
                    //if ($j3xItem->title == $j4xItem->title)
                    if ($j3xItem->name == $j4xItem->name) {
                        // Still old place
                        if (!$j4xItem->use_j3x_location) {
                            $mergedId [] = $j3xItem->id;
                            break;
                        }
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $mergedId;
    }

    /**
     * @param $j3xItems
     * @param $j4xItems
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function MergedJ3xIdsDbGalleries($j3xItems, $j4xItems)
    {
        $mergedId = [];

        try {
            foreach ($j3xItems as $j3xitem) {
                // fetch from db
                foreach ($j4xItems as $j4xItem) {
//                    if ($j3xitem->title == $j4xitem->title)
                    if ($j3xitem->name == $j4xItem->name) {
                        $mergedId [] = $j3xitem->id;
                        break;
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $mergedId;
    }

    /**
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function j3x_galleriesListSorted()
    {
        $galleries = [];

        try {
            // fetch from db
            $dbGalleries = $this->j3x_galleriesList();

            // sort recursively
            $galleries = $this->j3x_galleriesSortedByParent($dbGalleries, 0, 0);
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleries;
    }

    /**
     * @param $dbGalleries
     * @param $parentId
     * @param $level
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function j3x_galleriesSortedByParent($dbGalleries, $parentId = 0, $level = 0)
    {
        $sortedGalleries = [];

        try {
            // parent galleries
            $galleries = [];

            // galleries of given level
            foreach ($dbGalleries as $gallery) {
                if ($gallery->parent == $parentId) {
                    $gallery->level = $level;

                    // collect gallery
                    $galleries [] = $gallery;

                    // add childs recursively
                    $id                 = $gallery->id;
                    $subGalleries [$id] = $this->j3x_galleriesSortedByParent($dbGalleries, $id, $level + 1);
                }
            }

            // Sort galleries of level
            if (count($galleries) > 1) {
                usort($galleries, [$this, 'cmpJ4xGalleries']);
            }

            // Collect sorted list with childs
            foreach ($galleries as $gallery) {
                $sortedGalleries[] = $gallery;

                // Add childs
                $id = $gallery->id;
                //echo 'array_push: $id' . $id . '<br>';

                foreach ($subGalleries [$id] as $subGallery) {
                    $sortedGalleries[] = $subGallery;
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $sortedGalleries;
    }

    /**
     * @param $dbGalleries
     * @param $parentId
     * @param $level
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function j4x_galleriesSortedByParent($dbGalleries, $parentId = 0, $level = 0)
    {
        $sortedGalleries = [];

        try {
            // parent galleries
            $galleries = [];

            // collects galleries of given level
            foreach ($dbGalleries as $gallery) {
                if ($gallery['parent'] == $parentId) {
                    $gallery['level'] = $level;

                    // collect gallery
                    $galleries [] = $gallery;

                    // add childs recursively
                    $id                 = $gallery['id'];
                    $subGalleries [$id] = $this->j4x_galleriesSortedByParent($dbGalleries, $id, $level + 1);
                }
            }

            // Sort galleries of level (Needs additional ordering from j3x gallery data
            if (count($galleries) > 1) {
                usort($galleries, [$this, 'cmpJ4xGalleries']);
            }

            // Collect sorted list with childs
            foreach ($galleries as $gallery) {
                $sortedGalleries[] = $gallery;

                // Add childs
                $id = $gallery['id'];
                //echo 'array_push: $id' . $id . '<br>';

                foreach ($subGalleries [$id] as $subGallery) {
                    $sortedGalleries[] = $subGallery;
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $sortedGalleries;
    }


    // Expected: List is already is sorted

    /**
     * @param $sortedGalleries
     * @param $parentId
     * @param $level
     * @param $lastNodeIdx
     *
     * @return int|mixed
     *
     * @throws Exception
     * @since version
     */
    public function setNestingNodes2J4xGalleries(&$sortedGalleries, $parentId = 0, $level = 0, $lastNodeIdx = 1)
    {
        // $changedGalleries = [];

        try {
            // parent galleries
            $galleries = [];

            // collect galleries of given level
            foreach ($sortedGalleries as $idx => $gallery) {
                if ($gallery['parent_id'] == $parentId) {
                    $sortedGalleries [$idx]['level'] = $level;
                    $sortedGalleries [$idx]['lft']   = $lastNodeIdx;

                    $lastNodeIdx++;

                    // add node ids recursively
                    $lastNodeIdx = $this->setNestingNodes2J4xGalleries(
                        $sortedGalleries,
                        $gallery ['id'],
                        $level + 1,
                        $lastNodeIdx,
                    );

                    $sortedGalleries [$idx]['rgt'] = $lastNodeIdx;
                    $lastNodeIdx++;
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $lastNodeIdx;
    }

    /**
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j4x_galleriesList()
    {
        $galleries = [];

        try {
            $db    = $this->getDatabase();
            $query = $db
                ->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent_id', 'level'))) // 'path'
                ->select('*')
                ->from('#__rsg2_galleries')
                ->order($db->quoteName('lft') . ' ASC');

            // Get the options.
            $db->setQuery($query);

            $galleries = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleries;
    }

    /**
     * @param $j4x_galleries
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function j4_GalleriesToJ3Form($j4x_galleries)
    {
        $j3x_galleries = [];

        try {
            foreach ($j4x_galleries as $j4x_gallery) {
                // leave out root gallery in nested form
                { // if ($j4x_gallery->id != 1) {
                    $j3x_gallery = new stdClass();

                    $j3x_gallery->id   = $j4x_gallery->id;
                    $j3x_gallery->name = $j4x_gallery->name;

//                    // parent 1 is going to root
//                    if($j4x_gallery->parent_id == 1) {
//                        $j4x_gallery->parent_id = 0;
//                    }

                    $j3x_gallery->parent = $j4x_gallery->parent_id;
                    // $j3x_gallery->ordering = $j4x_gallery->level;
                    $j3x_gallery->ordering = $j4x_gallery->lft;

                    $j3x_galleries[] = $j3x_gallery;
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $j3x_galleries;
    }

    /**
     * @param $galleries
     *
     * @return string
     *
     * @throws Exception
     * @since version
     */
    public function GalleriesListAsHTML($galleries)
    {
        $html = '';

        try {
            if (!empty ($galleries)) {
                // all root galleries and nested ones
                $html = $this->GalleriesOfLevelHTML($galleries, 0, 0);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $html;
    }

    /**
     * @param $galleries
     * @param $parentId
     * @param $level
     *
     * @return string
     *
     * @throws Exception
     * @since version
     */
    private function GalleriesOfLevelHTML($galleries, $parentId, $level)
    {
        $html = [];

        try {
            $galleryHTML = [];

            foreach ($galleries as $gallery) {
                if ($gallery->parent == $parentId) {
                    // html of this gallery
                    $galleryHTML [] = $this->GalleryHTML($gallery, $level);

                    $subHtml = $this->GalleriesOfLevelHTML($galleries, $gallery->id, $level + 1);
                    if (!empty ($subHtml)) {
                        $galleryHTML [] = $subHtml;
                    }
                }
            }

            // surround with <ul>
            if (!empty ($galleryHTML)) {
                $lineStart = str_repeat(" ", 3 * ($parentId));

                array_unshift($galleryHTML, $lineStart . '<ul class="list-group">');
                $galleryHTML [] = $lineStart . '</ul>';

                $html = $galleryHTML;
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return implode($html);
    }

    // ToDo use styling for nested from https://stackoverflow.com/questions/29063244/consistent-styling-for-nested-lists-with-bootstrap

    /**
     * @param $gallery
     * @param $level
     *
     * @return string
     *
     * @throws Exception
     * @since version
     */
    private function GalleryHTML($gallery, $level)
    {
        $html = [];

        $lineStart = str_repeat(" ", 3 * ($level + 1));
        $identHtml = '';
        if ($level > 0) {
            $identHtml = '<span class="text-muted">';
            $identHtml .= str_repeat('⋮&nbsp;&nbsp;&nbsp;', $level - 1);
            $identHtml .= '</span>';
            $identHtml .= '-&nbsp;';
        }

        $id     = $gallery->id;
        $parent = $gallery->parent;
        $order  = $gallery->ordering;
        $name   = $gallery->name;

        try {
            $html = <<<EOT
                $lineStart<li class="list-group-item">
                $lineStart   $identHtml<span> id: </span><span>$id</span>
                $lineStart   <span> parent: </span><span>$parent</span>
                $lineStart   <span> order: </span><span>$order</span>
                $lineStart   <span> name:</span><span>$name</span>
                $lineStart</li>
                EOT;
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $html;
    }

    /**
     * @param $J3xGalleryItemsSorted
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function convertJ3xGalleriesToJ4x($J3xGalleryItemsSorted)
    {
        $J4Galleries = [];

        try {
            // galleries of given level
            foreach ($J3xGalleryItemsSorted as $j3xGallery) {
                $J4Galleries[] = $this->convertJ3xGallery($j3xGallery);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $J4Galleries;
    }

    /**
     * @param $j3x_gallery
     *
     * @return array
     *
     * @since version
     */
    private function convertJ3xGallery($j3x_gallery)
    {
        $j4x_GalleryItem = [];

        // `id` int(11) NOT NULL auto_increment,
        $j4x_GalleryItem['id'] = $j3x_gallery->id;
        $test                  = $j3x_gallery->id;
        // `parent` int(11) NOT NULL default 0,
        $j4x_GalleryItem['parent_id'] = $j3x_gallery->parent;
        // `name` varchar(255) NOT NULL default '',
        $j4x_GalleryItem['name'] = $j3x_gallery->name;
        // `alias` varchar(255) NOT NULL DEFAULT '',
        $j4x_GalleryItem['alias'] = $j3x_gallery->alias;
        // `description` text NOT NULL,
        $j4x_GalleryItem['description'] = $j3x_gallery->description;
        // `published` tinyint(1) NOT NULL default '0',
        $j4x_GalleryItem['published'] = $j3x_gallery->published;
        // `checked_out` int(11) unsigned NOT NULL default '0',
        $j4x_GalleryItem['checked_out'] = $j3x_gallery->checked_out;
        // `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
        $j4x_GalleryItem['checked_out_time'] = $j3x_gallery->checked_out_time;
        // `ordering` int(11) NOT NULL default '0',
        $j4x_GalleryItem['ordering'] = $j3x_gallery->ordering; // Needed for sorting (Not for database)
        // `date` datetime NOT NULL default '0000-00-00 00:00:00',
        $j4x_GalleryItem['created'] = $j3x_gallery->date;
        // `hits` int(11) NOT NULL default '0',
        $j4x_GalleryItem['hits'] = $j3x_gallery->hits;
        // `params` text NOT NULL,
        $j4x_GalleryItem['params'] = $j3x_gallery->params;
        // `user` tinyint(4) NOT NULL default '0',
        $j4x_GalleryItem['created_by_alias'] = $j3x_gallery->user;
        // `uid` int(11) unsigned NOT NULL default '0',
        $j4x_GalleryItem['created_by'] = $j3x_gallery->uid;
        // `allowed` varchar(100) NOT NULL default '0',
        $j4x_GalleryItem['approved'] = $j3x_gallery->allowed;
        // `thumb_id` int(11) unsigned NOT NULL default '0',
        $j4x_GalleryItem['thumb_id'] = $j3x_gallery->thumb_id;
        // `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
        $j4x_GalleryItem['asset_id'] = $j3x_gallery->asset_id;
        // `access` int(10) unsigned DEFAULT NULL,
        $j4x_GalleryItem['access'] = $j3x_gallery->access;

        // ToDo: sizes from j3x config
        $j4x_GalleryItem['sizes'] = '';

        return $j4x_GalleryItem;
    }

//>>>yyyy===================================================================================================

    /**
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function copyDbAllJ3xGalleries2J4x()
    {
        $isOk = false;

        try {
            $j3xGalleriesItems = $this->j3x_galleriesList();
            $isOk              = $this->copyDbJ3xGalleries2J4x($j3xGalleriesItems);
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_DbGalleryList_imagesTransferred_YN()
    {
        $j3xGalleriesItems = [];

        try {
            //$j3xGalleriesItems = $this->j3x_galleriesListSorted();
            $j3xGalleriesItems = $this->j3x_galleries4DbImagesList();

            foreach ($j3xGalleriesItems as $j3xGalleriesItem) {
                $this->assignDbImagesTransferredFlag($j3xGalleriesItem);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $j3xGalleriesItems;
    }

    /**
     * Assigns flag isTransferred = true when number of images match in J3x and J4x gallery
     * ? regarding state use_j3x_location ???
     *
     * @param $j3xGalleriesItem item of J3x galleries that are also present in J4x supplemented by image count
     *
     * @return false
     *
     * @throws Exception
     * @since version
     */
    private function assignDbImagesTransferredFlag($j3xGalleriesItem)
    {
        $isOk = false;

        try {
            //--- J3x image count of gallery ---------------------

            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                // ->select($db->quoteName(array('id')))
                ->select('COUNT(*)')
                ->from('#__rsgallery2_files')
                ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($j3xGalleriesItem->id));

            $db->setQuery($query, 0, 1);
            $countJ3x = $db->loadResult();

            //--- J4x gallery id ---------------------

            $gallery_id_j4x = $this->convertDbJ3xGallery2J4xId($j3xGalleriesItem);

            //--- j4x image count of gallery ---------------------

            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                // ->select($db->quoteName(array('id')))
                ->select('COUNT(*)')
                ->from('#__rsg2_images')
                ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($gallery_id_j4x));

            $db->setQuery($query, 0, 1);
            $countJ4x = $db->loadResult();

//			//--- j4x image count of gallery ---------------------
//
//			// where image is not use_j3x_location
//			$db = $this->getDatabase();
//
//			$query = $db->getQuery(true)
//				// ->select($db->quoteName(array('id')))
//				->select('COUNT(*)')
//				->from('#__rsg2_images')
//				->where($db->quoteName('gallery_id') . ' = ' . $db->quote($gallery_id_j4x))
//				->where($db->quoteName('use_j3x_location') . ' = 1');
//
//			$db->setQuery($query, 0, 1);
//			$countJ4x = $db->loadResult();

            // J3x image count matches j4x image count
//			if ($countJ3x > 0 && $countJ3x == $countJ4x)
            if ($countJ3x == $countJ4x) {
                $j3xGalleriesItem->isTransferred = true;
            } else {
                $j3xGalleriesItem->isTransferred = false;
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     *
     * Is used for DB copy and image file copy
     *
     * @param $j3xGalleriesItems
     *
     * @return bool
     *
     * @throws Exception
     * @since versio
     * n
     */
    public function isImagesDoTransfer($j3xGalleriesItems)
    {
        $isDbImagesTransfer = false;

        try {
            // find first transfer active
            foreach ($j3xGalleriesItems as $j3xGalleriesItem) {
                if (!$j3xGalleriesItem->isTransferred) {
                    $isDbImagesTransfer = true;
                    break;
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isDbImagesTransfer;
    }

    /**
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_GalleryList_imagesTransferred_YN()
    {
        $j3xGalleriesItems = [];

        try {
            //$j3xGalleriesItems = $this->j3x_galleriesListSorted();
            $j3xGalleriesItems = $this->j3x_galleries4DbImagesList();

            foreach ($j3xGalleriesItems as $j3xGalleriesItem) {
                $this->assignImagesTransferredFlag($j3xGalleriesItem);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $j3xGalleriesItems;
    }

    /**
     * Assigns flag isTransferred = true when number of images match in J3x and J4x gallery
     *
     * @param $j3xGalleriesItem item of J3x galleries that are also present in J4x supplemented by image count
     *
     * @return false
     *
     * @throws Exception
     * @since version
     */
    private function assignImagesTransferredFlag($j3xGalleriesItem)
    {
        $isOk = false;

        try {
            //--- J3x image count of gallery ---------------------

            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                // ->select($db->quoteName(array('id')))
                ->select('COUNT(*)')
                ->from('#__rsgallery2_files')
                ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($j3xGalleriesItem->id));

            $db->setQuery($query, 0, 1);
            $countJ3x = $db->loadResult();

            //--- J4x gallery id ---------------------

            $gallery_id_j4x = $this->convertDbJ3xGallery2J4xId($j3xGalleriesItem);

            //--- j4x image count of gallery ---------------------

            // where image is not use_j3x_location
            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                // ->select($db->quoteName(array('id')))
                ->select('COUNT(*)')
                ->from('#__rsg2_images')
                ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($gallery_id_j4x))
                ->where($db->quoteName('use_j3x_location') . ' != 1');

            $db->setQuery($query, 0, 1);
            $countJ4x = $db->loadResult();

            // J3x image count matches j4x image count
//			if ($countJ3x > 0 && $countJ3x == $countJ4x)
            if ($countJ3x == $countJ4x) {
                $j3xGalleriesItem->isTransferred = true;
            } else {
                $j3xGalleriesItem->isTransferred = false;
            }

            // keep count information
            $j3xGalleriesItem->countJ3x = $countJ3x;
            $j3xGalleriesItem->countJ4x = $countJ4x;
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $selectedIds
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function copyDbSelectedJ3xGalleries2J4x($selectedIds)
    {
        $isOk = false;

        try {
            $j3xGalleriesItems = $this->j3x_galleriesListOfIds($selectedIds);

            $isOk = $this->copyDbJ3xGalleries2J4x($j3xGalleriesItems);
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $j3xGalleriesItems
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function copyDbJ3xGalleries2J4x($j3xGalleriesItems)
    {
        $isOk = false;

        try {
            // items exist ?
            if (count($j3xGalleriesItems)) {
                $j4xGalleriesItems = $this->convertJ3xGalleriesToJ4x($j3xGalleriesItems);

                // sort by parent and id recursively
                $j4xGalleryItemsSorted = $this->j4x_galleriesSortedByParent($j4xGalleriesItems, 0, 0);

                // set nested tree ()
                // fills $sortedGalleries
                $lastNodeIdx = $this->setNestingNodes2J4xGalleries($j4xGalleryItemsSorted, 0, 0, 1);

                // re-init nested gallery table
                $galleryTreeModel = new GalleryTreeModel ();
                $galleryTreeModel->reinitNestedGalleryTable($lastNodeIdx);

                $isOk = $this->writeGalleryList2Db($j4xGalleryItemsSorted);
                // ToDo: rebuild nested ....

            } else {
                Factory::getApplication()->enqueueMessage(Text::_('No items to insert into db'), 'warning');
                //Factory::getApplication()->enqueueMessage('No items to insert into db', 'warning');
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $j4xGalleriesItems
     *
     * @return int|true
     *
     * @throws Exception
     * @since version
     */
    public function writeGalleryList2Db($j4xGalleriesItems)
    {
        $isOk = true;

        try {
            // all gallery objects
            foreach ($j4xGalleriesItems as $j4xImageItem) {
                $isOk &= $this->writeGalleryItem2Db($j4xImageItem);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $j4x_GalleryItem
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function writeGalleryItem2Db($j4x_GalleryItem)
    {
        $isOk = false;

        try {
            // https://stackoverflow.com/questions/22373852/how-to-use-prepared-statements-in-joomla
            $columns = [];
            $values  = [];

            $db    = $this->getDatabase();
            $query = $db->getQuery(true);

            $columns[] = 'id';
            $values[]  = $db->quote(1 + (int)$j4x_GalleryItem['id']);

            $columns[] = 'name';
            $values[]  = $db->quote($j4x_GalleryItem['name']);
            $columns[] = 'alias';
            $values[]  = $db->quote($j4x_GalleryItem['alias']);
            $columns[] = 'description';
            $values[]  = $db->quote($j4x_GalleryItem['description']);

            $columns[] = 'note';
//            $values[] = $db->quote($j4ImageItem['note'];
            $values[]  = $db->quote('');
            $columns[] = 'params';
            $values[]  = $db->quote($j4x_GalleryItem['params']);
            $columns[] = 'published';
            $values[]  = $db->quote($j4x_GalleryItem['published']);

//            $columns[] = 'publish_up';
//            $values[] = $db->quote($j4ImageItem['publish_up']);
//            $columns[] = 'publish_down';
//            $values[] = $db->quote($j4ImageItem['publish_down']);

            $columns[] = 'hits';
            $values[]  = $db->quote($j4x_GalleryItem['hits']);

            $columns[] = 'checked_out';
            $values[]  = $db->quote($j4x_GalleryItem['checked_out']);
            $columns[] = 'checked_out_time';
            $values[]  = $db->quote($j4x_GalleryItem['checked_out_time']);
            $columns[] = 'created';
//            $test01 = $j4ImageItem['created'];
//            $test02 = $j4ImageItem['created']->toSql();
//            $values[] = $db->quote($j4ImageItem['created']->toSql();
            $values[]  = $db->quote($j4x_GalleryItem['created']);
            $columns[] = 'created_by';
            $values[]  = $db->quote($j4x_GalleryItem['created_by']);
            $columns[] = 'created_by_alias';
            $values[]  = $db->quote($j4x_GalleryItem['created_by_alias']);
            $columns[] = 'modified';
            $values[]  = $db->quote($j4x_GalleryItem['created']);
//            $columns[] = 'modified_by';
//            $values[] = $db->quote($j4x_GalleryItem['modified_by']);

            $columns[] = 'parent_id';
            $values[]  = $db->quote(1 + (int)$j4x_GalleryItem['parent_id']);

            $columns[] = 'level';
            $values[]  = $db->quote(1 + (int)$j4x_GalleryItem['level']);
//            $columns[] = 'path';
//            $values[] = $db->quote($j4x_GalleryItem['path']);
            $columns[] = 'lft';
            $values[]  = $db->quote($j4x_GalleryItem['lft']);
            $columns[] = 'rgt';
            $values[]  = $db->quote($j4x_GalleryItem['rgt']);

//            $columns[] = 'approved';
//            $values[] = $db->quote($j4x_GalleryItem['approved']);

            $columns[] = 'asset_id';
            $values[]  = $db->quote($j4x_GalleryItem['asset_id']);
//            $columns[] = 'access';
//            $values[] = $db->quote($j4ImageItem['access']);

            $columns[] = 'sizes';
            $values[]  = $db->quote($j4x_GalleryItem['sizes']);

//	        $testNames = implode(',', $columns);
//	        $testValues = implode(',', $values);
//

            // https://stackoverflow.com/questions/42385248/joomla-insert-multiple-rows-using-single-query
            // $db->quote(

            // Prepare the insert query.
            $query
                ->insert($db->quoteName('#__rsg2_galleries')) //make sure you keep #__
                ->columns($db->quoteName($columns))
                // ToDo: ? explode ?
                //->values(array(implode(',', $db->quote($values))));
                ->values(implode(',', $values));
//
//			$debug = $query->__toString();
//	        Factory::getApplication()->enqueueMessage('Sql query: ' . $debug, 'notice');
//
            $db->setQuery($query);
            $isOk = $db->execute();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_imagesList()
    {
        $images = [];

        try {
            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->select('*')
                ->from('#__rsgallery2_files')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $images = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $images;
    }

    /**
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_imagesInfoList()
    {
        $images = [];

        try {
            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                ->select($db->quoteName(['id', 'name', 'alias', 'gallery_id', 'title']))
                ->from('#__rsgallery2_files')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $images = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $images;
    }

    /**
     * @param $selectedIds
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_imagesListOfIds($selectedIds)
    {
        $images = [];

        try {
            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->select('*')
                // https://joomla.stackexchange.com/questions/22631/how-to-use-in-clause-in-joomla-query
                //->where($db->quoteName('status') . ' IN (' . implode(',', ArrayHelper::toInteger($array)) . ')')
                ->where($db->quoteName('id') . ' IN (' . implode(',', ArrayHelper::toInteger($selectedIds)) . ')')
                ->from('#__rsgallery2_files')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $images = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $images;
    }

    /**
     * @param $selectedIds
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_galleriesListOfIds($selectedIds)
    {
        $galleries = [];

        try {
            $db    = $this->getDatabase();
            $query = $db
                ->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->select('*')
                // https://joomla.stackexchange.com/questions/22631/how-to-use-in-clause-in-joomla-query
                //->where($db->quoteName('status') . ' IN (' . implode(',', ArrayHelper::toInteger($array)) . ')')
                ->where($db->quoteName('id') . ' IN (' . implode(',', ArrayHelper::toInteger($selectedIds)) . ')')
                ->from('#__rsgallery2_galleries')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $galleries = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleries;
    }

    /**
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j4x_imagesList()
    {
        $images = [];

        try {
            $db    = $this->getDatabase();
            $query = $db
                ->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent_id', 'level'))) // 'path'
                ->select('*')
                ->from('#__rsg2_images')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $images = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $images;
    }


    // ToDo: May be useful with gallery id

    /**
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_imagesMergeList()
    {
        $images = [];

        $select = [
            'id',
            'name',
//            'alias',
//            'descr',
            'gallery_id',
            'title',
//            'hits',
//            'date',
//            'rating',
//            'votes',
//            'comments',
//            'published',
//            'checked_out',
//            'checked_out_time',
            'ordering',
//            'approved',
//            'userid',
//            'params',
//            'asset_id'
        ];

        try {
            $db    = $this->getDatabase();
            $query = $db
                ->getQuery(true)
                ->select($db->quoteName($select))
                ->from('#__rsgallery2_files')
//                ->order('gallery_id ASC, ordering ASC');
                ->order(
                    $db->quoteName('gallery_id') . ' ASC, '
                    . $db->quoteName('ordering') . ' ASC',
                );

            // Get the options.
            $db->setQuery($query);

            $images = $db->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $images;
    }

//    // ToDo: May be useful with gallery id
//    public function j4x_imagesMergeList()
//    {
//        $images = array();
//
//        $select = [
//            'id',
//            'name',
////            'alias',
////            'description',
//
//            'gallery_id',
//            'title',
//
//            'note',
////            'params',
////            'published',
////
////            'hits',
////            'rating',
////            'votes',
////            'comments',
////
////            'publish_up',
////            'publish_down',
////
////            'checked_out',
////            'checked_out_time',
////            'created',
////            'created_by',
////            'created_by_alias',
////            'modified',
////            'modified_by',
////
//            'ordering',
////
////            'approved',
////            'asset_id',
////            'access',
//
//            'use_j3x_location'
//
//        ];
//
//        try {
//            $db = $this->>getDatabase();
//            $query = $db->getQuery(true)
//                ->select($db->quoteName($select))
//                ->from('#__rsg2_images')
//                ->order($db->quoteName('gallery_id') . ' ASC, '
//                    . $db->quoteName('ordering') . ' ASC');
//
//            // Get the options.
//            $db->setQuery($query);
//
//            $images = $db->loadObjectList();
//        }
//        catch (\RuntimeException $e)
//        {
//            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
//        }
//
//        return $images;
//    }

    /**
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function copyDbAllJ3xImages2J4x()
    {
        $isOk = false;

        try {
            $isOk = $this->resetImagesTable();

            $j3xImageItems = $this->j3x_imagesList();

            $isOk &= $this->copyDbJ3xImages2J4x($j3xImageItems);
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    // by galleries

    /**
     * @param $selectedJ3xGalleryIds
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function copyDbImagesOfSelectedGalleries($selectedJ3xGalleryIds)
    {
        $isOk = false;

        try {
//            $j3xGalleryItems = $this->j3x_galleriesListOfIds($selectedJ3xGalleryIds);

            $db    = $this->getDatabase();
            $query = $db
                ->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->select('*')
                ->from('#__rsgallery2_files')
                ->where(
                    $db->quoteName('gallery_id') . ' IN (' . implode(
                        ',',
                        ArrayHelper::toInteger($selectedJ3xGalleryIds),
                    ) . ')',
                )
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $imageIds = $db->loadObjectList();

            if (isset($imageIds)) {
                $isOk = $this->copyDbJ3xImages2J4x($imageIds);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * revertCopyDbJ3xImages2J4xUser (trunctate table)
     *
     * @param $selectedIds
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function revertCopyDbJ3xImages2J4xUser()
    {
        $isOk = false;

        try {
            $db    = $this->getDatabase();
            $query = $db->getQuery(true);

            $query->delete($db->quoteName('#__rsg2_images'));

            $db->setQuery($query);
            $isOk = $db->execute();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $selectedIds
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function copyDbSelectedJ3xImages2J4x($selectedIds)
    {
        $isOk = false;

        try {
            $j3xImageItems = $this->j3x_imagesListOfIds($selectedIds);

            $isOk = $this->copyDbJ3xImages2J4x($j3xImageItems);
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $j3xImageItems
     *
     * @return bool|int
     *
     * @throws Exception
     * @since version
     */
    public function copyDbJ3xImages2J4x($j3xImageItems)
    {
        $isOk = false;

        try {
            // items exist ?
            if (count($j3xImageItems)) {
                $j4ImageItems = $this->convertDbJ3xImagesToJ4x($j3xImageItems);

                $isOk = $this->writeImageList2Db($j4ImageItems);
            } else {
                Factory::getApplication()->enqueueMessage(Text::_('No items to insert into db'), 'warning');
                //Factory::getApplication()->enqueueMessage('No items to insert into db', 'warning');
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $selectedIds
     *
     * @return false
     *
     * @throws Exception
     * @since version
     */
    public function convertSelectedJ3xImages2J4x($selectedIds)
    {
        $isOk = false;

        try {
            // ...

        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $J3xImagesItems
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function convertDbJ3xImagesToJ4x($J3xImagesItems)
    {
        $j4ImageItems = [];

        try {
            // galleries of given level
            foreach ($J3xImagesItems as $j3xImage) {
                $j4ImageItems[] = $this->convertDbJ3xImage($j3xImage);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $j4ImageItems;
    }

    /**
     * @param $j4ImageItems
     *
     * @return int|true
     *
     * @throws Exception
     * @since version
     */
    public function writeImageList2Db($j4ImageItems)
    {
        $isOk = true;

        try {
            // all image objects
            foreach ($j4ImageItems as $j4xImageItem) {
                $isOk &= $this->writeImageItem2Db($j4xImageItem);
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $j4ImageItem
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function writeImageItem2Db($j4ImageItem)
    {
        $isOk = false;

        try {
            // https://stackoverflow.com/questions/22373852/how-to-use-prepared-statements-in-joomla
            $columns = [];
            $values  = [];

            $db    = $this->getDatabase();
            $query = $db->getQuery(true);

            $columns[] = 'id';
            $values[]  = $db->quote($j4ImageItem['id']);
            $columns[] = 'name';
            $values[]  = $db->quote($j4ImageItem['name']);
            $columns[] = 'alias';
            $values[]  = $db->quote($j4ImageItem['alias']);
            $columns[] = 'description';
            $values[]  = $db->quote($j4ImageItem['description']);

            $columns[] = 'gallery_id';
            // $values[] = $db->quote(1 + (int)$j4ImageItem['gallery_id']);
            // x_gallery_id = $this->convertDbJ3xGalleryId($j3x_image->gallery_id));
            $values[]  = $db->quote($j4ImageItem['gallery_id']);
            $columns[] = 'title';
            $values[]  = $db->quote($j4ImageItem['title']);

//            $columns[] = 'note';
//            $values[] = $db->quote($j4ImageItem['note']);
            $columns[] = 'params';
            $values[]  = $db->quote($j4ImageItem['params']);
            $columns[] = 'published';
            $values[]  = $db->quote($j4ImageItem['published']);

//            $columns[] = 'publish_up';
//            $values[] = $db->quote($j4ImageItem['publish_up']);
//            $columns[] = 'publish_down';
//            $values[] = $db->quote($j4ImageItem['publish_down']);

            $columns[] = 'hits';
            $values[]  = $db->quote($j4ImageItem['hits']);
            $columns[] = 'rating';
            $values[]  = $db->quote($j4ImageItem['rating']);
            $columns[] = 'votes';
            $values[]  = $db->quote($j4ImageItem['votes']);
            $columns[] = 'comments';
            $values[]  = $db->quote($j4ImageItem['comments']);

            $columns[] = 'checked_out';
            $values[]  = $db->quote($j4ImageItem['checked_out']);
            $columns[] = 'checked_out_time';
            $values[]  = $db->quote($j4ImageItem['checked_out_time']);
            $columns[] = 'created';
//            $test01 = $j4ImageItem['created'];
//            $test02 = $j4ImageItem['created']->toSql();
//            $values[] = $db->quote($j4ImageItem['created']->toSql();
            $values[]  = $db->quote($j4ImageItem['created']);
            $columns[] = 'created_by';
            $values[]  = $db->quote($j4ImageItem['created_by']);
            $columns[] = 'created_by_alias';
            $values[]  = $db->quote($j4ImageItem['created_by_alias']);
            $columns[] = 'modified';
            $values[]  = $db->quote($j4ImageItem['modified']);
            $columns[] = 'modified_by';
            $values[]  = $db->quote($j4ImageItem['modified_by']);

            $columns[] = 'ordering';
            $values[]  = $db->quote($j4ImageItem['ordering']);
            $columns[] = 'approved';
            $values[]  = $db->quote($j4ImageItem['approved']);

            $columns[] = 'asset_id';
            $values[]  = $db->quote($j4ImageItem['asset_id']);
//            $columns[] = 'access';
//            $values[] = $db->quote($j4ImageItem['access'];
            $columns[] = 'use_j3x_location';
            $values[]  = $db->quote($j4ImageItem['use_j3x_location']);

            $columns[] = 'sizes';
            $values[]  = $db->quote($j4ImageItem['sizes']);

            // Prepare the insert query.
            $query
                ->insert($db->quoteName('#__rsg2_images')) //make sure you keep #__
                ->columns($db->quoteName($columns))
                // ToDo: ? explode ?
                //->values(array(implode(',', $db->quote($values))));
                ->values(implode(',', $values));

            $db->setQuery($query);
            $isOk = $db->execute();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isOk;
    }

    /**
     * @param $j3x_image
     *
     * @return array
     *
     * @since version
     */
    private function convertDbJ3xImage($j3x_image)
    {
        $j4_imageItem = []; //new \stdClass();

        //`id` serial NOT NULL,
        $j4_imageItem['id'] = $j3x_image->id;
        //`name` varchar(255) NOT NULL default '',
        $j4_imageItem['name'] = $j3x_image->name;
        //`alias` varchar(255) NOT NULL DEFAULT '',
        $j4_imageItem['alias'] = $j3x_image->alias;
        //`description` text NOT NULL,
        $j4_imageItem['description'] = $j3x_image->desc == null ? '' : $j3x_image->desc;

        //`gallery_id` int(9) unsigned NOT NULL default '0',
//	    $j4x_gallery_id = $this->convertDbJ3xGalleryId2J4xId($j3x_image->gallery_id);
//	    $j4x_gallery_id = $this->convertDbJ3xGallery2J4xId($j3x_image);
        $j4x_gallery_id             = $j3x_image->gallery_id + 1; // definition on gallery J3x to J4x
        $j4_imageItem['gallery_id'] = $j4x_gallery_id;
        //`title` varchar(255) NOT NULL default '',
        $j4_imageItem['title'] = $j3x_image->title;

        //`note` varchar(255) NOT NULL DEFAULT '',
        $j4_imageItem['note'] = ''; // $j3x_image->note;
        //`params` text NOT NULL,
        $j4_imageItem['params'] = $j3x_image->params;
        //`published` tinyint(1) NOT NULL default '1',
        $j4_imageItem['published'] = $j3x_image->published;
//        //`publish_up` datetime,
//        $j4_imageItem['publish_up'] = $j3x_image->publish_up;
//        $pub = new DateTime($item->publish_up);
//        $item->publish_down = $pub->add(new DateInterval('P30D'))->format('Y-m-d H:i:s');
        //`publish_down` datetime,
//        $j4_imageItem['publish_down'] = $j3x_image->publish_down;

        //`hits` int(11) unsigned NOT NULL default '0',
        $j4_imageItem['hits'] = $j3x_image->hits;
        //`rating` int(10) unsigned NOT NULL default '0',
        $j4_imageItem['rating'] = $j3x_image->rating;
        //`votes` int(10) unsigned NOT NULL default '0',
        $j4_imageItem['votes'] = $j3x_image->votes;
        //`comments` int(10) unsigned NOT NULL default '0',
        $j4_imageItem['comments'] = $j3x_image->comments;

        //`checked_out` int(10) unsigned NOT NULL DEFAULT 0,
        $j4_imageItem['checked_out'] = $j3x_image->checked_out;
        //`checked_out_time` datetime,
        $j4_imageItem['checked_out_time'] = $j3x_image->checked_out_time;
        //`created` datetime NOT NULL,
        //$j4_imageItem['created'] = Factory::getDate($j3x_image->date);
        $j4_imageItem['created'] = $j3x_image->date;
        //`created_by` int(10) unsigned NOT NULL DEFAULT 0,
        $j4_imageItem['created_by'] = $j3x_image->userid;
        //`created_by_alias` varchar(255) NOT NULL DEFAULT '',
        //$j4_imageItem['created_by_alias'] = $j3x_image->created_by_alias;
        //`modified` datetime NOT NULL,
        $j4_imageItem['modified'] = $j3x_image->date;
        //`modified_by` int(10) unsigned NOT NULL DEFAULT 0,
        $j4_imageItem['modified_by'] = $j3x_image->userid;

        //`ordering` int(9) unsigned NOT NULL default '0',
        $j4_imageItem['ordering'] = $j3x_image->ordering;
        //`approved` tinyint(1) unsigned NOT NULL default '1',
        $j4_imageItem['approved'] = $j3x_image->approved;

        //`asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
        $j4_imageItem['asset_id'] = $j3x_image->asset_id;
        //`access` int(10) NOT NULL DEFAULT 0,
        $j4_imageItem['access'] = $j3x_image->access;

        // Mark as to be found in old directory
        $j4_imageItem['use_j3x_location'] = 1;

        // ToDo: sizes from j3x config
        $j4_imageItem['sizes'] = '';

        return $j4_imageItem;
    }


//	/**
//	 * j4x gallery id by same gallery names in J3 and J4
//	 * retrieve gallery itme first
//	 *
//	 * @return bool
//	 *
//	 * @since __BUMP_VERSION__
//	 */
//	private function convertDbJ3xGalleryId2J4xId($j3xGalleriesItem) //$j3x_image->gallery_id
//	{
//		$gallery_id_j4x = -1;
//
//		try
//		{
////			//--- J3x gallery item ---------------------
////
////			$db = $this->geDatabse());
////
////			$query = $db->getQuery(true)
////				// ->select($db->quoteName(array('id')))
////                ->select($db->quoteName(array('id', 'name'))) // 'path'
////				->from('#__rsgallery2_galleries')
////				->where($db->quoteName('id') . ' = ' . $db->quote($j3xGalleriesItem->gallery_id));
////
////			$db->setQuery($query, 0, 1);
////
////			$j3xGalleriesItem = $db->loadAssoc(); // loadRow
//
//			if ( ! empty($j3xGalleriesItem))
//			{
//				//--- J4x gallery id ---------------------
//
//				$gallery_id_j4x = $this->convertDbJ3xGallery2J4xId($j3xGalleriesItem);
//
//				if (str_contains($j3xGalleriesItem->name, 'erste'))
//				{
//
//					$test = $j3xGalleriesItem->id;
//				}
//
//				if ($j3xGalleriesItem->id == 9)
//				{
//
//					$test = $gallery_id_j4x;
//					$test = $gallery_id_j4x;
//				}
//			}
//
//		} //catch (\RuntimeException $e)
//		catch (\Exception $e) {
//			throw new \RuntimeException($e->getMessage() . ' from resetImagesTable');
//		}
//
//		return $gallery_id_j4x;
//	}

    /**
     * gallery id by same gallery names in J3 and J4
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    private function convertDbJ3xGallery2J4xId($j3xGalleriesItem) //$j3x_image->gallery_id
    {
        $gallery_id_j4x = -1;

        try {
            //--- J4x gallery id ---------------------

            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                // ->select($db->quoteName(array('id')))
                ->select('id')
                ->from('#__rsg2_galleries')
                ->where($db->quoteName('name') . ' = ' . $db->quote($j3xGalleriesItem->name));

            $db->setQuery($query, 0, 1);
            $gallery_id_j4x = $db->loadResult();

            if ($j3xGalleriesItem->id == 9) {
                $test = $gallery_id_j4x;
                $test = $gallery_id_j4x;
            }
        } //catch (\RuntimeException $e)
        catch (Exception $e) {
            throw new RuntimeException($e->getMessage() . ' from resetImagesTable');
        }

        return $gallery_id_j4x;
    }

    /**
     * Reset image table to empty state (No images in RSG J4x
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public static function resetImagesTable()
    {
        $isImagesReset = false;

        $imgTableName = '#__rsg2_images';

        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            //--- delete old rows -----------------------------------------------

            $query = $db->getQuery(true);

            $query->delete($db->quoteName($imgTableName));
            // all rows
            //$query->where($conditions);

            $db->setQuery($query);

            $isImagesReset = $db->execute();
        } //catch (\RuntimeException $e)
        catch (Exception $e) {
            throw new RuntimeException($e->getMessage() . ' from resetImagesTable');
        }

        return $isImagesReset;
    }

    /**
     * @param $cids
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function imageNamesById($cids)
    {
        $imageNamesById = [];

        $dbImages = [];

        try {
            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                ->select($db->quoteName(['name', 'id', 'gallery_id']))
                ->where($db->quoteName('id') . ' IN (' . implode(',', ArrayHelper::toInteger($cids)) . ')')
                ->from('#__rsgallery2_files')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $dbImages = $db->loadObjectList();

            if (!empty ($dbImages)) {
                foreach ($dbImages as $dbImage) {
                    $imageNamesById[$dbImage->id] =
                        [
                            'id'         => $dbImage->id,
                            'name'       => $dbImage->name,
                            // J4x gallery id is one higher as j3x
                            'gallery_id' => 1 + $dbImage->gallery_id,
                        ];
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $imageNamesById;
    }

    /**
     * @param $j3xImageIds
     *
     * @return bool
     *
     * @since version
     */
    public function updateMovedJ3xImages2J4x($j3xImageIds)
    {
        $isDbUpdated = false;

        try {
            //--- image names -----------------------------------------------

            // name, id, gallery_id
            $imgObjectsById = $this->imageNamesById($j3xImageIds);

            //--- check for j4x images existing images -----------------------------------------------

            [$idsExisting, $idsNotExisting] = $this->check4ExistingDisplayImage($imgObjectsById);

            //--- update db -------------------------------------------------

            $isDbWritten = true;
            if (count($idsExisting) > 0) {
                $isDbWritten &= $this->dbMarkImagesAsTransferred($idsExisting, false);
            }

            if (count($idsNotExisting) > 0) {
                $isDbWritten &= $this->dbMarkImagesAsTransferred($idsNotExisting, true);
            }

            // either list must be existing
            if ((count($idsExisting) > 0 || count($idsNotExisting) > 0) && $isDbWritten) {
                $isDbUpdated = true;
            }
        } //catch (\RuntimeException $e)
        catch (Exception $e) {
            throw new RuntimeException($e->getMessage() . ' from resetImagesTable');
        }

        return $isDbUpdated;
    }

    /**
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function CheckImagePaths()
    {
        $isPathsExisting = false;

        try {
            $j3xImagePath    = new ImagePathsJ3xModel ();
            $isPathsExisting = $j3xImagePath->isPathsExisting();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isPathsExisting;
    }

    /**
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function RepairImagePaths()
    {
        $isPathsRepaired = false;

        try {
            $j3xImagePath    = new ImagePathsJ3xModel ();
            $isPathsRepaired = $j3xImagePath->createAllPaths();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isPathsRepaired;
    }


    /*===================================================================================================
    J3x image physical move
    ===================================================================================================*/

    /**
     * @param $j3x_galleries
     *
     * @return array
     *
     * @since version
     */
    public function j3x_transformGalleryIdsTo_j4x($j3x_galleries)
    {
        $j4xGalleryIds = [];

        // otherwise convertDbJ3xGalleryId2J4xId
        foreach ($j3x_galleries as $j3x_gallery) {
            $j4xGalleryIds[] = $j3x_gallery->id + 1;
        }

        return $j4xGalleryIds;
    }

    //

    /**
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function j3xGalleriesWithImgCount()
    {
        $j3xGalleriesWithImgCount = [];

        try {
//		    $db = $this->getDatabase();
//		    //$db      = $this->getDatabase();
//
//		    $j3x_subquery = $db->getQuery(true)
//			    ->select("count(*)")
//			    ->from("#__rsgallery2_files")
//			    ->where("gallery_id = j3x.id");
//
//		    $query = $db->getQuery(true);
//		    $query->select($db->quoteName('j3x.gallery_id'), '(' . $j3x_subquery . ')  AS count'  )
//			    ->from($db->quoteName('#__rsgallery2_files', 'j3x'))
//			    ->where ('j3x.gallery_id in ' . $galleryIdsJ3x_NotMoved);
//

//		    $db = $this->>getDatabase();
//		    //$db      = $this->getDatabase();
//
//		    $j3x_subquery = $db->getQuery(true)
//			    ->select("count(*)")
//			    ->from("#__rsgallery2_files")
//			    ->where("gallery_id = j3x.gallery_id");
//
//		    $query = $db->getQuery(true);
//
//
//		    $query->select('DISTINCT  ' . $db->quoteName('j3x.gallery_id') . ' AS gallery_id', ', count (' . $j3x_subquery . ')  AS count'  )
//			    ->from($db->quoteName('#__rsgallery2_files', 'j3x'))
//			    ;

            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                ->select($db->quoteName('j3x.id', 'gallery_id'))
                ->from($db->quoteName('#__rsgallery2_galleries', 'j3x'))
                /* Count child images */
                ->select('COUNT(img.gallery_id) as img_count')
                ->join(
                    'LEFT',
                    $db->quoteName('#__rsgallery2_files', 'img')
                    . ' ON ('
                    . $db->quoteName('img.gallery_id') . ' = ' . $db->quoteName('j3x.id')
                    . ')',
                )
                ->group('j3x.id');

            $db->setQuery($query);

            // ToDo: load assoc direct ?
            $j3xGalleries = $db->loadObjectList();

            foreach ($j3xGalleries as $j3xGallery) {
                $gallery_id = $j3xGallery->gallery_id;

                $j3xGalleriesWithImgCount[$gallery_id]               = [];
                $j3xGalleriesWithImgCount[$gallery_id] ['img_count'] = $j3xGallery->img_count;
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $j3xGalleriesWithImgCount;
    }





//
//	    .....
//
//
//	    $query = $db->getQuery(true);
//	    // count gallery items
//	    $query->select('COUNT(*)')
//		    ->from('#__rsg2_images')
//		    ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($j4x_galleryId))
//		    ->where($db->quoteName('use_j3x_location') . ' = 1')
//	    ;
//
//	    $db->setQuery($query, 0, 1);
//	    $imgToBeMoved = $db->loadResult();
//
//	    $query = $db->getQuery(true);
//	    // count gallery items
//	    $query->select('COUNT(*)')
//		    ->from('#__rsg2_images')
//		    ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($j4x_galleryId))
//	    ;
//
//	    $db->setQuery($query, 0, 1);
//	    $imgAvailable = $db->loadResult();
//
//
//
//
//
//
//
//
//
////	    foreach ($j3x_galleryIds as $j4x_galleryId) {
////
////            $db = $this->getDatabase();
///
////            $query = $db->getQuery(true);
////            // count gallery items
////            $query->select('COUNT(*)')
////                ->from('#__rsg2_images')
////                ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($j4x_galleryId))
////                ->where($db->quoteName('use_j3x_location') . ' = 1')
////            ;
////
////            $db->setQuery($query, 0, 1);
////            $imgToBeMoved = $db->loadResult();
////
////            $query = $db->getQuery(true);
////            // count gallery items
////            $query->select('COUNT(*)')
////                ->from('#__rsg2_images')
////                ->where($db->quoteName('gallery_id') . ' = ' . $db->quote($j4x_galleryId))
////            ;
////
////            $db->setQuery($query, 0, 1);
////            $imgAvailable = $db->loadResult();
////
////            // $data = {}; // ...
////            $data ['toBeMoved'] = $imgToBeMoved;
////            $data ['count'] = $imgAvailable;
////            $j3xGalleryData [$j4x_galleryId] = $data;
////        }
//
//        return $j3xGalleryData;
//    }

    // J3x galleries ids where images are not moved
//	/**
//	 * @param $j4xGalleryIds
//	 *
//	 * @return array|mixed
//	 *
//	 * @throws \Exception
//	 * @since version
//	 */
//	public function galleryIdsJ3x_dbImagesNotMoved($j4xGalleryIds)
//	{
//		$galleryIdsJ3x_NotMoved = []; // ToDo: array() ==> []
//
//		try
//		{
//
//			// one query to rule them all :-(
//
//			$db = $this->getDatabase();
//
//			$j3x_subquery = $db->getQuery(true)
//				->select("count(*)")
//				->from("#__rsgallery2_files")
//				->where("gallery_id = j3x.id");
//
//			$j4x_subquery = $db->getQuery(true)
//				->select("count(*)")
//				->from("#__rsg2_images")
//				->where("gallery_id = j4x.id")
//				->where($db->quoteName('use_j3x_location') . ' = 1');
//
//
//			$query = $db->getQuery(true)
//				->select($db->quoteName('j3x.id'))
//				->from($db->quoteName('#__rsgallery2_galleries', 'j3x'))
//				->join('LEFT', '#__rsg2_galleries AS j4x ON j3x.id = (j4x.id-1)')
//				//->where ('(' . $j3x_subquery . ') = (' . $j4x_subquery . ')');
//				->where('(' . $j3x_subquery . ') != (' . $j4x_subquery . ')');
//
//			// J3x ds where images are not used
//
//			$db->setQuery($query);
//
//			$galleryIdsJ3x_NotMoved = $db->loadObjectList();
//
////            $db = $this->getDatabase();
////            $fieldlist = $db->qn(array('gallery_id')); // add the field names to an array
////            $fieldlist[0] = 'distinct ' . $fieldlist[0]; //prepend the distinct keyword to the first field name
////
////            $query = $db->getQuery(true)
//////                ->select($db->quoteName(a
///// rray('id', 'name', 'parent', 'ordering')))
////                ->select('distinct `gallery_id`')
//////                ->select('distinct ' . $db->qn(array('gallery_id')))
//////                  ->select($fieldlist)
////                ->from('#__rsg2_images')
////                ->where($db->quoteName('use_j3x_location') . ' = 1')
////                ->where("gallery_id IN (" . implode(',', $db->q($j4xGalleryIds)) . ")")
////                ->order('id ASC');
////
////            // Get the options.
////            $db->setQuery($query);
////
////            //$galleryIdsJ3x_NotMoved = $db->loadObjectList();
////            $galleryIdsJ3x_NotMoved = $db->loadColumn();
//
//		}
//		catch (\RuntimeException $e)
//		{
//			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
//		}
//
//		return $galleryIdsJ3x_NotMoved;
//	}
//
    /**
     * @param $j4xGalleryIds
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    public function galleryIdsJ3x_ImagesNotMoved($j3xGalleriesItems)
    {
        $galleryIdsJ3x_NotMoved = []; // ToDo: array() ==> []

        try {
            // find first transfer active
            foreach ($j3xGalleriesItems as $j3xGalleriesItem) {
                if (!$j3xGalleriesItem->isTransferred) {
                    $galleryIdsJ3x_NotMoved [] = $j3xGalleriesItem->id;
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $galleryIdsJ3x_NotMoved;
    }


    // ToDo: may be rewritten to se all galleries of a list

    /**
     * Returns list of image files which have not been moved
     *
     * @param $j4xGalleryIds
     *
     * @return mixed
     *
     * @throws Exception
     * @since version
     */
    public function j3x_imagesToBeMovedByGallery($j4xGalleryIds)
    {
        $imagesToBeMoved = []; // ToDo: array() ==> []

        try {
//            $j4xGalleryIds = [];
//
//            foreach ($j3x_galleries as $j3x_gallery) {
//                $j4xGalleryIds[] = $j3x_gallery->id + 1;
//            }

            $db = $this->getDatabase();

            $query = $db
                ->getQuery(true)
                ->select($db->qn(['id', 'name']))
                ->from('#__rsg2_images')
                ->where($db->quoteName('use_j3x_location') . ' = 1')
                ->where("gallery_id IN (" . implode(',', $db->q($j4xGalleryIds)) . ")")//->order('id ASC');
            ;

            // Get the options.
            $db->setQuery($query);

            $imagesToBeMoved = $db->loadObjectList();
            // $imagesToBeMoved = $db->loadColumn();

        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            throw $e;
        }

        return $imagesToBeMoved;
    }

//    public function moveImagesJ3x2J4xById($j3xImageIds)
//    {
//        $isImagesMoved = false;
//
//        try {
//
//            //--- image names -----------------------------------------------
//
//            // name, id, gallery_id
//            $imgObjectsById = $this->imageNamesById ($j3xImageIds);
//
//            //--- move images -----------------------------------------------
//
//            $movedIds = $this->moveOriginalOrDisplayImage($imgObjectsById);
//
//            //--- update db -------------------------------------------------
//            if (count ($movedIds)) {
//                $isDbWritten = $this->dbMarkImagesAsTransferred($movedIds);
//            }
//
//            //--- check ... -------------------------------------------------
//            // All transferred ?
//            if (count ($movedIds) == count ($j3xImageIds) && $isDbWritten) {
//                $isImagesMoved = true;
//            }
//
//        } //catch (\RuntimeException $e)
//        catch (\Exception $e) {
//            throw new \RuntimeException($e->getMessage() . ' from resetImagesTable');
//        }
//
//        return $isImagesMoved;
//    }

//    public function moveOriginalOrDisplayImage ($imgObjects)
//    {
//        $movedIds = [];
//        $notMovedIds = [];
//
//        try {
//
//            $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
//
//            $ImageWidths = $rsgConfig->get('image_size');
//            $exploded = explode(',', $ImageWidths);
//            $bigImageWidth = $exploded[0];
//
//            $j4xImagePath = new ImagePathsModel (); ? J3x
//            $j3xImagePath = new ImagePathsJ3xModel ();
//
//
//            // ToDo: Watermarked
//            foreach ($imgObjects as $imgObject) {
//                $id = $imgObject['id'];
//                $name = $imgObject['name'];
//                $galleryId = $imgObject['gallery_id'];
//
//                // galleryJ4x path is depending on gallery id
//                $j4xImagePath->setPaths_URIs_byGalleryId($galleryId);
//
//                $isPathsExisting = $j4xImagePath->isPathsExisting ();
//                if ( ! $isPathsExisting) {
//                    // throw new \RuntimeException('Folder missing in path ' . $j4xImagePath->galleryRoot);
//
//                    // create path
//                    $j4xImagePath->createAllPaths();
//
//                }
//
//                $j3xOrgFile = $j3xImagePath->getOriginalPath ($name);
//                $j4xOrgFile = $j4xImagePath->getOriginalPath ($name);
//
//                if (file_exists ($j3xOrgFile)) {
//                    rename($j3xOrgFile, $j4xOrgFile);
//                }
//
//                $j3xDisFile = $j3xImagePath->getDisplayPath ($name);
//                $j4xDisFile = $j4xImagePath->getSizePath ($bigImageWidth, $name);
//
//                if (file_exists ($j3xDisFile)) {
//                    rename($j3xDisFile, $j4xDisFile);
//                    $movedIds [] = $id;
//                }else {
//                    // already done
//                    if (file_exists ($j4xDisFile)) {
//                        $movedIds [] = $id;
//                    }else {
//                        // Mark as not found
//                        $notMovedIds [] = $id . ':' . $name;
//                    }
//                }
//
//                $j3xTmbFile = $j3xImagePath->getThumbPath ($name);
//                $j4xTmbFile = $j4xImagePath->getThumbPath ($name);
//
//                if (file_exists ($j3xTmbFile)) {
//                    rename($j3xTmbFile, $j4xTmbFile);
//                }
//
//            }
//
//            if (count($notMovedIds)) {
//                $notImgList = implode ($notMovedIds, '<br>');
//                Factory::getApplication()->enqueueMessage('Files may have already be moved ? No files found for ' . $notImgList);
//            }
//        }
//        catch (\RuntimeException $e)
//        {
//            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
//        }
//
//        return $movedIds;
//    }

    /**
     * @param $movedIds
     * @param $isUse_j3x_location
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    protected function dbMarkImagesAsTransferred($movedIds, $isUse_j3x_location = false)
    {
        $isIdsMarked = false;

        try {
            $db = $this->getDatabase();

            $query = $db->getQuery(true);

            // $testImplode = implode(',', ArrayHelper::toInteger($movedIds));

            $query
                ->update('#__rsg2_images')
                ->set($db->quoteName('use_j3x_location') . ' = ' . (int)$isUse_j3x_location)
                ->where($db->quoteName('id') . ' IN (' . implode(',', ArrayHelper::toInteger($movedIds)) . ')');

            //$queryDump = $query->dump();
            //Factory::getApplication()->enqueueMessage(Text::_('Test: \$queryDump' . $queryDump), 'notice');

            $db->setQuery($query);

            $isIdsMarked = $db->execute();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $isIdsMarked;
    }

    /**
     * @param $imgObjects
     *
     * @return array[]
     *
     * @throws Exception
     * @since version
     */
    public function check4ExistingDisplayImage($imgObjects)
    {
        $idsExisting    = [];
        $idsNotExisting = [];

        try {
            $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();

            $ImageWidths   = $rsgConfig->get('image_size');
            $exploded      = explode(',', $ImageWidths);
            $bigImageWidth = $exploded[0];

            $j4xImagePath = new ImagePathsModel (); // ToDo: J3x
            //$j3xImagePath = new ImagePathsJ3xModel (); // ToDo: J3x

            // ToDo: Watermarked
            foreach ($imgObjects as $imgObject) {
                $id        = $imgObject['id'];
                $name      = $imgObject['name'];
                $galleryId = $imgObject['gallery_id'];

                // galleryJ4x path is depending on gallery id
                $j4xImagePath->setPaths_URIs_byGalleryId($galleryId);

                $isPathsExisting = $j4xImagePath->isPathsExisting();
                if (!$isPathsExisting) {
                    throw new RuntimeException('Folder missing in path ' . $j4xImagePath->galleryRoot);
                }

//                $j4xOrgFile = $j4xImagePath->getOriginalPath ($name);
//                if (file_exists ($j4xOrgFile)) {
//                    ???;
//                }

                $j4xDisFile = $j4xImagePath->getSizePath($bigImageWidth, $name);

                if (file_exists($j4xDisFile)) {
                    $idsExisting [] = $id;
                } else {
                    $idsNotExisting [] = $id;
                }

//                $j4xTmbFile = $j4xImagePath->getThumbPath ($name);
//                if (file_exists ($j4xTmbFile)) {
//                    ???;
//                }

            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return [$idsExisting, $idsNotExisting];
    }

    // Image moving stage -> separate for original, display, thumb, ...

    /**
     *
     */
    const J3X_IMG_NOT_FOUND = 0;
    const J3X_IMG_MOVED = 1;
    const J3X_IMG_ALREADY_MOVED = 2;
    const J3X_IMG_J3X_DELETED = 3; //J4 exists and j3 is actively deleted
    const J3X_IMG_MOVING_FAILED = 4;
    const J3X_IMG_MOVED_AND_DB = 5;

    /**
     * @param $id
     * @param $name
     * @param $galleryId
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function j3x_moveImage($id, $name, $galleryId)
    {
        global $rsgConfig, $isDebugBackend, $isDevelop;

        if ($isDebugBackend) {
            // identify active file
            Log::add('j3x_moveImage ==> ' . $name . ' galId: ' . $galleryId . ' imgId: ' . $id);
        }

        // [$stateOriginal, $stateDisplay, $stateThumb, $stateWatermarked, $stateImageDb]

        //--- display image width --------------------------------------

        //$rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();

        $ImageWidths   = $rsgConfig->get('image_size');
        $exploded      = explode(',', $ImageWidths);
        $bigImageWidth = $exploded[0];

        //--- image paths ----------------------------------------

        $j4xImagePath = new ImagePathsModel ();
        $j3xImagePath = new ImagePathsJ3xModel ();

        $j4xImagePath->setPaths_URIs_byGalleryId($galleryId);

        $isPathsExisting = $j4xImagePath->isPathsExisting();
        if (!$isPathsExisting) {
            // throw new \RuntimeException('Folder missing in path ' . $j4xImagePath->galleryRoot);

            // create path
            $j4xImagePath->createAllPaths();
        }

        //--- original -----------------------------

        $stateOriginal = self::J3X_IMG_NOT_FOUND;

        $j3xOrgFile = $j3xImagePath->getOriginalPath($name);
        $j4xOrgFile = $j4xImagePath->getOriginalPath($name);

        $stateOriginal = $this->RenameJ3xImageFile($j3xOrgFile, $j4xOrgFile);

        //--- display -----------------------------

        // ToDo: check if does  regard resolution
        $stateDisplay = self::J3X_IMG_NOT_FOUND;

        $j3xDisFile = $j3xImagePath->getDisplayPath($name);
        $j4xDisFile = $j4xImagePath->getSizePath($bigImageWidth, $name);

        $stateDisplay = $this->RenameJ3xImageFile($j3xDisFile, $j4xDisFile);

        //--- thumb -----------------------------

        $stateThumb = self::J3X_IMG_NOT_FOUND;

        $j3xTmbFile = $j3xImagePath->getThumbPath($name);
        $j4xTmbFile = $j4xImagePath->getThumbPath($name);

        $stateThumb = $this->RenameJ3xImageFile($j3xTmbFile, $j4xTmbFile);

        //--- watermarked -----------------------------

        // ToDo: move / copy watermarked
        $stateWatermarked = self::J3X_IMG_NOT_FOUND;

//        $j3xWaterFile = $j3xImagePath->getThumbPath ($name);
//        $j4xwaterFile = $j4xImagePath->getThumbPath ($name);
//
//        $stateWatermarked = $this->RenameJ3xImageFile($j3xWaterFile, $j4xwaterFile);

        //--- image states -----------------------------

        $stateImageDb = self::J3X_IMG_NOT_FOUND; // J3X_IMG_MOVED_AND_DB = 4;

        // Is mved when all destionation images exist
        $isMoved = $this->isMovedState($stateOriginal)
            & $this->isMovedState($stateDisplay)
            & $this->isMovedState($stateThumb);
        // watermark exists and is copied ...
        //$isMoved &= $this->isMovedState ($stateWatermarked);

        //--- Update image DB -----------------------------

        // ready for DB update ?
        if ($isMoved) {
            $isDBUpdated = $this->dbMarkImagesAsTransferred([$id]);

            if ($isDBUpdated) {
                $stateImageDb = self::J3X_IMG_MOVED_AND_DB;
            }
        }

        if ($isDebugBackend) {
            // identify active file
            Log::add(
                'j3x_moveImage <== state [Display: ' . $stateDisplay . ' thumb: ' . $stateThumb . ' water: ' . $stateWatermarked . ' imgDb: ' . $stateImageDb . ']',
            );
        }

        return [$stateOriginal, $stateDisplay, $stateThumb, $stateWatermarked, $stateImageDb];
    }


    /**/
    /**
     * @param   string  $j3xFile
     * @param   string  $j4xFile
     *
     * @return int
     *
     * @since __BUMP_VERSION__
     */
    private function RenameJ3xImageFile(string $j3xFile, string $j4xFile)
    {
        $state = MaintenanceJ3xModel::J3X_IMG_NOT_FOUND;

        // source exist
        if (file_exists($j3xFile)) {
            // destination exists
            if (file_exists($j4xFile)) {
                $state = MaintenanceJ3xModel::J3X_IMG_ALREADY_MOVED;

                // Delete original
                unlink($j3xFile);
            } else {
                //---  do move --------------------------------------------

                $isMoved = rename($j3xFile, $j4xFile);

                if ($isMoved) {
                    $state = MaintenanceJ3xModel::J3X_IMG_MOVED;
                } else {
                    $state = MaintenanceJ3xModel::J3X_IMG_MOVING_FAILED;
                }
            }
        } else {
            // destination exists
            if (file_exists($j4xFile)) {
                $state = MaintenanceJ3xModel::J3X_IMG_ALREADY_MOVED;
            } else {
//                $state = J3X_IMG_NOT_FOUND;
            }
        }

        return $state;
    }

    private function isMovedState($state)
    {
        $isMoved = false;

        //
        if ($state == MaintenanceJ3xModel::J3X_IMG_MOVED
            || $state == MaintenanceJ3xModel::J3X_IMG_ALREADY_MOVED
            || $state == MaintenanceJ3xModel::J3X_IMG_J3X_DELETED
        ) {
            $isMoved = true;
        }

        return $isMoved;
    }

    /**
     * Use start templates matching a ..J3x folder.
     * This enables to separate J3x from new J4x
     * folders with same name
     * Change j3x 'gallery' to root galleries, (?galleries) or keep it
     *    On gid=0 = use root galleries J3x
     *    On gid!=0 still use gallery J3x
     *   * ToDo: Detect parent galleries
     * Find in menus all GID references and increase them
     * On transfer of gallery ids to new balanced tree the
     * id is increased. Therefore, links in menu are now invalid
     * Applies to gallery and slideshow menu items
     * Can only be run once to match the changed gallery id
     * On double call use j3xDegradeUpgradedJ4xMenuLinks
     *
     * index.php?option=com_rsgallery2&view=gallery&gid=0
     * index.php?option=com_rsgallery2&view=gallery&gid=227&displaySearch=0
     * index.php?option=com_rsgallery2&view=slideshow&gid=227
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function j3xUpgradeJ3xMenuLinks()
    {
        $successful = false;

        try {
            // link includes '&view=gallery' or '&view=slideshow&'
            $menuItemsDB = $this->dbValidJ3xGidMenuItems();

            if (!empty ($menuItemsDB)) {
                $successful = true;

                foreach ($menuItemsDB as $id => $menuItem) {
                    [$oldLink, $oldParams] = $menuItem;

                    //--- add parameters from j3x config by link type -----------------------------

                    // root gallery, gallery , slideshow
                    $newParams = $this->menuParamsByJ3xType($oldLink, $oldParams);

                    //--- change link for gid++ ----------------------------------------------

                    $newLink = $this->linkIncreaseGalleryId($oldLink);

                    //--- change link for type: ----------------------------------------------

                    // root gallery, gallery , slideshow
                    $newLink = $this->linkUpgradeByJ3xType($newLink, $newParams);

                    //--- change menu parameter for type ----------------------------------------

                    // valid link ?
                    if (!empty($newLink)) {
                        $successful &= $this->updateMenuItem($id, $newLink, $newParams);
                    } // else successful = false ...
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $successful;
    }

    /**
     * Opposite of j3xUpgradeJ3xMenuLinks
     * Needed if above done too many times
     * Attention gid==0 special case with double meaning can't be solved
     * 0: root gallery 0-> 1: change to gallery if (1: forbidden as tree root)
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function j3xDegradeUpgradedJ4xMenuLinks()
    {
        $successful = false;

        try {
            // ToDO: ? create function to remove this change analog to j3xUpgradeJ3xMenuLinks
            // '&view=galleryj3x&' or '&view=slideshowj3x&'
            $menuItemsDB = $this->dbValidJ4xGidMenuItems();

            // upgraded links found
            if (!empty ($menuItemsDB)) {
                $successful = true;

                foreach ($menuItemsDB as $id => $menuItem) {
                    [$oldLink, $oldParams] = $menuItem;

                    //--- change link for gid++ ----------------------------------------------

                    $newLink = $this->linkDecreaseGalleryId($oldLink);

                    //--- change link for type: ----------------------------------------------

                    // root gallery, gallery , slideshow
                    $newLink = $this->linkDegradeByJ4xType($newLink);

                    //--- change menu parameter for type ----------------------------------------

                    // ToDO: ? remove new parameters not existing in J3x ?
                    // root gallery, gallery , slideshow
                    // $newParams = $this->resetMenuParamsByJ3xType($oldLink, $oldParams);

                    // valid link ?
                    if (!empty($newLink)) {
                        $successful &= $this->updateMenuItem($id, $newLink, $oldParams);
                    }
                }
            } else {
                //--- decrease on already downgraded links ---------------------------------

                $menuItemsDB = $this->dbValidJ3xGidMenuItems();

                if (!empty ($menuItemsDB)) {
                    $successful = true;

                    foreach ($menuItemsDB as $id => $menuItem) {
                        [$oldLink, $oldParams] = $menuItem;

                        //--- change link for gid++ ----------------------------------------------

                        $newLink = $this->linkDecreaseGalleryId($oldLink);

                        //--- update link --------------------------------------------

                        // valid link ?
                        if (!empty($newLink)) {
                            $successful &= $this->updateMenuItem($id, $newLink, $oldParams);
                        }
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $successful;
    }

    /**
     * Uppercase to lower case in 'J'
     * index.php?option=com_rsgallery2&view=gallery&gid=0
     * index.php?option=com_rsgallery2&view=gallery&gid=227&displaySearch=0
     * index.php?option=com_rsgallery2&view=slideshow&gid=227
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function j3xLowerJ4xMenuLinks()
    {
        $successful = false;

        try {
            // link includes '&view=gallery' or '&view=slideshow&'
            $menuItemsDB = $this->dbUpperCaseJ4xGidMenuItems();

            if (!empty ($menuItemsDB)) {
                $successful = true;

                foreach ($menuItemsDB as $id => $menuItem) {
                    [$oldLink, $oldParams] = $menuItem;

                    //--- change link for type: ----------------------------------------------

                    // root gallery, gallery , slideshow
                    $newLink = $this->linkLowercaseJ4xType($oldLink);

                    //--- change menu parameter for type ----------------------------------------

                    // valid link ?
                    if (!empty($newLink)) {
                        $successful = $this->updateMenuItem($id, $newLink, $oldParams);
                    } // else successful = false ...
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $successful;
    }

    /**
     * Uppercase to lower case in 'J'
     * index.php?option=com_rsgallery2&view=gallery&gid=0
     * index.php?option=com_rsgallery2&view=gallery&gid=227&displaySearch=0
     * index.php?option=com_rsgallery2&view=slideshow&gid=227
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function j3xUpperJ4xMenuLinks()
    {
        $successful = false;

        try {
            // link includes '&view=gallery' or '&view=slideshow&'
            $menuItemsDB = $this->dbValidJ4xGidMenuItems();

            if (!empty ($menuItemsDB)) {
                $successful = true;

                foreach ($menuItemsDB as $id => $menuItem) {
                    [$oldLink, $oldParams] = $menuItem;

                    //--- change link for type: ----------------------------------------------

                    // root gallery, gallery , slideshow
                    $newLink = $this->linkUppercaseJ4xType($oldLink);

                    //--- change menu parameter for type ----------------------------------------

                    // valid link ?
                    if (!empty($newLink)) {
                        $successful = $this->updateMenuItem($id, $newLink, $oldParams);
                    } // else successful = false ...
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $successful;
    }

    /**
     * @param $oldLink
     *
     * @return int|string
     *
     * examples
     *    index.php?option=com_rsgallery2&view=gallery&gid=0
     *    index.php?option=com_rsgallery2&view=gallery&gid=2
     *    index.php?option=com_rsgallery2&view=slideshow&gid=227
     *
     * @since version
     */
    public function linkAddToGalleryId(string $oldLink, int $delta)
    {
        $newLink = $oldLink;

        //--- extract gallery id --------------------------

        $gidIdx    = strpos($oldLink, '&gid=') + 5;
        $gidEndIdx = strpos($oldLink, '&', $gidIdx);
        // no further ...
        if ($gidEndIdx == false) {
            $gidEndIdx = strlen($oldLink);
        }

        $galleryId = substr($oldLink, $gidIdx, $gidEndIdx - $gidIdx);

        if (intval($galleryId) == 0) {
            // debug stop as should not happen
            // debug stop for root gallery ?
            $test1 = intval($galleryId);
        }

        if (intval($galleryId) > 0) {
            $newGalleryId = strval(intval($galleryId) + $delta);

            $newLink = substr($oldLink, 0, $gidIdx)
                . $newGalleryId
                . substr($oldLink, $gidEndIdx);
        }

        return $newLink;
    }

    /**
     * @param $link
     *
     * @return int|string
     *
     *
     * @since version
     */
    public function menuParamsByJ3xType($oldLink, $oldParams)
    {
        // fall back use given
        $newParams = $oldParams;

        try {
            //--- extract gallery id --------------------------

            $gidIdx    = strpos($oldLink, '&gid=') + 5;
            $gidEndIdx = strpos($oldLink, '&', $gidIdx);
            // no further characters
            if ($gidEndIdx == false) {
                $gidEndIdx = strlen($oldLink);
            }

            $galleryId = substr($oldLink, $gidIdx, $gidEndIdx - $gidIdx);

            if (intval($galleryId) == 0) {
                // debug stop for root gallery
                $test1 = intval($galleryId);
            }

            //--- collect (j3x) config parameter----------------------------------

            $rsgJ3xConfig = $this->j3xConfigItems();
            // collect parameter (by j4x config, J3x should be transferred)
            $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();

            /**
             * // ToDo: use type as result of registry
             * $max_columns_in_images_view_j3x = $rsgConfig->get('max_columns_in_images_view_j3x');
             * $max_thumbs_in_images_view_j3x  = $rsgConfig->get('max_thumbs_in_images_view_j3x');
             * $displayGalleryName             = $rsgConfig->get('displayGalleryName');
             * $displayGalleryDescription      = $rsgConfig->get('displayGalleryDescription');
             * /**/

            //--- actual parameter to array ----------------------------------

            $params = $oldParams;

            //--- assign new parameter ----------------------------------

            /* ToDo: include left outs:
                [image slide page parameters:]
                * Displ. slideshow
                * Popup style
                * Display description
                * Display hits
                * Display voting
                * Display comments
                *
                *
                [parent gallery]
                * ? how many galleries max ?
                *
             /**/

            if (str_contains($oldLink, '&view=gallery&')) {
                // root gallery
                if (intval($galleryId) == 0) {
                    /**
                     * --- original J3x names root galleries front view -------------------------
                     * name="displaySearch"
                     * x name="displayRandom"
                     * x name="displayLatest"
                     * x name="displayBranding"
                     * x name="displayDownload"
                     * x name="displayStatus"
                     * x name="dispLimitbox"
                     * name="galcountNrs"
                     * x name="displaySlideshow"
                     * x name="current_slideshow"
                     * x name="showGalleryOwner"
                     * x name="showGallerySize"
                     * x name="includeKids"
                     * x name="showGalleryDate"
                     *
                     * /**/

                    /* needed in root galleryj3x view xml
                        name="displaySearch"
                        name="max_thumbs_in_root_galleries_view_j3x"
                        name="displayRandom"
                        name="displayLatest"
                        name="intro_text"
                        name="menu_show_intro_text"
                        name="gallery_layout"
                        name="display_limitbox"
                        name="galleries_show_title"
                        name="galleries_show_description"
                        name="galleries_show_owner"
                        name="galleries_show_size"
                        name="galleries_show_date"
                        name="galleries_show_pre_label"
                        name="galleries_show_slideshow"
                        name="galleries_description_side"
                        name="menu_show_intro_text"
                        name="latest_count"
                        name="random_count"
                    /**/

                    // ??? $newParams['images_show_search'] = $rsgJ3xConfig ['displaySearch'];
                    $newParams['images_column_arrangement_j3x'] = '1';
                    // $newParams['max_columns_in_images_view_j3x'] = $rsgJ3xConfig ['']; // not used as root = vertical

                    $newParams['max_thumbs_in_images_view_j3x'] = $rsgJ3xConfig ['galcountNrs'];

                    // $newParams['gallery_show_title'] = $rsgJ3xConfig ['???displayGalleryName??'];
                    $newParams['gallery_show_description'] = $rsgJ3xConfig ['displayGalleryDescription'];

                    $newParams['gallery_show_slideshow']  = $rsgJ3xConfig [''];
                    $newParams['images_show_title']       = $rsgJ3xConfig [''];
                    $newParams['images_show_description'] = '0'; //$rsgConfig [''];

                    $newParams['displaySearch'] = $rsgJ3xConfig ['displaySearch'];
                    $newParams['displayRandom'] = $rsgJ3xConfig ['displayRandom'];
                    $newParams['displayLatest'] = $rsgJ3xConfig ['displayLatest'];

                    $newParams['intro_text'] = $rsgJ3xConfig ['intro_text'];

                    /**
                     * + display_limitbox
                     *
                     *
                     * - displayBranding
                     * -
                     * /**/
                    /* ToDo: include left outs:
                        * Thumbnail Style
                        * direction left to right
                        * navigation bar top / bottom
                        *
                        *
                    /**/
                } else {
                    /**
                     * --- original J3x names gallery view -----------------------------------------------
                     * name="template"
                     * name="displayGalleryName"
                     * name="displayGalleryDescription"
                     * name="display_thumbs_style"
                     * name="display_thumbs_floatDirection"
                     * name="display_thumbs_colsPerPage"
                     * name="display_thumbs_maxPerPage"
                     * name="display_thumbs_showImgName"
                     * name="display_navigation_bar_mode"
                     * name="displaySlideshowGalleryView"
                     *
                     * /**/

                    /* needed in galleryj3x view
                    $paraPart = ""
                        . "&images_show_search=1"
                        . "&images_column_arrangement_j3x=1"
                        . "&max_columns_in_images_view_j3x=4"

                        . "&max_thumbs_in_images_view_j3x=20"
                        . "&gallery_show_title=0"
                        . "&gallery_show_description=0"

                        . "&gallery_show_slideshow=1"
                        . "&images_show_title=1"
                        . "&images_show_description=0"
                    /**/

                    $newParams['images_show_search']             = '0'; // $rsgJ3xConfig->get('');
                    $newParams['images_column_arrangement_j3x']  = '1';
                    $newParams['max_columns_in_images_view_j3x'] = $rsgJ3xConfig ['display_thumbs_colsPerPage'];

                    $newParams['max_thumbs_in_images_view_j3x'] = $rsgJ3xConfig ['display_thumbs_maxPerPage'];
                    $newParams['gallery_show_title']            = $rsgJ3xConfig ['displayGalleryName'];
                    $newParams['gallery_show_description']      = $rsgJ3xConfig ['displayGalleryDescription'];

                    $newParams['gallery_show_slideshow']  = $rsgJ3xConfig ['displaySlideshowGalleryView'];
                    $newParams['images_show_title']       = $rsgJ3xConfig ['display_thumbs_showImgName'];
                    $newParams['images_show_description'] = '0'; //$rsgConfig [''];

                    /* ToDo: include left outs:
                        * Thumbnail Style
                        * direction left to right
                        * navigation bar top / bottom
                        *
                        *
                    /**/
                }
            } else {
                // slideshow
                if (str_contains($oldLink, '&view=slideshow&')) {
                    /* needed in galleryj3x view
                    $paraPart = ""
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'

                    /**/

                    /* ToDo: include left outs:
                        * Thumbnail Style
                        * direction left to right
                        * navigation bar top / bottom
                        *
                        *
                    /**/
                    /**
                     * $newParams['max_columns_in_images_view_j3x'] = $max_columns_in_images_view_j3x;
                     * $newParams['max_thumbs_in_images_view_j3x']  = $max_thumbs_in_images_view_j3x;
                     * $newParams['displayGalleryName']             = $displayGalleryName;
                     * $newParams['displayGalleryDescription']      = $displayGalleryDescription;
                     * /**/
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $newParams;
    }

    /**
     * Old j3x menu links interfere with same name j4 views
     * Exchange the view type (j3x use) in menu link for
     * j3x view type fallback calls
     *
     * @param $newLink
     *
     * @return int|string
     *
     * examples
     *     index.php?option=com_rsgallery2&view=gallery&gid=0 => ...&view=rootgalleriesj3x&...
     *     index.php?option=com_rsgallery2&view=gallery&gid=2
     *     index.php?option=com_rsgallery2&view=slideshow&gid=227
     *
     * @since version
     */
    public function linkUpgradeByJ3xType(string $newLink, $params)
    {
        try {
            //--- extract gallery id --------------------------

            $gidIdx    = strpos($newLink, '&gid=') + 5;
            $gidEndIdx = strpos($newLink, '&', $gidIdx);
            // no further characters
            if ($gidEndIdx == false) {
                $gidEndIdx = strlen($newLink);
            }

            $galleryId = substr($newLink, $gidIdx, $gidEndIdx - $gidIdx);

            if (intval($galleryId) == 0) {
                // debug stop for root gallery
                $test1 = intval($galleryId);
            }

            //--- exchange gallery link type --------------------------

            if (str_contains($newLink, '&view=gallery&')) {
                // root gallery
                if (intval($galleryId) == 0) {
                    // todo: (2) Assign values from parameter see max_columns_in_images_view_j3x here and above

                    $newLink = substr($newLink, 0, $gidEndIdx);
                    $newLink = str_replace('&view=gallery&', '&view=rootgalleriesj3x&', $newLink);

                    $paraPart = ""
                        . "&max_thumbs_in_root_galleries_view_j3x=" . $params['max_thumbs_in_root_galleries_view_j3x'] . '"'
                        . "&displaySearch=" . $params['displaySearch'] . '"'
                        . "&displayRandom=" . $params['displayRandom'] . '"'
                        . "&displayLatest=" . $params['displayLatest'] . '"'
                        . "&intro_text=" . $params['intro_text'] . '"'
                        . "&menu_show_intro_text=" . $params['menu_show_intro_text'] . '"'
                        . "&display_limitbox=" . $params['display_limitbox'] . '"'
                        . "&galleries_show_title=" . $params['galleries_show_title'] . '"'
                        . "&galleries_show_description=" . $params['galleries_show_description'] . '"'
                        . "&galleries_show_owner=" . $params['galleries_show_owner'] . '"'
                        . "&galleries_show_size=" . $params['galleries_show_size'] . '"'
                        . "&galleries_show_date=" . $params['galleries_show_date'] . '"'
                        . "&galleries_show_pre_label=" . $params['galleries_show_pre_label'] . '"'
                        . "&galleries_show_slideshow=" . $params['galleries_show_slideshow'] . '"'
                        . "&galleries_description_side=" . $params['galleries_description_side'] . '"'
                        . "&latest_images_count=" . $params['latest_images_count'] . '"'
                        . "&random_images_count=" . $params['random_images_count'] . '"'// . "&m=" . $params['m'] . '"'
                    ;
                    $newLink  .= $paraPart;
                } else {
                    // ToDo: ? parent gallery ?
                    // gallery
                    $newLink = substr($newLink, 0, $gidEndIdx);
                    $newLink = str_replace('&view=gallery&', '&view=galleryj3x&', $newLink);

                    $paraPart = ""
                        . "&images_show_search=" . $params['images_show_search']
                        . "&images_column_arrangement_j3x=" . $params['images_column_arrangement_j3x']
                        . "&max_columns_in_images_view_j3x=" . $params['max_columns_in_images_view_j3x']
                        . "&max_thumbs_in_images_view_j3x=" . $params['max_thumbs_in_images_view_j3x']
                        . "&gallery_show_title=" . $params['gallery_show_title']
                        . "&gallery_show_description=" . $params['gallery_show_description']
                        . "&gallery_show_slideshow=" . $params['gallery_show_slideshow']
                        . "&images_show_title=" . $params['images_show_title']
                        . "&images_show_description=" . $params['images_show_description'];
                    $newLink  .= $paraPart;
                }
            } else {
                if (str_contains($newLink, '&view=slideshow&')) {
                    // slideshow
                    $newLink = substr($newLink, 0, $gidEndIdx);
                    $newLink = str_replace('&view=slideshow&', '&view=slideshowj3x&', $newLink);
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $newLink;
    }

    /**
     * Old j3x menu links interfere with same name j4 views
     * Exchange the view type (j3x use) in menu link for
     * j3x view type fallback calls
     *
     * @param $newLink
     *
     * @return int|string
     *
     * examples
     *     index.php?option=com_rsgallery2&view=rootgalleriesj3x&gid=0 => ...&view=gallery&gid=0
     *     index.php?option=com_rsgallery2&view=galleryj3x&gid=2       => ...2&view=gallery&gid=2
     *     index.php?option=com_rsgallery2&view=slideshowj3x&gid=227   => ...&view=slideshow&gid=227
     *
     * @since version
     */
    public function linkDegradeByJ4xType(string $newLink)
    {
        try {
            //--- extract gallery id --------------------------

            $gidIdx    = strpos($newLink, '&gid=') + 5;
            $gidEndIdx = strpos($newLink, '&', $gidIdx);
            // no further characters
            if ($gidEndIdx == false) {
                $gidEndIdx = strlen($newLink);
            }

            $galleryId = substr($newLink, $gidIdx, $gidEndIdx - $gidIdx);

            if (intval($galleryId) == 0) {
                // debug stop for root gallery
                $test1 = intval($galleryId);
            }

            //--- exchange gallery link type --------------------------

            if (str_contains($newLink, '&view=galleryj3x&')) {
                // ToDo: ? parent gallery ?
                // gallery
                $newLink = substr($newLink, 0, $gidEndIdx);
                $newLink = str_replace('&view=galleryj3x&', '&view=gallery&', $newLink);
            } else {
                if (str_contains($newLink, '&view=rootgalleriesj3x&')) {
                    // root gallery
                    if (intval($galleryId) == 0) {
                        $newLink = substr($newLink, 0, $gidEndIdx);
                        $newLink = str_replace('&view=rootgalleriesj3x&', '&view=gallery&', $newLink);
                    }
                } else {
                    if (str_contains($newLink, '&view=slideshowj3x&')) {
                        // slideshow
                        $newLink = substr($newLink, 0, $gidEndIdx);
                        $newLink = str_replace('&view=slideshowj3x&', '&view=slideshow&', $newLink);
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $newLink;
    }

    /**
     * @param $link
     *
     * @return int|string
     *
     * examples
     *      index.php?option=com_rsgallery2&view=gallery&gid=0
     *      index.php?option=com_rsgallery2&view=gallery&gid=2
     *      index.php?option=com_rsgallery2&view=slideshow&gid=227
     *
     * @since version
     */
    public function linkIncreaseGalleryId($link)
    {
        return $this->linkAddToGalleryId($link, +1);
    }

    /**
     * @param $link
     *
     * @return int|string
     *
     * @since version
     */
    public function linkDecreaseGalleryId($oldLink)
    {
        return $this->linkAddToGalleryId($oldLink, -1);
    }

    /**
     *
     *
     * @param $newLink
     *
     * @return int|string
     *
     * examples
     *     index.php?option=com_rsgallery2&view=rootgalleriesj3x&gid=0 => ...&view=gallery&gid=0
     *     index.php?option=com_rsgallery2&view=galleryj3x&gid=2       => ...2&view=gallery&gid=2
     *     index.php?option=com_rsgallery2&view=slideshowj3x&gid=227   => ...&view=slideshow&gid=227
     *
     * @since version
     */
    public function linkLowercaseJ4xType(string $newLink)
    {
        try {
            //--- extract gallery id --------------------------

            $gidIdx    = strpos($newLink, '&gid=') + 5;
            $gidEndIdx = strpos($newLink, '&', $gidIdx);
            // no further characters
            if ($gidEndIdx == false) {
                $gidEndIdx = strlen($newLink);
            }

            $galleryId = substr($newLink, $gidIdx, $gidEndIdx - $gidIdx);

            if (intval($galleryId) == 0) {
                // debug stop for root gallery
                $test1 = intval($galleryId);
            }

            //--- exchange gallery link type --------------------------

            if (str_contains($newLink, '&view=galleryJ3x&')) {
                // ToDo: ? parent gallery ?
                // gallery
                $newLink = substr($newLink, 0, $gidEndIdx);
                $newLink = str_replace('&view=galleryJ3x&', '&view=galleryj3x&', $newLink);
            } else {
                if (str_contains($newLink, '&view=rootgalleriesJ3x&')) {
                    // root gallery
                    if (intval($galleryId) == 0) {
                        $newLink = substr($newLink, 0, $gidEndIdx);
                        $newLink = str_replace('&view=rootgalleriesJ3x&', '&view=rootgalleriesj3x&', $newLink);
                    }
                } else {
                    if (str_contains($newLink, '&view=slideshowJ3x&')) {
                        // slideshow
                        $newLink = substr($newLink, 0, $gidEndIdx);
                        $newLink = str_replace('&view=slideshowJ3x&', '&view=slideshowj3x&', $newLink);
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $newLink;
    }

    /**
     *
     *
     * @param $newLink
     *
     * @return int|string
     *
     * examples
     *     index.php?option=com_rsgallery2&view=rootgalleriesj3x&gid=0 => ...&view=gallery&gid=0
     *     index.php?option=com_rsgallery2&view=galleryj3x&gid=2       => ...2&view=gallery&gid=2
     *     index.php?option=com_rsgallery2&view=slideshowj3x&gid=227   => ...&view=slideshow&gid=227
     *
     * @since version
     */
    public function linkUppercaseJ4xType(string $newLink)
    {
        try {
            //--- extract gallery id --------------------------

            $gidIdx    = strpos($newLink, '&gid=') + 5;
            $gidEndIdx = strpos($newLink, '&', $gidIdx);
            // no further characters
            if ($gidEndIdx == false) {
                $gidEndIdx = strlen($newLink);
            }

            $galleryId = substr($newLink, $gidIdx, $gidEndIdx - $gidIdx);

            if (intval($galleryId) == 0) {
                // debug stop for root gallery
                $test1 = intval($galleryId);
            }

            //--- exchange gallery link type --------------------------

            if (str_contains($newLink, '&view=galleryj3x&')) {
                // ToDo: ? parent gallery ?
                // gallery
                $newLink = substr($newLink, 0, $gidEndIdx);
                $newLink = str_replace('&view=galleryj3x&', '&view=galleryJ3x&', $newLink);
            } else {
                if (str_contains($newLink, '&view=rootgalleriesj3x&')) {
                    // root gallery
                    if (intval($galleryId) == 0) {
                        $newLink = substr($newLink, 0, $gidEndIdx);
                        $newLink = str_replace('&view=rootgalleriesj3x&', '&view=rootgalleriesJ3x&', $newLink);
                    }
                } else {
                    if (str_contains($newLink, '&view=slideshowj3x&')) {
                        // slideshow
                        $newLink = substr($newLink, 0, $gidEndIdx);
                        $newLink = str_replace('&view=slideshowj3x&', '&view=slideshowJ3x&', $newLink);
                    }
                }
            }
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $newLink;
    }

    /**
     * Writes back menu link with increased / decreased gallery ID
     *
     * @param   int|string  $newLink
     * @param   int|string  $id
     * @param   bool        $successful
     *
     * @return bool
     *
     * @since version
     */
    public function updateMenuItem(string $id, $newLink, $newParams)
    {
        $successful = false;

        //--- to string ----------------------------------

        $jsonParameter = json_encode($newParams, true);

        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true);

        $query
            ->update($db->quoteName('#__menu'))
            ->set($db->quoteName('link') . ' = ' . $db->quote($newLink))
            ->set($db->quoteName('params') . ' = ' . $db->quote($jsonParameter))
            ->where($db->quoteName('id') . ' = ' . $id);

        $db->setQuery($query);
        if ($db->execute()) {
            $successful = true;
        }

        return $successful;
    }

    /**
     * Select menu link item for rsg2 old j3x menu items
     * link includes '&view=gallery' or '&view=slideshow&'
     *
     * @return mixed
     *
     * @since version
     */
    public function dbValidJ3xGidMenuItems()
    {
        $menuLinks = [];

        //--- list from #__menu table in DB  -------------------------------------------

        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true);

        // select all menus with com_rsgallery2 and a gid > 0 (leave out root galleries)

        $query
            ->select(['id', 'link', 'params'])
            ->from('#__menu')
            ->where($db->quoteName('link') . ' LIKE ' . $db->quote($db->escape('%gid=%')))
//			->where($db->quoteName('link') . ' NOT LIKE ' . $db->quote($db->escape('%gid=0%')))
            ->where($db->quoteName('link') . ' LIKE ' . $db->quote($db->escape('%option=com_rsgallery2%')));

        $db->setQuery($query);

        // $menuItemsDB = $db->loadAssocList('id', 'link');
        $menuItemsDB = $db->loadAssocList('id');
        // $menuItemsDB = $db->loadObjectList();

        //--- restrict to 'legacy' j3x menu items -------------------------------------------

        if (!empty ($menuItemsDB)) {
            foreach ($menuItemsDB as $id => $menuItemDb) {
                // [$idDummy, $link, $params] = $menuItemDb;
                $link   = $menuItemDb ['link'];
                $params = json_decode($menuItemDb ['params'], true);

                // add matching link
                if (str_contains($link, '&view=gallery&')
                    || str_contains($link, '&view=slideshow&')) {
                    $menuLinks[$id] = [$link, $params];
                }
            }
        }

        return $menuLinks;
    }

    /**
     * Select menu link item for rsg2 which have already been upgraded to J4x
     * link includes '&view=galleryj3x&' or '&view=slideshowj3x&'
     *
     * @return mixed
     *
     * @since version
     */
    public function dbValidJ4xGidMenuItems()
    {
        $menuLinks = [];

        //--- list from #__menu table in DB  -------------------------------------------

        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true);

        // select all menus with com_rsgallery2 and a gid

        $query
            ->select(['id', 'link', 'params'])
            ->from('#__menu')
            ->where($db->quoteName('link') . ' LIKE ' . $db->quote($db->escape('%gid=%')))
//			->where($db->quoteName('link') . ' NOT LIKE ' . $db->quote($db->escape('%gid=0%')))
            ->where($db->quoteName('link') . ' LIKE ' . $db->quote($db->escape('%option=com_rsgallery2%')));

        $db->setQuery($query);

        // $menuItemsDB = $db->loadAssocList('id', 'link');
        $menuItemsDB = $db->loadAssocList('id');
        // $menuItemsDB = $db->loadObjectList();

        //--- restrict to 'legacy' j3x menu items -------------------------------------------

        if (!empty ($menuItemsDB)) {
            foreach ($menuItemsDB as $id => $menuItemDb) {
                // [$idDummy, $link, $params] = $menuItemDb;
                $link   = $menuItemDb ['link'];
                $params = json_decode($menuItemDb ['params'], true);

                // add matching link
                if (str_contains($link, '&view=galleryj3x&')
                    || str_contains($link, '&view=rootgalleriesj3x&')
                    || str_contains($link, '&view=slideshowj3x&')) {
                    $menuLinks[$id] = [$link, $params];
                }
            }
        }

        return $menuLinks;
    }

    /**
     * Select menu link item for rsg2 which have already been upgraded to J4x
     * link includes '&view=galleryj3x&' or '&view=slideshowj3x&'
     *
     * @return mixed
     *
     * @since version
     */
    public function dbUpperCaseJ4xGidMenuItems()
    {
        $menuLinks = [];

        //--- list from #__menu table in DB  -------------------------------------------

        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true);

        // select all menus with com_rsgallery2 and a gid

        $query
            ->select(['id', 'link', 'params'])
            ->from('#__menu')
            ->where($db->quoteName('link') . ' LIKE ' . $db->quote($db->escape('%gid=%')))
//			->where($db->quoteName('link') . ' NOT LIKE ' . $db->quote($db->escape('%gid=0%')))
            ->where($db->quoteName('link') . ' LIKE ' . $db->quote($db->escape('%option=com_rsgallery2%')));

        $db->setQuery($query);

        // $menuItemsDB = $db->loadAssocList('id', 'link');
        $menuItemsDB = $db->loadAssocList('id');
        // $menuItemsDB = $db->loadObjectList();

        //--- restrict to 'legacy' j3x menu items -------------------------------------------

        if (!empty ($menuItemsDB)) {
            foreach ($menuItemsDB as $id => $menuItemDb) {
                // [$idDummy, $link, $params] = $menuItemDb;
                $link   = $menuItemDb ['link'];
                $params = json_decode($menuItemDb ['params'], true);

                // add matching link
                if (str_contains($link, '&view=galleryJ3x&')
                    || str_contains($link, '&view=rootgalleriesJ3x&')
                    || str_contains($link, '&view=slideshowJ3x&')) {
                    $menuLinks[$id] = [$link, $params];
                }
            }
        }

        return $menuLinks;
    }

} // class
