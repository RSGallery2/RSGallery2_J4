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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

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
    static function OldConfigItems()
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
        } catch (RuntimeException $e) {
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

    // ToDo: There may other merged operation needed instead of 1:1 copy
    static function MergeJ3xConfiguration($J3xConfigItems, $configVars)
    {
        // component parameters to array
        $compConfig = [];
        $mergedConfigItems = [];

        try {

//			foreach ($configVars as  $key => $value)
//			{
//				$compConfig [$key] = $value;
//			}
//
//			// tell about merge version
//            // ToDo: use state table
//            $compConfig ['j3x_merged_cfg_version'] = '0.1';
//
//			// J3.5 old configuration vars
//            // ToDo instead: foreach ($configVars as  $key => $value) -> if 4key in J3 use J3 otherwise use J4 version
//			$mergedConfigItems = array_merge($J3xConfigItems, $compConfig);

            foreach ($configVars as $key => $value) {
                // Is J3x item ?
                if (array_key_exists($key, $J3xConfigItems)) {
                    $compConfig [$key] = $J3xConfigItems [$key];
                } else {
                    $compConfig [$key] = $value;
                }
            }

            // ToDo: transfer special cases


            ksort($mergedConfigItems);
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'OldConfigItems: Error executing MergeJ3xConfiguration: <br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $mergedConfigItems;
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
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'J3xTableExist: Error executing query: "' . "SHOW_TABLES" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


        return $tableExist;
    }


    public function j3x_galleriesList()
    {
        $galleries = array();

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName(array('id', 'name', 'parent', 'ordering')))
                ->from('#__rsgallery2_galleries AS a')
                ->order('a.ordering ASC');

            // Get the options.
            $db->setQuery($query);

            $galleries = $db->loadObjectList();

        }
        catch (RuntimeException $e)
        {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
        }


        return $galleries;
    }

    public function j4x_galleriesList()
    {
        $galleries = array();

        try {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName(array('id', 'name', 'parent_id', 'level'))) // 'path'
                ->from('#__rsg2_galleries AS a')
                ->order('a.level ASC');

            // Get the options.
            $db->setQuery($query);

            $galleries = $db->loadObjectList();

        }
        catch (RuntimeException $e)
        {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $galleries;
    }


    public function j4_GalleriesToJ3Form($j4x_galleries)
    {
        $j3x_galleries = [];

        try {
            foreach ($j4x_galleries as $j4x_gallery) {

                // leave out root gallery in nested form
                if ($j4x_gallery->id != 1) {
                    $j3x_gallery = new \stdClass();

                    $j3x_gallery->id = $j4x_gallery->id;
                    $j3x_gallery->name = $j4x_gallery->name;

                    // parent 1 is going to root
                    if($j4x_gallery->parent_id == 1) {
                        $j4x_gallery->parent_id = 0;
                    }
                    $j3x_gallery->parent = $j4x_gallery->parent_id;
                    $j3x_gallery->ordering = $j4x_gallery->level;

                    $j3x_galleries[] = $j3x_gallery;
                }
            }
        }
        catch (RuntimeException $e)
        {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
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
        } catch (RuntimeException $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
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

        } catch (RuntimeException $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
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

        } catch (RuntimeException $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $html;
    }


}