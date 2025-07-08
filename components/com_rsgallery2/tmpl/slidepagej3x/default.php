<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/

// ToDo:
// ToDo:

//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/slideshow.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/j3x.css', array('version' => 'auto', 'relative' => true));
////HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/rsgallery.css', array('version' => 'auto', 'relative' => true));
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.site.slidepageJ3x');


//if ($this->item->params->get('show_name')) {
//
//	if ($this->Params->get('show_rsgallery2_name_label')) {
//		echo Text::_('COM_RSGALLERY2_NAME') . $this->item->name;
//	} else {
//		echo $this->item->name;
//	}
//}
//
//echo $this->item->event->afterDisplayTitle;R
//echo $this->item->event->beforeDisplayContent;
//
// echo '<h1> RSGallery2 "legacy" view </h1>';
//
//
//echo $this->item->event->afterDisplayContent;

// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
	echo '<span style="color:red">'
		. 'Tasks: slidePageJ3x view<br>'
		. '* html aria-label ... <br>'
		. '* HTML 5 layout, bootstrap * <br>'
		. '* modal image (->slider)<br>'
		. '* length of filenames<br>'
		. '* what happens on empty galleries/ image lists<br>'
		. '* Size of replace images (missing/no images) <br>'
		. '* handle voting, exif ... separate function calls<br>'
		. '* handle comments<br>'
		. '* center EXIF<br>'
		. '* j3x.css: clean up (shade by CSS (Scss ...) <br>'
		//	. '* <br>'
		//	. '* <br>'
		//	. '* <br>'
		//	. '* <br>'
		. '</span><br><br>';
}

//$layoutName = $this->getLayout();
//
//$layoutSlidePage = null;
//if ($layoutName == 'default') {
//
////    $layoutImageName = 'SlidePageImageJ3x.default';
////    $layoutPropertiesName = 'SlidePagePropertiesJ3x.default';
//
////    $layoutImage = new FileLayout($layoutImageName);
////    $layoutProperties = new FileLayout($layoutPropertiesName);
//
//    $layoutSlidePage = new FileLayout('SlidePageImageJ3x.default');
//}
//
//$displayData['images'] = $this->items;
//$displayData['image_idx'] = $this->imageIdx;
//$displayData['gallery'] = $this->gallery;
//$displayData['params'] = $this->params->toObject();
//$displayData['menuParams'] = $this->menuParams;
//
//$displayData['isDebugSite'] = $this->isDebugSite;
//$displayData['isDevelopSite'] = $this->isDevelopSite;
//

// 2022.11.12 moved code from php layout folder

//================================================================================================
// display functions
//================================================================================================

/**
 * Show description (from semantic /html/inline.php)
 */
function _showDescription()
{
//	global $rsgConfig;
//	// $item = rsgInstance::getItem(); deprecated
//	$gallery = rsgGalleryManager::get();
//	$item    = $gallery->getItem();
//
//	if ($rsgConfig->get('displayHits')){
//		?>
<!--		<p class="rsg2_hits">--><?php //echo Text::_('COM_RSGALLERY2_HITS'); ?><!-- <span>--><?php //echo $item->hits; ?><!--</span></p>-->
<!--	--><?php
//	}
//
//	if ($item->descr) {
//		?>
<!--		<p class="rsg2_description">--><?php //echo stripslashes($item->descr); ?><!--</p>-->
<!--		--><?php
//	}

	?>
        <p class="rsg2_hits"><?php echo Text::_('COM_RSGALLERY2_HITS'); ?> <span><?php echo '?????'; ?></span></p>
        <p class="rsg2_description"><?php echo Text::_('COM_RSGALLERY2_DESCRIPTION'); ?> <span><?php echo '?????'; ?></span></p>
   	<?php


}

