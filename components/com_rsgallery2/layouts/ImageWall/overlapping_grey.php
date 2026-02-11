<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Layouts\ImageWall;

// phpcs:disable PSR1.Files.SideEffects
use Joomla\CMS\Uri\Uri;

// phpcs:enable PSR1.Files.SideEffects

\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/*--------------------------------------------------------------
Gallery of images with overlapping, blended bright borders
Background of image area is grey
--------------------------------------------------------------*/

extract($displayData);

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout ImagesFramedAreaJ3x <br>'
        . '* Size of replace images (missing/no images)-> DRY move to one place <br>'
        . '* length of filenames<br>'
        . '* what happens on empty image lists<br>'
        . '* Replace align="center by css from file<br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* modal image (->slider)<br>'
        //  . '* <br>'
        //  . '* <br>'
        //  . '* <br>'
        . '</span><br><br>';
}

//--- sanitize URLs -----------------------------------

if (!isset($images)) {
    $images = [];
}

$noImageUrl     = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl     = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';
$plane_whiteUrl = URI::root() . '/media/com_rsgallery2/images/pure_white.jpg';

if (!empty($images)) {
    //--- assign dummy white images  -----------------------------------

    // Images left out in area.
    // CSS will create 2,3 or 4 columns. Missing image would appear as black areas
    // White images must be added to cover the black background.
    $imgCount    = count($images);
    $imgReplaces = 12 - $imgCount % 12;

    //--- assign url to images  -----------------------------------

    // Display all images.
    foreach ($images as $idx => $image) {
        // show dummy thumb on galleries with no images
        if (!empty($image->isHasNoImages)) {
            $image->UrlOriginalFile = $noImageUrl;
            $image->UrlDisplayFiles = $noImageUrl;
            $image->UrlThumbFile    = $noImageUrl;
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

// <span class="image-wall-img-background">
?>

<?php if (!empty($images)) : ?>

    <div class="image-wall-overlapping-grey">

        <?php foreach ($images as $idx => $image) : ?>

            <img class="overlapping-img"
                 src="<?php echo $image->UrlDisplayFile ?>" alt="<?php echo $image->name; ?>"
            >

        <?php endforeach; ?>

        <?php foreach (range(1, $imgReplaces) as $i) : ?>

            <img class="overlapping-img"
                 src="<?php echo $plane_whiteUrl ?>" alt="<?php echo "plain white image added"; ?>"
            >

        <?php endforeach; ?>
    </div>

<?php endif; ?>


