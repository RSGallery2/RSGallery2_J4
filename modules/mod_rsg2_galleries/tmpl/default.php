<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_rsg2_images
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

//HTMLHelper::_('stylesheet', 'com_rsgallery2/maintenance.css', array('version' => 'auto', 'relative' => true));
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_rsg2_images', 'mod_rsg2_images/image_grid.css');

//echo '[PROJECT_NAME]' . $test . '<br />' . $url;
echo '[PROJECT_NAME]'  . '<br />' . $folderUrl  . '<br />' . '<br /><hr> ';

//foreach ($images as $image) {
//
//    echo $image . '<br />';
//
//}
//

//             <div class="grid-element"><img src="<?php echo $image; ? >"> </div>
?>

<div class="grid-container">
        <?php foreach ($images as $image) : ?>

            <figure class=”gallery__item gallery__item--1">
                <img src="<?php echo $image; ?>" class="gallery__img" alt="Image 1">
            </figure>

        <?php endforeach; ?>
    </div>
</div>
