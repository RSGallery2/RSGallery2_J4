<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2021-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/*---------------------------------------------------
Show slideshow from bootstrap
---------------------------------------------------*/

HTMLHelper::_('bootstrap.carousel', '.selector');
HTMLHelper::_('bootstrap.button', '.selector');


extract($displayData);

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout slideshowJ3x<br>'
        . '* html aria-label ... <br>'
        . '* View image name<br>'
        . '* what happens on empty galleries/ image lists<br>'
        . '* click on image ?==> big screen no arrows modal <br>'
        . '* center small images<br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}

//--- sanitize URLs -----------------------------------

if (!isset($images)) {
    $images = [];
}

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';

// echo json_encode($params);

//$interval = $params->get('interval', 6000);
//$max_thumbs_in_root_galleries_view_j3x = $params['max_thumbs_in_root_galleries_view_j3x'];
// $interval = $params['interval'];

$auto_start = $params->auto_start;
$interval   = $params->interval;
$showArrows = $params->showArrows;
$darkMode   = $params->darkMode;

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
        //            $image->UrlDisplayFile; = $missingUrl;
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

// allow:
?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 J3x images slideshow layout</h3>
    <hr>
<?php endif; ?>

<style>

    /* // object-fit: fill, contain, **cover**, none, scale-down /**/
    /* // object-position: center, right top, left bottom, 250px 125px /**/


    .rsg2_x__slideshowJ3x {
        /*max-width:100%*/
    }

    /**
	.carousel-inner  {
		margin: 0 auto;
		border: 5px solid lightgreen;

		width: 400px;
		height: 300px;

	}
	/**/

    /**/
    .carousel-inner {
        /*margin: 0 auto;*/
        /*border: 5px solid lightcyan;*/
    }
    /**/

    /**/
    .carousel-inner > .carousel-item {
        /*margin: 0 auto; Bad */
        /*border: 5px solid lightblue;*/
    }
    /**/

    /**/
    .carousel-inner > .carousel-item > img {
        /*margin: 0 auto;*/
        /*border: 5px solid darkred;*/
    }
    /**/

    .carousel-control-prev {
    }

    .carousel-control-prev-icon {
        background-color: grey;
    }

    .carousel-control-next-icon {
        background-color: grey;
    }

    .carousel-item img {
        object-fit: contain;
        /*object-fit: scale-down;*/
        object-position: center;

        height: 50vh;
        width: 100vh;

        overflow: hidden;
        margin: 0 auto;
    }

</style>

<div class="rsg2_slideshow_box">
    <div class="rsg2_x__slideshowJ3x">

        <?php if (!empty($isDebugSite)): ?>
            rsg2_x__slideshowJ3x<br>
        <?php endif; ?>

        <?php
        $uniqueId = substr(md5(uniqid()), 0, 12);;
        ?>

        <div id="rsg2_carousel_<?php echo $uniqueId; ?>"
             class="carousel slide <?php if ($darkMode): ?>carousel-dark<?php endif; ?>"
            <?php if ($auto_start): ?>
                data-bs-ride="carousel"
            <?php endif; ?>
        >

            <div class="carousel-indicators">
                <?php
                $isActive='aria-current="true" class="active"';
                foreach ($images as $idx => $image) {
                    ?>

                    <button type="button"
                            data-bs-target="#rsg2_carousel_<?php echo $uniqueId; ?>"
                            data-bs-slide-to="<?php echo $idx; ?>"
                            aria-label="Slide <?php echo $idx + 1; ?>" <?php echo $isActive; ?>
                    >
                    </button>

                    <?php
                    $isActive="";
                }
                ?>

            </div>


            <div class="carousel-inner">

                <?php
                $isActive="active";
                foreach ($images as $idx => $image) {
                    ?>

                    <div class="carousel-item <?php echo $isActive; ?>"
                        <?php if ($auto_start): ?>
                            data-bs-interval="<?php echo $interval; ?>"
                        <?php else: ?>
                            data-bs-interval="false"
                        <?php endif; ?>
                    >
                        <img
                                src="<?php echo $image->UrlDisplayFile; ?>"
                                alt="<?php echo $image->name; ?>"
                        >
                        <div class="carousel-caption">
                            <h3><?php echo $image->name; ?></h3>
                            <p><?php echo $image->description; ?></p>
                        </div>
                    </div>

                    <?php
                    $isActive="";
                }
                ?>

                <button class="carousel-control-prev" type="button" data-bs-target="#rsg2_carousel_<?php echo $uniqueId; ?>" data-bs-slide="prev">
                    <?php if ($showArrows): ?>
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <?php else: ?>
                        <span class="carousel-control-prev-icon" aria-hidden="true" hidden></span>
                    <?php endif; ?>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#rsg2_carousel_<?php echo $uniqueId; ?>" data-bs-slide="next">
                    <?php if ($showArrows): ?>
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <?php else: ?>
                        <span class="carousel-control-next-icon" aria-hidden="true" hidden></span>
                    <?php endif; ?>
                    <span class="visually-hidden">Next</span>
                </button>

            </div>
        </div>
    </div>

</div> <?php // box ?>

