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
        . '* <br>'
        . '* ??? global<br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        //	. '* <br>'
        . '</span><br><br>';
}


if ($this->menuParams->displaySearch) {
	$searchLayout = new FileLayout('Search.search');
	// $searchData['options'] = $searchOptions ...; // gallery
}

$layoutName = $this->getLayout();

// default is 'ImagesAreaJ3x.default'
if($layoutName == 'default') {

	$layoutName = 'ImagesAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['images'] = $this->items;
$displayData['params'] = $this->params;
$displayData['menuParams'] = $this->menuParams;
$displayData['pagination'] = $this->pagination;

$displayData['gallery'] = $this->gallery;

$displayData['isDebugSite'] = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

// return;

# ToDo: <h1> header on debug  ? develop ?

?>
<!-- ToDo: is form here needed ? check core ...  -->
<!-- ToDo: form link ...  -->
<form id="rsg2_galleryJ3x__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>"
      method="post" class="form-validate form-horizontal well">

    <div class="rsg2__form rsg2__images_area">

        <?php if (!empty($this->isDebugSite)): ?>
    	    <h1>RSGallery2 "gallery j3x legacy" view </h1>
        <?php endif; ?>

	    <?php //--- display search ---------- ?>

	    <?php if ($this->menuParams->displaySearch): ?>
		    <?php echo $searchLayout->render(); ?>
	    <?php endif; ?>

	    <?php //--- display gallery images ---------- ?>

	    <?php echo $layout->render($displayData); ?>

    </div>
</form>


