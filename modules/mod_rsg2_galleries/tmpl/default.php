<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_rsg2_images
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Layout\FileLayout;

\defined('_JEXEC') or die;


if (!empty($isDebugSite)) {
	echo '<br><br>--------------------------- mod_rsg2_galleries start ------------------------------<br>';
}

// message on empty data or other
if ( ! empty ($msg)) {
	echo $msg;

    if (!empty($isDebugSite)) {
		echo $msg . '<br>';
	}

	return;
}

$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_rsgallery2');

$wa->usePreset('com_rsgallery2.site.galleryJ3x');

$layoutName = $params->get('images_layout');
$layoutFolder = JPATH_SITE . '/components/com_rsgallery2/layouts';


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

if (!empty($isDebugSite)) {
	echo '<br>--------------------------- mod_rsg2_galleries end   ------------------------------<br>';
}
