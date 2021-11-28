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
	    echo '$layout_latest root ... galleries  ->render';
	    ?>

        <?php
		// RSGallery2_Project\Documentation\J!3x\ImagesUsedInDoc\site.start.randomLatestImages.png
	    echo '$layout_random images ->render';
	    ?>

        <?php
		// RSGallery2_Project\Documentation\J!3x\ImagesUsedInDoc\site.start.randomLatestImages.png
	    echo '$layout_latest images ->render';
	    ?>


    </form>
</div>


