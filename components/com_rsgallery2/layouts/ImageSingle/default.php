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
    . 'Image single tasks: <br>'
    . '* modal image (->slider)<br>'
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


$image = $displayData['image'];

?>

<h3>rsgallery 2 image single layout</h3>

<div id="rsg2_image" class="rsg2_image__container">

	<div class="rsg2_image__xxx" id="gallery"  data-bs-toggle="modal" data-bs-target="#exampleModal">

		<?php
		//foreach ($images as $idx => $image) {
			?>
			<figure>
				<img src="<?php echo $image->UrlThumbFile ?>"
				     alt="<?php echo $image->name ?>"
				     class="img-thumbnail rsg2_gallery__images_image"
				>
				<figcaption><?php echo $image->name; ?></figcaption>
			</figure>
			<?php
		//}
		?>
	</div>

<!--	<!-- Modal markup: https://getbootstrap.com/docs/4.4/components/modal/ -->-->
<!--	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">-->
<!--		<div class="modal-dialog" role="document">-->
<!--			<div class="modal-content">-->
<!--				<div class="modal-header">-->
<!--					<button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--						<span aria-hidden="true">×</span>-->
<!--					</button>-->
<!--				</div>-->
<!--				<div class="modal-body">-->
<!---->
<!--					<!-- Carousel markup goes here -->-->
<!---->
<!--					<div id="rsg2_carousel" class="carousel slide" data-ride="carousel">-->
<!---->
<!--						<div class="carousel-inner">-->
<!---->
<!--							--><?php
//							$isActive="active";
////							foreach ($images as $image) {
//								?>
<!---->
<!--								<div class="carousel-item --><?php //echo $isActive ?><!--" >-->
<!--									<div class="d-flex align-items-center justify-content-center min-vw-100  min-vh-100">-->
<!--										<!--                                        <img class="d-block " src="-->--><?php ////echo $image->UrlDisplayFiles[400] ?><!--<!--"-->-->
<!--										<img class="d-block " src="--><?php //echo $image->UrlOriginalFile ?><!--"-->
<!--										     alt="--><?php //echo $image->name ?><!--"-->
<!--										>-->
<!--									</div>-->
<!--								</div>-->
<!---->
<!--								--><?php
//								$isActive="";
////							}
//							?>
<!---->
<!---->
<!--						</div>-->
<!--					</div>-->
<!---->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
</div>






