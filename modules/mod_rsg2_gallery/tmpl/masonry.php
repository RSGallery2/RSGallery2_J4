<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

// global $msg;

////HTMLHelper::_('stylesheet', 'com_rsgallery2/maintenance.css', array('version' => 'auto', 'relative' => true));
///** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
//$wa = $app->getDocument()->getWebAssetManager();
//$wa->registerAndUseStyle('mod_rsg2_gallery', 'mod_rsg2_gallery/image_grid.css');
//
////echo '[PROJECT_NAME]' . $test . '<br />' . $url;
//echo '[PROJECT_NAME]'  . '<br />' . $folderUrl  . '<br />' . '<br /><hr> ';
//
////foreach ($images as $image) {
////
////    echo $image . '<br />';
////
////}
////

//             <div class="grid-element"><img src="<?php echo $image; ? >"> </div>

echo '<br><br>--------------------------- mod_rsg2_gallery masonry start ------------------------------<br>';

if (!empty ($msg)) {
    echo $msg;
    // return;
}

echo '<br>--------------------------- mod_rsg2_gallery masonry end   ------------------------------<br>';

?>

<!--<div class="grid-container">-->
<!--        --><?php //foreach ($images as $image) : ?>
<!---->
<!--            <figure class=”gallery__item gallery__item--1">-->
<!--                <img src="--><?php //echo $image; ?><!--" class="gallery__img" alt="Image 1">-->
<!--            </figure>-->
<!---->
<!--        --><?php //endforeach; ?>
<!--    </div>-->
<!--</div>-->
