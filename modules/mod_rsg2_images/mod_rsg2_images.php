<?php
/**
/**
 * @package     RSGallery2
 * @subpackage  mod_rsg2_images
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Rsgallery2\Module\Rsg2_images\Site\Helper\Rsg2_imagesHelper;

echo "mod_rsg2_images.php";

require ModuleHelper::getLayoutPath('mod_rsg2_images', $params->get('layout', 'default'));

$list = Rsg2_imagesHelper::getList($params);
$rsgConfig = JComponentHelper::getParams( 'com_content' );
//$show_date = $rsgConfig->get( 'show_create_date' );

// $lang = Factory::getLanguage();
$lang =  $app->getLanguage();
$lang->load('com_rsgallery2', JPATH_SITE, 'en-GB', true);
$lang->load('com_rsgallery2', JPATH_SITE, $lang->getTag(), true);

// require ModuleHelper::getLayoutPath('mod_rsg2_images', $params->get('layout', 'default'));
$app  = JFactory::getApplication('site');
$ctrl = JControllerLegacy::getInstance('MyCompModel');
$view = $ctrl->getView('Myview', $vFormat, 'MyCompView');


//require ModuleHelper::getLayoutPath('mod_rsg2_images', $params->get('layout', 'horizontal'));


//        // $app = JFactory::getApplication();
//        
//        //--- Retrieve params -----------------------
//
//        $selectGallery = $params->get('SelectGallery');
//        $localFolder = $params->get('LocalFolder');
//        $folderUrl = $params->get('FolderUrl');
//
//
//        $images = [];
//
//        // Use gallery images (?org/display/thumb ?)
//        if ($selectGallery > 0) {
//
//            // ToDo: retrieve path to thumbs ? ....
//
//        } else {
//
//            // Use local folder images ?
//            if ( $localFolder) {
//
//                $images = Rsg2_imagesHelper::getImageNamesOfFolder($localFolder);
//
//            } else {
//                // Use gallery is expected ?
//                if ($folderUrl) {
//
//                    $images = Rsg2_imagesHelper::getImageNamesOfUrl($folderUrl);
//
//                } else {
//
//                    // Nothing selected
//                    $app->enqueueMessage('mod_rsg2_images: source path for images is not defined in module "' . $module->title . '" definition');  // . __LINE__);
//                }
//            }
//        }
//
//
//        // Tests
//        $localFolder = JPATH_ROOT . '/images/rsgallery2/2/thumbs/';
//        $images = Rsg2_imagesHelper::getImageNamesOfFolder($localFolder);
//
//        $folderUrl = 'http://localhost/joomla4x/images/rsgallery2/2/thumbs/';
//        $folderUrl = JURI::root() . '/images/rsgallery2/2/thumbs/';
//        $images = Rsg2_imagesHelper::getImageNamesOfUrl($folderUrl);
//
