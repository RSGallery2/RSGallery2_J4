<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright (c) 2021-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout imagesArea<br>'
        . 'Image area Tasks: <br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* modal image (->slider)<br>'
        . '* length of filenames<br>'
        . '* what happens on empty galleries/ image lists<br>'
        . '* Size of replace images (missing/no images) <br>'
        . '* pagination<br>'
        . '* <br>'
        . '* checkout bootstrap CSS-Tricks: https://css-tricks.com/creating-a-modal-image-gallery-with-bootstrap-components/<br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
//        . '* <br>'
        . '</span><br><br>';
}

//$images = $displayData['images'];
extract($displayData);
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout Images Area<br>'
        . 'Images area layout Tasks: <br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
        . '</span><br><br>';
}

//--- sanitice URLs -----------------------------------

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.svg';

if ( ! empty($images)) {
	foreach ($images as $idx => $image) {
		// show dummy thumb on galleries with no images
		if (!empty($image->isHasNoImages)) {
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
}

?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 images area layout</h3>
    <hr>
<?php endif; ?>

<div id="rsg2_gallery" class="rsg2_gallery">

	<div class="rsg2_gallery__images" id="gallery"  data-bs-toggle="modal" data-bs-target="#exampleModal">

		<?php
		if ( ! empty($images)) {
			foreach ($images as $idx => $image) {
				?>
				<figure>
					<img src="<?php echo $image->UrlThumbFile ?>"
					     alt="<?php echo $image->name; ?>"
					     class="img-thumbnail rsg2_gallery__images_image"
					     data-target="#rsg2_carousel"
					     data-slide-to="<?php echo $idx ?>"bs-
					>
					<figcaption><?php echo $image->name; ?></figcaption>
				</figure>
				<?php
			}
		}
		?>
	</div>

	<!-- Modal markup: https://getbootstrap.com/docs/4.4/components/modal/ -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">

					<!-- Carousel markup goes here -->

					<div id="rsg2_carousel" class="carousel slide" data-ride="carousel">

						<div class="carousel-inner">

							<?php
							$isActive="active";
							foreach ($images as $image) {
								?>

								<div class="carousel-item <?php echo $isActiv; ?>" >
									<div class="d-flex align-items-center justify-content-center min-vw-100  min-vh-100">
										<!--                                        <img class="d-block " src="--><?php //echo $image->UrlDisplayFiles[400] ?><!--"-->
										<img class="d-block " src="<?php echo $image->UrlOriginalFile ?>"
										     alt="<?php echo $image->name; ?>"
										>
									</div>
								</div>

								<?php
								$isActive="";
							}
							?>


							<a class="carousel-control-prev" href="#rsg2_carousel" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#rsg2_carousel" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>






