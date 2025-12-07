<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2021-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Layouts\ImagesFlexJ3x;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/*---------------------------------------------------
image display by flex arrangement
---------------------------------------------------*/

extract($displayData);

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout Images flex <br>'
        . '* <br>'
//  . '* <br>'
//  . '* <br>'
//  . '* <br>'
//  . '* <br>'
        . '</span><br><br>';
}

//--- sanitize URLs -----------------------------------

if (!isset($images)) {
    $images = [];
}

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';

//--- assign dummy images if not found -----------------------------------

if (!empty($images)) {
    foreach ($images as $idx => $image) {
        // show dummy thumb on galleries with no images
        if (!empty($image->isHasNoImages)) {
            $image->UrlOriginalFile = $noImageUrl;
            $image->UrlDisplayFiles = $noImageUrl;
            $image->UrlThumbFile = $noImageUrl;
        }

//    else {
//
//        if (!$image->isOriginalFileExist) {
//            $image->UrlOriginalFile = $missingUrl;
//            ;
//        }
//
//        if (!$image->isDisplayFileExist) {
//            $image->UrlDisplayFiles = $missingUrl;;
//        }
//
//        if (!$image->isThumbFileExist) {
//            $image->UrlThumbFile = $missingUrl;
//        }
//
//    }
    }
}

// max_columns_in_images_view
$cols = $params->max_columns_in_images_view_j3x;
if ($cols < 2) {
    $cols = 2;
}

?>

<?php if (!empty($isDebugSite)) : ?>
    <h5>RSGallery2 j3x images area J3x layout II</h5>
    <hr>
<?php endif; ?>

<div id="rsg2_gallery" class="rsg2">

    <?php if (!empty($gallery)) : ?>
        <?php if ($params->gallery_show_title) : ?>
            <h2>
                <span class="rsg_gallery_title"><?php echo $gallery->name ?></span>
            </h2>
        <?php endif; ?>

        <?php if ($params->gallery_show_description) : ?>
            <div class="intro_text"><p><?php echo $gallery->description ?></p></div>
        <?php endif; ?>

        <div class="rsg2-clr"></div>

        <?php if ($params->gallery_show_slideshow) : ?>
            <div class="rsg2_slideshow_link">
                <a href="<?php echo $gallery->UrlSlideshow; ?>">
                    <?php echo ' ' . Text::_('COM_RSGALLERY2_SLIDESHOW'); ?>
                </a>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <h2><?php
            //echo Text::_('Gallery (ID ' . $galleryId . ') not defined');
            echo Text::_('Gallery (name) not defined in this situation');
        ?> </h2>
    <?php endif; ?>


    <div class="rsg2-flex-thumbs">

        <?php // <div class="d-flex p-2 justify-content-center align-items-center ?  flex-flow"> ?>
        <div class="d-flex p-2 justify-content-around align-items-center flex-wrap">

            <?php
            foreach ($images as $idx => $image) {
                ?>
                <div class="rsg2-flex-thumbs-row">
                    <div class="rsg2-flex-thumb  pb-4 mx-auto">

                        <div class="d-flex justify-content-center ">
                            <div class="shadow-box">
                                <div class="img-shadow">
                                    <a href="<?php echo $image->UrlImageAsInline ?>">
                                        <img src="<?php echo $image->UrlThumbFile ?>" alt="<?php echo $image->name; ?>">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center ">
                            <span class="rsg2_thumb_name">
                                <?php echo $image->title ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php
            }
            ?>

        </div>
    </div>

    <div class="pagination">
        <?php
        if (isset($pagination)) {
            echo $pagination->getListFooter();
        }
        ?>
    </div>

    <?php
/**
     * <?php if (!empty($isDebugSite)): ?>
     * <h3>RSGallery2 j3x images area J3x layout III</h3>
     * <hr>
     * <?php endif; ?>
     *
     * <div class="rsg2_gallery__images" id="gallery"  data-bs-toggle="modal" data-bs-target="#exampleModal">
     *
     * <?php * foreach ($images as $idx => $image) {
     * ?>
     * <figure>
     * <img src="<?php echo $image->UrlThumbFile ?>"
     * alt="<?php echo $image->name; ?>"
     * class="img-thumbnail rsg2_gallery__images_image"
     * data-target="#rsg2_carousel"
     * data-slide-to="<?php echo $idx ?>"bs-
     * >
     * <figcaption><?php echo $image->title; ?></figcaption>
     * </figure>
     * <?php * }
     * ?>
     * </div>
     *
     * <!-- Modal markup: https://getbootstrap.com/docs/4.4/components/modal/ -->
     * <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
     * <div class="modal-dialog" role="document">
     * <div class="modal-content">
     * <div class="modal-header">
     * <button type="button" class="close" data-dismiss="modal" aria-label="Close">
     * <span aria-hidden="true">×</span>
     * </button>
     * </div>
     * <div class="modal-body">
     *
     * <!-- Carousel markup goes here -->
     *
     * <div id="rsg2_carousel" class="carousel slide" data-ride="carousel">
     *
     * <div class="carousel-inner">
     *
     * <?php * $isActive="active";
     * foreach ($images as $image) {
     * ?>
     *
     * <div class="carousel-item <?php echo $isActive; ?>" >
     * <div class="d-flex align-items-center justify-content-center min-vw-100  min-vh-100">
     * <!--                                        <img class="d-block " src="--><?php //echo $image->UrlDisplayFiles[400] ?><!--"-->
     * <img class="d-block " src="<?php echo $image->UrlOriginalFile ?>"
     * alt="<?php echo $image->nam; ?>"
     * >
     * </div>
     * </div>
     *
     * <?php * $isActive="";
     * }
     * ?>
     *
     *
     * <a class="carousel-control-prev" href="#rsg2_carousel" role="button" data-slide="prev">
     * <span class="carousel-control-prev-icon" aria-hidden="true"></span>
     * <span class="sr-only">Previous</span>
     * </a>
     * <a class="carousel-control-next" href="#rsg2_carousel" role="button" data-slide="next">
     * <span class="carousel-control-next-icon" aria-hidden="true"></span>
     * <span class="sr-only">Next</span>
     * </a>
     * </div>
     * </div>
     *
     * <div class="modal-footer">
     * <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
     * </div>
     * </div>
     * </div>
     * </div>
     * </div>
     *
     */
    ?>
</div>
