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
    . 'Image area Tasks: <br>'
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



//$images = $displayData['images'];
extract($displayData); // $images
if ( ! isset($images)) {   //         if (isset($to_user, $from_user, $amount))
    $images = [];
}

//--- sanitize URLs -----------------------------------

$noImageUrl = URI::root() . '/media/com_rsgallery2/images/NoImagesAssigned.png';
$missingUrl = URI::root() . '/media/com_rsgallery2/images/MissingImage.png';

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

/**/
?>
<div class="rsg2">
    <div class="j25search_box pull-right">
        <form name="rsg2_search" class="form-search form-inline warning" method="post"
              action="/joomla3x/index.php/j3x-galleries-overview">
            <div class="input-prepend">
                <button type="submit" class="btn">Search</button>
                <input type="search" name="searchtextX" maxlength="200" class="inputbox search-query input-medium"
                       placeholder="Keywords"></div>
            <input type="hidden" name="option" value="com_rsgallery2"> <input type="hidden" name="rsgOption"
                                                                              value="search"> <input type="hidden"
                                                                                                     name="task"
                                                                                                     value="showResults">
        </form>
    </div>
    <div class="rsg2-clr"></div>

    <table id="rsg2-thumbsList" border="0">
        <tbody>
        <tr>
            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/158/asInline">
                            <img src="http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_5504.jpg.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5504				</span>
                <br><i></i></td>
            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/159/asInline">
                            <img src="http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_5505.jpg.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5505				</span>
                <br><i></i></td>
            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/160/asInline">
                            <img src="http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_5503-2.jpg.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5503-2				</span>
                <br><i></i>
            </td>
        </tr>
        <tr>
            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/161/asInline">
                            <img src="http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_5504-2.jpg.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5504-2				</span>
                <br><i></i></td>
            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/162/asInline">
                            <img src="http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_5505-2.jpg.jpg"
                                 alt="<p>&nbsp;item description&nbsp;</p>
<p>&nbsp;</p>
<p>asdf</p>
<p>asdf</p>
<p>asdf</p>">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5505-2				</span>
                <br><i></i></td>
            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/163/asInline">
                            <img src="http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_5503-3.jpg.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5503-3				</span>
                <br><i></i></td>
        </tr>
        <tr>
            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/157/asInline">
                            <img src="http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_5503.jpg.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5503				</span>
                <br><i></i></td>
        </tr>
        </tbody>
    </table>
    <div class="pagination">
    </div>
</div>
<?php
/**/


?>

<h3>rsgallery 2 j3x images area layout</h3>

<div id="rsg2_gallery" class="rsg2">


    <table id="rsg2-thumbsList" border="0">
        <tbody>

        <?php
        foreach ($images as $idx => $image) {
        ?>

            <td>
                <div class="shadow-box">
                    <div class="img-shadow">
                        <a href="/joomla3x/index.php/j3x-galleries-overview/item/158/asInline">
                            <img src="<?php echo $image->UrlThumbFile ?>" alt="">
                        </a>
                    </div>
                </div>
                <div class="rsg2-clr"></div>
                <br>
                <span class="rsg2_thumb_name">
					DSC_5504				</span>
                <br><i></i></td>

        <?php
        }
        ?>






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
				<figcaption><?php echo $image->name; ?></figcaption>
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
</div>






