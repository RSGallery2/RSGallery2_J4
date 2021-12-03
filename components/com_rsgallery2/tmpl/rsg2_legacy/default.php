<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
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

HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/j3x.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/rsgallery.css', array('version' => 'auto', 'relative' => true));



//if ($this->item->params->get('show_name')) {
//
//	if ($this->Params->get('show_rsgallery2_name_label')) {
//		echo Text::_('COM_RSGALLERY2_RSG2_LEGACY_NAME') . $this->item->name;
//	} else {
//		echo $this->item->name;
//	}
//}
//
//echo $this->item->event->afterDisplayTitle;
//echo $this->item->event->beforeDisplayContent;
//
// echo '<h1> RSGallery2 "legacy" view </h1>';
//
//
//echo $this->item->event->afterDisplayContent;

echo '';
// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
    echo '<span style="color:red">'
        . 'Tasks: <br>'
        . '* <br>'
        . '* ??? global<br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        . '</span><br><br>';
}

//$displayData['images'] = $this->images;
//$displayData['pagination'] = $this->pagination;
//echo $layout->render($displayData);

//if ($this->config->displaySearch) {
if (true) {
    $layout = new FileLayout('Search.search');
    echo $layout->render();
}


$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

	$layoutName = 'ImagesAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['images'] = $this->items;
$displayData['params'] = $this->params;
$displayData['pagination'] = $this->pagination;

// return;

# ToDo: <h1> header on debug  ? develop ?

?>

<div class="rsg2__form rsg2__images_area">
    <form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>" method="post" class="form-validate form-horizontal well">

        <h1>RSGallery2 "j3x legacy" root gallery and latest galleries overview </h1>

        <hr>

        <pre>
        /**
         *
         *      folders should be named galleries overview J3x
         *         -> Rsg2_legacy is wrong
         *
        */
        </pre>

        <pre>
        /**
         *      Overview not started
         *
         *          ($this->galleryId == 0) ==> standard overview
         *
         *          ($this->galleryId != 0) ==> parent gallery overview
         *
         *
         *          ??? ($this->galleryId != 0) ==> no childs gallery overview ???
         *
         *
         */
        </pre>


        <?php
//	    echo $layout->render($displayData);
	    ?>

        <?php
		// RSGallery2_Project\Documentation\J!3x\ImagesUsedInDoc\site.start.rootgalleries.png
	    echo 'ToDo: $layout_ root ... galleries  ->render';
	    ?>

        <div class="rsg2-pagenav-limitbox">
		<form action="/index.php/demo/demo-menu-root-galleries" method="post">
			<select id="limit" name="limit" class="inputbox input-mini" size="1" onchange="this.form.submit()">
				<option value="5" selected="selected">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<option value="20">20</option>
				<option value="25">25</option>
				<option value="30">30</option>
				<option value="50">50</option>
				<option value="100">100</option>
				<option value="0">All</option>
			</select>
		</form>
	</div>


	<div class="rsg_galleryblock">
		<div class="rsg2-galleryList-status"></div>
		<div class="rsg2-galleryList-thumb">
			<div class="img-shadow">
				<a href="/index.php/demo/demo-menu-root-galleries/gallery/7">
					<img class="rsg2-galleryList-thumb" src="http://rsgallery2.org/images/rsgallery/original/img_9114.jpg" alt="">
				</a>
			</div>
		</div>
		<div class="rsg2-galleryList-text">
			NextGallery <span class="rsg2-galleryList-newImages">
				<sup></sup>
			</span>
			<div class="rsg_gallery_details">
				<div class="rsg2_details">
					<a href="/index.php/demo/demo-menu-root-galleries/gallery/7/asSlideshow">Slideshow</a><br>
						Owner: adminfinnern<br>
						Size: 7 images<br>
						Created: 16 August 2020<br>
				</div>
			</div>
			<div class="rsg2-galleryList-description">
			</div>
		</div>
		<div class="rsg_sub_url_single">
		</div>
	</div>
	
	
	<div class="rsg_galleryblock">
		<div class="rsg2-galleryList-status"></div>
		<div class="rsg2-galleryList-thumb">
			<div class="img-shadow">
				<a href="/index.php/demo/demo-menu-root-galleries/gallery/5">
					<img class="rsg2-galleryList-thumb" src="http://rsgallery2.org/images/rsgallery/thumb/2015-10-11_00002.jpg.jpg" alt="">
				</a>
			</div>
		</div>
		<div class="rsg2-galleryList-text">
			Love Locks			<span class="rsg2-galleryList-newImages">
				<sup></sup>
			</span>
			<div class="rsg_gallery_details">
				<div class="rsg2_details">
					<a href="/index.php/demo/demo-menu-root-galleries/gallery/5/asSlideshow">
						Slideshow</a>
					<br>
					Owner: adminfinnern	<br>
					Size: 44 images<br>
					Created: 16 August 2020<br>
				</div>
			</div>
			<div class="rsg2-galleryList-description">
			</div>
		</div>
		<div class="rsg_sub_url_single">
		</div>
	</div>

	<div class="rsg_galleryblock">
		<div class="rsg2-galleryList-status"></div>
		<div class="rsg2-galleryList-thumb">
			<div class="img-shadow">
				<a href="/index.php/demo/demo-menu-root-galleries/gallery/4">
					<img class="rsg2-galleryList-thumb" src="http://rsgallery2.org/images/rsgallery/thumb/test_600x600-1.jpg.jpg" alt="">
				</a>
			</div>
		</div>
		<div class="rsg2-galleryList-text">
			Third gallery			<span class="rsg2-galleryList-newImages">
				<sup></sup>
			</span>
			<div class="rsg_gallery_details">
				<div class="rsg2_details">
					<a href="/index.php/demo/demo-menu-root-galleries/gallery/4/asSlideshow">
							Slideshow</a>
					<br>
					Owner: adminfinnern						<br>
					Size: 10 images						<br>
					Created: 16 August 2020						<br>
				</div>
			</div>
			<div class="rsg2-galleryList-description">			
			</div>
		</div>
		<div class="rsg_sub_url_single">
		</div>
	</div>

