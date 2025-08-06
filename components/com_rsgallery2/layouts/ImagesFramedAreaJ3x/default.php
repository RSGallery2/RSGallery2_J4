<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2021-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

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
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
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

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 Images framed J3x area layout (<?php echo $title; ?>)I</h3>
    <hr>
<?php endif; ?>


<ul id="rsg2-galleryList">
    <li class="rsg2-galleryList-item">
        <table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
            <tr>
                <td colspan="<?php echo $imgCount; ?>"><?php echo $title . ': ' ?></td>
            </tr>
            <tr>
                    <td colspan="<?php echo $imgCount; ?>">&nbsp;</td>
            </tr>
            <tr>

                <?php foreach ($images as $idx => $image): ?>

                    <td align="center">
                        <div class="shadow-box">
                            <div class="img-shadow">
                                <a href="<?php echo $image->UrlImageAsInline ?>">
                                    <img src="<?php echo $image->UrlThumbFile; ?>" alt="<?php echo $image->name; ?>" width="80">
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




