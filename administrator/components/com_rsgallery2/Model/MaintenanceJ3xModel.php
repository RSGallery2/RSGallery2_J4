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
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use JTableNested;
use Joomla\CMS\Helper\ModuleHelper;

//use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\Component\Rsgallery2\Administrator\Model\GalleryTreeModel;


/**
 * Class MaintenanceJ3xModel
 * @package Joomla\Component\Rsgallery2\Administrator\Model
 *
 * Handles old J3x RSG23 data structures. Especially for transferring the config data
 *
 *
 */

class MaintenanceJ3xModel extends BaseDatabaseModel
{

    /**
     * @return array|mixed
     * @throws \Exception
     */
    static function j3xConfigItems()
    {
        $oldItems = array();

        try {
            if (MaintenanceJ3xModel::J3xConfigTableExist()) {
                // Create a new query object.
                $db = Factory::getDbo();
                $query = $db->getQuery(true);

                $query
                    //->select('*')
                    ->select($db->quoteName(array('name', 'value')))
                    ->from($db->quoteName('#__rsgallery2_config'))
                    ->order($db->quoteName('name') . ' ASC');
                $db->setQuery($query);

                $oldItems = $db->loadAssocList('name', 'value');
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'OldConfigItems: Error executing query: "' . $query . '"' . '<br>';
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
        $assistedJ3xItems = [];  // j3x tp j4x
        $assistedJ4xItems = [];  // j4x to j3x
        $mergedItems = [];
        $untouchedJ3xItems = [];
        $untouchedJ4xItems = [];

        try {

            // Manual list of assisted merges (items which need special handling for merge j3x to j4x

            $assistedItems ['testJ3xNmae'] = array ('testJ4xname', 'testJ3xValue'); // To Be defined when used
            $assistedItems ['testJ4xName'] = array ('testJ3xname', 'testJ34OldValue'); // ? new Value may be different ...To Be defined when used



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

            // untouched J4x item ?
            foreach ($j4xConfigItems as  $name => $value) {
                // Not handled manually
                if (!array_key_exists($name, $assistedJ4xItems)) {
                    if ( ! array_key_exists($name, $mergedItems)) {
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

        return array (
            $assistedJ3xItems,
            $assistedJ4xItems,
            $mergedItems,
            $untouchedJ3xItems,
            $untouchedJ4xItems
        );
    }

    // ToDo: attention a double of this function exist. Remove either of them

    static function J3xConfigTableExist()
    {
        return MaintenanceJ3xModel::J3xTableExist('#__rsgallery2_config');
    }

    static function J3xGalleriesTableExist()
    {
        return MaintenanceJ3xModel::J3xTableExist('#__rsgallery2_galleries');
    }

    static function J3xImagesTableExist()
    {
        return MaintenanceJ3xModel::J3xTableExist('#__rsgallery2_files');
    }

    static function J3xTableExist($findTable)
    {
        $tableExist = false;

        try {
            $db = Factory::getDbo();
            $db->setQuery('SHOW TABLES');
            $existingTables = $db->loadColumn();

            $checkTable = $db->replacePrefix($findTable);

            $tableExist = in_array($checkTable, $existingTables);
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'J3xTableExist: Error executing query: "' . "SHOW_TABLES" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $tableExist;
    }


    public function applyExistingJ3xData()
    {
        $isOk = true;

        //--- configuration ---------------------------------------------

        try {

            $isOkConfig = $this->copyOldJ3xConfig2J4xOptions();
            $isOk &= $isOkConfig;

            if ( ! $isOkConfig) {
                Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x configuration failed'), 'error');
            }
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        //--- galleries ---------------------------------------------

        try {
            $isOkGalleries = $this->copyAllOldJ3xGalleries2J4x();
            $isOk &= $isOkGalleries;

            if ( ! $isOkGalleries) {
                Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x galleries failed'), 'error');
            }
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        //--- images ---------------------------------------------

        try {

            $isOkImages = $this->copyAllOldJ3xImages2J4x ();
            $isOk &= $isOkImages;

            if ( ! $isOkImages) {
                Factory::getApplication()->enqueueMessage(Text::_('Error: Transfer J3x images failed'), 'error');
            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }


        // ....
        // ? ACL, assets ...




        return $isOk;
    }

    public function j3x_galleriesList()
    {
        $galleries = array();

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->select('*')
                ->from('#__rsgallery2_galleries')
                ->order('ordering ASC');

            // Get the options.
            $db->setQuery($query);

            $galleries = $db->loadObjectList();

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }


        return $galleries;
    }

    private static function cmpJ4xGalleries($aGallery, $bGallery)
    {
        $a = $aGallery->ordering;
        $b = $bGallery->ordering;

        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    public function j3x_galleriesListSorted()
    {
        $galleries = array();

        try {
            // fetch from db
            $dbGalleries = $this->j3x_galleriesList();

            // sort recursively
            $galleries = $this->j3x_galleriesSortedByParent($dbGalleries, 0, 0);
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $galleries;
    }


    public function j3x_galleriesSortedByParent($dbGalleries, $parentId=0, $level=0)
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
                    $id = $gallery->id;
                    $subGalleries [$id] = $this->j3x_galleriesSortedByParent($dbGalleries, $id, $level+1);
                }
            }

            // Sort galleries of level
            if ( count ($galleries) > 1) {
                usort($galleries, array ($this, 'cmpJ4xGalleries'));
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
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $sortedGalleries;
    }

    public function j4x_galleriesSortedByParent($dbGalleries, $parentId=0, $level=0)
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
                    $id = $gallery['id'];
                    $subGalleries [$id] = $this->j4x_galleriesSortedByParent($dbGalleries, $id, $level+1);
                }
            }

            // Sort galleries of level (Needs additional ordering from j3x gallery data
            if ( count ($galleries) > 1) {
                usort($galleries, array ($this, 'cmpJ4xGalleries'));
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
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $sortedGalleries;
    }


    // Expected: List is already is sorted
    public function setNestingNodes2J4xGalleries(&$sortedGalleries, $parentId=0, $level=0, $lastNodeIdx=1)
    {
        // $changedGalleries = [];

        try {
            // parent galleries
            $galleries = [];

            // collect galleries of given level
            foreach ($sortedGalleries as $idx => $gallery) {

                if ($gallery['parent_id'] == $parentId) {

                    $sortedGalleries [$idx]['level'] = $level;
                    $sortedGalleries [$idx]['lft'] = $lastNodeIdx;

                    $lastNodeIdx++;

                    // add node ids recursively
                    $lastNodeIdx = $this->setNestingNodes2J4xGalleries($sortedGalleries, $gallery ['id'], $level+1, $lastNodeIdx);

                    $sortedGalleries [$idx]['rgt'] = $lastNodeIdx;
                    $lastNodeIdx++;
                }
            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $lastNodeIdx;
    }

    public function j4x_galleriesList()
    {
        $galleries = array();

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent_id', 'level'))) // 'path'
                ->select('*')
                ->from('#__rsg2_galleries')
                ->order('lft ASC');

            // Get the options.
            $db->setQuery($query);

            $galleries = $db->loadObjectList();

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $galleries;
    }


    public function j4_GalleriesToJ3Form($j4x_galleries)
    {
        $j3x_galleries = [];

        try {
            foreach ($j4x_galleries as $j4x_gallery) {

                // leave out root gallery in nested form
                { // if ($j4x_gallery->id != 1) {
                    $j3x_gallery = new \stdClass();

                    $j3x_gallery->id = $j4x_gallery->id;
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
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $j3x_galleries;
    }

    public function GalleriesListAsHTML($galleries)
    {
        $html = '';

        try {

            if ( ! empty ($galleries)) {
                // all root galleries and nested ones
                $html = $this->GalleriesOfLevelHTML($galleries, 0, 0);
            }
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $html;
    }

    private function GalleriesOfLevelHTML($galleries, $parentId, $level)
    {
        $html = [];

        try {

            $galleryHTML = [];

            foreach ($galleries as $gallery) {

                if ($gallery->parent == $parentId) {

                    // html of this gallery
                    $galleryHTML [] = $this->GalleryHTML($gallery, $level);

                    $subHtml = $this->GalleriesOfLevelHTML($galleries, $gallery->id, $level+1);
                    if (!empty ($subHtml)) {
                        $galleryHTML [] = $subHtml;
                    }
                }
            }

            // surround with <ul>
            if ( ! empty ($galleryHTML)) {

                $lineStart = str_repeat(" ", 3*($parentId));

                array_unshift ($galleryHTML,  $lineStart . '<ul class="list-group">');
                $galleryHTML [] = $lineStart . '</ul>';

                $html = $galleryHTML;
            }

        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return implode($html);
    }

    // ToDo use styling for nested from https://stackoverflow.com/questions/29063244/consistent-styling-for-nested-lists-with-bootstrap
    private function GalleryHTML($gallery, $level)
    {
        $html = [];

        $lineStart = str_repeat(" ", 3*($level+1));
        $identHtml = '';
        if ($level > 0) {
            $identHtml = '<span class="text-muted">';
            $identHtml .= str_repeat('⋮&nbsp;&nbsp;&nbsp;', $level - 1);
            $identHtml .= '</span>';
            $identHtml .= '-&nbsp;';
        }

        $id = $gallery->id;
        $parent = $gallery->parent;
        $order = $gallery->ordering;
        $name = $gallery->name;

        try {

            $html = <<<EOT
$lineStart<li class="list-group-item">
$lineStart   $identHtml<span> id: </span><span>$id</span>
$lineStart   <span> parent: </span><span>$parent</span>
$lineStart   <span> order: </span><span>$order</span>
$lineStart   <span> name:</span><span>$name</span>
$lineStart</li>
EOT;

        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $html;
    }

    public function convertJ3xGalleriesToJ4x ($J3xGalleryItemsSorted) {

        $J4Galleries = [];

        try {

            // galleries of given level
            foreach ($J3xGalleryItemsSorted as $j3xGallery) {

                $J4Galleries[] = $this->convertJ3xGallery($j3xGallery);
            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $J4Galleries;
    }


    private function convertJ3xGallery ($j3x_gallery) {

        $j4x_GalleryItem = [];

        // `id` int(11) NOT NULL auto_increment,
        $j4x_GalleryItem['id'] = $j3x_gallery->id;
        $test = $j3x_gallery->id;
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
        $j4x_GalleryItem['created']= $j3x_gallery->date;
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

        return $j4x_GalleryItem;
    }

//>>>yyyy===================================================================================================

    public function copyOldJ3xConfig2J4xOptions () {

        $isOk = false;

        try {

            $configModel = new ConfigRawModel ();

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
                ) = $this->MergeJ3xConfigTestLists($j3xConfigItems, $j4xConfigItems );

            if (count($mergedItems)) {
                // ToDo: write later
                // J3x config state: 0:not upgraded, 1:upgraded,  -1:upgraded and deleted
                // Smuggle the J3x config state "upgraded:1" into the list
                //$oldConfigItems ['j3x_config_upgrade'] = "1";

                $isOk = $configModel->copyJ3xConfigItems2J4xOptions(
                    $j4xConfigItems,
                    $assistedJ3xItems,
//                        $assistedJ4xItems,
                    $mergedItems);

            } else {
                Factory::getApplication()->enqueueMessage(Text::_('No old configuration items'), 'warning');
            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }

    public function copyAllOldJ3xGalleries2J4x () {

        $isOk = false;

        try {

            $j3xGalleriesItems = $this->j3x_galleriesList();
            $isOk = $this->copyOldJ3xGalleries2J4x ($j3xGalleriesItems);

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }

    public function copySelectedOldJ3xGalleries2J4x ($selectedIds) {

        $isOk = false;

        try {

            $j3xGalleriesItems = $this->j3x_galleriesListOfIds($selectedIds);

            $isOk = $this-copyOldJ3xGalleries2J4x ($j3xGalleriesItems);

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }

    public function copyOldJ3xGalleries2J4x ($j3xGalleriesItems) {

        $isOk = false;

        try {

            // items exist ?
            if (count($j3xGalleriesItems)) {

                $j4xGalleriesItems = $this->convertJ3xGalleriesToJ4x($j3xGalleriesItems);

                // sort by parent and id recursively
                $j4xGalleryItemsSorted = $this->j4x_galleriesSortedByParent($j4xGalleriesItems, 0, 0);

                // set nested tree ()
                // last $lastNodeIdx needed for root ???
                $lastNodeIdx = $this->setNestingNodes2J4xGalleries ($j4xGalleryItemsSorted, 0, 0, 1);

                // re-init nested gallery table
                $galleryTreeModel =  new GalleryTreeModel ();
                $galleryTreeModel->reinitNestedGalleryTable($lastNodeIdx);

                $isOk = $this->writeGalleryList2Db($j4xGalleryItemsSorted);

                // ToDo: rebuild nested ....

            } else {

                Factory::getApplication()->enqueueMessage(Text::_('No items to insert into db'), 'warning');
                //Factory::getApplication()->enqueueMessage('No items to insert into db', 'warning');
            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }

    public function writeGalleryList2Db ($j4xGalleriesItems) {

        $isOk = true;

        try {

            // all gallery objects
            foreach ($j4xGalleriesItems as $j4xImageItem) {

                $isOk &= $this->writeGalleryItem2Db($j4xImageItem);

            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }

    public function writeGalleryItem2Db ($j4x_GalleryItem) {

        $isOk = false;

        try {

            // https://stackoverflow.com/questions/22373852/how-to-use-prepared-statements-in-joomla
            $columns = [];
            $values = [];

            $db = Factory::getDbo();
            $query = $db->getQuery(true);

            $columns[] = 'id';
            $values[] = 1 + (int) $j4x_GalleryItem['id'];

            $columns[] = 'name';
            $values[] = $j4x_GalleryItem['name'];
            $columns[] = 'alias';
            $values[] = $j4x_GalleryItem['alias'];
            $columns[] = 'description';
            $values[] = $j4x_GalleryItem['description'];

            $columns[] = 'note';
//            $values[] = $j4ImageItem['note'];
            $values[] = '';
            $columns[] = 'params';
            $values[] = $j4x_GalleryItem['params'];
            $columns[] = 'published';
            $values[] = $j4x_GalleryItem['published'];

//            $columns[] = 'publish_up';
//            $values[] = $j4ImageItem['publish_up'];
//            $columns[] = 'publish_down';
//            $values[] = $j4ImageItem['publish_down'];

            $columns[] = 'hits';
            $values[] = $j4x_GalleryItem['hits'];

            $columns[] = 'checked_out';
            $values[] = $j4x_GalleryItem['checked_out'];
            $columns[] = 'checked_out_time';
            $values[] = $j4x_GalleryItem['checked_out_time'];
            $columns[] = 'created';
//            $test01 = $j4ImageItem['created'];
//            $test02 = $j4ImageItem['created']->toSql();
//            $values[] = $j4ImageItem['created']->toSql();
            $values[] = $j4x_GalleryItem['created'];
            $columns[] = 'created_by';
            $values[] = $j4x_GalleryItem['created_by'];
            $columns[] = 'created_by_alias';
            $values[] = $j4x_GalleryItem['created_by_alias'];
            $columns[] = 'modified';
            $values[] = $j4x_GalleryItem['created'];
//            $columns[] = 'modified_by';
//            $values[] = $j4x_GalleryItem['modified_by'];

            $columns[] = 'parent_id';
            $values[] = 1 + (int) $j4x_GalleryItem['parent_id'];

            $columns[] = 'level';
            $values[] = 1 + (int) $j4x_GalleryItem['level'];
//            $columns[] = 'path';
//            $values[] = $j4x_GalleryItem['path'];
            $columns[] = 'lft';
            $values[] = $j4x_GalleryItem['lft'];
            $columns[] = 'rgt';
            $values[] = $j4x_GalleryItem['rgt'];

//            $columns[] = 'approved';
//            $values[] = $j4x_GalleryItem['approved'];

            $columns[] = 'asset_id';
            $values[] = $j4x_GalleryItem['asset_id'];
//            $columns[] = 'access';
//            $values[] = $j4ImageItem['access'];

            // Prepare the insert query.
            $query
                ->insert($db->quoteName('#__rsg2_galleries')) //make sure you keep #__
                ->columns($db->quoteName($columns))
                ->values(implode(',', $db->quote($values)));
            $db->setQuery($query);
            $db->execute();

            $isOk = true;
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }



//<<<yyyy===================================================================================================

    public function j3x_imagesList()
    {
        $images = array();

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->select('*')
                ->from('#__rsgallery2_files')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $images = $db->loadObjectList();

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }


        return $images;
    }

   public function j3x_imagesListOfIds($selectedIds)
    {
        $images = array();

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
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

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }


        return $images;
    }

    public function j4x_imagesList()
    {
        $images = array();

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
//                ->select($db->quoteName(array('id', 'name', 'parent_id', 'level'))) // 'path'
                ->select('*')
                ->from('#__rsg2_images')
                ->order('id ASC');

            // Get the options.
            $db->setQuery($query);

            $images = $db->loadObjectList();

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $images;
    }

    public function copyAllOldJ3xImages2J4x () {

        $isOk = false;

        try {

            $isOk = $this->resetImagesTable();

            $j3xImageItems = $this->j3x_imagesList();

            $isOk &= $this->copyOldJ3xImages2J4x ($j3xImageItems);

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }

    public function copySelectedOldJ3xImages2J4x ($selectedIds) {

        $isOk = false;

        try {

            $j3xImageItems = $this->j3x_imagesListOfIds($selectedIds);

            $isOk = $this-copyOldJ3xImages2J4x ($j3xImageItems);

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }

    public function copyOldJ3xImages2J4x ($j3xImageItems) {

        $isOk = false;

        try {

            // items exist ?
            if (count($j3xImageItems)) {

                $j4ImageItems = $this->convertJ3xImagesToJ4x($j3xImageItems);

                $isOk = $this->writeImageList2Db($j4ImageItems);
            } else {

                Factory::getApplication()->enqueueMessage(Text::_('No items to insert into db'), 'warning');
                //Factory::getApplication()->enqueueMessage('No items to insert into db', 'warning');
            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }


    public function convertJ3xImagesToJ4x ($J3xImagesItems) {

        $j4ImageItems = [];

        try {

            // galleries of given level
            foreach ($J3xImagesItems as $j3xImage) {

                $j4ImageItems[] = $this->convertJ3xImage($j3xImage);

            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $j4ImageItems;
    }


    public function writeImageList2Db ($j4ImageItems) {

        $isOk = true;

        try {

            // all image objects
            foreach ($j4ImageItems as $j4xImageItem) {

                $isOk &= $this->writeImageItem2Db($j4xImageItem);

            }

        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }


    public function writeImageItem2Db ($j4ImageItem) {

        $isOk = false;

        try {

            // https://stackoverflow.com/questions/22373852/how-to-use-prepared-statements-in-joomla
            $columns = [];
            $values = [];

            $db = Factory::getDbo();
            $query = $db->getQuery(true);

            $columns[] = 'id';
            $values[] = $j4ImageItem['id'];
            $columns[] = 'name';
            $values[] = $j4ImageItem['name'];
            $columns[] = 'alias';
            $values[] = $j4ImageItem['alias'];
            $columns[] = 'description';
            $values[] = $j4ImageItem['description'];

            $columns[] = 'gallery_id';
            $values[] = 1 + (int) $j4ImageItem['gallery_id'];
            $columns[] = 'title';
            $values[] = $j4ImageItem['title'];

//            $columns[] = 'note';
//            $values[] = $j4ImageItem['note'];
            $columns[] = 'params';
            $values[] = $j4ImageItem['params'];
            $columns[] = 'published';
            $values[] = $j4ImageItem['published'];

//            $columns[] = 'publish_up';
//            $values[] = $j4ImageItem['publish_up'];
//            $columns[] = 'publish_down';
//            $values[] = $j4ImageItem['publish_down'];

            $columns[] = 'hits';
            $values[] = $j4ImageItem['hits'];
            $columns[] = 'rating';
            $values[] = $j4ImageItem['rating'];
            $columns[] = 'votes';
            $values[] = $j4ImageItem['votes'];
            $columns[] = 'comments';
            $values[] = $j4ImageItem['comments'];

            $columns[] = 'checked_out';
            $values[] = $j4ImageItem['checked_out'];
            $columns[] = 'checked_out_time';
            $values[] = $j4ImageItem['checked_out_time'];
            $columns[] = 'created';
//            $test01 = $j4ImageItem['created'];
//            $test02 = $j4ImageItem['created']->toSql();
//            $values[] = $j4ImageItem['created']->toSql();
            $values[] = $j4ImageItem['created'];
            $columns[] = 'created_by';
            $values[] = $j4ImageItem['created_by'];
            $columns[] = 'created_by_alias';
            $values[] = $j4ImageItem['created_by_alias'];
            $columns[] = 'modified';
            $values[] = $j4ImageItem['modified'];
            $columns[] = 'modified_by';
            $values[] = $j4ImageItem['modified_by'];

            $columns[] = 'ordering';
            $values[] = $j4ImageItem['ordering'];
            $columns[] = 'approved';
            $values[] = $j4ImageItem['approved'];

            $columns[] = 'asset_id';
            $values[] = $j4ImageItem['asset_id'];
//            $columns[] = 'access';
//            $values[] = $j4ImageItem['access'];

            // Prepare the insert query.
            $query
                ->insert($db->quoteName('#__rsg2_images')) //make sure you keep #__
                ->columns($db->quoteName($columns))
                ->values(implode(',', $db->quote($values)));
            $db->setQuery($query);
            $db->execute();

            $isOk = true;
        }
        catch (\RuntimeException $e)
        {
            Factory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $isOk;
    }


    private function convertJ3xImage ($j3x_image) {

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
        $j4_imageItem['gallery_id'] = $j3x_image->gallery_id;
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
        $j4_imageItem['modified'] = $j3x_image->date;;
        //`modified_by` int(10) unsigned NOT NULL DEFAULT 0,
        $j4_imageItem['modified_by'] = $j3x_image->userid;;

        //`ordering` int(9) unsigned NOT NULL default '0',
        $j4_imageItem['ordering'] = $j3x_image->ordering;
        //`approved` tinyint(1) unsigned NOT NULL default '1',
        $j4_imageItem['approved'] = $j3x_image->approved;

        //`asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
        $j4_imageItem['asset_id'] = $j3x_image->asset_id;
        //`access` int(10) NOT NULL DEFAULT 0,
        $j4_imageItem['access'] = $j3x_image->access;
        return $j4_imageItem;
    }

    /**
     * Reset image table to empty state
     * Deletes all galleries and initialises the root of the nested tree
     *
     * @return bool
     *
     * @since version
     */
    public static function resetImagesTable()
    {
        $isImagesReset = false;

        $id_galleries = '#__rsg2_images';

        try {
            $db = Factory::getDbo();

            //--- delete old rows -----------------------------------------------

            $query = $db->getQuery(true);

            $query->delete($db->quoteName($id_galleries));
            // all rows
            //$query->where($conditions);

            $db->setQuery($query);

            $isImagesReset = $db->execute();

        } //catch (\RuntimeException $e)
        catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage() . ' from resetImagesTable');
        }

        return $isImagesReset;
    }

    /**/
}