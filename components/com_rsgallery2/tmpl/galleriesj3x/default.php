<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;

// https://blog.kulturbanause.de/2014/09/responsive-images-srcset-sizes-adaptive/

// ToDo:
// ToDo:

//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/images.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/j3x.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('stylesheet', 'com_rsgallery2/site/j3x/rsgallery.css', array('version' => 'auto', 'relative' => true));
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.site.galleryJ3x');


//if ($this->item->params->get('show_name')) {
//
//	if ($this->Params->get('show_rsgallery2_name_label')) {
//		echo Text::_('COM_RSGALLERY2_NAME') . $this->item->name;
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
if (!empty ($this->isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks: galleriesJ3x view<br>'
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
if ($layoutName == 'default') {
    $layoutName = 'GalleriesAreaJ3x.default';
}

$layout = new FileLayout($layoutName);

$displayData['parentGallery'] = $this->parentGallery;
$displayData['parent_id']     = $this->state->get('gallery.id');
$displayData['galleries']     = $this->items;


$displayData['params'] = $this->params->toObject();
//$displayData['menuParams'] = $this->menuParams;
$displayData['pagination'] = $this->pagination;

$displayData['isDebugSite']   = $this->isDebugSite;
$displayData['isDevelopSite'] = $this->isDevelopSite;

// return;

# ToDo: <h1> header on debug  ? develop ?

?>

<div class="rsg2__form rsg2__images_area">
    <form id="rsg2_gallery__form" action="<?php echo Route::_('index.php?option=com_rsgallery2&view=galleriesj3x'); ?>" method="post"
          class="form-validate form-horizontal well">

        <?php if (!empty($this->isDebugSite)): ?>
            <h1>RSGallery2 "galleries j3x legacy" view </h1>
        <?php endif; ?>

        <?php if (!empty($this->isDebugSite)): ?>
            <hr>
        <?php endif; ?>

	    <?php
	    echo $layout->render($displayData);
        ?>

    </form>
</div>