...
	
	<div class="pagination">
		<br>
		Results 1 - 5 of 5	
	</div>
	
	<div class="rsg2-clr"></div>

	<ul id="rsg2-galleryList">
		<li class="rsg2-galleryList-item">
			<table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
				<tbody>
					<tr>
						<td colspan="3">Random images</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td align="center">
							<div class="shadow-box">
								<div class="img-shadow">
									<a href="/index.php/demo/demo-menu-root-galleries/item/6/asInline">
										<img src="http://rsgallery2.org/images/rsgallery/thumb/test_150x150.jpg.jpg" alt="test_150x150" width="80">
									</a>
								</div>
								<div class="rsg2-clr">
								</div>
								<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
							</div>
						</td>
						<td align="center">
							<div class="shadow-box">
								<div class="img-shadow">
									<a href="/index.php/demo/demo-menu-root-galleries/item/17/asInline">
										<img src="http://rsgallery2.org/images/rsgallery/original/154_5497.jpg" alt="154_5497" width="80">
									</a>
								</div>
								<div class="rsg2-clr">
								</div>
								<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
							</div>
						</td>
						<td align="center">
							<div class="shadow-box">
								<div class="img-shadow">
									<a href="/index.php/demo/demo-menu-root-galleries/item/62/asInline">
										<img src="http://rsgallery2.org/images/rsgallery/thumb/2015-10-11_00012-1.jpg.jpg" alt="2015-10-11_00012-1" width="80">
									</a>
								</div>
								<div class="rsg2-clr">
								</div>
								<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</li>
	</ul>
	


	<ul id="rsg2-galleryList">
		<li class="rsg2-galleryList-item">
			<table class="table_border" cellspacing="0" cellpadding="0" border="0" width="100%">
				<tbody>
					<tr>
						<td colspan="3">Latest images</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td align="center">
							<div class="shadow-box">
								<div class="img-shadow">
									<a href="/index.php/demo/demo-menu-root-galleries/item/88/asInline">
										<img src="http://rsgallery2.org/images/rsgallery/original/dsc_5520.jpg" alt="dsc_5520" width="80">
									</a>
								</div>
								<div class="rsg2-clr">
								</div>
								<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
							</div>
						</td>
						<td align="center">
							<div class="shadow-box">
								<div class="img-shadow">
									<a href="/index.php/demo/demo-menu-root-galleries/item/89/asInline">
										<img src="http://rsgallery2.org/images/rsgallery/original/dsc_5526.jpg" alt="dsc_5526" width="80">
									</a>
								</div>
								<div class="rsg2-clr">
								</div>
								<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
							</div>
						</td>
						<td align="center">
							<div class="shadow-box">
								<div class="img-shadow">
									<a href="/index.php/demo/demo-menu-root-galleries/item/90/asInline">
										<img src="http://rsgallery2.org/images/rsgallery/original/dsc_5527.jpg" alt="dsc_5527" width="80">
									</a>
								</div>
								<div class="rsg2-clr"></div>
								<div class="rsg2_details">Uploaded:&nbsp;Sunday, 16 August 2020</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</li>
	</ul>



        <?php if ((int) $params->get('displayLatest', 0) === 0) : ?>
            <?php echo $this->loadTemplate('latest_images'); ?>
        <?php endif; ?>

        <?php if ((int) $params->get('displayRandom', 0) === 0) : ?>
            <?php echo $this->loadTemplate('random_images'); ?>
        <?php endif; ?>






    </form>
</div>


