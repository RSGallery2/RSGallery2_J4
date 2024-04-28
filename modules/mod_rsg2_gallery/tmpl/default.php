<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_rsg2_gallery
 *
 * @copyright (c) 2005-2024 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
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

if (!empty($isDebugSite))
{
	echo '<br><br>--------------------------- mod_rsg2_gallery start ------------------------------<br>';
}

// message on empty data or other
if ( ! empty ($msg)) {

	echo $msg;

	if (!empty($isDebugSite))
	{
		echo $msg . '<br>';
	}

	return;
}

$layoutName = $this->getLayout();
$layoutFolder = JPATH_SITE . '/components/com_rsgallery2/layouts';

$layout = new FileLayout($layoutName, $layoutFolder);

$displayData['images'] = $images;
$displayData['params'] = $params->toObject();
//$displayData['menuParams'] = $this->menuParams;

$displayData['isDebugSite'] = $isDebugSite;
$displayData['isDevelopSite'] = $isDevelopSite;

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

<div class="rsg2_x_form rsg2__images_area">

	<?php if (!empty($isDebugSite)): ?>
        <h1> Module RSGallery2 "gallery images" J3x view </h1>
        <hr>
	<?php endif; ?>

	<?php //--- display images in J3x slideshow ---------- ?>

	<?php echo $layout->render($displayData); ?>

</div>


<?php

if (!empty($isDebugSite))
{
	echo '<br>--------------------------- mod_rsg2_gallery end   ------------------------------<br>';
}
?>
