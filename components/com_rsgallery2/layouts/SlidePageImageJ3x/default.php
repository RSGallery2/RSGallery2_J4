<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2021-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

/*---------------------------------------------------
? does what ?
---------------------------------------------------*/

extract($displayData);

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout SlidePageImageJ3x<br>'
//	. '* <br>'
//	. '* <br>'
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

$image = null;
if (count($images)) {
    $image = $images [$image_idx];
}

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

//}

?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 J3x slide image layout</h3>
    <hr>
<?php endif; ?>

<?php if (true || $params->galleries_show_slideshow): ?>
    <div class="rsg2_slideshow_link">
        <a href="<?php echo $gallery->UrlSlideshow; ?>">
            <?php echo ' ' . Text::_('COM_RSGALLERY2_SLIDESHOW'); ?>
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
                       target="_blank"
                    >
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
                    <a href="<?php echo $image->UrlDownload; ?>"
                       title="Download"
                       class="btn btn-light"
                    >
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                <div class="rsg2-clr">&nbsp;</div>
            </td>
        </tr>
        </tbody>
    </table>
</div>


<?php echo $this->pagination->getListFooter(); ?>


<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 slide (?page) properties J3x layout</h3>
    <hr>
<?php endif; ?>

<div class="rsg_sem_inl_ImgDetails">

    <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'DescriptionTab']); ?>

    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DescriptionTab', Text::_('COM_RSGALLERY2_DESCRIPTION', true)); ?>

    <div class="page_inline_tabs_description">
        <div class="card bg-light ">
            <div class="card-body">
                <div class="container page_inline_hits">
                    <i class="fas fa-flag"></i>
                    <strong><?php echo ' ' . Text::_('COM_RSGALLERY2_HITS', true) . ' ' . $image->hits; ?></strong>
                </div>
            </div>
        </div>
        <div class="card bg-light ">
            <div class="card-body">
                <?php echo $image->description; ?>
            </div>
        </div>
        <div class="page_inline_description">
        </div>
    </div>


    <?php echo HTMLHelper::_('bootstrap.endTab'); ?>


    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'VotingTab', Text::_('COM_RSGALLERY2_VOTING', true)); ?>

	<p><h3>This may be a voting  </h3></p>

    <div class="rating-block row-fluid text-center">
        <h4>Average user rating</h4>
        <h2 class="bold padding-bottom-7">0&nbsp;<small>/&nbsp;0</small>
        </h2>
        <!--button type="submit" name="filter_submit" class="btn btn-primary"><?php echo Text::_('JGLOBAL_FILTER_BUTTON'); ?></button-->
        <button id="star_1"
                type="button"
                class="btn btn-default btn-grey  btn-mini btn_star "
                aria-label="Left Align">
            <i class="fas fa-solid fa-star"></i>
        </button>
        <button id="star_2"
                type="button"
                class="btn btn-default btn-grey  btn-mini btn_star "
                aria-label="Left Align">
            <i class="fas fa-solid fa-star"></i>
        </button>
        <button id="star_3"
                type="button"
                class="btn btn-default btn-grey  btn-mini btn_star "
                aria-label="Left Align">
            <i class="fas fa-solid fa-star"></i>
        </button>
        <button id="star_4"
                type="button"
                class="btn btn-default btn-grey  btn-mini btn_star "
                aria-label="Left Align">
            <i class="fas fa-solid fa-star"></i>
        </button>
        <button id="star_5"
                type="button"
                class="btn btn-default btn-grey  btn-mini btn_star "
                aria-label="Left Align">
            <i class="fas fa-solid fa-star"></i>
        </button>
        <label id="DoVote"
               title="Rate image by click on star button">Rate image&nbsp;&nbsp;
        </label>
    </div>

    <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'CommentsTab', Text::_('COM_RSGALLERY2_COMMENTS', true)); ?>

	<p><h3>ToDo: This may be a comment</h3> <br>with more than one line .....</p>

    <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ExxifInfoTab', Text::_('COM_RSGALLERY2_EXIF', true)); ?>

	<p><h3>ToDo: Display selected image exif info  </h3></p>

    <?php echo HTMLHelper::_('bootstrap.endTab'); ?>


    <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>


    <input type="hidden"
           name="task"
           value="rating.rateSingleImage">
    <input type="hidden"
           name="rating"
           value="">
    <input type="hidden"
           name="paginationImgIdx"
           value="">
    <input type="hidden"
           name="id"
           value="157">
    <!--input id="token"
           type="hidden"
           name="<?php // Session::getFormToken() ?>"
           value="1"-->

</div>

