<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2021-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/*---------------------------------------------------
? does what ?
---------------------------------------------------*/

extract($displayData);

if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: layout Image Single<br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
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
}

if (!isset($image)) {
    $image = (object)[];
}

?>

<?php if (!empty($isDebugSite)): ?>
    <h3>RSGallery2 image single layout</h3>
    <hr>
<?php endif; ?>

<div id="rsg2_image" class="rsg2_image__container">

    <div class="rsg2_image__xxx" id="gallery" data-bs-toggle="modal" data-bs-target="#exampleModal">

		<?php
		//foreach ($images as $idx => $image) {
        ?>
        <figure>
            <img src="<?php echo $image->UrlThumbFile ?>"
                 alt="<?php echo $image->name; ?>"
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






