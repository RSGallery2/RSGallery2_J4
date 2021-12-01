<?php
/**
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_rsg2_gallery
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Rsgallery2\Module\Rsgallery2\Site\Helper\Rsg2_galleryHelper;


// ToDo: all see gallery module 


// $app = JFactory::getApplication();

//--- Retrieve params -----------------------

$selectGallery = $params->get('SelectGallery');
$localFolder = $params->get('LocalFolder');
$folderUrl = $params->get('FolderUrl');


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

            $images = Rsg2_galleryHelper::getImageNamesOfUrl($folderUrl);

        } else {

            // Nothing selected
            $app->enqueueMessage('mod_rsg2_gallery: source path for images is not defined in module "' . $module->title . '" definition');  // . __LINE__);
        }
    }
}


// Tests
$localFolder = JPATH_ROOT . '/images/rsgallery2/2/thumbs/';
$images = Rsg2_imagesHelper::getImageNamesOfFolder($localFolder);

$folderUrl = 'http://localhost/joomla4x/images/rsgallery2/2/thumbs/';
$folderUrl = JUri::root() . '/images/rsgallery2/2/thumbs/';
$images = Rsg2_galleryHelper::getImageNamesOfUrl($folderUrl);


require ModuleHelper::getLayoutPath('mod_rsg2_gallery', $params->get('layout', 'default'));


?>

<div class="rsg2__mod rsg2__image_area">

		<h1> Module RSGallery2 "gallery" view </h1>

		<hr>

	<?php
//	echo $layoutImages->render($displayData);
	?>

</div>


