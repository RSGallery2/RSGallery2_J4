<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2021 - 2020
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;


HTMLHelper::_('bootstrap.carousel', '.selector');
HTMLHelper::_('bootstrap.button', '.selector');


//$images = $displayData['images'];
extract($displayData);
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Slideshow J3x layout Tasks: <br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* what happens on empty galleries/ image lists<br>'
        . '* click on image ? <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}


//--- sanitize URLs -----------------------------------

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';

// assign dummy images if not found
foreach ($images as $idx => $image) {

    // show dummy thumb on galleries with no images
    if (! empty($image->isHasNoImages))
    {
        $image->UrlOriginalFile = $noImageUrl;
        $image->UrlDisplayFiles = $noImageUrl;;
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


?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 J3x images slideshow layout</h3>
    <hr>
<?php endif; ?>

<div class="rsg2_gallery">

    <div class="rsg2__slideshowJ3x" >

        <?php if (!empty($isDebugSite)): ?>
        <?php endif; ?>

        <!-- Carousel markup goes here -->

        <div id="rsg2_carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">

                <?php
                $isActive="active";
                foreach ($images as $idx => $image) {
                    ?>

                    <div class="carousel-item <?php echo $isActive ?>" >
                        <div class="d-block w-100">
                            <img class="d-block "
                                src="<?php echo $image->UrlOriginalFile ?>"
                                alt="<?php echo $image->name ?>"
                            >
                        </div>
                    </div>

                    <?php
                    $isActive="";
                }
                ?>


                <a class="carousel-control-prev" href="#rsg2_carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#rsg2_carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

    <?php if (!empty($isDebugSite)): ?>
        <hr>
    <?php endif; ?>
        <div id="rsg2_carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">

                <?php
                $isActive="active";
                foreach ($images as $image) {
                ?>

                <div class="carousel-item <?php echo $isActive ?>" >
                        <img class="d-block w-100"
                             src="<?php echo $image->UrlOriginalFile ?>"
                             alt="<?php echo $image->name ?>"
                        >
                </div>

                <?php
                $isActive="";
                }
                ?>

            </div>
            <a class="carousel-control-prev" href="#rsg2_carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#rsg2_carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>






