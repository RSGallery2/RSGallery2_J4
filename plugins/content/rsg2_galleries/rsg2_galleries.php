<?php
/**
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_rsg2_images
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Rsg2_imagesNamespace\Module\Rsg2_images\Site\Helper\Rsg2_imagesHelper;

// $app = JFactory::getApplication();

//--- Retrieve params -----------------------

/* ToDo: galleries list ....
/**

$selectGallery = 0;
$images = [];

// Use gallery images (?org/display/thumb ?)
if ($selectGallery > 0) {

    // ToDo: retrieve path to thumbs ? ....

} else {

    // Use local folder images ?
    if ( $localFolder) {

        $images = Rsg2_imagesHelper::getImageNamesOfFolder($localFolder);

    } else {
        // Use gallery is expected ?
        if ($folderUrl) {

            $images = Rsg2_imagesHelper::getImageNamesOfUrl($folderUrl);

        } else {

            // Nothing selected
            $app->enqueueMessage('mod_rsg2_images: source path for images is not defined in module "' . $module->title . '" definition');  // . __LINE__);
        }
    }
}


// Tests
$localFolder = JPATH_ROOT . '/images/rsgallery2/2/thumbs/';
$images = Rsg2_imagesHelper::getImageNamesOfFolder($localFolder);

$folderUrl = 'http://localhost/joomla4x/images/rsgallery2/2/thumbs/';
$folderUrl = JUri::root() . '/images/rsgallery2/2/thumbs/';
$images = Rsg2_imagesHelper::getImageNamesOfUrl($folderUrl);


require ModuleHelper::getLayoutPath('mod_rsg2_images', $params->get('layout', 'default'));
/**/