// voting
function htmlRatingData($ratingData, $isVotingEnabled, $gid, $imageId)
{
	global $rsgConfig;

	$html = [];

	$html[] = '<div class="container span12">';

	$html[] = '        <div class="rsg2_rating_container">';

	//--- result of rating ------------------------------------

	// ToDo: add limit here and remove from *js
	//$html[] = '                <form name="rsgvoteform" method="post" action="' . \Joomla\CMS\Router\Route::_('index.php?option=com_rsgallery2&view=gallery&id=' . $gid) .'&startShowSingleImage=1" id="rsgVoteForm">';
	//                                                                                         index.php/single-gallery/item/1/asInline
	//                                                                                         index.php?option=com_rsgallery2&page=inline&id=" . $item->id
//		$html[] = '                <form name="rsgvoteform" method="post" action="' . \Joomla\CMS\Router\Route::_('index.php?option=com_rsgallery2&&page=inline&id="&id=' . $imageId) .'" id="rsgVoteForm">';
	$html[] = '                <form name="rsgvoteform" method="post" action="'
		. \Joomla\CMS\Router\Route::_('index.php?option=com_rsgallery2&page=inline&id=' . $imageId) .'" id="rsgVoteForm">';

	$html[] = '                <div class="rating-block row-fluid text-center" >';

	$html[] = '                    <h4>' . Text::_('COM_RSGALLERY2_AVERAGE_USER_RATING') . '</h4>';
	$html[] = '                    <h2 class="bold padding-bottom-7">' . $ratingData->average . '&nbsp<small>/&nbsp' . $ratingData->count . '</small></h2>';

	for ($idx = 0; $idx < 5; $idx++)
	{
		$html[] = '                    ' . htmlStars($idx, $ratingData->average, $ratingData->lastRating);
	}

	if ($isVotingEnabled)
	{
		$html[] = '                <label id="DoVote" title="' . Text::_('COM_RSGALLERY2_AVERAGE_RATE_IMAGE_DESC') . '">' . Text::_('COM_RSGALLERY2_AVERAGE_RATE_IMAGE') . '&nbsp;&nbsp;</label>';

//		$templateName = $rsgConfig->get('template');
//		$templateUri = JURI_SITE . "/components/com_rsgallery2/templates/" . $templateName;
//
//		$doc = Factory::getApplication->getDocument();
//		$vote_js = $templateUri . "/js/OneImageVote.js";
//		$doc->addScript($vote_js);
	}

	$html[] = '                </div>'; //

	$html[] = '                <input type="hidden" name="task" value="rating.rateSingleImage" />';
	$html[] = '                <input type="hidden" name="rating" value="" />';
	$html[] = '                <input type="hidden" name="paginationImgIdx" value="" />';
	$html[] = '                <input type="hidden" name="id" value="' . $imageId . '" />';
	$html[] = '                <input id="token" type="hidden" name="' . Session::getFormToken() . '" value="1" />';

	$html[] = '                </form>';

	$html[] = '		</div>'; // rsg2_exif_container

	$html[] = '</div>'; // class="container span12">';

	return implode("\n", $html);
}

function htmlExifData($exifTags)
{
	?>

    <div class="exif-block container d-flex align-items-center justify-content-center">

        <div class="rsg2_exif_container">
            <div class="card-body">

                <h4 class="card-title"><?php echo Text::_('COM_RSGALLERY2_EXIF_DATA'); ?></h4>

                <div class="card-text">

                    <dl class="dl-horizontal text-center">

		            <?php // user requested EXIF tags ?>
						<?php foreach ($exifTags as $exifKey => $exifValue): ?>
                            <dt class="text-end col-sm-x3"><?php echo Text::_($exifKey); ?></dt>
                            <dd class="text-start col-sm-9"><?php echo $exifValue; ?></dd>
						<?php endforeach; ?>

                    </dl>
                </div>
            </div>
        </div>
    </div>

<?php
}

