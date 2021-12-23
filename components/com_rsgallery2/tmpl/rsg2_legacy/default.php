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


//if ($this->config->displaySearch) {
if (true) {
    $layout = new FileLayout('Search.search');
    echo $layout->render();
}


$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

	$layoutName = 'GalleriesAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['galleries'] = $this->items;
$displayData['params'] = $this->params;
$displayData['menuParams'] = $this->menuParams;
$displayData['pagination'] = $this->pagination;

// return;

# ToDo: <h1> header on debug  ? develop ?

?>

<div class="rsg2__form rsg2__galleries_area">

        <h1>RSGallery2 "j3x legacy" root gallery and latest galleries overview </h1>

        <hr>

        <pre>
        /**
         *      name should be rsg2RootJ3 -> model view ...
        */
        </pre>


	<?php
	/**
	echo '--- galleries' . '-------------------------------' . '<br>';
	foreach ($this->items as $idx => $gallery)
	{
		// $row = $idx % $cols;
		echo 'images: ' . $gallery->name . '<br>';
	}
	?>

	<?php
	echo '--- randomImages' . '-------------------------------' . '<br>';
	foreach ($this->randomImages as $idx => $image)
	{
		// $row = $idx % $cols;
		echo 'images: ' . $image['name']. '<br>';
	}
	?>

	<?php
	echo '--- latesImages' . '-------------------------------' . '<br>';
	foreach ($this->latestImages as $idx => $image)
	{
		// $row = $idx % $cols;
		echo 'images: ' . $image['name']. '<br>';
	}
	 * /**/

	echo $layout->render($displayData);


	?>

	<hr>


	<?php
		/**
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
	<?php
	/**/
	?>
	<div class="rsg2-clr"></div>



	    <?php echo $this->loadTemplate('latest_images'); ?>
	    <?php echo $this->loadTemplate('random_images'); ?>


	    <?php
		     /*
	    ?>
	        <?php if ((int) $params->get('displayLatest', 0) === 0) : ?>
		    <?php echo $this->loadTemplate('latest_images'); ?>
		    <?php endif; ?>

		    <?php if ((int) $params->get('displayRandom', 0) === 0) : ?>
		    <?php echo $this->loadTemplate('random_images'); ?>
		    <?php endif; ?>


	    <?php endif; ?>
			/**/
		?>


</div>


