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


echo '<span style="color:red">'
    . 'Galleries J3x area Tasks: <br>'
    . '* call gallery view<br>'
    . '* length of filenames<br>'
    . '* what happens on empty galleries/ image lists<br>'
	. '* Size of replace images (missing/no images) <br>'
	. '* pagination<br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
. '</span><br><br>';



//$galleries = $displayData['images'];
extract($displayData); // $galleries
if ( ! isset($galleries)) {   //         if (isset($to_user, $from_user, $amount))
    $galleries = [];
}

//--- sanitize URLs -----------------------------------

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/NoImagesAssigned.png';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/MissingImage.png';

// assign dummy images if not found
foreach ($galleries as $idx => $gallery) {

    // show dummy thumb on galleries with no images
    if (! empty($gallery->isHasNoImages))
    {
        $gallery->UrlOriginalFile = $noImageUrl;
        $gallery->UrlDisplayFiles = $noImageUrl;;
        $gallery->UrlThumbFile = $noImageUrl;

    }

//    else {
//
//        if (!$gallery->isOriginalFileExist) {
//            $gallery->UrlOriginalFile = $missingUrl;
//            ;
//        }
//
//        if (!$gallery->isDisplayFileExist) {
//            $gallery->UrlDisplayFiles = $missingUrl;;
//        }
//
//        if (!$gallery->isThumbFileExist) {
//            $gallery->UrlThumbFile = $missingUrl;
//        }
//
//    }
}


// max_columns_in_images_view
$cols = $params->get('max_columns_in_images_view',2);

?>

<h3>rsgallery 2 j3x galleries area layout II</h3>

<div id="rsg2_gallery" class="rsg2">

		
	<div class="yyyy">$config->intro_text = $rsgConfig->get('intro_text');</div>
	<div class="yyyy">ToDo: limit selection box -> external </div>

	<div class="intro_text"><?php echo $params->get('intro_text'); ?></div>

	<?php
	foreach ($galleries as $idx => $gallery) {
		// $row = $idx % $cols;
	?>
		<div class="rsg_galleryblock system-unpublished">
			<div class="rsg2-galleryList-status">ToDo: galleryList-status</div>
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
                <?php if ($params->get('galleries_show_title')): ?>
                    <span><?php echo $gallery->name ?></span>
                    <span class="rsg2-galleryList-newImages">ToDo: new images count</span>
                <?php endif; ?>
                </div>
                <div class="rsg_gallery_details">

                    <div class="rsg2_details">

                        <a href="<?php echo $gallery->UrlSlideshow?>">
                            Slideshow
                        </a>
                        <?php if (($params->get('yyy')) && !empty($gallery->author_name)): ?>
                            <div>xOwner: <?php echo $gallery->author_name ?></div>
                        <?php endif; ?>
                        <div>xSize: <?php echo $gallery->image_count ?></div>
                        <div>xCreated: <?php echo Text::_('COM_RSGALLERY2_CREATED') . ' '
		                      . HTMLHelper::_("date", $gallery->created, Text::_('COM_RSGALLERY2_DATE_FORMAT_LC3'))
		                       . '#' . $gallery->created; ?></div>

                    </div>
                    <div class="rsg2-galleryList-description">
	                    <div>xDesc: <?php echo $gallery->description ?></div>

                    </div>
                </div>

    		</div>
		</div>
        <div class="rsg2-clr"></div>
	<?php
	}
	?>



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


