<?php
/**
 * @package     RSGallery2
 * @subpackage  mod_rsg2_slideshow
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
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

if (!empty($isDebugSite)) {
	echo '<br><br>--------------------------- mod_rsg2_slideshow start ------------------------------<br>';
}

// message on empty data or other
if ( ! empty ($msg)) {
	echo $msg;

    if (!empty($isDebugSite)) {
		echo $msg . '<br>';
	}

	return;
}


// $layoutName = $this->getLayout();
$layoutName = $params->get('slides_layout');
$layoutFolder = JPATH_SITE . '/components/com_rsgallery2/layouts';

// default is 'ImagesAreaJ3x.default'
//if($layoutName == 'default') {
//
//	$layoutName = 'SlideshowJ3x.default';
//} else {
//
//yyy	$layoutName = $layoutName;
//}

$layout = new FileLayout($layoutName, $layoutFolder);

$displayData['images'] = $images;
$displayData['params'] = $params->toObject();
//$displayData['menuParams'] = $this->menuParams;

$displayData['isDebugSite'] = $isDebugSite;
$displayData['isDevelopSite'] = $isDevelopSite;

?>

    <div class="rsg2_x_form rsg2__slide_area">

        <?php if (!empty($isDebugSite)): ?>
            <h1> Module RSGallery2 "slideshow" J3x view </h1>
            <hr>
        <?php endif; ?>

        <?php //--- display images in J3x slideshow ---------- ?>

        <?php echo $layout->render($displayData); ?>

    </div>


<?php

if (!empty($isDebugSite)) {
    echo '<br>--------------------------- mod_rsg2_slideshow end  ------------------------------<br>';
}


?>
