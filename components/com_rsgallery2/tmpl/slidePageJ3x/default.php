<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2005-2023 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\FileLayout;

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/

// ToDo:
// ToDo:

HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/slideshow.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/j3x.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/rsgallery.css', array('version' => 'auto', 'relative' => true));


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
        . 'Slide page image J3x Tasks: <br>'
        . '* !!! Pagination !!!<br>'
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

//	$images = $this->items;
	$image_idx = $this->imageIdx;
	$gallery = $this->gallery;
	$params = $this->params;
	$menuParams = $this->menuParams;

	$image = $this->image;

?>

<form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=SlidePageJ3x'); ?>" method="post" class="form-validate form-horizontal well">

    <div class="rsg2__form rsg2__slide_page">

        <?php if (!empty($this->isDebugSite)): ?>
            <h1> Menu RSGallery2 "slide page J3x" view </h1>
            <hr>
        <?php endif; ?>

        <?php //--- display images in J3x slideshow ---------- ?>

<!-- removed 2022.11.12       <div class="rsg2">-->
<!---->
<!--            --><?php //if (!empty($layoutSlidePage)): ?>
<!--            --><?php
////	            echo $layoutImage->render($displayData);
////	            echo $layoutProperties->render($displayData);
//
//	            echo $layoutSlidePage->render($displayData);
//
//                ?>
<!--            --><?php //endif; ?>
<!---->
<!--        </div>-->

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


	    <div class="mx-auto w-100">
			<?php echo $this->pagination->getListFooter(); ?>
	    </div>

	    <div class=" w-100">
            <?php if ($this->params->def('show_pagination_results', 1)) : ?>
			    <p class="com-contact-category__counter counter float-end pt-3 pe-2">
                    <?php echo $this->pagination->getPagesCounter(); ?>
			    </p>
            <?php endif; ?>

            <?php echo $this->pagination->getPagesLinks(); ?>
	    </div>


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


		    <div class="text-center">
	            <?php echo HTMLHelper::_('bootstrap.endTab'); ?>
		    </div>

            <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'VotingTab', Text::_('COM_RSGALLERY2_VOTING', true)); ?>

		    <p><h3>Todo script for voting</h3></p>

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
</form>


