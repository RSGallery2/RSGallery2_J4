<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright (c) 2021-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
//use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

HTMLHelper::_('bootstrap.button', '.selector');

HTMLHelper::_('script', 'com_rsgallery2/site/j3x/OneImageVote.js', ['version' => 'auto', 'relative' => true]);

//$images = $displayData['images'];
extract($displayData); // $images
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

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
// "/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;id=157&amp;Itemid=114"
$voteLink = Route::_('index.php?option=com_rsgallery2&page=inline&id=' . $image->id);
$voteLink = Route::_('index.php?option=com_rsgallery2&task=voteJ3x&gid=2&iid=' . $image->id);



?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 slide (?page) properties J3x layout</h3>
    <hr>
<?php endif; ?>

<div class="rsg_sem_inl_ImgDetails">

    <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'DescriptionTab')); ?>

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

<hr>

<hr>
<h3>old code </h3>

<!--div class="rsg_sem_inl_ImgDetails">
    <div class="well">
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tabDesc"
                       data-toggle="tab">Description</a>
                </li>
                <li class="">
                    <a href="#tabVote"
                       data-toggle="tab">Voting</a>
                </li>
                <li class="">
                    <a href="#tabComments"
                       data-toggle="tab">Comments</a>
                </li>
                <li class="">
                    <a href="#tabExif"
                       data-toggle="tab">EXIF</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active"
                     id="tabDesc">
                    <div class="page_inline_tabs_description">
                        <div class="container span12">
                            <dl class="dl-horizontal ">
                                <dt>
                                    <i class="icon-flag"/> Hits</dt>
                                <dd>
                                    <strong>2</strong>
                                </dd>
                            </dl>
                            <div class="well">
                                <p class="rsg2_description"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane "
                     id="tabVote">
                    <div class="page_inline_tabs_voting">
                        <div class="container span12">
                            <div class="rsg2_rating_container">
                                <form name="rsgvoteform"
                                      method="post"
                                      action="/joomla3x/index.php?option=com_rsgallery2&amp;page=inline&amp;id=157&amp;Itemid=114"
                                      id="rsgVoteForm">
                                    <div class="rating-block row-fluid text-center">
                                        <h4>Average user rating</h4>
                                        <h2 class="bold padding-bottom-7">0&nbsp;<small>/&nbsp;0</small>
                                        </h2>
                                        <button id="star_1"
                                                type="button"
                                                class="btn btn-default btn-grey  btn-mini btn_star "
                                                aria-label="Left Align">
															<span class="icon-star"
                                                                  aria-hidden="true"/>
                                        </button>
                                        <button id="star_2"
                                                type="button"
                                                class="btn btn-default btn-grey  btn-mini btn_star "
                                                aria-label="Left Align">
															<span class="icon-star"
                                                                  aria-hidden="true"/>
                                        </button>
                                        <button id="star_3"
                                                type="button"
                                                class="btn btn-default btn-grey  btn-mini btn_star "
                                                aria-label="Left Align">
															<span class="icon-star"
                                                                  aria-hidden="true"/>
                                        </button>
                                        <button id="star_4"
                                                type="button"
                                                class="btn btn-default btn-grey  btn-mini btn_star "
                                                aria-label="Left Align">
															<span class="icon-star"
                                                                  aria-hidden="true"/>
                                        </button>
                                        <button id="star_5"
                                                type="button"
                                                class="btn btn-default btn-grey  btn-mini btn_star "
                                                aria-label="Left Align">
															<span class="icon-star"
                                                                  aria-hidden="true"/>
                                        </button>
                                        <label id="DoVote"
                                               title="Rate image by click on star button">Rate image&nbsp;&nbsp;</label>
                                    </div>
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
                                    <input id="token"
                                           type="hidden"
                                           name="7341b1bda558a909ac78f70058eb5225"
                                           value="1">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane "
                     id="tabComments">
                    <div class="page_inline_tabs_comments">
                        <div class="container span12">
                            <div class="rsg2_comments_container">
                                <div id="comment">
                                    <table width="100%"
                                           class="comment_table">
                                        <tbody>
                                        <tr>
                                            <td class="title">
																						<span class="posttitle">No comments yet! <br/>
																							<br>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <a id="lblAddComment"/>
                                <div class="clearfix">
                                    <form name="rsgCommentForm"
                                          class="form-horizontal"
                                          method="post"
                                          action="/joomla3x/index.php?option=com_rsgallery2&amp;view=gallery&amp;gid=1&amp;Itemid=114&amp;startShowSingleImage=1"
                                          id="rsgCommentForm">
                                        <div class="well">
                                            <h4>Create comment</h4>
                                            <button id="commitSend"
                                                    class="btn btn-primary pull-right"
                                                    type="submit"
                                                    onclick="Joomla.submitbutton(this.form);return false"
                                                    title="COM_RSGALLERY2_SEND_COMMENT_DESC">
                                                <i class="icon-save"/> Add comment
                                            </button>
                                            <div class="control-group">
                                                <div class="control-label">
                                                    <label id="commentUserName-lbl"
                                                           for="commentUserName"
                                                           class="hasPopover"
                                                           title=""
                                                           data-content="COM_RSGALLERY2_YOUR_NAME_DESC"
                                                           data-original-title="Name">
                                                        Name</label>
                                                </div>
                                                <div class="controls">
                                                    <input type="text"
                                                           name="commentUserName"
                                                           id="commentUserName"
                                                           value=""
                                                           class="input"
                                                           size="10"
                                                           readonly="">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <div class="control-label">
                                                    <label id="commentTitle-lbl"
                                                           for="commentTitle"
                                                           class="hasPopover required"
                                                           title=""
                                                           data-content="Title for the article."
                                                           data-original-title="Title">
                                                        Title<span class="star">&nbsp;*</span>
                                                    </label>
                                                </div>
                                                <div class="controls">
                                                    <input type="text"
                                                           name="commentTitle"
                                                           id="commentTitle"
                                                           value=""
                                                           class="input-xxlarge input-large-text required"
                                                           size="120"
                                                           required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <div class="control-label">
                                                    <label id="commentText-lbl"
                                                           for="commentText"
                                                           class="hasPopover"
                                                           title=""
                                                           data-content="COM_RSGALLERY2_COMMENTS_FIELD_COMMENT_DESC"
                                                           data-original-title="Comment">
                                                        Comment</label>
                                                </div>
                                                <div class="controls">
                                                    <div class="js-editor-tinymce">
                                                        <div id="mceu_8"
                                                             class="mce-tinymce mce-container mce-panel"
                                                             hidefocus="1"
                                                             tabindex="-1"
                                                             role="application"
                                                             style="visibility: hidden; border-width: 1px;">
                                                            <div id="mceu_8-body"
                                                                 class="mce-container-body mce-stack-layout">
                                                                <div id="mceu_9"
                                                                     class="mce-toolbar-grp mce-container mce-panel mce-stack-layout-item mce-first"
                                                                     hidefocus="1"
                                                                     tabindex="-1"
                                                                     role="group">
                                                                    <div id="mceu_9-body"
                                                                         class="mce-container-body mce-stack-layout">
                                                                        <div id="mceu_10"
                                                                             class="mce-container mce-toolbar mce-stack-layout-item mce-first mce-last"
                                                                             role="toolbar">
                                                                            <div id="mceu_10-body"
                                                                                 class="mce-container-body mce-flow-layout">
                                                                                <div id="mceu_11"
                                                                                     class="mce-container mce-flow-layout-item mce-first mce-btn-group"
                                                                                     role="group">
                                                                                    <div id="mceu_11-body">
                                                                                        <div id="mceu_0"
                                                                                             class="mce-widget mce-btn mce-btn-small mce-first"
                                                                                             tabindex="-1"
                                                                                             aria-labelledby="mceu_0"
                                                                                             role="button"
                                                                                             aria-label="Bold">
                                                                                            <button role="presentation"
                                                                                                    type="button"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-bold"/>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div id="mceu_1"
                                                                                             class="mce-widget mce-btn mce-btn-small"
                                                                                             tabindex="-1"
                                                                                             aria-labelledby="mceu_1"
                                                                                             role="button"
                                                                                             aria-label="Underline">
                                                                                            <button role="presentation"
                                                                                                    type="button"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-underline"/>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div id="mceu_2"
                                                                                             class="mce-widget mce-btn mce-btn-small mce-last"
                                                                                             tabindex="-1"
                                                                                             aria-labelledby="mceu_2"
                                                                                             role="button"
                                                                                             aria-label="Strikethrough">
                                                                                            <button role="presentation"
                                                                                                    type="button"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-strikethrough"/>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div id="mceu_12"
                                                                                     class="mce-container mce-flow-layout-item mce-btn-group"
                                                                                     role="group">
                                                                                    <div id="mceu_12-body">
                                                                                        <div id="mceu_3"
                                                                                             class="mce-widget mce-btn mce-btn-small mce-first mce-disabled"
                                                                                             tabindex="-1"
                                                                                             aria-labelledby="mceu_3"
                                                                                             role="button"
                                                                                             aria-label="Undo"
                                                                                             aria-disabled="true">
                                                                                            <button role="presentation"
                                                                                                    type="button"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-undo"/>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div id="mceu_4"
                                                                                             class="mce-widget mce-btn mce-btn-small mce-last mce-disabled"
                                                                                             tabindex="-1"
                                                                                             aria-labelledby="mceu_4"
                                                                                             role="button"
                                                                                             aria-label="Redo"
                                                                                             aria-disabled="true">
                                                                                            <button role="presentation"
                                                                                                    type="button"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-redo"/>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div id="mceu_13"
                                                                                     class="mce-container mce-flow-layout-item mce-btn-group"
                                                                                     role="group">
                                                                                    <div id="mceu_13-body">
                                                                                        <div id="mceu_5"
                                                                                             class="mce-widget mce-btn mce-splitbtn mce-btn-small mce-menubtn mce-first"
                                                                                             role="button"
                                                                                             tabindex="-1"
                                                                                             aria-label="Bullet list"
                                                                                             aria-haspopup="true">
                                                                                            <button type="button"
                                                                                                    hidefocus="1"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-bullist"/>
                                                                                            </button>
                                                                                            <button type="button"
                                                                                                    class="mce-open"
                                                                                                    hidefocus="1"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-caret"/>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div id="mceu_6"
                                                                                             class="mce-widget mce-btn mce-splitbtn mce-btn-small mce-menubtn mce-last"
                                                                                             role="button"
                                                                                             tabindex="-1"
                                                                                             aria-label="Numbered list"
                                                                                             aria-haspopup="true">
                                                                                            <button type="button"
                                                                                                    hidefocus="1"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-numlist"/>
                                                                                            </button>
                                                                                            <button type="button"
                                                                                                    class="mce-open"
                                                                                                    hidefocus="1"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-caret"/>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div id="mceu_14"
                                                                                     class="mce-container mce-flow-layout-item mce-last mce-btn-group"
                                                                                     role="group">
                                                                                    <div id="mceu_14-body">
                                                                                        <div id="mceu_7"
                                                                                             class="mce-widget mce-btn mce-btn-small mce-first mce-last"
                                                                                             tabindex="-1"
                                                                                             aria-labelledby="mceu_7"
                                                                                             role="button"
                                                                                             aria-label="Paste as text">
                                                                                            <button role="presentation"
                                                                                                    type="button"
                                                                                                    tabindex="-1">
                                                                                                <i class="mce-ico mce-i-pastetext"/>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="mceu_15"
                                                                     class="mce-edit-area mce-container mce-panel mce-stack-layout-item"
                                                                     hidefocus="1"
                                                                     tabindex="-1"
                                                                     role="group"
                                                                     style="border-width: 1px 0px 0px;">
                                                                    <iframe id="commentText_ifr"
                                                                            frameborder="0"
                                                                            allowtransparency="true"
                                                                            title="Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help"
                                                                            src="javascript:&quot;&quot;"
                                                                            style="width: 100%; height: 550px; display: block;"/>
                                                                </div>
                                                                <div id="mceu_16"
                                                                     class="mce-statusbar mce-container mce-panel mce-stack-layout-item mce-last"
                                                                     hidefocus="1"
                                                                     tabindex="-1"
                                                                     role="group"
                                                                     style="border-width: 1px 0px 0px;">
                                                                    <div id="mceu_16-body"
                                                                         class="mce-container-body mce-flow-layout">
                                                                        <div id="mceu_17"
                                                                             class="mce-path mce-flow-layout-item mce-first">
                                                                            <div role="button"
                                                                                 class="mce-path-item mce-last"
                                                                                 data-index="0"
                                                                                 tabindex="-1"
                                                                                 id="mceu_17-0"
                                                                                 aria-level="1">p</div>
                                                                        </div>
                                                                        <span id="mceu_19"
                                                                              class="mce-wordcount mce-widget mce-label mce-flow-layout-item">Words: 0</span>
                                                                        <div id="mceu_18"
                                                                             class="mce-flow-layout-item mce-resizehandle mce-resizehandle-both mce-last">
                                                                            <i class="mce-ico mce-i-resize"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <textarea name="commentText"
                                                                  id="commentText"
                                                                  cols="50"
                                                                  rows="5"
                                                                  style="width: 100%; height: 250px; display: none;"
                                                                  class="mce_editable joomla-editor-tinymce"
                                                                  aria-hidden="true">	</textarea>
                                                        <div class="toggle-editor btn-toolbar pull-right clearfix">
                                                            <div class="btn-group">
                                                                <a class="btn"
                                                                   href="#"
                                                                   onclick="tinyMCE.execCommand('mceToggleEditor', false, 'commentText');return false;"
                                                                   title="Toggle editor">
																														<span class="icon-eye"
                                                                                                                              aria-hidden="true"/> Toggle editor		</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden"
                                                   name="task"
                                                   value="comment.addComment">
                                            <input type="hidden"
                                                   name="rating"
                                                   value="">
                                            <input type="hidden"
                                                   name="paginationImgIdx"
                                                   value="">
                                            <input type="hidden"
                                                   name="id"
                                                   value="157">
                                            <input id="token"
                                                   type="hidden"
                                                   name="7341b1bda558a909ac78f70058eb5225"
                                                   value="1">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane "
                     id="tabExif">
                    <div class="page_inline_tabs_exif">
                        <div class="container span12">
                            <div class="rsg2_exif_container">
                                <dl class="dl-horizontal">
                                    <dt>FileName</dt>
                                    <dd>DSC_5503.jpg</dd>
                                    <dt>FileDateTime</dt>
                                    <dd>14-Feb-2021 10:46:44</dd>
                                    <dt>FileSize</dt>
                                    <dd>4680973</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div-->




