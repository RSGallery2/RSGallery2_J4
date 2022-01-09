<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2021 - 2020
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;


//$images = $displayData['images'];
extract($displayData); // $images
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Image J3x area Tasks: <br>'
        . '* Change date format<br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . '* modal image (->slider)<br>'
        . '* length of filenames<br>'
        . '* what happens on empty galleries/ image lists<br>'
        . '* Size of replace images (missing/no images) <br>'
        . '* border and title? for latest and random<br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
        . '</span><br><br>';
}





//--- sanitize URLs -----------------------------------

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/NoImagesAssigned.svg';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/MissingImage.svg';

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


// max_columns_in_images_view
$cols = $params->get('max_columns_in_images_view',2);

?>

<?php if (!empty($isDebugSite)): ?>
    <h3>rsgallery 2 j3x images area layout II</h3>
<?php endif; ?>

<div id="rsg2_gallery" class="rsg2">

	<?php if ($menuParams->gallery_show_title): ?>
		<h2>
			<div class="rsg_gallery_title"><?php echo $gallery->name ?></div>
		</h2>
	<?php endif; ?>

	<?php if ($menuParams->gallery_show_description): ?>
		<div class="intro_text"><p><?php echo $gallery->description ?></p></div>
	<?php endif; ?>

    <div class="rsg2-clr"></div>

    <?php if ($menuParams->gallery_show_slideshow): ?>
        <div class="rsg2_slideshow_link">
            <a href="<?php echo $gallery->UrlSlideshow?>">
                Slideshow
            </a>
        </div>
    <?php endif; ?>

    <table id="rsg2-thumbsList">
        <tbody>

        <?php
        foreach ($images as $idx => $image) {
	        $row = $idx % $cols;
	    ?>

	        <?php if ($row == 0 ): ?>
			<tr>
	        <?php endif ?>

	            <td>
	                <div class="shadow-box">
	                    <div class="img-shadow">
	                        <a href="<?php echo $image->UrlImageAsInline?>">
	                            <img src="<?php echo $image->UrlThumbFile ?>" alt="<?php echo $image->name ?>">
	                        </a>
	                    </div>
	                </div>
	                <div class="rsg2-clr"></div>
	                <br>
	                <span class="rsg2_thumb_name">
						<?php echo $image->title ?>
	                </span>

		        </td>

	        <?php if ($row == $cols-1 ): ?>
		        <tr>
	        <?php endif ?>

	    <?php
        }
        ?>

        </tbody>
    </table>

	<div class="pagination">
		<?php
		if(isset ($pagination))
		{
			echo $pagination->getListFooter();
		}
		?>
	</div>

	<?php /**
    <?php if (!empty($isDebugSite)): ?>
    	<h3>rsgallery 2 j3x images area layout III</h3>
    <?php endif; ?>

    <div class="rsg2_gallery__images" id="gallery"  data-bs-toggle="modal" data-bs-target="#exampleModal">

		<?php
		foreach ($images as $idx => $image) {
			?>
			<figure>
				<img src="<?php echo $image->UrlThumbFile ?>"
				     alt="<?php echo $image->name ?>"
				     class="img-thumbnail rsg2_gallery__images_image"
				     data-target="#rsg2_carousel"
				     data-slide-to="<?php echo $idx ?>"bs-
				>
				<figcaption><?php echo $image->title; ?></figcaption>
			</figure>
			<?php
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

								<div class="carousel-item <?php echo $isActive ?>" >
									<div class="d-flex align-items-center justify-content-center min-vw-100  min-vh-100">
										<!--                                        <img class="d-block " src="--><?php //echo $image->UrlDisplayFiles[400] ?><!--"-->
										<img class="d-block " src="<?php echo $image->UrlOriginalFile ?>"
										     alt="<?php echo $image->name ?>"
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
    /**/ ?>
</div>