//	// toDo improve ....
//	// https://bootsnipp.com/snippets/Vp4P
//	// https://bootsnipp.com/snippets/featured/comment-posts-layout
//	// https://bootsnipp.com/snippets/featured/blog-post-footer
//	// sophisticated
//	// https://bootsnipp.com/snippets/featured/collapsible-tree-menu-with-accordion
//	// https://bootsnipp.com/snippets/a35Pl
//
//	$formFields = $comments->formFields;
//	$imgComments = $comments->comments;
//
//	$html = [];
//
//	$html[] = '<div class="container span12">';
//
//	$html[] =  '        <div class="rsg2_comments_container">';
//
//	if (empty($imgComments))
//	{
//		$html[] = '<div id="comment">';
//		$html[] = '    <table width="100%" class="comment_table">';
//		$html[] = '        <tr>';
//		$html[] = '            <td class="title">';
//		$html[] = '                <span class="posttitle">' . Text::_('COM_RSGALLERY2_NO_COMMENTS_YET') . ' <br></span>';
//		$html[] = '                 ';
//		$html[] = '                 <br>';
//		$html[] = '            </td>';
//		$html[] = '        </tr>';
//		$html[] = '    </table>';
//		$html[] = '</div>';
//	}
//	else
//	{
//		// Comments existing
//
//		//--- add comment link bar -------------------------------------------------
//
//		$html[] = '<div id="comment" class="title pull-right">';
//
//		$html[] = '    <button class="btn btn-success" type="button">';
//		$html[] = '        <i class="icon-comment"></i>';
//		$html[] = '	       <a class="special" href="#lblAddComment">' . Text::_('COM_RSGALLERY2_ADD_COMMENT') . '</a>';
//		//$html[] = '	       <a class="special" href="#bottom">' . Text::_('COM_RSGALLERY2_ADD_COMMENT') . '</a>';
//		//$html[] = '	       <a class="special" href="#commentUserName">' . Text::_('COM_RSGALLERY2_ADD_COMMENT') . '</a>';
//		$html[] = '    </button>';
//		$html[] = '';
//
//		$html[] = '</div>';
//
//		// $html[] = '<div class="clearfix" />';
//
//		//--- existing comments -----------------------------------------------------
//
//		/**/
//		// each comment
//		foreach ($imgComments as $comment)
//		{
//
//			// $html[] = '<div class="row">';
//
//			$html[] = '<div class="media">';
//
//			$html[] = '    <a class="pull-left span2" href="#">';
//			//$html[] = '<div class="thumbnail">';
//
//			// $html[] = '<img class="img-responsive user-photo" src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">';
//			$html[] = '        <div>';
//			//$html[] = '            <i class="icon-user large-icon" style="font-size:24px;"></i>';
//			$html[] = '            <i class="icon-user large-icon"></i>';
//			$html[] = '            <strong>' . $comment->user_name . '</strong>';
//			//$html[] = '            <br> <span class="text-muted">commented 5 days ago</span>';
//			$html[] = '        </div>';
//
//			//$html[] = '</div>'; //<!-- /thumbnail -->
//			$html[] = '    </a>';
//
//
//			$html[] = '<div class="clearfix" >';
//
//
//			$html[] = '    <div class="media-body  span10">';
//			//$html[] = '        <i class="icon-comment large-icon" style="font-size:24px;"></i>';
//			$html[] = '        <i class="icon-comment large-icon"></i>';
//			$html[] = '        <strong class="media-heading title">' . $comment->subject . '</strong>';
//			//$html[] = '        <strong>myusername</strong> <span class="text-muted">commented 5 days ago</span>';
//
//			$html[] = '        <p><div>' . $comment->comment . '</div></p>';
//
//			$html[] = '    </div>';
//			$html[] = '';
//
//			$html[] = '</div>';
//
//			$html[] = '</div>'; // class="media">';
//
//			$html[] = '<hr>';
//		}
//
//		/**/
//	}
//
//	//--- add comment -----------------------------------------------------
//
//
//	// Manipulate form fieldset "name" depending on user
//	$user = Factory::getContainer()->get(UserFactoryInterface::class);
//	// User is logged in
//	if ( ! empty($user->id))
//	{
//		$user4Form ['commentUserName'] = $user->name;
//		//$this->bind ($user4Form);
//		//JForm::bind($user4Form);
//		// $this->params_form = $params_form; see alsi where comments are collected
//		/**
//		$params = YireoHelper::toRegistry($this->item->params)->toArray();
//		$params_form = JForm::getInstance('params', $file);
//		$params_form->bind(array('params' => $params));
//		$this->params_form = $params_form;
//		/**/
//	}
//
//	$html[] = '';
//
//	//$html[] = '<a name="lblAddComment"></a>';
//	$html[] = '<a id="lblAddComment"></a>';
//
//	/**/
//	//$html[] = '<hr>';
//	$html[] = '';
//	$html[] = '<div class="clearfix" >';
//
//	$html[] = '                <form name="rsgCommentForm" class="form-horizontal" method="post"';
//	$html[] = '                    action="' . \Joomla\CMS\Router\Route::_('index.php?option=com_rsgallery2&view=gallery$id=' . $gid) .'&startShowSingleImage=1" id="rsgCommentForm">';
//
//	$html[] = '                    <div class ="well">';
//	$html[] = '                        <h4>'. Text::_('COM_RSGALLERY2_CREATE_COMMENT') . '</h4>';
//
//	// ToDo: text-align="center
//	$html[] = '                        <button id="commitSend" class="btn btn-primary pull-right" ';
//	$html[] = '                            type="submit" ';
////    $html[] = '						       onclick="Joomla.submitbutton(\'comment.saveComment\')"';
//	$html[] = '						       onclick="Joomla.submitbutton(this.form);return false" ';
//	$html[] = '							   title="' . Text::_('COM_RSGALLERY2_SEND_COMMENT_DESC') . '">';
//	$html[] = '						       <i class="icon-save"></i> ' . Text::_('COM_RSGALLERY2_ADD_COMMENT') . '';
//	$html[] = '						   </button>';
//
//	$html[] = '                        ' . $formFields->renderFieldset ('comment');
//
//	$html[] = '                    	   <input type="hidden" name="task" value="comment.addComment" />';
//	$html[] = '                    	   <input type="hidden" name="rating" value="" />';
//	$html[] = '                    	   <input type="hidden" name="paginationImgIdx" value="" />';
//	$html[] = '                    	   <input type="hidden" name="id" value="' . $imageId . '" />';
//	$html[] = '                    	   <input id="token" type="hidden" name="' . Session::getFormToken() . '" value="1" />';
//
//	$html[] = '                    </div>';
//	$html[] = '                </form>';
//	/**/
//	$html[] = '</div>';
//
//	$html[] = '            </div>'; // container
//
//	$html[] = '</div>'; // class="container">';
function htmlComments($comments, $gallery_id, $image_id)
{
	?>

    <div class="exif-block container d-flex align-items-center justify-content-center">

        <div class="rsg2_comments_container">
            <div class="card-body">

                <h4 class="card-title"><?php echo Text::_('COM_RSGALLERY2_COMMENTS'); ?></h4>

                <div class="card-text">

					<?php if (!empty ($comments)) : ?>

					<?php else : ?>
                        <h5>Script for comments not activated</h5>
					<?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php
}


