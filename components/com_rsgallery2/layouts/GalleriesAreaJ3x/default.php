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

		
	<div class="intro_text">ToDo: search -> external </div>
	<div class="intro_text">ToDo: limit selection box -> external </div>

	<div class="intro_text">ToDo: intro_text </div>

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
                <?php if ($params->get('galleries_show_title') || True): ?>
                    <span><?php echo $gallery->name ?></span>
                    <span class="rsg2-galleryList-newImages">ToDo: galleryList-newImages</span>
                <?php endif; ?>
                </div>
                <div class="rsg_gallery_details">

                    <div class="rsg2_details">

                        <a href="<?php echo $gallery->UrlSlideshow?>">
                            Slideshow
                        </a>
                        <?php if ($params->get('yyy') || True): ?>
                            <div>Owner: <php echo $gallery->user ></div>
                        <?php endif; ?>
                        <div>Size: <php echo $gallery->size ></div>
                        <div>Created: <php echo $gallery->date ></div>

                    </div>
                    <div class="rsg2-galleryList-description">

                    </div>
                </div>

    		</div>
		</div>
        <div class="rsg2-clr"></div>
        <hr>
        <span class="rsg2_thumb_name">
				<?php echo $gallery->name ?>
			</span>
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




    <?php
    /**/
    ?>
	<h3>rsgallery 2 j3x images area layout III demo</h3>


		<div class="rsg_galleryblock system-unpublished">
			<div class="rsg2-galleryList-status"></div>
			<div class="rsg2-galleryList-thumb">
				<div class="img-shadow">
					<a href="/Joomla3x/index.php/gallery-overview/3-gallery-03">
						<img class="rsg2-galleryList-thumb" 
						src="http://127.0.0.1/Joomla3x/images/rsgallery/thumb/DSC_5505-2.jpg.jpg" 
						alt="">
					</a>
				</div>
			</div>
			<div class="rsg2-galleryList-text">Gallery 03
					<span class="rsg2-galleryList-newImages">		  </span>
					<div class="rsg_gallery_details">
					
					<div class="rsg2_details">
						<a href="/Joomla3x/index.php/gallery-overview/3-gallery-03?page=slideshow">
							Slideshow</a>
						<br>
						Owner: finnern                        <br>
						Size: 4 images                        <br>
						Created: 30 December 2020                        <br>
					</div>
				</div>
				<div class="rsg2-galleryList-description">
					
				</div>
			</div>
            <div class="rsg_sub_url_single">Subgalleries:
                <a href="/joomla3x/index.php/j3x-galleries-overview/gallery/5">
                    Landschaft 22			(24 images)
                </a>,
                <a href="/joomla3x/index.php/j3x-galleries-overview/gallery/4">
                    3.			(17 images)
                </a>
            </div>
		</div>

	<div class="rsg_galleryblock" id="gallery"  data-bs-toggle="modal" data-bs-target="#exampleModal">

		<?php
		foreach ($galleries as $idx => $gallery) {
			?>
			<figure>
				<img src="<?php echo $gallery->UrlThumbFile ?>"
				     alt="<?php echo $gallery->name ?>"
				     class="img-thumbnail rsg2_gallery__images_image"
				     data-target="#rsg2_carousel"
				     data-slide-to="<?php echo $idx ?>"bs-
				>
				<figcaption><?php echo $gallery->title; ?></figcaption>
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
							foreach ($galleries as $gallery) {
								?>

								<div class="carousel-item <?php echo $isActive ?>" >
									<div class="d-flex align-items-center justify-content-center min-vw-100  min-vh-100">
										<!--                                        <img class="d-block " src="--><?php //echo $gallery->UrlDisplayFiles[400] ?><!--"-->
										<img class="d-block " src="<?php echo $gallery->UrlOriginalFile ?>"
										     alt="<?php echo $gallery->name ?>"
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






