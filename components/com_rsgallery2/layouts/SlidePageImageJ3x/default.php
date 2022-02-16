<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2021 - 2020
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

//$images = $displayData['images'];
extract($displayData); // $images
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . '* Slide image J3x Tasks: <br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* modal image (->slider)<br>'
        . '* length of filenames<br>'
        . '* what happens on empty galleries/ image lists<br>'
        . '* Size of replace images (missing/no images) <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
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
    <h3>RSGallery2 J3x slide image layout</h3>
    <hr>
<?php endif; ?>

<?php if (true || $menuParams->galleries_show_slideshow): ?>
    <div class="rsg2_slideshow_link">
        <a href="<?php echo $gallery->UrlSlideshow?>">
            Slideshow
        </a>
    </div>
<?php endif; ?>

<div class="rsg_sem_inl_dispImg">
    <table>
        <tbody>
        <tr>
            <td>
                <h2 class="rsg2_display_name"><?php echo $image->name; ?></h2>
            </td>
        </tr>
        <tr>
            <td>
                <!--div align="center"-->
                <div class="rsg_sem_inl_img_a_link">
                    <a href="<?php echo $image->UrlOriginalFile; ?>"
                       target="_blank">
                        <img class="rsg2-displayImage"
                             src="<?php echo $image->UrlDisplayFile; ?>"
                             alt="<?php echo $image->name; ?>"
                             title="<?php echo $image->title; ?>">
                    </a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="rsg2-toolbar">
                    <!--a href="/joomla3x/index.php?option=com_rsgallery2&amp;task=downloadfile&amp;id=157&amp;Itemid=114" -->
                    <a href=<?php echo $image->UrlDownload; ?>
                       title="Download"
                       class="btn btn-light">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                <div class="rsg2-clr">&nbsp;</div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
