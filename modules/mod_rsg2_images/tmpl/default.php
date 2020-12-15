<?php
/**
 * @package     RSGallery2
 * @subpackage  mod_rsg2_images
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

if (empty ($list))
{
    echo " mod_rsg_images/imagesModel:getitems: image list empty";

	return;
}

?>

<div class="mod-articlesnews newsflash">
	<?php foreach ($list as $item) : ?>
		<?php require ModuleHelper::getLayoutPath('mod_articles_news', '_item'); ?>
	<?php endforeach; ?>
</div>

<?php

//    //HTMLHelper::_('stylesheet', 'com_rsgallery2/maintenance.css', array('version' => 'auto', 'relative' => true));
//    /** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
//    $wa = $app->getDocument()->getWebAssetManager();
//    $wa->registerAndUseStyle('mod_rsg2_images', 'mod_rsg2_images/image_grid.css');
//    
//    //echo '[PROJECT_NAME]' . $test . '<br />' . $url;
//    echo '[PROJECT_NAME]'  . '<br />' . $folderUrl  . '<br />' . '<br /><hr> ';
//    
//    //foreach ($images as $image) {
//    //
//    //    echo $image . '<br />';
//    //
//    //}
//    //
//    
//    //             <div class="grid-element"><img src="<?php echo $image; ? >"> </div>
//    ?>
<!--    -->
<!--    <div class="grid-container">-->
<!--            --><?php //foreach ($images as $image) : ?>
<!--    -->
<!--                <figure class=”gallery__item gallery__item--1">-->
<!--                    <img src="--><?php //echo $image; ?><!--" class="gallery__img" alt="Image 1">-->
<!--                </figure>-->
<!--    -->
<!--            --><?php //endforeach; ?>
<!--        </div>-->
<!--    </div>-->
