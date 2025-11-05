<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Helper\ModuleHelper;
use Rsg2_imagesNamespace\Module\Rsg2_images\Site\Helper\Rsg2_galleriesHelper;


// ToDo: all see images module 


// $app = Factory::getApplication();

//--- Retrieve params -----------------------

$selectGallery = $params->get('SelectGallery');
$localFolder   = $params->get('LocalFolder');
$folderUrl     = $params->get('FolderUrl');


$images = [];

/**
 * // Use gallery images (?org/display/thumb ?)
 * if ($selectGallery > 0) {
 *
 * // ToDo: retrieve path to thumbs ? ....
 *
 * } else {
 *
 * // Use local folder images ?
 * if ( $localFolder) {
 *
 * $images = Rsg2_galleriesHelper::getImageNamesOfFolder($localFolder);
 *
 * } else {
 * // Use gallery is expected ?
 * if ($folderUrl) {
 *
 * $images = Rsg2_galleriesHelper::getImageNamesOfUrl($folderUrl);
 *
 * } else {
 *
 * // Nothing selected
 * $app->enqueueMessage('mod_rsg2_images: source path for images is not defined in module "' . $module->title . '" definition');  // . __LINE__);
 * }
 * }
 * }
 *
 *
 * // Tests
 * $localFolder = JPATH_ROOT . '/images/rsgallery2/2/thumbs/';
 * $images = Rsg2_galleriesHelper::getImageNamesOfFolder($localFolder);
 *
 * $folderUrl = 'http://localhost/joomla4x/images/rsgallery2/2/thumbs/';
 * $folderUrl = \Joomla\CMS\Uri\Uri::root() . '/images/rsgallery2/2/thumbs/';
 * $images = Rsg2_galleriesHelper::getImageNamesOfUrl($folderUrl);
 *
 *
 * require ModuleHelper::getLayoutPath('mod_rsg2_images', $params->get('layout', 'default'));
 /**/

?>

<div class="rsg2__mod rsg2__image_area">

    <h1> Module RSGallery2 "galleries" view </h1>

    <hr>

    <?php
    //	echo $layoutImages->render($displayData);
    ?>

</div>


