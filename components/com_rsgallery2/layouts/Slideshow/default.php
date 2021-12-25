<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2021 - 2020
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//$images = $displayData['images'];
extract($displayData);
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Slideshow layout Tasks: <br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
        . '</span><br><br>';
}

?>

<?php if (!empty($isDebugSite)): ?>
    <h3>rsgallery 2 images slideshow layout</h3>
<?php endif; ?>

<div class="rsg2_gallery">

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

                    <div class="carousel-item <?php echo $isActive ?>" >
                        <div class="d-block w-100>
                            <img class="d-block " src="<?php echo $image->UrlOriginalFile ?>"
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






