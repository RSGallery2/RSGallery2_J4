<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Tmpl\Rootgalleriesj3x;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;


$this->document->getWebAssetManager()->usePreset('com_rsgallery2.site.imageWallJ3x');

//--- retrieve img wall layout --------------------------------

$params = $this->params;

$layoutSelection = $params->get('layout_img_wall');
$layoutName      = 'Imagewall.' . $layoutSelection;

$displayData['images'] = $this->items;
$gallery = $this->gallery;

if (!empty($this->isDebugSite)) {
    echo '--- latestImages (3)' . '-------------------------------' . '<br>';
}

// echo $layout->render($displayData);

?>

<form id="rsg2_root_image_wall__form" action="<?php
echo Route::_('index.php?option=com_rsgallery2&view=rootgalleriesj3x'); ?>"
      method="post" class="form-validate form-horizontal well">

    <?php if (!empty($this->isDebugSite)) : ?>
        <h1><?php echo text::_('RSGallery2 imagewallj3x'); ?> view </h1>
        <hr>
    <?php endif; ?>

    <?php if (!empty($gallery)) : ?>
        <?php if ($params->get('gallery_show_title')) : ?>
            <h2>
                <span class="rsg_gallery_title"><?php echo $gallery->name ?></span>
            </h2>
        <?php endif; ?>

        <?php if ($params->get('gallery_show_description')) : ?>
            <div class="intro_text"><p><?php echo $gallery->description ?></p></div>
        <?php endif; ?>

        <?php if ($params->get('gallery_show_slideshow')) : ?>
            <div class="rsg2_slideshow_link">
                <a href="<?php echo $gallery->UrlSlideshow; ?>">
                    <?php echo ' ' . Text::_('COM_RSGALLERY2_SLIDESHOW'); ?>
                </a>
            </div>
        <?php endif; ?>

<!--        <div class="rsg2-clr"></div>-->

        <?php //--- image wall ------------------------------------------ ?>
        <?php
        $layout = new FileLayout($layoutName);
        echo $layout->render($displayData);
        ?>

    <?php else : ?>
        <h2><?php
            //echo Text::_('Gallery (ID ' . $galleryId . ') not defined');
            echo Text::_("Gallery name not defined in this situation");
            ?> </h2>
    <?php endif; ?>


</form>
