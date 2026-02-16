<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

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

if (!empty($isDebugSite)) {
    echo '<br><br>--------------------------- mod_rsg2_gallery start ------------------------------<br>';
}

// message on empty data or other
if (!empty($msg)) {
    echo $msg;

    if (!empty($isDebugSite)) {
        echo $msg . '<br>';
    }

    return;
}

//--- Js/css -------------------------------------------

$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_rsgallery2');
$wa->usePreset('com_rsgallery2.site.galleryJ3x');


$layoutName   = $params->get('images_layout');
if ($layoutName == 'default') {
    $layoutName = 'ImagesAreaJ3x.default';
}
$layoutFolder = JPATH_SITE . '/components/com_rsgallery2/layouts';

$layout = new FileLayout($layoutName, $layoutFolder);

//--- layout data -----------------------------------------------

$displayData['images'] = $images;
$displayData['params'] = $params->toObject();
//$displayData['menuParams'] = $this->menuParams;

$displayData['isDebugSite']   = $isDebugSite;
$displayData['isDevelopSite'] = $isDevelopSite;

$displayData['gallery']   = $galleryData;
$displayData['galleryId'] = $galleryData->id;

$displaySearch = $params->get('displaySearch', false);
if ($displaySearch) {
    $searchLayout = new FileLayout('Search.search');
    // $searchData['options'] = $searchOptions ...; // gallery
}


//--- create html data -----------------------------------------------

?>

<div class="rsg2_x_form rsg2__images_area">

    <?php if (!empty($isDebugSite)) : ?>
        <h1><?php echo Text::_('Module RSGallery2 "gallery j3x legacy" J3x view'); ?> view </h1>
        <hr>
    <?php endif; ?>

    <?php //--- display search ---------- ?>

    <?php if ($displaySearch) : ?>
        <?php echo $searchLayout->render(); ?>
    <?php endif; ?>

    <?php //--- display images in J3x slideshow ---------- ?>

    <?php echo $layout->render($displayData); ?>

    <?php //--- display pagination ---------- ?>

    <?php echo $pagination->getListFooter(); ?>

</div>


<?php

if (!empty($isDebugSite)) {
    echo '<br>--------------------------- mod_rsg2_gallery end   ------------------------------<br>';
}
?>
