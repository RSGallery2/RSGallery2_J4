<?php
/**
 * @package     RSGallery2
 * @subpackage  mod_rsg2_slideshow
 *
 * @copyright (c) 2005-2024 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Layout\FileLayout;

\defined('_JEXEC') or die;

// use Joomla\CMS\Helper\ModuleHelper;


//// on develop show open tasks if existing
//if (!empty ($this->isDevelopSite))
//{
//	echo '<span style="color:red">'
//		. 'Tasks: slideshowJ3x view<br>'
//		//	. '* <br>'
//		//	. '* <br>'
//		//	. '* <br>'
//		//	. '* <br>'
//		//	. '* <br>'
//		. '</span><br><br>';
//}

echo '<br><br>--------------------------- mod_rsg2_slideshow start ------------------------------<br>';

if ( ! empty ($msg)) {
	echo $msg;
	return;
}



// $layoutName = $this->getLayout();
$layoutName = $params->get('slides_layout');

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

	$layoutName = 'SlideshowJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['images'] = $images;
$displayData['params'] = $params->toObject();
//$displayData['menuParams'] = $this->menuParams;

$displayData['isDebugSite'] = $isDebugSite;
$displayData['isDevelopSite'] = $isDevelopSite;

// ToDo check that random number identifies the slideshow ...

?>

    <div class="rsg2_x_form rsg2__slide_area">

        <?php if (!empty($isDebugSite)): ?>
            <h1> Menu RSGallery2 "slideshow" J3x view </h1>
            <hr>
        <?php endif; ?>

        <?php //--- display images in J3x slideshow ---------- ?>

        <?php echo $layout->render($displayData); ?>

    </div>


<?php

echo '<br>--------------------------- mod_rsg2_slideshow end  ------------------------------<br>';

?>
