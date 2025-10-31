<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2021-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

/*---------------------------------------------------
? does what ? one image ?
---------------------------------------------------*/

HTMLHelper::_('bootstrap.button', '.selector');
HTMLHelper::_('script', 'com_rsgallery2/site/j3x/OneImageVote.js', ['version' => 'auto', 'relative' => true]);

extract($displayData);

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks:  layout slidePagePropertiesJ3x<br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* modal image (->slider)<br>'
        . '* length of filenames<br>'
        . '* what happens on empty galleries/ image lists<br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
        . '</span><br><br>';
}

if (!isset($images)) {
    $images = [];
}

//if (!empty ($images)) {
//    // ToDo: has one image ? see $image->id below
//}

// "/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;id=157&amp;Itemid=114"
$voteLink = Route::_('index.php?option=com_rsgallery2&page=inline&id=' . $image->id);
$voteLink = Route::_('index.php?option=com_rsgallery2&task=voteJ3x&id=2&iid=' . $image->id);


?>

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

    <h3>This may be a voting </h3>

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

    <h3>ToDo: This may be a comment</h3> <br>with more than one line .....

    <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ExxifInfoTab', Text::_('COM_RSGALLERY2_EXIF', true)); ?>

    <h3>ToDo: Display selected image exif info </h3>

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

<hr>


