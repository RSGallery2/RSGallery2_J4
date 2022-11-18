<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2021 - 2020
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Finder\Administrator\Indexer\Parser\Html;

defined('_JEXEC') or die;

extract($displayData);
if ( ! isset($galleries)) {   //         if (isset($to_user, $from_user, $amount))
    $galleries = [];
}
//echo '<br>--- $this->params ------------------------------------------<br>';
//echo json_encode($params);
//echo $params->toString();
//echo json_encode($params->toArray());
//foreach ($params->toArray() as $paramName => $paramValue) {
//
//	echo $paramName . ': '  . json_encode($paramValue) . '<br>';
//
//}
//echo $params;

//echo json_encode($params, JSON_PRETTY_PRINT);
//echo json_encode(json_decode($params), JSON_PRETTY_PRINT);
//echo json_encode(json_decode($params->toString()), JSON_PRETTY_PRINT);
//echo '<br>------------------------------------------------------------<br>';


if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout GalleriesAreaJ3x<br>'
        . '* Change date format<br>'
        . '* Use CSS flex: align right of thumb<br>'
    	. '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* Galleries J3x area Tasks: <br>'
        . '* New images count ?? new images since last visit -> see class rsg2-galleryList-newImages<br>'
        . '* Size of replace images (missing/no images) <br>'
    	. '* HasNewImagesText ? text or icon ? -> see class rsg2-galleryList-status<br>'
        . '* SubGalleryList array ( -> see class rsg_sub_url_single<br>'
        . '* Display sub galleries with just thumb and small information <br>'
        . '* Limit sub galleries print ... if count bigger <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}

/**
   -> see class rsg2-galleryList-status

<div class="rsg_sub_url_single">
    Subgalleries:
    <a href="/joomla3x/index.php/j3x-galleries-overview/gallery/5">
        Landschaft		(24 images)
    </a>,
    <a href="/joomla3x/index.php/j3x-galleries-overview/gallery/4">
        Berge			(17 images)
    </a>
</div>
/**/





//--- sanitize URLs -----------------------------------

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';

// assign dummy images if not found
foreach ($galleries as $idx => $gallery) {

    // show dummy thumb on galleries with no images
    if (! empty($gallery->isHasNoImages))
    {
        $gallery->UrlOriginalFile = $noImageUrl;
        $gallery->UrlDisplayFiles = $noImageUrl;;
        $gallery->UrlThumbFile = $noImageUrl;

    }
}


?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 j3x galleries area layout</h3>
    <hr>
<?php endif; ?>


<div id="rsg2_gallery" class="rsg2">

	XXXX
	<div class="form-label intro_text"><?php echo $params->intro_text; ?></div>
	XXXX<br>

	<?php foreach ($galleries as $idx => $gallery) : ?>

		<?php
//			if ($idx > $this->params->Nr of items ) {
//			    break;
//		    }
		?>

		<div class="rsg_galleryblock system-unpublished">
			<div class="rsg2-galleryList-status">//Status//</div>
			<div class="rsg2-galleryList-thumb">
				<!---div class="shadow-box"-->
				<div class="img-shadow">
					<a href="<?php echo $gallery->UrlGallery?>">
						<img class="rsg2-galleryList-thumb" 
							src="<?php echo $gallery->UrlThumbFile ?>" 
							alt="<?php echo $gallery->name ?>">
					</a>
				</div>
			</div>

			<div class="rsg2-galleryList-text">
                <div>
                <?php if ($params->galleries_show_title): ?>
                    <span><?php echo $gallery->name ?></span>
                    <span class="rsg2-galleryList-newImages"></span>
                <?php endif; ?>
                </div>
                <div class="rsg_gallery_details">

                    <div class="rsg2_details">

                        <?php if ($params->galleries_show_slideshow): ?>
                            <div class="rsg2_slideshow_link">
                                <a href="<?php echo $gallery->UrlSlideshow?>">
                                    Slideshow
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if ($params->galleries_show_owner && !empty($gallery->author_name)): ?>
                                <div>
                                    <?php echo Text::_('COM_RSGALLERY2_OWNER') . ': ' . $gallery->author_name ?>
                                </div>
                        <?php endif; ?>
                        <?php if ($params->galleries_show_size): ?>
                            <div><?php echo Text::_('COM_RSGALLERY2_SIZE') . ': ' . $gallery->image_count ?></div>
                        <?php endif; ?>
                        <?php if ($params->galleries_show_date): ?>
                            <div><?php echo Text::_('COM_RSGALLERY2_CREATED') . ': ' . $gallery->created; ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($params->galleries_show_description): ?>
                        <?php if (! empty ($gallery->description)): ?>
                            <div class="rsg2-galleryList-description">
                                <div><?php echo $gallery->description ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
    		</div>
            <div class="rsg_sub_url_single">

                <?php if (count($gallery->subGalleryList) > 0): ?>
                    <?php echo Text::_('COM_RSGALLERY2_SUBGALLERIES') . ': ' ?>

                    <?php foreach ($gallery->subGalleryList as $subIdx => $subGallery) : ?>

                        <?php if ($subIdx > 0): ?>
                            ,&nbsp;
                        <?php endif; ?>
                        <?php echo $subGallery->name . ' (' . $subGallery->imgCount .')'; ?>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
		</div>
        <div class="rsg2-clr"></div>
	<?php endforeach; ?>

	<div class="pagination">
		<?php
        // params_>get('');
		if(isset ($pagination))
		{
			echo $pagination->getListFooter();
		}
		?>
	</div>
	
</div class="rsg2">