function htmlStars($idx, $average, $lastRating)
{
	$html = [];

	$intAvg = (int) floor($average);
	$avgRem = ((double) $average) - $intAvg; // reminder

	$isSelected = "";
	if ($lastRating > 0 && ($lastRating - 1) == $idx)
	{
		$isSelected = "checked";
	}

	$isButtonActive = false;
	$isHalfStar     = false;
	if ($idx < $intAvg)
	{
		$isButtonActive = true;
	}

	if ($idx == $intAvg)
	{
		if ($avgRem > 0.49)
		{
			$isHalfStar     = true;
			$isButtonActive = true;
		}
	}

	if ($isHalfStar) {
		$iconClass = "icon-star-2";
	}
	else
	{
		$iconClass = "icon-star";
	}

	$buttonClassAdd = 'btn-warning ';
	if (!$isButtonActive)
	{
		$buttonClassAdd = 'btn-default btn-grey ';
	}

	$html[] = '<button id="star_' . ($idx + 1) . '" type="button" class="btn ' . $buttonClassAdd . ' btn-mini btn_star ' . $isSelected . '" aria-label="Left Align">';
	$html[] = '    <span class="' . $iconClass . '" aria-hidden="true"></span>';
	$html[] = '</button>';

	return implode("\n", $html);
}


//	$images = $this->items;
$image_idx = $this->imageIdx;
$gallery   = $this->gallery;
$params    = $this->params;
//	$menuParams = $this->menuParams;

$image = $this->image;

?>

