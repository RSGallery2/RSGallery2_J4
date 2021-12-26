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


// on develop show open tasks if existing
if (!empty ($this->isDevelopSite))
{
    echo '<span style="color:red">'
        . 'Tasks: <br>'
        . '* User limit selection box -> layout ? Nbr of galleries  -> yes no ?  <br>'
        . '* Format of date is already in database -> improve ... <br>'
        . '* RSG2_legacy should be renamed to rsg2RootJ3x -> all: model view ...<br>'
        . '* Events in general<br>'
        . '* User count of galleries displayed not working: 0, 1,2,3<br>'
        //	. '* <br>'
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

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;



// return;

# ToDo: <h1> header on debug  ? develop ?

?>

<div class="rsg2__form rsg2__galleries_area">

    <?php if (!empty($isDebugSite)): ?>
        <h2>RSGallery2 "j3x legacy" root gallery and latest galleries overview </h2>
    <?php endif; ?>

	<?php // display root galleries ?>
	<?php echo $layout->render($displayData); ?>

    <?php if (!empty($this->isDebugSite)): ?>
		<hr>
    <?php endif; ?>


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
		/**/
	?>

	<div class="rsg2-clr"></div>

	<?php echo $this->loadTemplate('latest_images'); ?>
	<?php echo $this->loadTemplate('random_images'); ?>

</div>


