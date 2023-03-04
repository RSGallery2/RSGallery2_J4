<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright (c) 2021-2023 RSGallery2 Team
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
        . 'Tasks: layout slideshow<br>'
        . 'Slideshow layout Tasks: <br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* <br>'
        . '* checkout Flexible CSS Carousel CSS-Tricks: https://css-tricks.com/a-super-flexible-css-carousel-enhanced-with-javascript-navigation/<br>'
        . '* checkout Flexible CSS Carousel CSS-Tricks: https://css-tricks.com/css-only-carousel/<br>'
        . '* checkout Flexible CSS Carousel CSS-Tricks: https://css-tricks.com/creating-responsive-touch-friendly-carousels-with-flickity/<br>'
//        . '* <br>'
//        . '* <br>'
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
    <h3>RSGallery2 images slideshow layout</h3>
    <div class="p-3 mb-2 bg-success bg-gradient text-white">Test with indicators</div>
    <hr>
<?php endif; ?>

<div class="rsg2_gallery">

    <?php /**/ ?>
    <h3>Test slideshow I</h3>
    <hr>

    <!-- The slideshow/carousel -->
    <div id="demo1" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo1" data-bs-slide-to="0" class="active"
                    style="color:black; background-color:red;"
            ></button>
            <button type="button" data-bs-target="#demo1" data-bs-slide-to="1"
                    style="color:black; background-color:red;"
            ></button>
            <button type="button" data-bs-target="#demo1" data-bs-slide-to="2"
                    style="color:black; background-color:red;"
            ></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img
                    src="http://127.0.0.1/joomla4x/images/rsgallery2/2/original/DSC_5501.JPG" alt="DSC_5501.JPG"
                    class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img
                        src="http://127.0.0.1/joomla4x/images/rsgallery2/2/original/DSC_5502.JPG" alt="DSC_5502.JPG"
                    class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img
                        src="http://127.0.0.1/joomla4x/images/rsgallery2/2/original/DSC_5503.JPG" alt="DSC_5503.JPG"
                    class="d-block w-100">
            </div>
        </div>
        <!-- Left and right controls/icons -->
        <button class="carousel-control-prev"
                style="color:black; background-color:red;"
                type="button" data-bs-target="#demo1" data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next"
                style="color:black; background-color:red;"
                type="button" data-bs-target="#demo1" data-bs-slide="next"
        >
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <hr>
    <?php /**/ ?>

    <?php /**/ ?>
    <h3>Test slideshow II WWW3 + data </h3>
    <hr>

    <!-- The slideshow/carousel -->
    <div id="demo2" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo2" data-bs-slide-to="0" class="active"
                    style="color:black; background-color:red;"
            ></button>
            <button type="button" data-bs-target="#demo2" data-bs-slide-to="1"
                    style="color:black; background-color:red;"
            ></button>
            <button type="button" data-bs-target="#demo2" data-bs-slide-to="2"
                    style="color:black; background-color:red;"
            ></button>
        </div>

        <div class="carousel-inner">
            <?php
            $isActive="active";

            foreach ($images as $image) {
                ?>

                <div class="carousel-item <?php echo $isActive; ?>" >
                    <div class="d-block w-100">
                        <img class="d-block "
                             src="<?php echo $image->UrlDisplayFile ?>"
                             alt="<?php echo $image->name; ?>"
                        >
                    </div>
                </div>

                <?php
                $isActive="";
            }
            ?>

        </div>
        <!-- Left and right controls/icons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#demo2" data-bs-slide="prev"
                style="color:black; background-color:red;"
        >
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo2" data-bs-slide="next"
                style="color:black; background-color:red;"
        >
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <hr>
    <?php /**/ ?>


    <?php /** ?>
    <div class="rsg2__slideshow" >

        <?php if (!empty($isDebugSite)): ?>
        <hr>
        <?php endif; ?>

        <!-- Carousel markup goes here -->

        <div id="rsg2_carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">

                <?php
                $isActive="active";

                foreach ($images as $image) {
                    ?>

                    <div class="carousel-item <?php echo $isActive; ?>" >
                        <div class="d-block w-100">
                            <img class="d-block "
                                 src="<?php echo $image->UrlOriginalFile ?>"
                                 alt="<?php echo $image->name; ?>"
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
    </div>

    <div class="rsg2__slideshow" >

        <?php if (!empty($isDebugSite)): ?>
            <h3>Test slideshow III</h3>
            <hr>
        <?php endif; ?>

        <div id="rsg2_carousel2" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">

                <?php
                $isActive="active";
                foreach ($images as $image) {
                ?>

                <div class="carousel-item <?php echo $isActive; ?>" >
                        <img class="d-block w-100"
                             src="<?php echo $image->UrlOriginalFile ?>"
                             alt="<?php echo $image->name; ?>"
                        >
                </div>

                <?php
                $isActive="";
                }
                ?>

            </div>
            <a class="carousel-control-prev" href="#rsg2_carousel2" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#rsg2_carousel2" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <?php /**/ ?>
</div>