<form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=slidepagej3x'); ?>" method="post" class="form-validate form-horizontal well">

    <div class="rsg2__form rsg2__slide_page">

		<?php if (!empty($this->isDebugSite)): ?>
            <h1> Menu RSGallery2 "slide page J3x" view </h1>
            <hr>
		<?php endif; ?>

		<?php //--- display images in J3x slideshow ---------- ?>

        <!-- removed 2022.11.12       <div class="rsg2">-->
        <!---->
        <!--            --><?php //if (!empty($layoutSlidePage)): ?>
        <!--            --><?php ////	            echo $layoutImage->render($displayData);
		////	            echo $layoutProperties->render($displayData);
		//
		//	            echo $layoutSlidePage->render($displayData);
		//
		//                ?>
        <!--            --><?php //endif; ?>
        <!---->
        <!--        </div>-->

		<?php if (true || $params->galleries_show_slideshow): ?>
            <div class="rsg2_slideshow_link">
                <a href="<?php echo $gallery->UrlSlideshow ?>">
                    Slideshow
                </a>
            </div>
		<?php endif; ?>

        <div class="rsg_sem_inl_dispImg">
            <table class="table table-borderless">
                <thead>
                <tr>
                    <td>
                        <h2 class="rsg2_display_name"><?php echo $image->name; ?></h2>
                    </td>
                </tr>
                </thead>

                <tbody>
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
                            <a href="<?php echo $image->UrlDownload; ?>"
                               title="Download"
                               class="btn btn-light">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        <!--						    <div class="rsg2-clr">&nbsp;</div>-->
                    </td>
                </tr>

                <tr>
                    <td>
						<?php if ($this->isShowPagination) : ?>
                        <!--						    <p>-->
                        <div class="rsg2-j3x-pagination">


                            <div class="rsg2-j3x-pagination--buttons">

                                <!-- must be before 			    </p>-->
								<?php if ($this->params->def('show_pagination_results', 1)) : ?>
                                <!--				    <p class="com-contact-category__counter counter float-end pt-3 pe-2">-->
                                <!--									    <p class="com-contact-category__counter counter float-end pt-3 pe-2">-->
                                <div class="com-contact-category__counter counter float-end pt-3 pe-2">
                                    <div class="rsg2-j3x-pagination--counter">
                                        <!--				    <p class="com-contact-category__counter counter text-center pt-3 pe-2">-->
										<?php echo $this->pagination->getPagesCounter(); ?>
                                    </div>
                                    <div>
                                        <!--									    </p>-->
										<?php endif; ?>

										<?php echo $this->pagination->getPagesLinks(); ?>
                                    </div>

                                </div>
                                <!--						    </p>-->
								<?php endif; ?>
                                <td>
                </tr>

                <tr>
                    <td>
						<?php if ($this->isShowDescription) : ?>

                            <!--					    <p><h3>Todo description if or not if </h3></p>-->
                            <!--					    <div class="page_inline_tabs_description">-->
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
						<?php endif; ?>
                    </td>

                </tr>

                <tr>
                    <td>
						<?php if ($this->isShowVoting) : ?>

                            <div class="rating-block row-fluid text-center">
                                <h4><?php echo Text::_('COM_RSGALLERY2_AVERAGE_USER_RATING'); ?></h4>
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
						<?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td>
						<?php if ($this->isShowExif) : ?>

							<?php htmlExifData($image->exifTags); ?>

						<?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td>
						<?php if ($this->isShowComments) : ?>

							<?php htmlComments($image->comments, $image->gallery_id, $image->id); ?>
						<?php endif; ?>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>


		<?php if (!empty($isDebugSite)): ?>
            <h5>RSGallery2 slide (?page) properties J3x layout</h5>
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


            <div class="text-center">
				<?php echo HTMLHelper::_('bootstrap.endTab'); ?>
            </div>

			<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'VotingTab', Text::_('COM_RSGALLERY2_VOTING', true)); ?>

		    <h3>Todo script for voting</h3>

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

		    <h3>ToDo: Display selected image exif info  </h3>

			<?php echo HTMLHelper::_('bootstrap.endTab'); ?>


			<?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>



            <input type="hidden" name="task" value="">
            <input type="hidden" name="rating" value="">
            <input type="hidden" name="paginationImgIdx" value="">
            <input type="hidden" name="id" value="157">
			<?php echo HTMLHelper::_('form.token'); ?>

        </div>
    </div>
</form>


