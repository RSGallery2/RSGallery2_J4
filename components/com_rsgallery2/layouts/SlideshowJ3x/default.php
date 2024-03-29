<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright (c) 2021-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

/*---------------------------------------------------
Show slideshow from bootstrap
---------------------------------------------------*/

HTMLHelper::_('bootstrap.carousel', '.selector');
HTMLHelper::_('bootstrap.button', '.selector');


extract($displayData);

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout slideshowJ3x<br>'
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

if ( ! isset($images)) {
	$images = [];
}

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';

//--- assign dummy images if not found -----------------------------------

if ( ! empty($images))
{
	foreach ($images as $idx => $image)
	{

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

$interval = 2000; // 1000=1sec

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
	.carousel-inner  {
		/*margin: 0 auto;*/
		/*border: 5px solid lightcyan;*/
	}
	/**/

	/**/
    .carousel-inner > .carousel-item  {
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

<hr>
<h5> (1) boostrap itself  </h5>
<div class="rsg2_slideshow_box">
    <div class="rsg2_x__slideshowJ3x">

        <?php if (!empty($isDebugSite)): ?>
            rsg2_x__slideshowJ3x<br>
        <?php endif; ?>

        <?php
        $uniqueId = substr(md5(uniqid()), 0, 12);;
        ?>

        <div id="rsg2_carousel_<?php echo $uniqueId; ?>" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-indicators">
                <?php
                $isActive='aria-current="true" class="active"';
                foreach ($images as $idx => $image) {
                    ?>

                    <button type="button"
                            data-bs-target="#rsg2_carousel_<?php echo $uniqueId; ?>"
                            data-bs-slide-to="<?php echo $idx; ?>"
                            aria-label="Slide <?php echo $idx+1; ?>" <?php echo $isActive; ?>
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

                    <div class="carousel-item <?php echo $isActive; ?>" data-bs-interval="<?php echo $interval; ?>">
<!--                        <img class="d-block w-100"-->
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
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#rsg2_carousel_<?php echo $uniqueId; ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

            </div>
        </div>
    </div>

</div> <?php // box ?>

