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

\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/*---------------------------------------------------
gallery thumbs display by rows like in J3x
---------------------------------------------------*/

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

$imgCount = count($images);

?>

<?php //--- 04 ------------------------------------------
?>

<div class="image-wall-overlapping-black">

    <?php foreach ($images as $idx => $image) : ?>

        <img class="overlapping-img"
             src="<?php echo $image->UrlDisplayFile ?>" alt="<?php echo $image->name; ?>">

    <?php endforeach; ?>
</div>


