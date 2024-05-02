<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_rsg2_gallery
 *
 * @copyright (c) 2005-2024 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Layout\FileLayout;

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

$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_rsgallery2');

$wa->usePreset('com_rsgallery2.site.galleryJ3x');

$layoutName = $params->get('images_layout');
$layoutFolder = JPATH_SITE . '/components/com_rsgallery2/layouts';

// default is 'ImagesAreaJ3x.default'
//if($layoutName == 'default') {
//
//	$layoutName = 'ImagesAreaJ3x.default';
//} else {
//
//yyy	$layoutName = $layoutName;
//}

$layout = new FileLayout($layoutName, $layoutFolder);

$displayData['images'] = $images;
$displayData['params'] = $params->toObject();
//$displayData['menuParams'] = $this->menuParams;

$displayData['isDebugSite'] = $isDebugSite;
$displayData['isDevelopSite'] = $isDevelopSite;

$displayData['gallery'] = $galleryData;
$displayData['galleryId'] = $galleryData->id;

$displaySearch = $params->get('displaySearch', false);
if ($displaySearch) {
	$searchLayout = new FileLayout('Search.search');
	// $searchData['options'] = $searchOptions ...; // gallery
}

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
        <h1><?php echo text::_('Module RSGallery2 "gallery j3x legacy" J3x view'); ?> view </h1>
        <hr>
	<?php endif; ?>

	<?php //--- display search ---------- ?>

	<?php if ($displaySearch): ?>
		<?php echo $searchLayout->render(); ?>
	<?php endif; ?>

    <?php //--- display images in J3x slideshow ---------- ?>

	<?php echo $layout->render($displayData); ?>

	<?php //--- display pagination ---------- ?>

    <?php echo $pagination->getListFooter(); ?>

</div>


<?php

if (!empty($isDebugSite))
{
	echo '<br>--------------------------- mod_rsg2_gallery end   ------------------------------<br>';
}
?>
