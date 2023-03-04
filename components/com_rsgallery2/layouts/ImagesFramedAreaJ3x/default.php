<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright (c) 2021-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;


//$images = $displayData['images'];
extract($displayData); // $images
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout ImagesFramedAreaJ3x <br>'
        . 'Images framed J3x area Tasks: <br>'
        . '* Size of replace images (missing/no images)-> DRY move to one place <br>'
        . '--<br>'
        . '* length of filenames<br>'
        . '* what happens on empty image lists<br>'
        . '* Replace align="center by css from file<br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* modal image (->slider)<br>'
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

$imgCount = count($images);

?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 Images framed J3x area layout (<?php echo $title; ?>)I</h3>
    <hr>
<?php endif; ?>


<ul id="rsg2-galleryList">
    <li class="rsg2-galleryList-item">
        <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td colspan="<?php echo $imgCount; ?>"><?php echo $title  . ': ' ?></td>
                </tr>
                <tr>
                    <td colspan="<?php echo $imgCount; ?>">&nbsp;</td>
                </tr>
                <tr>

                    <?php foreach ($images as $idx => $image): ?>

                        <td align="center">
                            <div class="shadow-box">
                                <div class="img-shadow">
                                    <a href="<?php echo $image->UrlImageAsInline?>">
                                        <img src="<?php echo $image->UrlThumbFile; ?>" alt="<?php echo $image->name; ?>"  width="80">
                                    </a>
                                </div>
                                <div class="rsg2-clr"></div>
                                <div class="rsg2_details">
	                                <?php echo Text::_('COM_RSGALLERY2_UPLOADED') . ': ' . $image->created; ?>
                                </div>
                            </div>
                        </td>

                    <?php endforeach; ?>

                </tr>
                <tr>
                    <td colspan="<?php echo $imgCount; ?>">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </li>
</ul>




